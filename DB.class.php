<?php
/**
 * simDB CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * simDB is simple object-relational update queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 * 
 * Some examples:
 *
 * CREATE TABLE
 * $result = DB::create($conn)
 * ->table("data_table")
 * ->id('id')
 * ->varchar('title', '255')
 * ->execute();
 * 
 * INSERT INTO TABLE
 * $result = DB::insert($conn)
 * ->into("data_table")
 * ->set('title','data1')
 * ->execute();
 * 
 * UPDATE TABLE
 * $result = DB::update($conn)
 * ->table("data_table")
 * ->set('id','1')
 * ->set('title','data2')
 * ->executeODKU();
 * 
 * SELECT FROM TABLE
 * $result = DB::select($conn)
 * ->names('id','title')
 * ->from("data_table")
 * ->where('id','1')
 * ->limit(3)
 * ->executeAll();
 *
 */

require_once('DBselect.class.php');
require_once('DBinsert.class.php');
require_once('DBupdate.class.php');
require_once('DBcreate.class.php');
require_once('DBdelete.class.php');

class DB
{
    /**
     * Select, insert , update or create
     *
     * @param string $name - function name
     * @param array $params - params
     * @return this object
     */
    public static function __callStatic($name, array $params)
    {
        $funcs = array('select', 'insert' , 'update', 'create', 'delete');
        foreach ($funcs as $f) {
            if ($name===$f) {
                $classname = 'DB'.$f;
                if (class_exists($classname)) {
                    return new $classname($params[0]);
                } else {
                    die("Error! $classname class doesn`t found");
                }
            }
        }
    }
}