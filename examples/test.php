<?php
require __DIR__ . '/../vendor/autoload.php';

require_once('models/Users.class.php');

$users = new Users();
var_dump($users->findAll());

// require_once('models/Comrads.class.php');
// $comrads = new Comrads();
// $comrads->migrate();
// $comrads->create([
//     "name" => "john smith",
//     "phone" => "+375441212121",
//     "description" => "some text about john smith"
// ]);
// $comrads->where(["id"=>2])->update([
//     "name" => "john smith",
//     "phone" => "+375441212121",
//     "description" => "some text about john smith"
// ]);
// var_dump($comrads->findAll([
//     "id"=>["IN"=>[1,2]],
// ]));