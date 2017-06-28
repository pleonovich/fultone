# simDB
simDB is simple object-relational update queries helper for SafeMySQL

### Requirements:
<https://github.com/colshrapnel/safemysql>

### DB connection
```php
$opts = array(
	'user'    => Config::DB_USER,
	'pass'    => Config::DB_PASS,
	'db'      => Config::DB_NAME,
	'charset' => Config::DB_CHARSET
);
$conn = new SafeMySQL($opts);
```

### Create new table
```php
$result = DB::create($conn)
->table("table_name")
->id('id')
->varchar('title', '255')
->execute();
```

### Insert into table
```php
$result = DB::insert($conn)
->into("data_table")
->set('title','data1')
->execute();
```

### Select from table
```php
$result = DB::select($conn)
->names('id','title')
->from("data_table")
->where('id','1')
->executeAll();
```

### Update table
```php
$result = DB::update($conn)
->table("data_table")
->set('id','1')
->set('title','data2')
->executeODKU();
```

### Delete from table
```php
$result = DB::delete($conn)
->from("data_table")
->where('id','1')
->execute();
```