'use strict';

/**
 * User form tab extension.
 *
 * @author Maximilien Schall-Fonteilles <maximilien.schall@gmail.com>
 */
define(
    [
        'jquery',
        'underscore',
        'oro/translator',
        'backbone',
        'pim/form',
        'supplier/template/tab/user-grid',
        'pim/fetcher-registry',
        'pim/attribute-manager',
        'pim/user-context',
        'routing',
        'oro/mediator',
        'oro/datagrid-builder',
        'oro/pageable-collection',
        'pim/datagrid/state',
        'require-context'
    ],
    function ($,
              _,
              __,
              Backbone,
              BaseForm,
              gridTemplate,
              FetcherRegistry,
              AttributeManager,
              UserContext,
              Routing,
              mediator,
              datagridBuilder,
              PageableCollection,
              DatagridState,
              requireContext) {
        return BaseForm.extend({
            template: _.template(gridTemplate),
            panesTemplate: _.template(gridTemplate),
            className: 'tab-pane active product-associations',
            dataGrid: {},
            /**
             * Initialize view.
             */
            initialize: function () {
                this.dataGrid = {
                    name: 'supplier-user-grid',
                };

                BaseForm.prototype.initialize.apply(this, arguments);
            },
            /**
             * Configure view by adding mediator listeners.
             */
            configure: function () {
                this.refreshListeners();

                return BaseForm.prototype.configure.apply(this, arguments);
            },
            /**
             * Render view.
             * Render user grid & regenerate events.
             *
             * @returns {exports}
             */
            render: function () {
                if (!this.configured) {
                    return;
                }

                this.$el.html(this.template());
                this.renderGrid();

                return this;
            },
            /**
             * Render grid on post update event.
             */
            postUpdate: function () {
                this.render();
            },
            /**
             * Refresh all listeners.
             */
            refreshListeners: function () {
                mediator.clear('datagrid:selectModel:' + this.dataGrid.name);
                mediator.on('datagrid:selectModel:' + this.dataGrid.name, function (user) {
                    this.addUser(user);
                }.bind(this));

                mediator.clear('datagrid:unselectModel:' + this.dataGrid.name);
                mediator.on('datagrid:unselectModel:' + this.dataGrid.name, function (user) {
                    this.removeUser(user);
                }.bind(this));

                this.listenTo(this.getRoot(), 'pim_enrich:form:entity:post_fetch', this.postUpdate.bind(this));
            },
            /**
             * Render grid.
             */
            renderGrid: function () {
                var urlParams = {
                    alias: this.dataGrid.name,
                    supplier_user_ids: this.getFormData().userIds
                };

                var dataGridState = DatagridState.get(this.dataGrid.name, ['filters']);
                if (null !== dataGridState.filters) {
                    var collection = new PageableCollection();
                    var filters = collection.decodeStateData(dataGridState.filters);

                    collection.processFiltersParams(urlParams, filters, this.dataGrid.name + '[_filter]');
                }

                var gridName = this.dataGrid.name;
                $.get(Routing.generate('pim_datagrid_load', urlParams)).then(function (response) {
                    var metadata = response.metadata;

                    this.$('#grid-' + gridName).data({metadata: metadata, data: JSON.parse(response.data)});

                    var gridModules = metadata.requireJSModules;
                    gridModules.push('pim/datagrid/state-listener');
                    gridModules.push('oro/datafilter-builder');
                    gridModules.push('oro/datagrid/pagination-input');

                    var resolvedModules = [];
                    _.each(gridModules, function (module) {
                        resolvedModules.push(requireContext(module));
                    });
                    datagridBuilder(resolvedModules)
                }.bind(this));
            },
            /**
             * Add user.
             *
             * @param {Object} user
             */
            addUser: function (user) {
                var data = this.getFormData();
                var userId = parseInt(user.attributes.id, 10);

                var userIds = data.userIds;
                if (!userIds || userIds.length === 0) {
                    userIds = [];
                }

                userIds.push(userId);
                data.userIds = userIds;
                this.setData(data);
            },
            /**
             * Remove user.
             *
             * @param {Object} user
             */
            removeUser: function (user) {
                var data = this.getFormData();
                var userId = parseInt(user.attributes.id, 10);

                var userIds = data.userIds;
                if (!userIds || userIds.length === 0) {
                    return;
                }

                userIds.splice(userIds.indexOf(userId), 1);
                data.userIds = userIds;
                this.setData(data);
            },
        });
    }
);
