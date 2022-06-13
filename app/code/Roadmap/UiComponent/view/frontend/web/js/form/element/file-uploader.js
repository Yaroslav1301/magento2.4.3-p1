define([
    'Magento_Ui/js/form/element/file-uploader',
    'ko'
],function (Element, ko) {
    'use strict';

    return Element.extend({

        uploadedImagePath: ko.observable(''),

        addFile: function (file) {
            if (file['name']) {
                this.uploadedImagePath(file['name']);
            }
            this._super();
        }
    });
});
