define([
    'uiComponent',
    'jquery',
    'Roadmap_CustomizeCheckoutStep/js/action/save-comment-to-quote'
], function (Component, $, saveComment) {
    'use strict';

    return Component.extend({
        defaults: {
            template: "Roadmap_CustomizeCheckoutStep/add-comment"
        },

        showCommentArea: function () {
            var commentBlock = $('#customer-comment-block');
            if (commentBlock.css('display') === 'none') {
                commentBlock.show();
                $('#comment-arrow').removeClass('down').addClass('up');
            } else {
                commentBlock.hide();
                $('#comment-arrow').removeClass('up').addClass('down');
            }
        },

        putCommentToQuote: function () {
            var comment = $('#customer-comment').val();
            saveComment.save(comment);
        }
    });
});
