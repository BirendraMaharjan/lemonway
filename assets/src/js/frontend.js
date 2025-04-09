/**
 * SASS
 */
import '../sass/frontend.scss';
const { __ } = wp.i18n;
/**
 * JavaScript
 */

/**
 * Add your JavaScript code here
 */
jQuery(function ($) {
	if ($('.field-type-date').length && $.fn.datepicker) {
		$('.field-type-date input').datepicker({
			dateFormat: 'yy/mm/dd',
			changeYear: true, // Allows changing the year
			changeMonth: true, // Allows changing the month
			yearRange: '1950:2020', // Optional: Sets the year range for the dropdown
			defaultDate: new Date(2000, 0, 1),
			beforeShow(input, inst) {
				// Add a custom class to the datepicker popup
				setTimeout(() => {
					inst.dpDiv.addClass('lemonway-datepicker');
				}, 0);
			},
		});
	}

	const lemonyApp = {
		init() {
			this.attachEventListeners();
			this.toggleAccount();
			this.deactivateLinkAccount();
			return false;
		},
		deactivateLinkAccount() {
			this.handleDeactivateClick();
			this.handleModalCloseClick();
			this.handleDeactivationConfirmation();
		},
		handleDeactivateClick() {
			// Using event delegation in case new elements are added dynamically
			$(document).on('click', '.link-account-deactivate', (e) => {
				e.preventDefault();

				const ibanId = $(e.currentTarget).data('id'); // Get the iban_id from data attribute

				// Show the modal and store the iban_id in the confirmation button
				this.toggleModal(true);
				$('#lemonway-iban-deactivation').data('id', ibanId);
			});
		},
		handleModalCloseClick() {
			// Handle closing of the modal
			$('#lemonway-modal-close').on('click', (e) => {
				e.preventDefault();
				this.toggleModal(false); // Hide the modal
			});
		},
		handleDeactivationConfirmation() {
			// Handle when the user confirms the deactivation
			$('#lemonway-iban-deactivation').on('click', (e) => {
				e.preventDefault();

				const ibanId = $(e.currentTarget).data('id'); // Get the iban_id from the confirm button

				this.showLoadingState();

				// Perform the AJAX request to deactivate the account
				this.deactivateAccount(ibanId, e);
			});
		},
		deactivateAccount(ibanId, event) {
			$.ajax({
				url: ajaxObj.ajaxUrl,
				type: 'POST',
				data: {
					action: 'lemonway_deactivate_link_bank_account',
					iban_id: ibanId,
					nonce: ajaxObj.security, // Use the nonce for security
				},
				success: (response) =>
					this.handleDeactivationResponse(response, event),
				error: () => this.handleDeactivationError(),
			});
		},

		handleDeactivationResponse(response, event) {
			this.hideLoadingState();
			if (response.success) {
				$(event.target).remove();
				$('#lemonway-modal-close').text('Close');
				this.updateModalContent(response.data.message);
			} else {
				// Show the error message in the modal
				this.updateModalContent(response.error);
			}
		},

		handleDeactivationError() {
			// Handle the error when AJAX fails
			this.updateModalContent(
				__('An error occurred while deactivating.', 'lemonway')
			);
		},

		updateModalContent(message) {
			// Helper function to update the modal content
			$('#lemonway-modal .modal-content').html(message);
			this.toggleModal(true);
		},
		showLoadingState() {
			// Show the loading state in the modal
			$('#lemonway-modal .modal-content').html(
				'<div class="loading-spinner">' +
					__('Loading...', 'lemonway') +
					'</div>'
			);
		},
		toggleModal(show) {
			// Show or hide the modal
			$('#lemonway-modal').toggleClass('active', show);
		},
		hideLoadingState() {
			// Hide the loading state by removing the spinner
			$('#lemonway-modal .modal-content').html(''); // Clear the spinner
		},
		toggleAccount() {
			$('.lemonway-settings-account-type-fields input').on(
				'click',
				function () {
					$('.lemonway-settings-company-fields').toggleClass(
						'lemonway-hide',
						$(this).val() === 'individual'
					);
				}
			);
		},
		attachEventListeners() {
			const self = this;
			$('.lemonway-save-dokan-btn').on('click', (e) => {
				e.preventDefault();

				const current = $(e.currentTarget);
				const form = current.closest('form'),
					formId = form.attr('id'),
					formStep =
						form.attr('lemonway-action') ??
						current.attr('data-action');

				self.submitSettings(formId, formStep);
			});
			$('.lemonway-disconnect-dokan-btn').on('click', (e) => {
				e.preventDefault();

				const current = $(e.currentTarget);
				const form = current.closest('form'),
					formId = form.attr('id'),
					formStep = 'disconnect';
				$(':input', form)
					.not(':button, :submit, :reset, :hidden, :checkbox')
					.val('')
					.prop('selected', false);

				self.submitSettings(formId, formStep, true);
			});
		},
		submitSettings(formId, formStep, isDisconnect) {
			const form = $(`form#${formId}`);
			const formData = new FormData(form[0]);
			formData.append('action', 'lemonway_dokan_settings');
			formData.append('form_id', formId);
			formData.append('form_step', formStep);

			this.handleRequest(form, formData, formStep, isDisconnect);
		},
		handleRequest(form, formData, formStep, isDisconnect) {
			const loadingSpan = '<span class="dokan-loading"></span>';
			if (isDisconnect) {
				form.find('.ajax_prev.disconnect').append(loadingSpan);
			} else {
				form.find('.ajax_prev.save').append(loadingSpan);
			}

			$.ajax({
				type: 'POST',
				url: ajaxObj.ajaxUrl,
				data: formData,
				dataType: 'json',
				processData: false,
				contentType: false,
				enctype: 'multipart/form-data',
				success(response) {
					const button = '.lemonway-save-dokan-btn';

					console.log(response.data);
					console.log(response);
					if (response.success) {
						if (response.data.btn_text) {
							form.find(button).text(response.data.btn_text);
						}
						if (response.data.form_step === 'verification') {
							form.find('#form-input-birthDateVerify')
								.closest('.lemonway-hide')
								.removeClass('lemonway-hide');

							form.attr('lemonway-action', 'verification');
						} else if (response.data.form_step === 'verified') {
							if (response.data.redirect_url) {
								setTimeout(function () {
									window.location.href =
										response.data.redirect_url;
								}, 1000);
							}
							form.attr('lemonway-action', 'step1');
						} else if (response.data.form_step === 'step2') {
							form.find('.step.step1').addClass('lemonway-hide');
							form.find('.step.step2').removeClass(
								'lemonway-hide'
							);
							form.attr('lemonway-action', 'step2');
						} else if (response.data.form_step === 'step3') {
							form.find('.step.step2').addClass('lemonway-hide');
							form.find('.step.step3').removeClass(
								'lemonway-hide'
							);
							form.attr('lemonway-action', 'step3');
						} else if (response.data.form_step === 'completed') {
							form.attr('lemonway-action', 'step1');
							if (response.data.redirect_url) {
								setTimeout(function () {
									window.location.href =
										response.data.redirect_url;
								}, 1000);
							}
						} else if (response.data.form_step === 'bank_link') {
							form.attr('lemonway-action', 'bank_link');
							if (response.data.redirect_url) {
								setTimeout(function () {
									window.location.href =
										response.data.redirect_url;
								}, 1000);
							}
						}
						$('.dokan-ajax-response').html(
							$('<div/>', {
								class: 'dokan-alert dokan-alert-success',
								html: '<p>' + response.data.msg + '</p>',
							})
						);
					} else {
						$('.dokan-ajax-response').html(
							$('<div/>', {
								class: 'dokan-alert dokan-alert-danger',
								html: '<p>' + response.data + '</p>',
							})
						);
					}
				},
				error(xhr, status, error) {
					$('.dokan-ajax-response').html(
						$('<div/>', {
							class: 'dokan-alert dokan-alert-danger',
							html: '<p>Ajax Error: ' + error + '</p>',
						})
					);
				},
				complete() {
					form.find('span.dokan-loading').remove();

					$('html,body').animate({
						scrollTop: $('.dokan-dashboard-header').offset().top,
					});
				},
			});
		},
	};

	lemonyApp.init();
});
