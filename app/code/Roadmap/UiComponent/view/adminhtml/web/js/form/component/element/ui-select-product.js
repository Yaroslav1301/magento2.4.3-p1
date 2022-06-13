define([
    'Magento_Ui/js/form/element/ui-select'
], function (Select) {
    'use strict';
    return Select.extend({
        toggleOptionSelected: function (data) {
            var isSelected = this.isSelected(data.value);

            if (this.lastSelectable && data.hasOwnProperty(this.separator)) {
                return this;
            }

            if (!this.multiple) {
                var isSelectedValue = false;
                if (!isSelected && !this.isGroupLabel(data.value)) {
                    this.value(data.value);
                    isSelectedValue = true;
                }
                if (isSelectedValue) {
                    this.listVisible(false);
                }

            } else {
                if (!isSelected && !this.isGroupLabel(data.value)) { /*eslint no-lonely-if: 0*/
                    this.value.push(data.value);
                } else {
                    this.value(_.without(this.value(), data.value));
                }
            }

            return this;
        },

        isGroupLabel: function (value) {
            var labels = [
                'Simple Products',
                'Configurable Products',
                'Bundle Products',
                'Downloadable Products',
                'Grouped Products'
            ];
            return labels.includes(value);
        }
    });
});
