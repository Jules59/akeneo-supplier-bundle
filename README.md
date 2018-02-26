# SupplierBundle

Allow to handle suppliers in your Akeneo application.  
All users associated at least with one supplier will see only products associated to this supplier.

## Requirements

| SupplierBundle       | Akeneo Version     |
| -------------------- | ------------------ |
| v1.0.*               | v2.0.*             |

## Installation

If you have not install the [custom entity bundle](https://github.com/akeneo-labs/CustomEntityBundle) yet, please do it.

You can install this bundle with composer :
```bash
    php composer.phar require "igc-lille/supplier-bundle":"1.0.*"
```

Enable the bundle in the app/AppKernel.php file in the registerBundles() method:
```php
    $bundles = [
        // ...
        new Cgi\SupplierBundle\CgiSupplierBundle(),
        // ...
    ]
```

## Documentation

- [Configure supplier as reference data](src/docs/reference-data-configuration.md)
- [Use CSV file to import you suppliers](src/docs/supplier-import-configuration.md)
- [Add properties to your supplier](src/docs/override-supplier.md)
- [Provided events](src/docs/events.md)