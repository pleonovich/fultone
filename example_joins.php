<?php

require_once('Config.class.php');
require_once('safemysql.php');
require_once('DB.class.php');

// INNER JOIN
$result = DB::select()
->all()
->from("data_table")
->innerJoin("data_text","id")
->executeAll();
echo "<h3>innerJoin:</h3>";
var_dump($result);

// LEFT JOIN
$result = DB::select()
->all()
->from("data_table")
->leftJoin("data_text","id")
->executeAll();
echo "<h3>leftJoin:</h3>";
var_dump($result);

// RIGHT JOIN
$result = DB::select()
->all()
->from("data_table")
->rightJoin("data_text","id")
->executeAll();
echo "<h3>rightJoin:</h3>";
var_dump($result);

// CROSS JOIN
$result = DB::select()
->all()
->from("data_table")
->crossJoin("data_text","id")
->executeAll();
echo "<h3>crossJoin:</h3>";
var_dump($result);