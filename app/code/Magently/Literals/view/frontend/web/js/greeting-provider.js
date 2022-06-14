define([
    'underscore',
    'uiElement'
], function (_, Component) {
    'use strict';

    var Customization;

    Customization = _.extend({
        defaults: {
            componentGreeting: "Hello from our ${ $.component } component!",
            nameGreeting: "The component name is ${ $.name }."
        },
        initialize: function () {
            this._super();
            return this;
        }
    });

    return Component.extend(Customization);
});
