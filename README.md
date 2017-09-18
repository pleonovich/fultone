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

# Using Models

### Migrate table form model class
```php

class Users extends Model {

    protected static $table = 'userlist';

    protected static function schema($create){
        $create
        ->id()
        ->varchar('user_name')
        ->varchar('user_login')
        ->varchar('email')
        ->text('secret')
        ->int('manager')
        ->int('moderator');
    }
}

Users::migrate();
```

### Migrate data form model class
```php

class Users extends Model {

    protected static $table = 'userlist';

    protected static function setData($insert){
        $insert
        ->set('user_name','Admin')
        ->set('user_login','admin')
        ->set('secret','12345')
        ->set('manager','1')
        ->set('moderator','1');
    }
}

Users::insertData();
```

### Get all
```php
// Description:
Array all()

// Example:
$result = Users::all();
```

### Get specific columns
```php
// Description:
Array getNames( mixed ) unlimited number of arguments

// Example:
$result = Users::getNames('user_name','user_login');
```

### Get one column
```php
// Description:
Array column( String )

// Example:
$result = Users::column('user_name');
```

### Get by id
```php
// Description:
Array getById( int $id )

// Example:
$result = Users::getById(1);
```

### Get by value
```php
// Description:
Array getByValue( String $name, String $value )

// Example:
$result = Users::getByValue('user_name','Admin');
``` 

### Get names by value
```php
// Description:
Array namesByValue( Array $names, String $name, String $value )

// Example:
$result = Users::namesByValue(array('user_name','user_login'),'user_name','Admin');
``` 

### Update data from post
```php
// Description:
Boolean save()

// Example:
$_POST['id'] = 2;
$_POST['user_login'] = 'johnd';
Users::save();

// if id = 0 data will be inserted
$_POST['id'] = 0;
$_POST['user_name'] = 'Stranger';
$_POST['user_login'] = 'strr';
$_POST['email'] = 'strr@gmail.com';
Users::save();
```
