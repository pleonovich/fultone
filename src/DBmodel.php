<?php 

namespace Fultone;

use Fultone\DBquery as DBquery;
use Fultone\DBtable as DBtable;

/**
 * Fultone 2.0.1
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with db models
 *
 */

abstract class DBmodel extends DBquery {

    public $name;

	function __construct () {
		parent::__construct();
		$this->table = new DBtable($this->name);
		static::schema($this->table);
	}

	abstract protected function schema (DBtable $table);

	public function migrate () {
		$table = new DBtable($this->name);
		static::schema($table);
		if(!$table) throw new \Exception("Table schema error");
		var_dump($table);
		if(is_a($table,'Fultone\DBtable')) {
			return $this->createTable();
		}
		throw new \Exception("Table schema wrong type error");
	}

	protected function setData () {
		return [];
	}

	public function insertData () {
		$data = $this->setData();
		return $this->create($data);
	}
	
}