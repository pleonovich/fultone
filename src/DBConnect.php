<?php
namespace Fultone;

use Fultone\Config as Config;

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
    private static $opts;
    
    /**
     * DB connection
     * 
     * @return SafeMySQL
     */
    public static function set(ConnectionConfig $opts)
    {
        self::$opts = $opts;
    }

    /**
     * DB connection
     * 
     * @return SafeMySQL
     */
    public static function get()
    {
        if (self::$db===null) {
            self::$db = new \SafeMySQL(array(
            'user'    => Config::$db_user,
            'pass'    => Config::$db_pass,
            'db'      => Config::$db_name,
            'charset' => Config::$db_charset,
            'host'    => Config::$db_host,
            'port'    => Config::$db_port,
            'socket'  => Config::$db_socket,
            'errmode' => Config::$db_errmode,
            ));
        }
        return self::$db;
    }
}