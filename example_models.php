<?php

require_once('Config.class.php');
require_once('safemysql.php');
require_once('lib/log.class.php');
require_once('DB.class.php');
require_once('Model.class.php');
require_once('models/Users.class.php');

// MIGRATE TABLE FROM MODEL USERS
$result = Users::migrate();
echo "<h3>migrate:</h3>";
var_dump($result);

// INSERT DATA FROM MODEL USERS
$result = Users::insertData();
echo "<h3>insertData:</h3>";
var_dump($result);

// INSERT INTO USERS
$result = Users::insert()
->set('user_name','John Doe')
->set('user_login','johnd')
->set('email',date('johnd@gmail.com'))
->execute();
echo "<h3>Insert:</h3>";
var_dump($result);

// GET ALL
$result = Users::all();
echo "<h3>All:</h3>";
var_dump($result);

// GET NAMES
$result = Users::getNames('user_name','user_login');
echo "<h3>getNames:</h3>";
var_dump($result);

// GET COLUMN
$result = Users::column('user_name');
echo "<h3>column:</h3>";
var_dump($result);

// GET BY ID
$result = Users::getById(1);
echo "<h3>getById:</h3>";
var_dump($result);

// GET BY VALUE
$result = Users::getByValue('user_name','Admin');
echo "<h3>getByValue:</h3>";
var_dump($result);

// GET NAMES BY VALUE
$result = Users::namesByValue(array('user_name','user_login'),'user_name','Admin');
echo "<h3>namesByValue:</h3>";
var_dump($result);

// UPDATE DATA FROM POST
$_POST['id'] = 2;
$_POST['user_login'] = 'johnd';
$result = Users::save();
echo "<h3>save:</h3>";
var_dump($result);

// ADD DATA FROM POST
$_POST['id'] = 0;
$_POST['user_name'] = 'Stranger';
$_POST['user_login'] = 'strr';
$_POST['email'] = 'strr@gmail.com';
$result = Users::save();
echo "<h3>save:</h3>";
var_dump($result);


?>