# Events

Supplier bundle provide you 3 events to use.

| Event | Class & constant | Explanation |
| ----- | ---------------- | ------ |
| supplier.event.create | SupplierEvents:CREATE | This event is dispatched just after a new supplier in instantiated in supplier factory. |
| supplier.event.pre_update |  SupplierEvents:PRE_UPDATE | This event is dispatched just before a supplier is updated by the supplier updater. |
| supplier.event.post_update | SupplierEvents:POST_UPDATE | This event is dispatched just after a supplier is updated by the supplier updater. |


Of course, all akeneo save events are dispatched for supplier entity.