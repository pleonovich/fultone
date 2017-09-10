<?php

require_once('Config.class.php');
require_once('safemysql.php');
require_once('DB.class.php');

// CREATE TABLE
$result = DB::create()
->table("data_table")
->id('id')
->varchar('title', '255')
->execute();
echo "<h3>Create:</h3>";
var_dump($result);

// INSERT INTO TABLE
$result = DB::insert()
->into("data_table")
->set('title','data1')
->execute();
echo "<h3>Insert:</h3>";
var_dump($result);

// SELECT FROM TABLE
$result = DB::select()
->all()
->from("data_table")
->innerJoin("data_text","id")
//->where('id','1')
->executeAll();
echo "<h3>Select:</h3>";
var_dump($result);

// UPDATE TABLE
$result = DB::update()
->table("data_table")
->set('id','1')
->set('title','data2')
->executeODKU();
echo "<h3>Update:</h3>";
var_dump($result);

// DELETE FROM TABLE
$result = DB::delete()
->from("data_table")
->where('id','3')
->execute();
echo "<h3>Delete:</h3>";
var_dump($result);