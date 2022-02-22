define([
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/checkout-data',
    'Magento_Ui/js/modal/confirm',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Checkout/js/action/get-payment-information',
    'mage/url',
    'ko'
], function ($, Component, checkoutData, confirmation, getTotalsAction, getPaymentInformationAction, url, ko) {
    'use strict';

    return Component.extend({

        /**
         * Get options from data provider
         *
         * @see \Kozr\UiComponent\Model\Frontend\Checkout\Shipping\ShippingOptionsConfigProvider
         * @array
         */
        optionsArray: window.checkoutConfig.shipping_options,
        checkChanges: ko.observable(false),
        initialize: function () {
            this._super();
            if (!this.optionsArray.enabled) {
                return false;
            }

            this.checkShippingMethod();
            var here = this;
            this.checkChanges.subscribe(function () {
                $('.payment-method._active').removeClass('_active');
                checkoutData.setSelectedPaymentMethod('');
                var deferred = $.Deferred();
                getTotalsAction([], deferred);
                getPaymentInformationAction(deferred);
            });
        },

        /**
         * Check payment method was selected
         */
        checkShippingMethod: function () {
            let self = this;
            $('body').on('click', '#cashondelivery', function () {

                self.showConfirmation(this);
            });
            if (checkoutData.getSelectedPaymentMethod()
                && checkoutData.getSelectedPaymentMethod() === 'cashondelivery') {
                self.showConfirmation();
            }
        },

        /**
         * Show confirmation popup
         */
        showConfirmation: function (changeChecked) {
            let self = this;
            confirmation({
                title: $.mage.__(self.optionsArray.title),
                content: $.mage.__(self.optionsArray.body),

                buttons: [{
                    text: $.mage.__('Cancel'),
                    class: 'action-secondary action-dismiss',
                    click: function (event) {
                        this.closeModal(event);
                        changeChecked.checked = false;
                        self.checkChanges(true);
                        self.checkChanges(false);
                    }
                }, {
                    text: $.mage.__('Confirm'),
                    class: 'action-primary action-accept',
                    click: function (event) {
                        this.closeModal(event, true);
                    }
                }]
            });
        }
    });
});
