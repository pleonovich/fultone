<?php

namespace Fultone;

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
    const TYPE_BIT = 'BIT';

    const TYPE_FLOAT = 'FLOAT';
    const TYPE_DOUBLE = 'DOUBLE';
    const TYPE_DECIMAL = 'DECIMAL';

    const TYPE_CHAR = 'CHAR';
    const TYPE_VARCHAR = 'VARCHAR';
    const TYPE_TINYTEXT = 'TINYTEXT';
    const TYPE_TEXT = 'TEXT';
    const TYPE_MEDIUMTEXT = 'MEDIUMTEXT';
    const TYPE_LONGTEXT = 'LONGTEXT';
    const TYPE_JSON = 'JSON';

    const TYPE_DATE = 'DATE';
    const TYPE_TIME = 'TIME';
    const TYPE_YEAR = 'YEAR';
    const TYPE_DATETIME = 'DATETIME';
    const TYPE_TIMESTAMP = 'TIMESTAMP';

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