<?php
/**
 * @author leonovich.pavel@gmail.com
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 */

class DBConnect {

    /**
    * @var SafeMySQL
    */
    private static $db;
    
    /**
     * DB connection
     * 
     * @return SafeMySQL
     */
    public static function get()
    {
        if (self::$db===null) {
            self::$db = new SafeMySQL(array(
            'user'    => Config::DB_USER,
            'pass'    => Config::DB_PASS,
            'db'      => Config::DB_NAME,
            'charset' => Config::DB_CHARSET,
            'host'    => Config::DB_HOST,
            'port'    => Config::DB_PORT,

            'socket'    => Config::DB_SOCKET,
            'errmode'   => Config::DB_ERRMODE,
            ));
        }
        return self::$db;
    }
}