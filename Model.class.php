<?php 
/**
 * MODEL CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * Simple way to work with popular MySQL queries
 *
 */

class Model extends DB {
	
	protected $table;
	protected $description;
	protected $names = array();
	protected $conn;
	
	function __construct($table) {
		$this->table = $table;
		$this->conn = self::conn();
	}

	/**
     * Get all from db table
     *
     * @return boolean - result
     */
	public function all () {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from($this->table)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get names from db table
     *
     * @return boolean - result
     */
	public function getNames () {
		$res = false;
		try {
			$res = self::select()
			->names(func_get_args())
			->from($this->table)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get all from db table
     *
     * @return boolean - result
     */
	public function __get ($name) {
		$res = false;
		try {
			$res = self::select()
			->names($name)
			->from($this->table)
			->executeCol();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get row by id
     *
     * @param int $id - row id
     * @return array - result
     */
	public function getById ( $id ) {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from($this->table)
			->where("id","=",$id)
			->executeRow();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
     * Get row by value
     *
     * @param string $name - column name
     * @param string $value - value
     * @return array - result
     */
	public function getByValue ( $name, $value ) {
		$res = false;
		try {
			$res = self::select()
			->names('*')
			->from($this->table)
			->where($name,"=",$value)
			->executeRow();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}

	/**
	 * Get names from db table
	 *
	 * @param string $names - select columns
     * @param string $name - column name
     * @param string $value - value
     * @return boolean - result
     */
	 public function namesByValue () {
		$args = func_get_args();
		$names = array_shift($args);
		$name = array_shift($args);
		$value = array_shift($args);
		$res = false;
		try {
			$res = self::select()
			->names($names)
			->from($this->table)
			->where($name,"=",$value)
			->executeAll();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}
	
	/**
     * Save current post data to db
     *
     * @return boolean - result
     */
	public function save () {
		$res = false;
		try {
			$res = self::update()
			->table($this->table)
			->setPOST()
			->executeODKU();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}
	
	/**
     * Delete data by value
     *
     * @return boolean - result
     */
	public function remove ( $name, $value ) {
		$res = false;
		try {
			$res = self::delete()
			->from($this->table)
			->where($name, $value)
			->execute();
		} catch ( Exception $e ) {
			LOG::writeException($e);
		}
		return $res;
	}
	
	public static function factory ( $table ) {
		return new Model($table);
	}
	
}