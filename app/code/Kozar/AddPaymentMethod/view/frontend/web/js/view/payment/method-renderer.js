define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'custompayment',
                component: 'Kozar_AddPaymentMethod/js/view/payment/method-renderer/custompayment'
            }
        );

        /** Add view logic here if needed */

        return Component.extend({});
    }
);
