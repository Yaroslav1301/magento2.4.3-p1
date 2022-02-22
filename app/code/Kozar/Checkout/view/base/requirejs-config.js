var config = {
    'config': {
        'mixins': {
            'Magento_Checkout/js/view/shipping': {
                'Kozar_Checkout/js/view/shipping-payment-mixin': true
            },
            'Magento_Checkout/js/view/payment': {
                'Kozar_Checkout/js/view/shipping-payment-mixin': true
            }
        }
    }
}
