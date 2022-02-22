require([
    'jquery'
], function (jQuery) {
    'use strict';
    jQuery(document).ready(function(){
            jQuery(document).on('click', 'select[name=magenest\\[status\\]]', function() {
                var selected = jQuery('select[name=magenest\\[status\\]]').val();
                if (selected === "1") {
                    jQuery('input[name=magenest\\[textField\\]]').prop("disabled",false);
                }else {
                    jQuery('input[name=magenest\\[textField\\]]').prop("disabled",true);
                }
            });
    });
});
