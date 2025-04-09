/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./assets/src/js/payment.js ***!
  \**********************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator.return && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, catch: function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function asyncGeneratorStep(n, t, e, r, o, a, c) { try { var i = n[a](c), u = i.value; } catch (n) { return void e(n); } i.done ? t(u) : Promise.resolve(u).then(r, o); }
function _asyncToGenerator(n) { return function () { var t = this, e = arguments; return new Promise(function (r, o) { var a = n.apply(t, e); function _next(n) { asyncGeneratorStep(a, r, o, _next, _throw, "next", n); } function _throw(n) { asyncGeneratorStep(a, r, o, _next, _throw, "throw", n); } _next(void 0); }); }; }
/**
 * Lemonway Payment Gateway Handler for WooCommerce
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
var __ = wp.i18n.__;
jQuery(function ($) {
  'use strict';

  // Exit if not on checkout page.
  if (!lemonway_payment.is_checkout_page) {
    return;
  }
  var lemonwayHandler = {
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
    initialize: function initialize() {
      var _this = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
        return _regeneratorRuntime().wrap(function _callee$(_context) {
          while (1) switch (_context.prev = _context.next) {
            case 0:
              _context.prev = 0;
              _this.showLoadingState();

              // Initialize payment methods in parallel
              _context.next = 4;
              return Promise.allSettled([_this.initializePaypalButton(), _this.initializeCardFields()]);
            case 4:
              _this.updatePaymentUI();
              _this.setupPaymentMethodListeners();
              _context.next = 12;
              break;
            case 8:
              _context.prev = 8;
              _context.t0 = _context["catch"](0);
              _this.handleError("<div class=\"woocommerce-error\">".concat(wc_checkout_params.i18n_checkout_error, "</div>"));
              // eslint-disable-next-line no-console
              console.error('Payment initialization failed:', _context.t0);
            case 12:
            case "end":
              return _context.stop();
          }
        }, _callee, null, [[0, 8]]);
      }))();
    },
    init: function init() {
      var _this2 = this;
      $(function () {
        _this2.showLoadingState();
        _this2.paymentMethod = $('input[name="payment_method"]:checked', _this2.checkoutForm).val();
        _this2.paymentType = $('input[name="lemonway_payment_type"]:checked', _this2.checkoutForm).val() || 'card';
        $(document.body).on('updated_checkout', /*#__PURE__*/_asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2() {
          return _regeneratorRuntime().wrap(function _callee2$(_context2) {
            while (1) switch (_context2.prev = _context2.next) {
              case 0:
                _context2.next = 2;
                return _this2.initialize();
              case 2:
                setTimeout(function () {
                  _this2.hideLoadingState();
                }, 1000);
              case 3:
              case "end":
                return _context2.stop();
            }
          }, _callee2);
        })));
      });
    },
    /**
     * Show loading state on checkout form.
     */
    showLoadingState: function showLoadingState() {
      $(this.loading).addClass('processing').block({
        message: null,
        overlayCSS: {
          background: '#fff',
          opacity: 0.6
        }
      });
    },
    /**
     * Hide loading state on checkout form.
     */
    hideLoadingState: function hideLoadingState() {
      $(this.loading).removeClass('processing').unblock();
    },
    /**
     * Reset order data after completion or failure.
     */
    resetOrderData: function resetOrderData() {
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
    handleError: function handleError(message) {
      if (this.isProcessing) {
        this.hideLoadingState();
        this.resetOrderData();
      }

      // Safely remove existing notices.
      $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();

      // Safely add new error message.
      if ($(this.checkoutForm).length) {
        $(this.checkoutForm).prepend("<div class=\"woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout\">".concat(message, "</div>"));

        // Trigger validation.
        $(this.checkoutForm).find('.input-text, select, input:checkbox').trigger('validate').trigger('blur');
        this.scrollToError();
      }
      $(document.body).trigger('checkout_error', [message]);
    },
    /**
     * Scroll to the first error message.
     */
    scrollToError: function scrollToError() {
      var $form = $(this.checkoutForm);
      var offset = 100;
      var $header = $('#site-header');
      if ($header.length) {
        offset = $header.outerHeight() + 15;
      }
      if ($form.length) {
        $('html, body').animate({
          scrollTop: $form.offset().top - offset
        }, 1000);
      }
    },
    /**
     * Update payment UI based on selected method.
     */
    updatePaymentUI: function updatePaymentUI() {
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
    isLemonwaySelected: function isLemonwaySelected() {
      return this.paymentMethod === 'lemonway-gateway';
    },
    /**
     * Setup listeners for payment method changes
     */
    setupPaymentMethodListeners: function setupPaymentMethodListeners() {
      var _this3 = this;
      $(this.checkoutForm).on('change', 'input[name="payment_method"], input[name="lemonway_payment_type"]', function () {
        _this3.paymentType = $(_this3.checkoutForm).find('input[name="lemonway_payment_type"]:checked').val() || 'card';
        _this3.paymentMethod = $(_this3.checkoutForm).find('input[name="payment_method"]:checked').val();
        _this3.updatePaymentUI();
      });
    },
    /**
     * Initialize Lemonway Hosted Fields for card payments
     */
    initializeCardFields: function initializeCardFields() {
      var _this4 = this;
      return new Promise(function (resolve) {
        var checkCardFieldsReady = function checkCardFieldsReady() {
          if (window.LwHostedFields && typeof window.LwHostedFields.findLabelFor === 'function') {
            _this4.isCardInitialized = true;
            var style = "\n                            #text { color: black; }\n                            #text.invalid { color: red; }\n                            #text.valid { font-weight: bolder; }\n                        ";
            var config = {
              server: {
                webkitToken: 'Initialize via MoneyInWebInit API'
              },
              client: {
                holderName: {
                  containerId: 'holder-name',
                  placeHolder: 'Cardholder Name',
                  style: style
                },
                cardNumber: {
                  containerId: 'card-number',
                  placeHolder: '4111 1111 1111 1111',
                  style: style
                },
                expirationDate: {
                  containerId: 'expiration-date',
                  placeHolder: 'MM/YY',
                  style: style
                },
                cvv: {
                  containerId: 'cvv',
                  placeHolder: '123',
                  style: style
                }
              }
            };
            _this4.hostedFields = new LwHostedFields(config);
            _this4.hostedFields.mount();

            // Handle card form submission
            $(document).on('click', 'form #place_order', function (e) {
              if (_this4.paymentType !== 'card' || !_this4.isLemonwaySelected()) {
                return;
              }
              e.preventDefault();
              _this4.processCardPayment();
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
    processCardPayment: function processCardPayment() {
      var _this5 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee3() {
        var response, result;
        return _regeneratorRuntime().wrap(function _callee3$(_context3) {
          while (1) switch (_context3.prev = _context3.next) {
            case 0:
              if (!_this5.isProcessing) {
                _context3.next = 2;
                break;
              }
              return _context3.abrupt("return");
            case 2:
              _this5.isProcessing = true;
              _this5.showLoadingState();
              _context3.prev = 4;
              _context3.next = 7;
              return _this5.submitOrderRequest();
            case 7:
              response = _context3.sent;
              result = _this5.processPaymentResponse(response);
              if (!(result && result.token)) {
                _context3.next = 12;
                break;
              }
              _context3.next = 12;
              return _this5.submitCardPayment(result.token);
            case 12:
              _context3.next = 17;
              break;
            case 14:
              _context3.prev = 14;
              _context3.t0 = _context3["catch"](4);
              _this5.handleError("<div class=\"woocommerce-error\">".concat(_context3.t0.message || wc_checkout_params.i18n_checkout_error, "</div>"));
            case 17:
            case "end":
              return _context3.stop();
          }
        }, _callee3, null, [[4, 14]]);
      }))();
    },
    /**
     * Submit card payment to Lemonway
     *
     * @param {string} token - Payment token from order creation
     */
    submitCardPayment: function submitCardPayment(token) {
      var _this6 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee4() {
        return _regeneratorRuntime().wrap(function _callee4$(_context4) {
          while (1) switch (_context4.prev = _context4.next) {
            case 0:
              _context4.prev = 0;
              _this6.hostedFields.config.webkitToken = token;
              _context4.next = 4;
              return _this6.hostedFields.submit(true);
            case 4:
              // Payment succeeded - redirect to success page
              if (_this6.orderSuccessRedirectUrl) {
                window.location.href = _this6.orderSuccessRedirectUrl;
              }
              _context4.next = 10;
              break;
            case 7:
              _context4.prev = 7;
              _context4.t0 = _context4["catch"](0);
              throw new Error("Card payment failed: ".concat(_context4.t0.message));
            case 10:
            case "end":
              return _context4.stop();
          }
        }, _callee4, null, [[0, 7]]);
      }))();
    },
    /**
     * Initialize PayPal Smart Button
     */
    initializePaypalButton: function initializePaypalButton() {
      var _this7 = this;
      return new Promise(function (resolve) {
        var checkPaypalReady = function checkPaypalReady() {
          if (window.paypal && window.paypal.Buttons) {
            _this7.isPaypalInitialized = true;
            window.paypal.Buttons({
              createOrder: function createOrder() {
                return _this7.createPaypalOrder();
              },
              onApprove: function onApprove(data, actions) {
                return _this7.approvePaypalPayment(actions);
              },
              onCancel: function onCancel() {
                return _this7.cancelPaypalPayment();
              },
              onError: function onError(error) {
                return _this7.handlePaypalError(error);
              },
              fundingSource: window.paypal.FUNDING.PAYPAL
            }).render(_this7.paypalContainerSelector).catch(function (error) {
              // eslint-disable-next-line no-console
              console.error('PayPal button render failed:', error);
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
    createPaypalOrder: function createPaypalOrder() {
      var _this8 = this;
      return _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee5() {
        var response;
        return _regeneratorRuntime().wrap(function _callee5$(_context5) {
          while (1) switch (_context5.prev = _context5.next) {
            case 0:
              if (_this8.isLemonwaySelected()) {
                _context5.next = 2;
                break;
              }
              throw new Error('Lemonway payment method not selected');
            case 2:
              _this8.showLoadingState();
              $('.woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message').remove();
              _context5.prev = 4;
              _context5.next = 7;
              return _this8.submitOrderRequest();
            case 7:
              response = _context5.sent;
              return _context5.abrupt("return", _this8.processPaymentResponse(response));
            case 11:
              _context5.prev = 11;
              _context5.t0 = _context5["catch"](4);
              _this8.handleError("<div class=\"woocommerce-error\">".concat(_context5.t0.message, "</div>"));
              throw _context5.t0;
            case 15:
            case "end":
              return _context5.stop();
          }
        }, _callee5, null, [[4, 11]]);
      }))();
    },
    /**
     * Handle approved PayPal payment
     *
     * @param {Object} actions - The PayPal actions object used to capture payment.
     */
    approvePaypalPayment: function approvePaypalPayment(actions) {
      var _this9 = this;
      return new Promise(function (resolve) {
        _this9.capturePayment(_this9.currentOrderId, _this9.orderSuccessRedirectUrl, actions).then(resolve).catch(function (error) {
          _this9.handleError("<div class=\"woocommerce-error\">".concat(error.message, "</div>"));
          resolve();
        });
      });
    },
    /**
     * Handle canceled PayPal payment
     */
    cancelPaypalPayment: function cancelPaypalPayment() {
      this.hideLoadingState();
      this.handleError("<div class=\"woocommerce-error\">".concat(__('Your payment has been cancelled. Please try again or contact support if the issue persists.', 'lemonway'), "</div>"));
      this.resetOrderData();
    },
    /**
     * Handle PayPal errors
     *
     * @param {Error|string} error - The error object or message to be displayed. This could be an instance of an `Error` or a string.
     */
    handlePaypalError: function handlePaypalError(error) {
      if (!$('.woocommerce-error').length) {
        this.handleError("<div class=\"woocommerce-error\">".concat(error.toString(), "</div>"));
      }
      this.hideLoadingState();
      this.resetOrderData();
    },
    /**
     * Submit order to WooCommerce
     */
    submitOrderRequest: function submitOrderRequest() {
      var isPayPage = lemonway_payment.is_checkout_pay_page;
      var requestData = isPayPage ? {
        order_id: lemonway_payment.order_id,
        action: 'lemonway_create_order',
        nonce: lemonway_payment.nonce,
        lemonway_payment_type: this.paymentType
      } : $(this.checkoutForm).serialize();
      return $.ajax({
        type: 'POST',
        url: isPayPage ? lemonway_payment.ajaxurl : wc_checkout_params.checkout_url,
        data: requestData,
        dataType: 'json'
      });
    },
    /**
     * Process payment response from server
     *
     * @param {Object} response - AJAX response from server
     */
    processPaymentResponse: function processPaymentResponse(response) {
      var _response$data2;
      // Handle pay page response format
      if (lemonway_payment.is_checkout_pay_page) {
        var _response$data;
        response = ((_response$data = response.data) === null || _response$data === void 0 ? void 0 : _response$data.data) || response;
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
              lemonway_transaction_id: response.lemonway_transaction_id
            };
          case 'paypal':
            return response.paypal_order_id;
          default:
            // eslint-disable-next-line no-console
            console.warn('Unknown payment type:', response.payment_type);
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
      throw new Error(response.messages || ((_response$data2 = response.data) === null || _response$data2 === void 0 ? void 0 : _response$data2.message) || wc_checkout_params.i18n_checkout_error);
    },
    /**
     * Capture payment for PayPal orders
     *
     * @param {string} orderId - The ID of the order being processed.
     *  @param {string} successUrl - The URL to redirect to after a successful payment.
     *   @param {Object} actions - The actions object for handling PayPal actions. It may include a restart function in case of a declined instrument.
     */
    capturePayment: function capturePayment(orderId, successUrl, actions) {
      this.showLoadingState();
      return $.ajax({
        type: 'POST',
        url: lemonway_payment.ajaxurl,
        data: {
          order_id: orderId,
          action: 'lemonway_capture_payment',
          nonce: lemonway_payment.nonce
        },
        dataType: 'json'
      }).then(function (response) {
        if (!response.success) {
          var _response$data3, _response$data4, _response$data5;
          var details = (_response$data3 = response.data) === null || _response$data3 === void 0 || (_response$data3 = _response$data3.message) === null || _response$data3 === void 0 || (_response$data3 = _response$data3.details) === null || _response$data3 === void 0 ? void 0 : _response$data3[0];
          if ((details === null || details === void 0 ? void 0 : details.issue) === 'INSTRUMENT_DECLINED' && actions !== null && actions !== void 0 && actions.restart) {
            return actions.restart();
          }
          var errorMessage = (details === null || details === void 0 ? void 0 : details.description) || ((_response$data4 = response.data) === null || _response$data4 === void 0 || (_response$data4 = _response$data4.message) === null || _response$data4 === void 0 ? void 0 : _response$data4.message) || ((_response$data5 = response.data) === null || _response$data5 === void 0 ? void 0 : _response$data5.message);
          throw new Error(errorMessage);
        }
        window.location.href = successUrl;
      }).catch(function (error) {
        var _error$responseJSON;
        if ((_error$responseJSON = error.responseJSON) !== null && _error$responseJSON !== void 0 && _error$responseJSON.reload) {
          window.location.reload();
        } else {
          throw error;
        }
      });
    }
  };

  // Call it at the end.
  lemonwayHandler.init();
});
/******/ })()
;
//# sourceMappingURL=payment.js.map