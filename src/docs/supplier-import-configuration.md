# Import suppliers with CSV file

## Create CSV supplier import profile

### From interface

From the front, you can create a job instance based on job profile "CSV Supplier Import" available in the "Supplier Connector".

### From comment line

```bash
bin/console akeneo:batch:create-job "Supplier Connector" "supplier_csv_import" "import" "<your_job_code>" "{}" "<your_job_label>"
```

=> For Akeneo EE, you have to allow users to see the job instance. Just follow this procedure :  
`Retrieve the id of the job instance`
```bash
bin/console doctrine:query:sql "SELECT id FROM akeneo_batch_job_instance WHERE code='<your_job_code>';"
```
`Retrieve the if of the group you want allow to access the job`
```bash
bin/console doctrine:query:sql "SELECT id FROM oro_access_group WHERE name='<your_user_group_name>';"
```
`Set the permissions to view & edit`
```bash
bin/console doctrine:query:sql "INSERT INTO pimee_security_job_profile_access VALUES (NULL, <found_job_id>, <found_user_group_id>, 1, 1);"
```

## CSV file

Suppliers are define with 3 properties :
- code
- name
- users

You can find an [example of CSV file](data/suppliers.csv) to import in this bundle.

The file is composed by 3 columns:

| Column | Property                                         |
| ------ | -------------------------------------------------|
| code   | `Identifier` of the supplier to create or update |
| name   | Supplier name                                    |
| users  | `User names` of users associated to supplier     |


## Validation
These properties are under validation.

### Code
- Is required
- Is unique
- Can contain 30 characters maximum
- Can contain only letters, numbers & underscores

### Name
- Is required
- Can contain 255 characters maximum

### Users
- Have to exist