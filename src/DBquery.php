<?php

namespace Fultone;

use Fultone\DBtable;

/**
 * @author leonovich.pavel@gmail.com
 * DBquery is simple queries mapper, using SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 */


class DBquery
{
    /**
    * @var SafeMySQL
    */
    public $db;

    /**
    * @var DBtable
    */
    public $table;

    protected $post;
    protected $distinct = false;
    protected $join = array();
    protected $where = array();
    protected $operators = array('=','!=','<>','>','<','>=','<=','LIKE','IS','IN','NOT IN');
    protected $types = array('AND','OR', 'NOT');
    protected $groupBy;
    protected $orderBy;
    protected $orderByDesc = false;
    protected $limit = array();
    protected $emode = 'error';

    function __construct() {
        $this->db = DBConnect::get();
    }
    
    private function addJoin ( $type, $table, $using ) {
        $next = count($this->where);
        $this->join[$next]['type'] = $type;
        $this->join[$next]['table'] = $table;
        $this->join[$next]['using'] = $using;
    }

    public function leftJoin ($table, $using) {
        $this->addJoin('LEFT OUTER', $table, $using);
        return $this;
    }

    public function rightJoin ($table, $using) {
        $this->addJoin('RIGHT OUTER', $table, $using);
        return $this;
    }

    public function innerJoin ($table, $using) {
        $this->addJoin('INNER', $table, $using);
        return $this;
    }

    public function crossJoin ($table, $using) {
        $this->addJoin('CROSS', $table, $using);
        return $this;
    }

    private function getJoinQuery()
    {
        if (count($this->join)==0) {
            return null;
        }
        $query = " ";
        foreach ($this->join as $j) {
            $query.= $this->db->parse(" ?p JOIN ?n USING(?n) ", $j['type'], $j['table'], $j['using']);
        }
        return $query;
    }

    /**
     * Set distinct is true
     *
     * @return this object
     */
    public function distinct()
    {
		$this->distinct = true;
        return $this;
    }

    private function addWhere(string $name, string $operator, $value, string $type = null)
    {
        if (!$this->table->issetFieldName($name)) {
            $this->error("Unknown column '$name' in filter options");
        }
        $next = count($this->where);
        $this->where[$next]['name'] = $name;
        $this->where[$next]['operator'] = $operator;
        $this->where[$next]['value'] = $value;
        if ($type!==null) {
            $this->where[$next]['type'] = $type;
        }
    }

    /**
     * SET WHERE
     * 
     * @param Array $filter - filter options
     * @param string $type - operator, default - AND
     * @return this
     */
    public function where(Array $filter, string $type = "AND")
    {
        if (isset($filter) > 0) { 
            foreach ($filter as $name=>$value) {
                if(in_array(strtoupper($name), $this->types)) {
                    $this->where($value, $name);
                    break; 
                }
                if (is_array($value)) {
                    if (count($value) > 1) {
                        throw new Exception("Invalid value in filter options");
                    }
                    foreach($value as $operator=>$value) {
                        if (!in_array($operator, $this->operators)) {
                            throw new Exception("Unknown operator in filter options");
                        }
                        $this->addWhere($name, $operator, $value, $type);
                    }
                    continue;
                }
                $this->addWhere($name, "=", $value, $type);
            }
        }
        return $this;
    }

    private function getWhereQuery()
    {
        if (count($this->where)==0) {
            return null;
        }
        $query = " WHERE ";
        foreach ($this->where as $key=>$one) {
            if (isset($one['type']) && in_array($one['type'], $this->types)) {
                $query.= ($key !== 0) ? $one['type'] : null;
            }
            if (in_array($one['operator'], $this->operators)) {
                if ($one['operator'] === 'IN' && is_array($one['value'])) {
                    $query.= $this->db->parse(" ?n ".$one['operator']." (?a) ", $one['name'], $one['value']);
                } else {
                    $query.= $this->db->parse(" ?n ".$one['operator']." ?s ", $one['name'], $one['value']);
                }
            }
        }
        $this->where = [];
        return $query;
    }

    /**
     * ORDER BY
     * @param string|Array $names - fields names
     * @param boolean $desc - sort order
     * @return this
     */
    public function orderBy($names, $desc = false)
    {
        if (is_array($names)) {
            $this->orderBy = $names;
        } else {
            $this->orderBy[] = $names;
        }
        $this->orderByDesc = $desc;
        return $this;
    }

    /**
     * GROUP BY
     * @param string|Array $names - fields names
     * @return this
     */
    public function groupBy($names)
    {
        if (is_array($names)) {
            $this->groupBy = $names;
        } else {
            $this->groupBy[] = $names;
        }
        return $this;
    }

    private function getGroupByQuery()
    {
        if ($this->groupBy!=null) {
            return $this->db->parse(" GROUP BY ?p ", $this->table->getFieldsNames());
        }
        return null;
    }
    
    private function getOrderByQuery()
    {
        if ($this->orderBy!=null) {
            if (is_array($this->orderBy)) {
                $query = $this->db->parse(" ORDER BY ?a ", $this->orderBy);
            } else {
                $query = $this->db->parse(" ORDER BY ?n ", $this->orderBy);
            }
            if ($this->orderByDesc) {
                $query.= " DESC ";
            }
            return $query;
        }
        return null;
    }

    private function getLimitQuery()
    {
        if (count($this->limit)>0) {
            $limit = $this->db->parse(" LIMIT ?i", $this->limit[0]);
            if ($this->limit[1]!==null) {
                $limit.= $this->db->parse(", ?i ", $this->limit[1]);
            }
            return $limit;
        }
        return null;
    }

    /**
     * LIMIT
     * 
     * @param int $start - limit or start from
     * @param int $max - max rows
     * @return this
     */
    public function limit(int $start, int $max = null)
    {
        $this->limit = array($start, $max);
        return $this;
    }

    private function checkFieldNames(Array $data) {
        foreach($data as $name=>$value) {
            if (!$this->table->issetFieldName($name)) {
                $this->error("Unknown column '$name' in filter options");
            }
        }
    }

    private function renderCreateQuery()
    {
        $query = $this->db->parse(" CREATE TABLE IF NOT EXISTS ?n ", $this->table->name);
        $q = array();
        foreach ($this->table->fields as $f) {
            $s = " ".$f->name." ".$f->type;
            $s.= !in_array($f->type, array('TEXT','DATE','TIME','DATETIME')) ? "(".$f->length.")" : "";            
            $s.= $f->isNull ? " NULL" : " NOT NULL";
            $s.= $f->default!==null ? " DEFAULT ".$f->default : "";
            $s.= $f->key ? " AUTO_INCREMENT PRIMARY KEY " : null;
            $q[] = $s;
        }
        $query.= " (\n".implode(",\n", $q)." \n) CHARACTER SET ".$this->table->charset." COLLATE ".$this->table->collate."; ";
        return $query;
    }

    private function renderInsertQuery(Array $data)
    {
        $this->checkFieldNames($data);
        $query = $this->db->parse(" INSERT INTO ?n ", $this->table->name);
        $query.= $this->db->parse(" ( ?p ) VALUES ( ?a ) ", implode(", ", array_keys($data)), $data);
        $query.= $this->getWhereQuery();
        return $query;
    }

    private function renderUpdateQuery(Array $data)
    {
        $this->checkFieldNames($data);
        $query = $this->db->parse(" UPDATE ?n SET ", $this->table->name);
        $q = array();
        foreach ($data as $name => $value) {
            $q[]= $this->db->parse(" ?n=?s ", $name, $value);
        }
        $query.= implode(",", $q);
        $query.= $this->getWhereQuery();
        return $query;
    }

    private function renderOnDuplicateUpdateQuery(Array $data)
    {
        $this->checkFieldNames($data);
        $names = array_keys($data);
        $query = $this->db->parse(" INSERT INTO ?n ", $this->table->name);
        $query.= $this->db->parse(" ( ?p ) VALUES ( ?a ) ", implode(",", $names), $data);
        $query.= " ON DUPLICATE KEY UPDATE ";
        $q = array();
        foreach ($this->names as $k => $n) {
            if (!isset($this->values[$k])) {
                die(" Error. values array doesn`t match names array .");
            }
            $q[]= $this->db->parse(" ?n=?s ", $n, $this->values[$k]);
        }
        $query.= implode(",", $q);
        $query.= $this->getWhereQuery();
        return $query;
    }

    private function renderSelectQuery()
    {
        $query = " SELECT ".($this->distinct ? 'DISTINCT ' : '').$this->table->getFieldsNames();
        $query.= $this->db->parse(" FROM ?n ", $this->table->name);
        $query.= $this->getJoinQuery();
        $query.= $this->getWhereQuery();
        $query.= $this->getGroupByQuery();
        $query.= $this->getOrderByQuery();
        $query.= $this->getLimitQuery(); //LOG::write($query,"query");
        echo $query;
        return $query;
    }

    private function renderDeleteQuery()
    {
        $query = $this->db->parse(" DELETE FROM ?n ", $this->table->name);
        $query.= $this->getWhereQuery();
        return $query;
    }
    
    /**
     * Create table in db from model
     *
     * @return boolean - result
     */
    public function createTable() {
        try {
            $query = $this->renderCreateQuery();
            return $this->db->query($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }
    
    /**
     * Find all rows
     *
     * @param Array $where - where options
     * @return boolean - result
     */
    public function findAll(Array $where = []) {
        try {
            $this->table->init();
            $this->where($where);
            $query = $this->renderSelectQuery();
            return $this->db->getAll($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }

    /**
     * Find one row
     *
     * @param Array $where - where options
     * @return boolean - result
     */
    public function findOne(Array $where = []) {
        try {
            $this->table->init();
            $this->where($where);
            $query = $this->renderSelectQuery();
            return $this->db->getRow($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }

    /**
     * Insert row data
     *
     * @param Array $data - row data
     * @return boolean - result
     */
    public function create(Array $data) {
        try {
            $query = $this->renderInsertQuery($data);
            return $this->db->query($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }

    /**
     * Update row data
     *
     * @param Array $data - row data
     * @return boolean - result
     */
    public function update(Array $data) {
        try {
            $query = $this->renderUpdateQuery($data);
            return $this->db->query($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }

	/**
     * Create or update row data
     *
     * @param Array $data - row data
     * @return boolean - result
     */
	public function createOrUpdate(Array $data) {
		try {
            $query = $this->renderOnDuplicateUpdateQuery($data);
			return $this->db->query($query);
		} catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
		}
	}

    /**
     * Delete data
     *
     * @param Array $where - where options
     * @return boolean - result
     */
    public function delete(Array $where) {
        try {
            $this->where($where);
            $query = $this->renderDeleteQuery();
            return $this->db->query($query);
        } catch ( Exception $e ) {
            LOG::writeException($e);
            throw $e;
        }
    }

    /**
     * Init table by name
     *
     * @param string $tableName - table name
     * @return boolean - result
     */
    public function table(string $tableName) {
        $this->table = new DBtable($tableName);
        $this->table->init();
    }

    protected function error($err)
	{
		$err  = __CLASS__.": ".$err;
		if ( $this->emode == 'error' )
		{
			$err .= ". Error initiated in ".$this->caller().", thrown";
			trigger_error($err,E_USER_ERROR);
		} else {
			throw new exception($err);
		}
	}

	protected function caller()
	{
		$trace  = debug_backtrace();
		$caller = '';
		foreach ($trace as $t)
		{
			if ( isset($t['class']) && $t['class'] == __CLASS__ )
			{
				$caller = $t['file']." on line ".$t['line'];
			} else {
				break;
			}
		}
		return $caller;
	}

}
