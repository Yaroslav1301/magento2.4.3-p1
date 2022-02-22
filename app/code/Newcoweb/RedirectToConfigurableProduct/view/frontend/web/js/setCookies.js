require([
        "jquery"
    ], function($) {
        "use strict";
        $(document).on('click', '.item-results .ng-scope', function () {
            $.cookie('search', '1');
        })
    $(document).mouseup(function(e)
    {
        var container = $(".ss-ac-wrapper");
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            if ($.cookie('search')) {
                $.removeCookie('search');
        }
        }
    });
    $.removeCookie = function (key, options) {
        if ($.cookie(key) === undefined) {
            return false;
        }
        $.cookie(key, '', $.extend({}, options, { expires: -1 }));
        return !$.cookie(key);
    };
    });
