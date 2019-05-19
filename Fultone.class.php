<?php 
/**
 * Fultone 2.0.1
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with db models
 *
 */

abstract class Fultone extends DBquery {

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
		if(!$table) return false;
		if(is_a($table,'Table')) return $this->createTable();
		return false;
	}

	protected function setData () {
		return [];
	}

	public function insertData () {
		$data = $this->setData();
		return $this->create($data);
	}
	
}