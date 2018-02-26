'use strict';

/**
 * Supplier label extension.
 *
 * @author Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
define(
    ['pim/form'],
    function (BaseForm) {
        return BaseForm.extend({
            tagName: 'h1',
            className: 'AknTitleContainer-title',
            /**
             * Configure view.
             */
            configure: function () {
                this.listenTo(this.getRoot(), 'pim_enrich:form:entity:post_update', this.render);

                return BaseForm.prototype.configure.apply(this, arguments);
            },
            /**
             * Render view.
             */
            render: function () {
                this.$el.text(
                    this.getSupplierName()
                );

                return this;
            },
            /**
             * Return supplier "name" property.
             */
            getSupplierName: function() {
                return this.getFormData().name;
            }
        });
    }
);