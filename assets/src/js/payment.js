/**
 * Lemonway Payment Gateway Handler for WooCommerce yml
 *
 * Handles all frontend payment processing for Lemonway gateway including:
 * - Credit card payments via Hosted Fields
 * - PayPal Smart Button integration
 * - Wire transfer processing
 *
 * Features:
 * - Robust error handling and user feedback
 * - Dynamic payment method switching
 * - Proper WooCommerce checkout integration
 * - Async payment processing with loading states
 *
 * @since 1.0.0
 * @requires jQuery, WooCommerce checkout, Lemonway Hosted Fields SDK, PayPal SDK
 */

// Import the necessary functions from wp-i18n
const { __ } = wp.i18n;
jQuery(function ($) {
	'use strict';

	// Exit if not on checkout page.
	if (!lemonway_payment.is_checkout_page) {
		return;
	}

	const lemonwayHandler = {
		// DOM Selectors.
		checkoutForm: 'form.checkout, form#order_review',
		checkoutFormButton: '#place_order',
		loading: 'form.checkout, form#order_review',
		paypalContainerSelector: '#lemonway-paypal-button-container',
		cardContainerSelector: '#lemonway-payment-method-card-fields',

		// Payment state.
		paymentType: 'card',
		paymentMethod: '',
		orderSuccessRedirectUrl: '',
		orderCancelRedirectUrl: '',
		hostedFields: null,
		isPaypalInitialized: false,
		isCardInitialized: false,
		currentOrderId: '',
		isProcessing: false,

		/**
		 * Initialize all payment methods and event handlers.
		 */
		async initialize() {
			try {
				this.showLoadingState();

				// Initialize payment methods in parallel
				await Promise.allSettled([
					this.initializePaypalButton(),
					this.initializeCardFields(),
				]);

				this.updatePaymentUI();
				this.setupPaymentMethodListeners();
			} catch (error) {
				this.handleError(
					`<div class="woocommerce-error">${wc_checkout_params.i18n_checkout_error}</div>`
				);
				// eslint-disable-next-line no-console
				console.error('Payment initialization failed:', error);
			}
		},
		init() {
			$(() => {
				this.showLoadingState();

				this.paymentMethod = $(
					'input[name="payment_method"]:checked',
					this.checkoutForm
				).val();
				this.paymentType =
					$(
						'input[name="lemonway_payment_type"]:checked',
						this.checkoutForm
					).val() || 'card';

				$(document.body).on('updated_checkout', async () => {
					await this.initialize();
					setTimeout(() => {
						this.hideLoadingState();
					}, 1000);
				});
			});
		},

		/**
		 * Show loading state on checkout form.
		 */
		showLoadingState() {
			$(this.loading)
				.addClass('processing')
				.block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6,
					},
				});
		},

		/**
		 * Hide loading state on checkout form.
		 */
		hideLoadingState() {
			$(this.loading).removeClass('processing').unblock();
		},

		/**
		 * Reset order data after completion or failure.
		 */
		resetOrderData() {
			this.orderSuccessRedirectUrl = '';
			this.orderCancelRedirectUrl = '';
			this.currentOrderId = '';
			this.isProcessing = false;
		},

		/**
		 * Handle and display errors to the user.
		 *
		 * @param {string} message - Error message HTML.
		 */
		handleError(message) {
			if (this.isProcessing) {
				this.hideLoadingState();
				this.resetOrderData();
			}

			// Safely remove existing notices.
			$(
				'.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message'
			).remove();

			// Safely add new error message.
			if ($(this.checkoutForm).length) {
				$(this.checkoutForm).prepend(
					`<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">${message}</div>`
				);

				// Trigger validation.
				$(this.checkoutForm)
					.find('.input-text, select, input:checkbox')
					.trigger('validate')
					.trigger('blur');

				this.scrollToError();
			}

			$(document.body).trigger('checkout_error', [message]);
		},

		/**
		 * Scroll to the first error message.
		 */
		scrollToError() {
			const $form = $(this.checkoutForm);
			let offset = 100;
			const $header = $('#site-header');
			if ($header.length) {
				offset = $header.outerHeight() + 15;
			}
			if ($form.length) {
				$('html, body').animate(
					{ scrollTop: $form.offset().top - offset },
					1000
				);
			}
		},

		/**
		 * Update payment UI based on selected method.
		 */
		updatePaymentUI() {
			// Hide all Lemonway elements initially
			$(this.paypalContainerSelector).hide();
			$(this.cardContainerSelector).hide();

			// Exit if Lemonway not selected
			if (!this.isLemonwaySelected()) {
				$(this.checkoutFormButton).show();
				return;
			}

			// Show relevant elements based on payment type
			switch (this.paymentType) {
				case 'card':
					$(this.checkoutFormButton).show();
					$(this.cardContainerSelector).show();
					break;

				case 'paypal':
					$(this.checkoutFormButton).hide();
					$(this.paypalContainerSelector).show();
					break;
			}
		},

		/**
		 * Check if Lemonway is the selected payment method
		 *
		 * @return {boolean} payment method.
		 */
		isLemonwaySelected() {
			return this.paymentMethod === 'lemonway-gateway';
		},

		/**
		 * Setup listeners for payment method changes
		 */
		setupPaymentMethodListeners() {
			$(this.checkoutForm).on(
				'change',
				'input[name="payment_method"], input[name="lemonway_payment_type"]',
				() => {
					this.paymentType =
						$(this.checkoutForm)
							.find('input[name="lemonway_payment_type"]:checked')
							.val() || 'card';

					this.paymentMethod = $(this.checkoutForm)
						.find('input[name="payment_method"]:checked')
						.val();

					this.updatePaymentUI();
				}
			);
		},

		/**
		 * Initialize Lemonway Hosted Fields for card payments
		 */
		initializeCardFields() {
			return new Promise((resolve) => {
				const checkCardFieldsReady = () => {
					if (
						window.LwHostedFields &&
						typeof window.LwHostedFields.findLabelFor === 'function'
					) {
						this.isCardInitialized = true;

						const style = `
                            #text { color: black; }
                            #text.invalid { color: red; }
                            #text.valid { font-weight: bolder; }
                        `;

						const config = {
							server: {
								webkitToken:
									'Initialize via MoneyInWebInit API',
							},
							client: {
								holderName: {
									containerId: 'holder-name',
									placeHolder: 'Cardholder Name',
									style,
								},
								cardNumber: {
									containerId: 'card-number',
									placeHolder: '4111 1111 1111 1111',
									style,
								},
								expirationDate: {
									containerId: 'expiration-date',
									placeHolder: 'MM/YY',
									style,
								},
								cvv: {
									containerId: 'cvv',
									placeHolder: '123',
									style,
								},
							},
						};

						this.hostedFields = new LwHostedFields(config);
						this.hostedFields.mount();

						// Handle card form submission
						$(document).on('click', 'form #place_order', (e) => {
							if (
								this.paymentType !== 'card' ||
								!this.isLemonwaySelected()
							) {
								return;
							}

							e.preventDefault();
							this.processCardPayment();
						});

						resolve();
					} else {
						setTimeout(checkCardFieldsReady, 500);
					}
				};

				checkCardFieldsReady();
			});
		},

		/**
		 * Process card payment when place order clicked
		 */
		async processCardPayment() {
			if (this.isProcessing) {
				return;
			}

			this.isProcessing = true;
			this.showLoadingState();

			try {
				const response = await this.submitOrderRequest();
				const result = this.processPaymentResponse(response);

				if (result && result.token) {
					await this.submitCardPayment(result.token);
				}
			} catch (error) {
				this.handleError(
					`<div class="woocommerce-error">${
						error.message || wc_checkout_params.i18n_checkout_error
					}</div>`
				);
			}
		},

		/**
		 * Submit card payment to Lemonway
		 *
		 * @param {string} token - Payment token from order creation
		 */
		async submitCardPayment(token) {
			try {
				this.hostedFields.config.webkitToken = token;
				await this.hostedFields.submit(true);

				// Payment succeeded - redirect to success page
				if (this.orderSuccessRedirectUrl) {
					window.location.href = this.orderSuccessRedirectUrl;
				}
			} catch (error) {
				throw new Error(`Card payment failed: ${error.message}`);
			}
		},

		/**
		 * Initialize PayPal Smart Button
		 */
		initializePaypalButton() {
			return new Promise((resolve) => {
				const checkPaypalReady = () => {
					if (window.paypal && window.paypal.Buttons) {
						this.isPaypalInitialized = true;

						window.paypal
							.Buttons({
								createOrder: () => this.createPaypalOrder(),
								onApprove: (data, actions) =>
									this.approvePaypalPayment(actions),
								onCancel: () => this.cancelPaypalPayment(),
								onError: (error) =>
									this.handlePaypalError(error),
								fundingSource: window.paypal.FUNDING.PAYPAL,
							})
							.render(this.paypalContainerSelector)
							.catch((error) => {
								// eslint-disable-next-line no-console
								console.error(
									'PayPal button render failed:',
									error
								);
							});

						resolve();
					} else {
						setTimeout(checkPaypalReady, 500);
					}
				};

				checkPaypalReady();
			});
		},

		/**
		 * Create PayPal order when button clicked
		 */
		async createPaypalOrder() {
			if (!this.isLemonwaySelected()) {
				throw new Error('Lemonway payment method not selected');
			}

			this.showLoadingState();
			$(
				'.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message'
			).remove();

			try {
				const response = await this.submitOrderRequest();
				return this.processPaymentResponse(response);
			} catch (error) {
				this.handleError(
					`<div class="woocommerce-error">${error.message}</div>`
				);
				throw error;
			}
		},

		/**
		 * Handle approved PayPal payment
		 *
		 * @param {Object} actions - The PayPal actions object used to capture payment.
		 */
		approvePaypalPayment(actions) {
			return new Promise((resolve) => {
				this.capturePayment(
					this.currentOrderId,
					this.orderSuccessRedirectUrl,
					actions
				)
					.then(resolve)
					.catch((error) => {
						this.handleError(
							`<div class="woocommerce-error">${error.message}</div>`
						);
						resolve();
					});
			});
		},

		/**
		 * Handle canceled PayPal payment
		 */
		cancelPaypalPayment() {
			this.hideLoadingState();
			this.handleError(
				`<div class="woocommerce-error">${__(
					'Your payment has been cancelled. Please try again or contact support if the issue persists.',
					'lemonway'
				)}</div>`
			);
			this.resetOrderData();
		},

		/**
		 * Handle PayPal errors
		 *
		 * @param {Error|string} error - The error object or message to be displayed. This could be an instance of an `Error` or a string.
		 */
		handlePaypalError(error) {
			if (!$('.woocommerce-error').length) {
				this.handleError(
					`<div class="woocommerce-error">${error.toString()}</div>`
				);
			}
			this.hideLoadingState();
			this.resetOrderData();
		},

		/**
		 * Submit order to WooCommerce
		 */
		submitOrderRequest() {
			const isPayPage = lemonway_payment.is_checkout_pay_page;
			const requestData = isPayPage
				? {
						order_id: lemonway_payment.order_id,
						action: 'lemonway_create_order',
						nonce: lemonway_payment.nonce,
						lemonway_payment_type: this.paymentType,
				  }
				: $(this.checkoutForm).serialize();

			return $.ajax({
				type: 'POST',
				url: isPayPage
					? lemonway_payment.ajaxurl
					: wc_checkout_params.checkout_url,
				data: requestData,
				dataType: 'json',
			});
		},

		/**
		 * Process payment response from server.
		 *
		 * @param {Object} response - AJAX response from server
		 */
		processPaymentResponse(response) {
			// Handle pay page response format
			if (lemonway_payment.is_checkout_pay_page) {
				response = response.data?.data || response;
			}

			if (!response) {
				throw new Error('Empty server response');
			}

			if (response.result === 'success') {
				this.orderSuccessRedirectUrl = response.success_redirect;
				this.orderCancelRedirectUrl = response.cancel_redirect;
				this.currentOrderId = response.order_id;

				switch (response.payment_type) {
					case 'card':
						return {
							token: response.token,
							lemonway_transaction_id:
								response.lemonway_transaction_id,
						};

					case 'paypal':
						return response.paypal_order_id;

					default:
						// eslint-disable-next-line no-console
						console.warn(
							'Unknown payment type:',
							response.payment_type
						);
						return null;
				}
			}

			// Handle error responses
			if (response.reload) {
				window.location.reload();
			}

			if (response.refresh) {
				$(document.body).trigger('update_checkout');
			}

			throw new Error(
				response.messages ||
					response.data?.message ||
					wc_checkout_params.i18n_checkout_error
			);
		},

		/**
		 * Capture payment for PayPal orders.
		 *
		 * @param {string} orderId - The ID of the order being processed.
		 *  @param {string} successUrl - The URL to redirect to after a successful payment.
		 *   @param {Object} actions - The actions object for handling PayPal actions. It may include a restart function in case of a declined instrument.
		 */
		capturePayment(orderId, successUrl, actions) {
			this.showLoadingState();

			return $.ajax({
				type: 'POST',
				url: lemonway_payment.ajaxurl,
				data: {
					order_id: orderId,
					action: 'lemonway_capture_payment',
					nonce: lemonway_payment.nonce,
				},
				dataType: 'json',
			})
				.then((response) => {
					if (!response.success) {
						const details = response.data?.message?.details?.[0];

						if (
							details?.issue === 'INSTRUMENT_DECLINED' &&
							actions?.restart
						) {
							return actions.restart();
						}

						const errorMessage =
							details?.description ||
							response.data?.message?.message ||
							response.data?.message;
						throw new Error(errorMessage);
					}

					window.location.href = successUrl;
				})
				.catch((error) => {
					if (error.responseJSON?.reload) {
						window.location.reload();
					} else {
						throw error;
					}
				});
		},
	};

	// Call it at the end.
	lemonwayHandler.init();
});
