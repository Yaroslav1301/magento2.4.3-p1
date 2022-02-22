define(['jquery'], function ($) {
    "use strict";
    function cleanUrl()
    {
        var uri = window.location.toString();
        if (uri.indexOf("?") > 0) {
            var clean_uri = uri.substring(0, uri.indexOf("?"));
            window.history.replaceState({}, document.title, clean_uri);
            window.location.href = clean_uri;
        }
    }
    return function myscript()
    {
        $('#first').on('click', function () {
            cleanUrl();
            window.location.href += "?tabId=0";

        });
        $('#second').on('click', function () {
            cleanUrl();
            window.location.href += "?tabId=1";
        });
        $('#third').on('click', function () {
            cleanUrl();
            window.location.href += "?tabId=2";
        });
    }
});
