<?php

class Comrads extends Fultone {

    public $name = 'comrads';

    protected function schema (DBtable $table) {
        $table
        ->field('id', DBfield::TYPE_INT, 11, false, true)
        ->field('name', DBfield::TYPE_VARCHAR, 255, false)
        ->field('phone', DBfield::TYPE_VARCHAR, 255, false)
        ->field('description', DBfield::TYPE_TEXT, null, false);
    }

}