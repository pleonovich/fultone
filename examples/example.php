<?php

require __DIR__ . '/../vendor/autoload.php';

require 'src/Config.class.php';
require 'models/Users.class.php';



// CREATE TABLE
// $result = DB::create()
//     ->table("data_table")
//     ->id('id')
//     ->varchar('title', '255')
//     ->execute();
// echo "Create: data_table: ";
// var_dump($result);

// INSERT INTO TABLE
// $result = DB::insert()
//     ->into("data_table")
//     ->set('title','data1')
//     ->execute();
// echo "Insert into data_table: ";
// var_dump($result);

// SELECT FROM TABLE
// $result = DB::select("data_table")
//     ->all()
//     ->executeAll();
// echo "Select from data_table: ";
// var_dump($result);

// UPDATE TABLE
// $result = DB::update()
//     ->table("data_table")
//     ->set('id','1')
//     ->set('title','data2')
//     ->executeODKU();
// echo "Update data_table: ";
// var_dump($result);

// DELETE FROM TABLE
// $result = DB::delete()
//     ->from("data_table")
//     ->where('id','3')
//     ->execute();
// echo "Delete from data_table: ";
// var_dump($result);