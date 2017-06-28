# simDB
simDB is simple object-relational queries helper for SafeMySQL

### Requirements:
<https://github.com/colshrapnel/safemysql>

### DB connection configuration
Connection configuration in Config.class.php
```php
class Config
{
    
    // DB CONNECTION
    const DB_USER = 'root';
    const DB_PASS = '';
    const DB_NAME = 'simdb';
    const DB_CHARSET = 'utf8';

}
```

### Create new table
```php
$result = DB::create()
->table("table_name")
->id('id')
->varchar('title', '255')
->execute();
```

### Insert into table
```php
$result = DB::insert()
->into("data_table")
->set('title','data1')
->execute();
```

### Select from table
```php
$result = DB::select()
->names('id','title')
->from("data_table")
->where('id','1')
->executeAll();
```

### Update table
```php
$result = DB::update()
->table("data_table")
->set('id','1')
->set('title','data2')
->executeODKU();
```

### Delete from table
```php
$result = DB::delete()
->from("data_table")
->where('id','1')
->execute();
```