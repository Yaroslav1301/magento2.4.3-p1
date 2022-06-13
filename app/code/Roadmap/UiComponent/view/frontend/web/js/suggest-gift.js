define([
    'uiComponent',
    'ko'
],function (Component, ko) {
    'use strict';

    return Component.extend({

        initialize: function (config) {
            this._super();
            this.config = config;

        },
        formTitle: ko.observable('Please fill the form'),

    });
});
