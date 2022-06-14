define([
    'underscore',
    'uiElement',
    'ko'
], function (_, Component, ko) {
    'use strict';

    var Customization;

    Customization = _.extend({
        defaults: {
            componentGreeting: ko.observable(),
            nameGreeting: ko.observable(),
            imports: {
                componentGreeting: "${ $.greetingProvider }:componentGreeting",
                nameGreeting: "${ $.greetingProvider }:nameGreeting"
            }
        },
        initialize: function () {
            this._super();
            return this;
        }
    });

    return Component.extend(Customization);
});
