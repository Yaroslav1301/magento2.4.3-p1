define([
    'jquery',
    'Magento_Checkout/js/checkout-data',
    'Magento_Ui/js/modal/confirm',
    'ko',
    'Magento_Checkout/js/model/payment/additional-validators',
    'Magento_Checkout/js/action/redirect-on-success'
], function ($, checkoutData, confirmation, ko, additionalValidators,redirectOnSuccessAction) {
    'use strict';
    return function (target) {
        return target.extend({
            /**
             * @inheritdoc
             */
            placeOrder: function (data, event) {
                var self = this;
                var myViewModel =  {
                    checkConfirm: ko.observable(false)
                };
                confirmation({
                    title: $.mage.__('Are you sure you want to use '+checkoutData.getSelectedShippingRate()+' shipping method?'),
                    content: $.mage.__(),

                    buttons: [{
                        text: $.mage.__('Cancel'),
                        class: 'action-secondary action-dismiss',
                        click: function (event) {
                            this.closeModal(event, true);
                        }
                    }, {
                        text: $.mage.__('Confirm'),
                        class: 'action-primary action-accept',
                        click: function (event) {
                            this.closeModal(event, true);
                            myViewModel.checkConfirm(true);
                        }
                    }]
                });

                myViewModel.checkConfirm.subscribe(function (newValue) {
                    if (event) {
                        event.preventDefault();
                    }

                    if (self.validate() &&
                        additionalValidators.validate() &&
                        self.isPlaceOrderActionAllowed() === true
                    ) {
                        self.isPlaceOrderActionAllowed(false);

                        self.getPlaceOrderDeferredObject()
                            .done(
                                function () {
                                    self.afterPlaceOrder();

                                    if (self.redirectAfterPlaceOrder) {
                                        redirectOnSuccessAction.execute();
                                    }
                                }
                            ).always(
                                function () {
                                    self.isPlaceOrderActionAllowed(true);
                                }
                            );

                        return true;
                    }

                    return false;
                });

            }
        });
    };
});


