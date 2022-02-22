define([
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'Magento_Checkout/js/model/step-navigator',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/checkout-data',
    'mage/storage',
    'Magento_Checkout/js/model/url-builder',
    'mage/url',
    'Magento_Checkout/js/model/quote',
], function ($, ko, Component, _, stepNavigator, customer, checkoutData, storage, urlBuilder, url, quote) {
    'use strict';

    /**
     * register-step - is the name of the component's .html template,
     * Roadmap_AddNewCheckoutStep  - is the name of your module directory.
     */

    return Component.extend({
        defaults: {
            template: 'Roadmap_AddNewCheckoutStep/register-step'
        },

        isVisible: ko.observable(false),
        continueGuest: ko.observable(false),
        isEmailCheckComplete: null,
        checkRequest: null,
        isEmailAvailable: ko.observable(true),
        validatedEmail: ko.observable(),
        canRegister: false,


        initialize: function () {
            this._super();
            if (!this.isUserLoggedIn() && !quote.isVirtual()) {
                this.isVisible(true);
                stepNavigator.registerStep(
                    'register-step',
                    null,
                    'Register Step or Continue Guest',
                    this.isVisible,
                    _.bind(this.navigate, this),
                    15
                );
            }
            return this;
        },

        navigate: function () {
            this.isVisible(true);
        },

        navigateToNextStep: function () {
            if (this.continueGuest()) {
                stepNavigator.next();
            }
        },

        isUserLoggedIn: function () {
            return !!customer.isLoggedIn();
        },

        checkEmailAvailability: function (deffered) {
            storage.post(
                urlBuilder.createUrl('/customers/isEmailAvailable', {}),
                JSON.stringify({
                    customerEmail: checkoutData.getInputFieldEmailValue()
                }),
                false
            ).done(function (isEmailAvailable) {
                if (isEmailAvailable) {
                    deffered.resolve();
                } else {
                    deffered.reject();
                }
            }).fail(function () {
                deffered.reject();
            });
        },

        updateEmailAvailability: function () {
            this.isEmailCheckComplete = $.Deferred();
            this.checkEmailAvailability(this.isEmailCheckComplete);
            $.when(this.isEmailCheckComplete).done(function () {
                this.isEmailAvailable(true);
            }.bind(this)).fail(function () {
                this.isEmailAvailable(false);
            }.bind(this));

        },

        updateImportantValues: function () {
            var self = this;
            $('#shipping-method-buttons-container button').on('click', function () {
                if (!self.isEmailAvailable()) {
                    self.continueGuest(true);
                    $('#register-step').trigger('click');
                }
            });
            if (checkoutData.getInputFieldEmailValue() === "") {
                return false;
            } else {
                this.validatedEmail(checkoutData.getInputFieldEmailValue());
                this.updateEmailAvailability();
                return true;
            }
        },

        registerNewCustomer: function () {
            var self = this;
            if (this.canRegister) {
                var data = checkoutData.getShippingAddressFromData();
                data['email'] = checkoutData.getValidatedEmailValue();
                data['password'] = $('#password').val();
                data['confirm_password'] = $('#confirm_password').val();
                if (data['password'] === data['confirm_password']) {
                    $.ajax({
                        url: url.build('register/checkout/customer'),
                        type: 'POST',
                        showLoader: true,
                        data: data
                    }).done(function (response) {
                        var comment = response['comment'];
                        if (response['result']) {
                            $("#registration-message-success").addClass('register-success-message').text(comment).show();
                            self.cleanPasswordFields();
                            self.isEmailAvailable(false);
                            self.continueGuest(true);
                            setTimeout(self.navigateToNextStep.bind(self), 2000);
                        } else {
                            $("#registration-message-success").addClass('register-error-message').text(comment).show();
                            self.cleanPasswordFields();
                        }
                    });
                } else {
                    $('#pswd-message-confirm').show();
                }

            } else {
                $('#pswd-message-not-valid').show();
            }

        },

        onFocus: function () {
            $('#pswd_info').show();
            $('#pswd-message-not-valid').hide();
            $('#pswd-message-confirm').hide();
        },

        onBlur: function () {
            $('#pswd_info').hide();
        },

        onKeyUp: function () {
            var validateArray = [false, false, false, false];
            //validate length
            var password = $('#password').val();
            if ( password.length < 8 ) {
                $('#length').removeClass('valid').addClass('invalid');
                validateArray[0] = false;
            } else {
                $('#length').removeClass('invalid').addClass('valid');
                validateArray[0] = true;
            }

            //validate letter
            if ( password.match(/[A-z]/) ) {
                $('#letter').removeClass('invalid').addClass('valid');
                validateArray[1] = true;
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
                validateArray[1] = false;
            }

            //validate capital letter
            if ( password.match(/[A-Z]/) ) {
                $('#capital').removeClass('invalid').addClass('valid');
                validateArray[2] = true;
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
                validateArray[2] = false;
            }

            //validate number
            if ( password.match(/\d/) ) {
                $('#number').removeClass('invalid').addClass('valid');
                validateArray[3] = true;
            } else {
                $('#number').removeClass('valid').addClass('invalid');
                validateArray[3] = false;
            }

            for (var i = 0; i < validateArray.length; i++) {
                if (validateArray[i] === true) {
                    this.canRegister = true;
                } else {
                    this.canRegister = false;
                    break;
                }
            }
        },

        onFocusConfirm: function () {
            $('#pswd-message-not-valid').hide();
            $('#pswd-message-confirm').hide();
        },

        cleanPasswordFields: function () {
            $('#password').val('');
            $('#confirm_password').val('');
        }
    });
});
