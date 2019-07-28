# Fultone
simple object-relational queries helper for SafeMySQL

### Requirements:
<https://github.com/colshrapnel/safemysql>

### DB connection configuration
```php
Fultone\Config::$db_user = 'root';
Fultone\Config::$db_pass = '1234';
Fultone\Config::$db_name = 'simdb';
Fultone\Config::$db_charset = 'utf8';
```
# Using Models

### Create model for users table
```php
use Fultone\DBmodel as DBmodel;
use Fultone\DBtable as DBtable;
use Fultone\DBfield as DBfield;

class Users extends DBmodel {

    public $name = 'user_list';

    protected function schema(DBtable $table) {
        $table
        ->field('id', DBfield::TYPE_INT, 10, false)
        ->field('user_name', DBfield::TYPE_VARCHAR, 255, false)
        ->field('user_login', DBfield::TYPE_VARCHAR, 255, false)
        ->field('email', DBfield::TYPE_VARCHAR, 255, false)
        ->field('secret', DBfield::TYPE_TEXT, null, false);
    }

    // Set some data for admin
    protected function setData(){
        return [
            'user_name'=>'Admin',
            'user_login'=>'admin',
            'email'=>'admin@admin.net',
            'secret'=>'12345'
        ];
    }
}

$users = new Users();

// Migrate table to connected db
$users->migrate();

// Migrate data form model class
$users->insertData();
```

### Insert into table
```php
$users->create([
    "name" => "john smith",
    "phone" => "+375441212121",
    "description" => "some text about john smith"
]);
```

### Select from table
```php
// get all rows
$result = $users->findAll();

// get one row by value
$result = $users->findOne([ "id"=> 1 ]);

// set operator
// one of '=','!=','<>','>','<','>=','<=','LIKE','IS','IN','NOT IN'
$result = $users->findAll([ "id"=> ["!=" => 1 ]);

// operator IN
$result = $users->findAll("id"=>[ "IN" => [1,2] ]);

// search by string
$result = $users->findAll([ "name"=> ["LIKE"=>"%john%"] ]);

// conditions operators AND, OR, NOT
$result = $users->findAll([ "AND"=> [
    "id" => ["!=" => 1 ],
    "phone" => ["LIKE"=>"+37529%"],
]);
```

### Update table
```php
$users->where([
    "id"=> 1
])->update([
    "name" => "john smith",
    "phone" => "+375441212121",
    "description" => "some text about john smith"
]);
```

### Delete from table
```php
$users->delete([ "id"=> 1 ]);
```
