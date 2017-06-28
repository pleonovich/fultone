<?php

require_once('Config.class.php');
require_once('safemysql.php');
require_once('DB.class.php');

$opts = array(
	'user'    => Config::DB_USER,
	'pass'    => Config::DB_PASS,
	'db'      => Config::DB_NAME,
	'charset' => Config::DB_CHARSET
);

// SafeMySQL
$conn = new SafeMySQL($opts);

// CREATE TABLE
$result = DB::create($conn)
->table("data_table")
->id('id')
->varchar('title', '255')
->execute();
echo "<h3>Create:</h3>";
var_dump($result);

// INSERT INTO TABLE
$result = DB::insert($conn)
->into("data_table")
->set('title','data1')
->execute();
echo "<h3>Insert:</h3>";
var_dump($result);

// SELECT FROM TABLE
$result = DB::select($conn)
->names('id','title')
->from("data_table")
->where('id','1')
->limit(3)
->executeAll();
echo "<h3>Select:</h3>";
var_dump($result);

// UPDATE TABLE
$result = DB::update($conn)
->table("data_table")
->set('id','1')
->set('title','data2')
->executeODKU();
echo "<h3>Update:</h3>";
var_dump($result);