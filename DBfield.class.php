<?php
/**
 * @author leonovich.pavel@gmail.com
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 */

class DBfield {

    public $name;
    public $type;
    public $length;
    public $value;
    public $isNull;
    public $key;
    public $default;
    public $comments;

    const TYPE_INT = 'INT';
    const TYPE_TINYINT = 'TINYINT';
    const TYPE_MEDIUMINT = 'MEDIUMINT';
    const TYPE_SMALLINT = 'SMALLINT';
    const TYPE_BIGINT = 'BIGINT';
    const TYPE_CHAR = 'CHAR';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_TEXT = 'TEXT';

    function __construct (string $name, string $type, $length, $isNull = true, $key = false, $default = null) {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->isNull = $isNull;
        $this->key = $key;
        $this->default = $default;
    }

    public function set($value) {
        $this->value = $value;
    }

    public function setDefault(string $default) {
        $this->default = $default;
    }

    public function setComment(string $comment) {
        $this->comment = $comment;
    }

    public static function factory(string $name, string $type, $length, $isNull = true, $key = false, $default = null){
        return new DBfield($name, $type, $length, $isNull, $key, $default);
    }

}