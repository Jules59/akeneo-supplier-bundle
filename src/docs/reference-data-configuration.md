# Reference data supplier configuration

This section explains how to use supplier as reference data.

## Use supplier as simple select product attribute

- Update your config to add the supplier reference data.
```yaml
    pim_reference_data:
        ...
        supplier:
            class: Cgi\SupplierBundle\Entity\Supplier
            type: simple    
```
- Create an attribute with type "Reference data simple select" & choose "supplier" as reference data.

## Use supplier as multi-select product attribute

- Update your config to add the supplier reference data.
```yaml
    pim_reference_data:
        ...
        suppliers:
            class: Cgi\SupplierBundle\Entity\Supplier
            type: multi    
```
- Create an attribute with type "Reference data multi select" & choose "supplier" as reference data.

