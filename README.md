# SupplierBundle

Allow to handle suppliers in your Akeneo application.  
All users associated at least with one supplier will see only products associated to this supplier.

## Requirements

| SupplierBundle       | Akeneo Version     |
| -------------------- | ------------------ |
| v1.0.*               | v2.0.*             |

## Installation

If you have not installed the [custom entity bundle](https://github.com/akeneo-labs/CustomEntityBundle) yet, please do it.

You can install this bundle with composer :

Add the following lines to the "repositories" section of your composer.json file.
```javascript
"repositories": [
        // ...
        {
            "type": "vcs",
            "url": "https://github.com/igcopensources/akeneo-supplier-bundle.git",
            "branch": "master"
        }
        // ...
]
```
Then launch the following command line.  
```bash
php composer.phar require "igcopensources/akeneo-supplier-bundle":"1.0.*"
```

Enable the bundle in the app/AppKernel.php file in the registerBundles() method:
  
```php
$bundles = [
    // ...
    new Cgi\SupplierBundle\CgiSupplierBundle(),
    // ...
]
```

Update your application.
```bash
rm -rf var/cache/*
bin/console doctrine:schema:update --force -e=prod
bin/console pim:installer:assets -e=prod
yarn run webpack
```

## Documentation

- [Configure supplier as reference data](docs/reference-data-configuration.md)
- [Use CSV file to import you suppliers](docs/supplier-import-configuration.md)
- [Add properties to your supplier](docs/override-supplier.md)
- [Provided events](docs/events.md)