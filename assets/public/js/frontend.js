/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/src/sass/frontend.scss":
/*!***************************************!*\
  !*** ./assets/src/sass/frontend.scss ***!
  \***************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!***********************************!*\
  !*** ./assets/src/js/frontend.js ***!
  \***********************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sass_frontend_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sass/frontend.scss */ "./assets/src/sass/frontend.scss");
/**
 * SASS
 */

var __ = wp.i18n.__;
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
      changeYear: true,
      // Allows changing the year
      changeMonth: true,
      // Allows changing the month
      yearRange: '1950:2020',
      // Optional: Sets the year range for the dropdown
      defaultDate: new Date(2000, 0, 1),
      beforeShow: function beforeShow(input, inst) {
        // Add a custom class to the datepicker popup
        setTimeout(function () {
          inst.dpDiv.addClass('lemonway-datepicker');
        }, 0);
      }
    });
  }
  var lemonyApp = {
    init: function init() {
      this.attachEventListeners();
      this.toggleAccount();
      this.deactivateLinkAccount();
      return false;
    },
    deactivateLinkAccount: function deactivateLinkAccount() {
      this.handleDeactivateClick();
      this.handleModalCloseClick();
      this.handleDeactivationConfirmation();
    },
    handleDeactivateClick: function handleDeactivateClick() {
      var _this = this;
      // Using event delegation in case new elements are added dynamically
      $(document).on('click', '.link-account-deactivate', function (e) {
        e.preventDefault();
        var ibanId = $(e.currentTarget).data('id'); // Get the iban_id from data attribute

        // Show the modal and store the iban_id in the confirmation button
        _this.toggleModal(true);
        $('#lemonway-iban-deactivation').data('id', ibanId);
      });
    },
    handleModalCloseClick: function handleModalCloseClick() {
      var _this2 = this;
      // Handle closing of the modal
      $('#lemonway-modal-close').on('click', function (e) {
        e.preventDefault();
        _this2.toggleModal(false); // Hide the modal
      });
    },
    handleDeactivationConfirmation: function handleDeactivationConfirmation() {
      var _this3 = this;
      // Handle when the user confirms the deactivation
      $('#lemonway-iban-deactivation').on('click', function (e) {
        e.preventDefault();
        var ibanId = $(e.currentTarget).data('id'); // Get the iban_id from the confirm button

        _this3.showLoadingState();

        // Perform the AJAX request to deactivate the account
        _this3.deactivateAccount(ibanId, e);
      });
    },
    deactivateAccount: function deactivateAccount(ibanId, event) {
      var _this4 = this;
      $.ajax({
        url: ajaxObj.ajaxUrl,
        type: 'POST',
        data: {
          action: 'lemonway_deactivate_link_bank_account',
          iban_id: ibanId,
          nonce: ajaxObj.security // Use the nonce for security
        },
        success: function success(response) {
          return _this4.handleDeactivationResponse(response, event);
        },
        error: function error() {
          return _this4.handleDeactivationError();
        }
      });
    },
    handleDeactivationResponse: function handleDeactivationResponse(response, event) {
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
    handleDeactivationError: function handleDeactivationError() {
      // Handle the error when AJAX fails
      this.updateModalContent(__('An error occurred while deactivating.', 'lemonway'));
    },
    updateModalContent: function updateModalContent(message) {
      // Helper function to update the modal content
      $('#lemonway-modal .modal-content').html(message);
      this.toggleModal(true);
    },
    showLoadingState: function showLoadingState() {
      // Show the loading state in the modal
      $('#lemonway-modal .modal-content').html('<div class="loading-spinner">' + __('Loading...', 'lemonway') + '</div>');
    },
    toggleModal: function toggleModal(show) {
      // Show or hide the modal
      $('#lemonway-modal').toggleClass('active', show);
    },
    hideLoadingState: function hideLoadingState() {
      // Hide the loading state by removing the spinner
      $('#lemonway-modal .modal-content').html(''); // Clear the spinner
    },
    toggleAccount: function toggleAccount() {
      $('.lemonway-settings-account-type-fields input').on('click', function () {
        $('.lemonway-settings-company-fields').toggleClass('lemonway-hide', $(this).val() === 'individual');
      });
    },
    attachEventListeners: function attachEventListeners() {
      var self = this;
      $('.lemonway-save-dokan-btn').on('click', function (e) {
        var _form$attr;
        e.preventDefault();
        var current = $(e.currentTarget);
        var form = current.closest('form'),
          formId = form.attr('id'),
          formStep = (_form$attr = form.attr('lemonway-action')) !== null && _form$attr !== void 0 ? _form$attr : current.attr('data-action');
        self.submitSettings(formId, formStep);
      });
      $('.lemonway-disconnect-dokan-btn').on('click', function (e) {
        e.preventDefault();
        var current = $(e.currentTarget);
        var form = current.closest('form'),
          formId = form.attr('id'),
          formStep = 'disconnect';
        $(':input', form).not(':button, :submit, :reset, :hidden, :checkbox').val('').prop('selected', false);
        self.submitSettings(formId, formStep, true);
      });
    },
    submitSettings: function submitSettings(formId, formStep, isDisconnect) {
      var form = $("form#".concat(formId));
      var formData = new FormData(form[0]);
      formData.append('action', 'lemonway_dokan_settings');
      formData.append('form_id', formId);
      formData.append('form_step', formStep);
      this.handleRequest(form, formData, formStep, isDisconnect);
    },
    handleRequest: function handleRequest(form, formData, formStep, isDisconnect) {
      var loadingSpan = '<span class="dokan-loading"></span>';
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
        success: function success(response) {
          var button = '.lemonway-save-dokan-btn';
          console.log(response.data);
          console.log(response);
          if (response.success) {
            if (response.data.btn_text) {
              form.find(button).text(response.data.btn_text);
            }
            if (response.data.form_step === 'verification') {
              form.find('#form-input-birthDateVerify').closest('.lemonway-hide').removeClass('lemonway-hide');
              form.attr('lemonway-action', 'verification');
            } else if (response.data.form_step === 'verified') {
              if (response.data.redirect_url) {
                setTimeout(function () {
                  window.location.href = response.data.redirect_url;
                }, 1000);
              }
              form.attr('lemonway-action', 'step1');
            } else if (response.data.form_step === 'step2') {
              form.find('.step.step1').addClass('lemonway-hide');
              form.find('.step.step2').removeClass('lemonway-hide');
              form.attr('lemonway-action', 'step2');
            } else if (response.data.form_step === 'step3') {
              form.find('.step.step2').addClass('lemonway-hide');
              form.find('.step.step3').removeClass('lemonway-hide');
              form.attr('lemonway-action', 'step3');
            } else if (response.data.form_step === 'completed') {
              form.attr('lemonway-action', 'step1');
              if (response.data.redirect_url) {
                setTimeout(function () {
                  window.location.href = response.data.redirect_url;
                }, 1000);
              }
            } else if (response.data.form_step === 'bank_link') {
              form.attr('lemonway-action', 'bank_link');
              if (response.data.redirect_url) {
                setTimeout(function () {
                  window.location.href = response.data.redirect_url;
                }, 1000);
              }
            }
            $('.dokan-ajax-response').html($('<div/>', {
              class: 'dokan-alert dokan-alert-success',
              html: '<p>' + response.data.msg + '</p>'
            }));
          } else {
            $('.dokan-ajax-response').html($('<div/>', {
              class: 'dokan-alert dokan-alert-danger',
              html: '<p>' + response.data + '</p>'
            }));
          }
        },
        error: function error(xhr, status, _error) {
          $('.dokan-ajax-response').html($('<div/>', {
            class: 'dokan-alert dokan-alert-danger',
            html: '<p>Ajax Error: ' + _error + '</p>'
          }));
        },
        complete: function complete() {
          form.find('span.dokan-loading').remove();
          $('html,body').animate({
            scrollTop: $('.dokan-dashboard-header').offset().top
          });
        }
      });
    }
  };
  lemonyApp.init();
});
})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map