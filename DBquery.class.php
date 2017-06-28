<?php
/**
 * DBQUERY CLASS 1.0.0
 *
 * @author leonovich.pavel@gmail.com
 * DBquery is simple ORM object-relational queries mapper, using SafeMySQL.
 *
 * Requirements:
 * https://github.com/colshrapnel/safemysql
 *
 */

abstract class DBquery
{

    protected $db;
    protected $table;
    protected $post;
    protected $description;
    protected $names;
    protected $values;
    protected $where = array();
    protected $operators = array('=','!=','<>','>','<','>=','<=','LIKE','IS','IN','NOT IN');
    protected $types = array('AND','OR');
    protected $groupBy;
    protected $orderBy;
    protected $orderByDesc = false;
    protected $limit = array();

    function __construct(SafeMySQL $db)
    {
        $this->db = $db;
    }

    protected function describe()
    {
        return $this->db->getAll(" DESCRIBE ?n ", $this->table);
    }

    protected function initNames()
    {
        $this->description = $this->describe();
        foreach ($this->description as $one) {
            $this->names[] = $one["Field"];
            $this->values[$one["Field"]] = null;
        }
    }

    protected function checkPost()
    {
        $this->initNames();
        return array_diff($_POST, $this->values);
    }

    protected function addWhere($name, $operator, $value, $type = null)
    {
        $next = count($this->where);
        $this->where[$next]['name'] = $name;
        $this->where[$next]['operator'] = $operator;
        $this->where[$next]['value'] = $value;
        if ($type!==null) {
            $this->where[$next]['type'] = $type;
        }
    }
    
    public function where($name, $operator, $value = null)
    {
        if(!in_array($operator, $this->operators)) {
            $value = $operator;
            $operator = '=';
        }
        $this->addWhere($name, $operator, $value);
        return $this;
    }
    
    public function andWhere($name, $operator, $value)
    {
        $this->addWhere($name, $operator, $value, 'AND');
        return $this;
    }
    
    public function orWhere($name, $operator, $value)
    {
        $this->addWhere($name, $operator, $value, 'OR');
        return $this;
    }

    protected function getWhereQuery()
    {
        if (count($this->where)==0) {
            return null;
        }
        $query = " WHERE ";
        foreach ($this->where as $one) {
            if (isset($one['type']) && in_array($one['type'], $this->types)) {
                $query.= $one['type'];
            }
            if (in_array($one['operator'], $this->operators)) {
                $query.= $this->db->parse(" ?n ".$one['operator']." ?s ", $one['name'], $one['value']);
            }
        }
        return $query;
    }

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

    public function groupBy($names)
    {
        if (is_array($names)) {
            $this->groupBy = $names;
        } else {
            $this->groupBy[] = $names;
        }
        return $this;
    }

    protected function getGroupByQuery()
    {
        if ($this->groupBy!=null) {
            return $this->db->parse(" GROUP BY ?p ", implode(", ", $this->groupBy));
        }
        return null;
    }
    
    protected function getOrderByQuery()
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
    
    protected function getLimitQuery()
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
        
    public function limit($start, $max = null)
    {
        $this->limit = array($start, $max);
        return $this;
    }

    abstract protected function render();
    
    /**
     * Execute query
     */
    public function execute()
    {
        $query = $this->render();
        return $this->db->query($query);
    }

    public function __toString()
    {
        return $this->render();
    }
}