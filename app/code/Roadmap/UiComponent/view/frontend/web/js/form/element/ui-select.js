define([
    'Magento_Ui/js/form/element/ui-select',
    'ko'
], function (Select, ko) {
    'use strict';

    return Select.extend({

        selectedSku: ko.observable(''),

        toggleOptionSelected: function (data) {
            var isSelected = this.isSelected(data.value);

            if (this.lastSelectable && data.hasOwnProperty(this.separator)) {
                return this;
            }

            if (!this.multiple) {
                if (!isSelected) {
                    this.value(data.value);
                    this.selectedSku(data.value);
                }
                this.listVisible(false);
            } else {
                if (!isSelected) { /*eslint no-lonely-if: 0*/
                    this.value.push(data.value);
                } else {
                    this.value(_.without(this.value(), data.value));
                }
            }
            return this;
        }
    });
});
