/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/* global AdminOrder */
define([
    'jquery',
    'Magento_Sales/order/create/scripts'
], function (jQuery) {
    'use strict';


    AdminOrder.prototype.setShippingMethod = function (method) {
        if (method == 'simpleshipping_simpleshipping') {
            jQuery('.admin__field.field.field-middlename').addClass('required _required');
            jQuery('.admin__field-control.control input.input-text.admin__control-text').addClass('required-entry _required');

        } else {
            jQuery('.admin__field.field.field-middlename.required._required').removeClass('required _required');
            jQuery('.admin__field-control.control input.input-text.admin__control-text.required-entry._required').addClass('required-entry _required');
            jQuery('#order-billing_address_middlename-error').css("display","none");
        }
        var data = {};
        data['order[shipping_method]'] = method;
        this.loadArea([
            'shipping_method',
            'totals',
            'billing_method'
        ], true, data);
    }
});
