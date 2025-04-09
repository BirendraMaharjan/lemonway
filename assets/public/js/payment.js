/*! For license information please see payment.js.LICENSE.txt */
(()=>{function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(){"use strict";t=function(){return n};var r,n={},o=Object.prototype,a=o.hasOwnProperty,i=Object.defineProperty||function(e,t,r){e[t]=r.value},c="function"==typeof Symbol?Symbol:{},s=c.iterator||"@@iterator",u=c.asyncIterator||"@@asyncIterator",l=c.toStringTag||"@@toStringTag";function d(e,t,r){return Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{d({},"")}catch(r){d=function(e,t,r){return e[t]=r}}function h(e,t,r,n){var o=t&&t.prototype instanceof g?t:g,a=Object.create(o.prototype),c=new I(n||[]);return i(a,"_invoke",{value:F(e,r,c)}),a}function p(e,t,r){try{return{type:"normal",arg:e.call(t,r)}}catch(e){return{type:"throw",arg:e}}}n.wrap=h;var m="suspendedStart",f="suspendedYield",y="executing",v="completed",w={};function g(){}function _(){}function k(){}var b={};d(b,s,(function(){return this}));var P=Object.getPrototypeOf,x=P&&P(P(N([])));x&&x!==o&&a.call(x,s)&&(b=x);var L=k.prototype=g.prototype=Object.create(b);function S(e){["next","throw","return"].forEach((function(t){d(e,t,(function(e){return this._invoke(t,e)}))}))}function E(t,r){function n(o,i,c,s){var u=p(t[o],t,i);if("throw"!==u.type){var l=u.arg,d=l.value;return d&&"object"==e(d)&&a.call(d,"__await")?r.resolve(d.__await).then((function(e){n("next",e,c,s)}),(function(e){n("throw",e,c,s)})):r.resolve(d).then((function(e){l.value=e,c(l)}),(function(e){return n("throw",e,c,s)}))}s(u.arg)}var o;i(this,"_invoke",{value:function(e,t){function a(){return new r((function(r,o){n(e,t,r,o)}))}return o=o?o.then(a,a):a()}})}function F(e,t,n){var o=m;return function(a,i){if(o===y)throw Error("Generator is already running");if(o===v){if("throw"===a)throw i;return{value:r,done:!0}}for(n.method=a,n.arg=i;;){var c=n.delegate;if(c){var s=O(c,n);if(s){if(s===w)continue;return s}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if(o===m)throw o=v,n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o=y;var u=p(e,t,n);if("normal"===u.type){if(o=n.done?v:f,u.arg===w)continue;return{value:u.arg,done:n.done}}"throw"===u.type&&(o=v,n.method="throw",n.arg=u.arg)}}}function O(e,t){var n=t.method,o=e.iterator[n];if(o===r)return t.delegate=null,"throw"===n&&e.iterator.return&&(t.method="return",t.arg=r,O(e,t),"throw"===t.method)||"return"!==n&&(t.method="throw",t.arg=new TypeError("The iterator does not provide a '"+n+"' method")),w;var a=p(o,e.iterator,t.arg);if("throw"===a.type)return t.method="throw",t.arg=a.arg,t.delegate=null,w;var i=a.arg;return i?i.done?(t[e.resultName]=i.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=r),t.delegate=null,w):i:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,w)}function T(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function C(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function I(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(T,this),this.reset(!0)}function N(t){if(t||""===t){var n=t[s];if(n)return n.call(t);if("function"==typeof t.next)return t;if(!isNaN(t.length)){var o=-1,i=function e(){for(;++o<t.length;)if(a.call(t,o))return e.value=t[o],e.done=!1,e;return e.value=r,e.done=!0,e};return i.next=i}}throw new TypeError(e(t)+" is not iterable")}return _.prototype=k,i(L,"constructor",{value:k,configurable:!0}),i(k,"constructor",{value:_,configurable:!0}),_.displayName=d(k,l,"GeneratorFunction"),n.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===_||"GeneratorFunction"===(t.displayName||t.name))},n.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,k):(e.__proto__=k,d(e,l,"GeneratorFunction")),e.prototype=Object.create(L),e},n.awrap=function(e){return{__await:e}},S(E.prototype),d(E.prototype,u,(function(){return this})),n.AsyncIterator=E,n.async=function(e,t,r,o,a){void 0===a&&(a=Promise);var i=new E(h(e,t,r,o),a);return n.isGeneratorFunction(t)?i:i.next().then((function(e){return e.done?e.value:i.next()}))},S(L),d(L,l,"Generator"),d(L,s,(function(){return this})),d(L,"toString",(function(){return"[object Generator]"})),n.keys=function(e){var t=Object(e),r=[];for(var n in t)r.push(n);return r.reverse(),function e(){for(;r.length;){var n=r.pop();if(n in t)return e.value=n,e.done=!1,e}return e.done=!0,e}},n.values=N,I.prototype={constructor:I,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=r,this.done=!1,this.delegate=null,this.method="next",this.arg=r,this.tryEntries.forEach(C),!e)for(var t in this)"t"===t.charAt(0)&&a.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=r)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function n(n,o){return c.type="throw",c.arg=e,t.next=n,o&&(t.method="next",t.arg=r),!!o}for(var o=this.tryEntries.length-1;o>=0;--o){var i=this.tryEntries[o],c=i.completion;if("root"===i.tryLoc)return n("end");if(i.tryLoc<=this.prev){var s=a.call(i,"catchLoc"),u=a.call(i,"finallyLoc");if(s&&u){if(this.prev<i.catchLoc)return n(i.catchLoc,!0);if(this.prev<i.finallyLoc)return n(i.finallyLoc)}else if(s){if(this.prev<i.catchLoc)return n(i.catchLoc,!0)}else{if(!u)throw Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return n(i.finallyLoc)}}}},abrupt:function(e,t){for(var r=this.tryEntries.length-1;r>=0;--r){var n=this.tryEntries[r];if(n.tryLoc<=this.prev&&a.call(n,"finallyLoc")&&this.prev<n.finallyLoc){var o=n;break}}o&&("break"===e||"continue"===e)&&o.tryLoc<=t&&t<=o.finallyLoc&&(o=null);var i=o?o.completion:{};return i.type=e,i.arg=t,o?(this.method="next",this.next=o.finallyLoc,w):this.complete(i)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),w},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.finallyLoc===e)return this.complete(r.completion,r.afterLoc),C(r),w}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var r=this.tryEntries[t];if(r.tryLoc===e){var n=r.completion;if("throw"===n.type){var o=n.arg;C(r)}return o}}throw Error("illegal catch attempt")},delegateYield:function(e,t,n){return this.delegate={iterator:N(e),resultName:t,nextLoc:n},"next"===this.method&&(this.arg=r),w}},n}function r(e,t,r,n,o,a,i){try{var c=e[a](i),s=c.value}catch(e){return void r(e)}c.done?t(s):Promise.resolve(s).then(n,o)}function n(e){return function(){var t=this,n=arguments;return new Promise((function(o,a){var i=e.apply(t,n);function c(e){r(i,o,a,c,s,"next",e)}function s(e){r(i,o,a,c,s,"throw",e)}c(void 0)}))}}var o=wp.i18n.__;jQuery((function(e){"use strict";lemonway_payment.is_checkout_page&&{checkoutForm:"form.checkout, form#order_review",checkoutFormButton:"#place_order",loading:"form.checkout, form#order_review",paypalContainerSelector:"#lemonway-paypal-button-container",cardContainerSelector:"#lemonway-payment-method-card-fields",paymentType:"card",paymentMethod:"",orderSuccessRedirectUrl:"",orderCancelRedirectUrl:"",hostedFields:null,isPaypalInitialized:!1,isCardInitialized:!1,currentOrderId:"",isProcessing:!1,initialize:function(){var e=this;return n(t().mark((function r(){return t().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,e.showLoadingState(),t.next=4,Promise.allSettled([e.initializePaypalButton(),e.initializeCardFields()]);case 4:e.updatePaymentUI(),e.setupPaymentMethodListeners(),t.next=12;break;case 8:t.prev=8,t.t0=t.catch(0),e.handleError('<div class="woocommerce-error">'.concat(wc_checkout_params.i18n_checkout_error,"</div>")),console.error("Payment initialization failed:",t.t0);case 12:case"end":return t.stop()}}),r,null,[[0,8]])})))()},init:function(){var r=this;e((function(){r.showLoadingState(),r.paymentMethod=e('input[name="payment_method"]:checked',r.checkoutForm).val(),r.paymentType=e('input[name="lemonway_payment_type"]:checked',r.checkoutForm).val()||"card",e(document.body).on("updated_checkout",n(t().mark((function e(){return t().wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return e.next=2,r.initialize();case 2:setTimeout((function(){r.hideLoadingState()}),1e3);case 3:case"end":return e.stop()}}),e)}))))}))},showLoadingState:function(){e(this.loading).addClass("processing").block({message:null,overlayCSS:{background:"#fff",opacity:.6}})},hideLoadingState:function(){e(this.loading).removeClass("processing").unblock()},resetOrderData:function(){this.orderSuccessRedirectUrl="",this.orderCancelRedirectUrl="",this.currentOrderId="",this.isProcessing=!1},handleError:function(t){this.isProcessing&&(this.hideLoadingState(),this.resetOrderData()),e(".woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message").remove(),e(this.checkoutForm).length&&(e(this.checkoutForm).prepend('<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">'.concat(t,"</div>")),e(this.checkoutForm).find(".input-text, select, input:checkbox").trigger("validate").trigger("blur"),this.scrollToError()),e(document.body).trigger("checkout_error",[t])},scrollToError:function(){var t=e(this.checkoutForm),r=100,n=e("#site-header");n.length&&(r=n.outerHeight()+15),t.length&&e("html, body").animate({scrollTop:t.offset().top-r},1e3)},updatePaymentUI:function(){if(e(this.paypalContainerSelector).hide(),e(this.cardContainerSelector).hide(),this.isLemonwaySelected())switch(this.paymentType){case"card":e(this.checkoutFormButton).show(),e(this.cardContainerSelector).show();break;case"paypal":e(this.checkoutFormButton).hide(),e(this.paypalContainerSelector).show()}else e(this.checkoutFormButton).show()},isLemonwaySelected:function(){return"lemonway-gateway"===this.paymentMethod},setupPaymentMethodListeners:function(){var t=this;e(this.checkoutForm).on("change",'input[name="payment_method"], input[name="lemonway_payment_type"]',(function(){t.paymentType=e(t.checkoutForm).find('input[name="lemonway_payment_type"]:checked').val()||"card",t.paymentMethod=e(t.checkoutForm).find('input[name="payment_method"]:checked').val(),t.updatePaymentUI()}))},initializeCardFields:function(){var t=this;return new Promise((function(r){var n=function(){if(window.LwHostedFields&&"function"==typeof window.LwHostedFields.findLabelFor){t.isCardInitialized=!0;var o="\n                            #text { color: black; }\n                            #text.invalid { color: red; }\n                            #text.valid { font-weight: bolder; }\n                        ",a={server:{webkitToken:"Initialize via MoneyInWebInit API"},client:{holderName:{containerId:"holder-name",placeHolder:"Cardholder Name",style:o},cardNumber:{containerId:"card-number",placeHolder:"4111 1111 1111 1111",style:o},expirationDate:{containerId:"expiration-date",placeHolder:"MM/YY",style:o},cvv:{containerId:"cvv",placeHolder:"123",style:o}}};t.hostedFields=new LwHostedFields(a),t.hostedFields.mount(),e(document).on("click","form #place_order",(function(e){"card"===t.paymentType&&t.isLemonwaySelected()&&(e.preventDefault(),t.processCardPayment())})),r()}else setTimeout(n,500)};n()}))},processCardPayment:function(){var e=this;return n(t().mark((function r(){var n,o;return t().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(!e.isProcessing){t.next=2;break}return t.abrupt("return");case 2:return e.isProcessing=!0,e.showLoadingState(),t.prev=4,t.next=7,e.submitOrderRequest();case 7:if(n=t.sent,!(o=e.processPaymentResponse(n))||!o.token){t.next=12;break}return t.next=12,e.submitCardPayment(o.token);case 12:t.next=17;break;case 14:t.prev=14,t.t0=t.catch(4),e.handleError('<div class="woocommerce-error">'.concat(t.t0.message||wc_checkout_params.i18n_checkout_error,"</div>"));case 17:case"end":return t.stop()}}),r,null,[[4,14]])})))()},submitCardPayment:function(e){var r=this;return n(t().mark((function n(){return t().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return t.prev=0,r.hostedFields.config.webkitToken=e,t.next=4,r.hostedFields.submit(!0);case 4:r.orderSuccessRedirectUrl&&(window.location.href=r.orderSuccessRedirectUrl),t.next=10;break;case 7:throw t.prev=7,t.t0=t.catch(0),new Error("Card payment failed: ".concat(t.t0.message));case 10:case"end":return t.stop()}}),n,null,[[0,7]])})))()},initializePaypalButton:function(){var e=this;return new Promise((function(t){var r=function(){window.paypal&&window.paypal.Buttons?(e.isPaypalInitialized=!0,window.paypal.Buttons({createOrder:function(){return e.createPaypalOrder()},onApprove:function(t,r){return e.approvePaypalPayment(r)},onCancel:function(){return e.cancelPaypalPayment()},onError:function(t){return e.handlePaypalError(t)},fundingSource:window.paypal.FUNDING.PAYPAL}).render(e.paypalContainerSelector).catch((function(e){console.error("PayPal button render failed:",e)})),t()):setTimeout(r,500)};r()}))},createPaypalOrder:function(){var r=this;return n(t().mark((function n(){var o;return t().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(r.isLemonwaySelected()){t.next=2;break}throw new Error("Lemonway payment method not selected");case 2:return r.showLoadingState(),e(".woocommerce-NoticeGroup-checkout, .woocommerce-error, .woocommerce-message").remove(),t.prev=4,t.next=7,r.submitOrderRequest();case 7:return o=t.sent,t.abrupt("return",r.processPaymentResponse(o));case 11:throw t.prev=11,t.t0=t.catch(4),r.handleError('<div class="woocommerce-error">'.concat(t.t0.message,"</div>")),t.t0;case 15:case"end":return t.stop()}}),n,null,[[4,11]])})))()},approvePaypalPayment:function(e){var t=this;return new Promise((function(r){t.capturePayment(t.currentOrderId,t.orderSuccessRedirectUrl,e).then(r).catch((function(e){t.handleError('<div class="woocommerce-error">'.concat(e.message,"</div>")),r()}))}))},cancelPaypalPayment:function(){this.hideLoadingState(),this.handleError('<div class="woocommerce-error">'.concat(o("Your payment has been cancelled. Please try again or contact support if the issue persists.","lemonway"),"</div>")),this.resetOrderData()},handlePaypalError:function(t){e(".woocommerce-error").length||this.handleError('<div class="woocommerce-error">'.concat(t.toString(),"</div>")),this.hideLoadingState(),this.resetOrderData()},submitOrderRequest:function(){var t=lemonway_payment.is_checkout_pay_page,r=t?{order_id:lemonway_payment.order_id,action:"lemonway_create_order",nonce:lemonway_payment.nonce,lemonway_payment_type:this.paymentType}:e(this.checkoutForm).serialize();return e.ajax({type:"POST",url:t?lemonway_payment.ajaxurl:wc_checkout_params.checkout_url,data:r,dataType:"json"})},processPaymentResponse:function(t){var r,n;if(lemonway_payment.is_checkout_pay_page&&(t=(null===(n=t.data)||void 0===n?void 0:n.data)||t),!t)throw new Error("Empty server response");if("success"===t.result)switch(this.orderSuccessRedirectUrl=t.success_redirect,this.orderCancelRedirectUrl=t.cancel_redirect,this.currentOrderId=t.order_id,t.payment_type){case"card":return{token:t.token,lemonway_transaction_id:t.lemonway_transaction_id};case"paypal":return t.paypal_order_id;default:return console.warn("Unknown payment type:",t.payment_type),null}throw t.reload&&window.location.reload(),t.refresh&&e(document.body).trigger("update_checkout"),new Error(t.messages||(null===(r=t.data)||void 0===r?void 0:r.message)||wc_checkout_params.i18n_checkout_error)},capturePayment:function(t,r,n){return this.showLoadingState(),e.ajax({type:"POST",url:lemonway_payment.ajaxurl,data:{order_id:t,action:"lemonway_capture_payment",nonce:lemonway_payment.nonce},dataType:"json"}).then((function(e){if(!e.success){var t,o,a,i=null===(t=e.data)||void 0===t||null===(t=t.message)||void 0===t||null===(t=t.details)||void 0===t?void 0:t[0];if("INSTRUMENT_DECLINED"===(null==i?void 0:i.issue)&&null!=n&&n.restart)return n.restart();var c=(null==i?void 0:i.description)||(null===(o=e.data)||void 0===o||null===(o=o.message)||void 0===o?void 0:o.message)||(null===(a=e.data)||void 0===a?void 0:a.message);throw new Error(c)}window.location.href=r})).catch((function(e){var t;if(null===(t=e.responseJSON)||void 0===t||!t.reload)throw e;window.location.reload()}))}}.init()}))})();