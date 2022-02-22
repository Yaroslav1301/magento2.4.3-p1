define(
    [
        'jquery',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/url-builder',
        'Magento_Checkout/js/model/error-processor',
        'mage/url'
    ],
    function ($, quote, urlBuilder, errorProcessor, urlFormatter) {
        'use strict';
        return {
            /**
             * Save Comment ibn the quote
             *
             * @param comment
             */
            save: function (comment) {
                if (comment) {
                    var quoteId = quote.getQuoteId();
                    var url;
                    url = urlBuilder.createUrl('/carts/mine/set-comment', {});
                    var payload = {
                        cartId: quoteId,
                        comment: {
                            comment: comment
                        }
                    };
                    if (!payload.comment.comment) {
                        return true;
                    }
                    var result = true;
                    $.ajax({
                        url: urlFormatter.build(url),
                        data: JSON.stringify(payload),
                        global: false,
                        contentType: 'application/json',
                        type: 'PUT',
                        async: false
                    }).done(
                        function (response) {
                            result = true;
                        }
                    ).fail(
                        function (response) {
                            result = false;
                            errorProcessor.process(response);
                        }
                    );
                    return result;
                }
            }
        };
    }
);
