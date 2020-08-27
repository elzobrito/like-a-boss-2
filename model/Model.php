<?php

namespace Model;

use elzobrito\QueryBuilder;
use Config\Database;

class Model
{

    protected $table;
    protected $drive;
    protected $fillable = array();

    /**
     * @var array
     */
    private $clausules = [];

    /**
     * @param $name
     * @param $arguments
     * @return $this
     */
    function __call($name, $arguments)
    {
        $clausule = $arguments[0];
        if (count($arguments) > 1) {
            $clausule = $arguments;
        }
        $this->clausules[strtolower($name)] = $clausule;

        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function update($fields, $wheres, $values)
    {
        if (isset($this->id)) {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            $val = $qb
                ->table($this->table)
                ->fields($fields)
                ->where($wheres)
                ->update($values);
            return $val;
        }
    }

    public function save($fields = null, $valores = null)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        $val = $qb
            ->table($this->table)
            ->fields(($fields != null ? $fields : $this->fill()))
            ->insert(($valores != null ? $valores : $this->values()));
        return $val;
    }

    public function delete()
    {
        if (isset($this->id)) {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            $val = $qb
                ->table($this->table)
                ->where(['id = ?'])
                ->delete([$this->id]);
            return $val;
        }
    }

    public function findFor($fields, $wheres, $values, $join = null)
    {
        if ($join != null) {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            $val = $qb->table($this->table)
                ->fields($fields)
                ->where($wheres)
                ->join($join)
                ->select($values);
        } else {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            $val = $qb->table($this->table)
                ->fields($fields)
                ->where($wheres)
                ->select($values);
        }
        return $val;
    }

    public function all()
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        $val = $qb->table($this->table)
            ->fields(['*'])
            ->select();
        return $val;
    }

    public function count()
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        $val = $qb->table($this->table)
            ->fields(['count(*) as total'])
            ->select();
        return $val;
    }


    public function find($fields, $wheres, $values = null, $join = null, $tables = null)
    {

        if ($join != null) {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            if ($values != null) {
                $val = $qb->table($tables != null ? $tables : $this->table)
                    ->fields($fields)
                    ->where($wheres)
                    ->join($join)
                    ->select($values);
            } else {
                $val = $qb->table($tables != null ? $tables : $this->table)
                    ->fields($fields)
                    ->where($wheres)
                    ->join($join)
                    ->select();
            }
        } else {
            $qb = new QueryBuilder(Database::getDB($this->drive));
            $val = $qb->table($tables != null ? $tables : $this->table)
                ->fields($fields)
                ->where($wheres)
                ->select($values);
        }

        return $val;
    }


    public function findForId($id)
    {
        $qb = new QueryBuilder(Database::getDB($this->drive));
        $val = $qb->table($this->table)
            ->fields(['*'])
            ->where(['id = ?'])
            ->select([$id]);
        return $val;
    }

    public function values()
    {
        $filds = [];
        $array = [];
        $filds = $this->fill();
        foreach ($this as $key => $value) {
            if (in_array($key, $filds))
                $array[] = $value;
        }
        return $array;
    }


    public function request_cripto()
    {
        $val_temp = null;
        foreach ($this->fillable as $key => $value)
            if (substr($value, 0, 2) == 'id') {
                if (isset($_REQUEST[$value]))
                    if (!is_numeric($_REQUEST[$value])) {
                        $val_temp = $this->decryptIt($_REQUEST[$value]);
                        if (is_numeric($val_temp)) {
                            $this->$value = $val_temp;
                        } else {
                            $this->$value = $_REQUEST[$value];
                        }
                    } else {
                        $this->$value = $_REQUEST[$value];
                    }
            }else{
                $this->$value = $_REQUEST[$value];
            }
    }

    public function request()
    {
        foreach ($this->fillable as $key => $value) {
            if (isset($_REQUEST[$value]))
                $this->$value = $_REQUEST[$value];
        }
    }

    public function fill()
    {
        $array = [];
        foreach ($this->fillable as $key => $value) {
            $array[] = $value;
        }
        return $array;
    }
}
