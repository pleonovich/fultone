<?php
use Fultone\DBmodel as DBmodel;
use Fultone\DBtable as DBtable;
use Fultone\DBfield as DBfield;

/**
 * COMRADS CLASS EXAMPLE
 */
class Comrads extends DBmodel {

    public $name = 'comrads';

    protected function schema (DBtable $table) {
        $table
        ->field('id', DBfield::TYPE_INT, 11, false, true)
        ->field('name', DBfield::TYPE_VARCHAR, 255, false)
        ->field('phone', DBfield::TYPE_VARCHAR, 255, false)
        ->field('description', DBfield::TYPE_TEXT, null, false);
    }

}