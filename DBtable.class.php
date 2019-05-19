<?php
/**
 * @author leonovich.pavel@gmail.com
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 */

class DBtable {

    /**
    * @var SafeMySQL
    */
    private $db;
    
    public $charset = 'utf8';
    public $collate = 'utf8_general_ci';

    public $name;
    public $fieldNames = [];
    public $fields = [];

    function __construct (string $name) {
        $this->name = $name;
        $this->db = DBConnect::get();
    }

    public function init() {
        if (count($this->fields) === 0) {
            $description = $this->db->getAll(" DESCRIBE ?n ", $this->name);
            $this->fieldNames = [];
            foreach ($description as $one) {
                $type = self::parseType($one['Type']);
                $isNull = self::parseIsNull($one['Null']);
                $this->fieldNames[] = $one["Field"];
                $this->fields[$one["Field"]] = new DBfield($one["Field"], $type['type'], $type['length'], $isNull);
            }
        }
    }

    /**
     * Add new column
     * 
     * @param string - column name 
     * @param string - data type 
     * @param int - data length 
     * @param boolean - is null 
     * @return Table
     */
    public function field(string $name, string $type, $length, $isNull = null, $key = false, $default = null) {
        $this->fields[$name] = new DBfield($name, $type, $length, $isNull, $key, $default);
        return $this;
    }

    public function checkFieldsNames() {
        if(count($this->fields) > 0 && count($this->fieldNames) === 0) {
            foreach($this->fields as $field) {
                $this->fieldNames[] = $field->name;
            }
        }
    }

    public function getFieldsNames() {
        $this->checkFieldsNames();
        return implode(", ", $this->fieldNames);
    }

    public function issetFieldName(string $name) {
        $this->checkFieldsNames();
        return (in_array($name, $this->fieldNames));
    }

    private static function parseType(string $type) {
        preg_match("/^([a-z]*)\(?([0-9]*)\)?$/i", $type, $matches);
        return [
            "type" => ((strlen($matches[1]) > 0) ? $matches[1] : null),
            "length" => ((strlen($matches[2]) > 0) ? $matches[2] : null),
        ];
    }

    private static function parseIsNull(string $isNull) {
        return ($isNull === "YES") ? true : false;
    }

}