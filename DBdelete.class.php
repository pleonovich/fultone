<?php
/**
 * DB DELETE CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBcreate is simple object-relational create queries helper for SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 * 
 * Example:
 *
 * $result = DBdelete::factory()
 * ->from("test_delete")
 * ->id('id')
 * ->varchar('name', '50')
 * ->date('birthdate')
 * ->text('about')
 * ->execute();
 *
 */

require_once('DBquery.class.php');

class DBdelete extends DBquery
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Set table name
     *
     * @param string $table - table name
     * @return this object
     */
    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Render sql query
     */
    public function render()
    {
        $query = $this->db->parse(" DELETE FROM ?n ", $this->table);        
        $query.= $this->getWhereQuery();
        //echo $query;
        return $query;
    }

    public static function factory()
    {
        return new DBcreate();
    }

}