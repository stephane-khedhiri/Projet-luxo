<?php

namespace Core;

use Core\Database;

class Models
{
    /**
     * @var string
     */
    protected $table;
    /**
     * @var Database
     */
    protected $db;


    public function __construct(Database $db)
    {
        $this->db = $db;
        if (is_null($this->table)) {
            $parts = explode('\\', get_class($this));
            $class_name = end($parts);
            $this->table = strtolower(str_replace('Model', '', $class_name)) . 's';
        }
    }

    public function all(){
        return $this->query('SELECT * FROM ' . $this->table);
    }

    /**
     * @param int $id
     * @param string $entity
     * @return array|bool|mixed
     */
    public function find($id, $entity){
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [(int)$id], true, $entity);
    }

    /**
     * @param array $where
     * @return mixed | int | void
     */
    public function count($where = false){
        if($where){
            $sql_parts = [];
            $attributes = [];
            foreach ($where as $k =>$v){
                $sql_parts[] = "$k = ?";
                $attributes[] = $v;
            }
            $sql_parts = implode(', ', $sql_parts);
            return $this->query("SELECT COUNT(id) AS count FROM {$this->table} WHERE $sql_parts", $attributes, true)->count;
        }

        return $this->query("SELECT COUNT(id) AS count FROM {$this->table}",null,true)->count;
    }

    /**
     * @param int $id
     * @param array $fields
     * @return array|bool|mixed
     */
    public function update($id, $fields){
        $sql_parts = [];
        $attributes = [];
        foreach($fields as $k => $v){
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $attributes[] = $id;
        $sql_part = implode(', ', $sql_parts);
        return $this->query("UPDATE {$this->table} SET $sql_part WHERE id = ?", $attributes, true);
    }

    /**
     * @param int $id
     * @return array|bool|mixed
     */
    public function delete($id){
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id], true);
    }

    /**
     * @param array $fields
     * @return array|bool|mixed
     */
    public function create($fields){
        $sql_parts = [];
        $attributes = [];
        foreach($fields as $k => $v){
            $sql_parts[] = "$k = ?";
            $attributes[] = $v;
        }
        $sql_part = implode(', ', $sql_parts);
        return $this->query("INSERT INTO {$this->table} SET $sql_part", $attributes, true);
    }

    /**
     * @param string $key
     * @param string $value
     * @return array
     */
    public function extract($key, $value){
        $records = $this->all();
        $return = [];
        foreach($records as $v){
            $return[$v->$key] = $v->$value;
        }
        return $return;
    }

    /**
     * @param string $statement
     * @param array $attributes
     * @param bool $one
     * @param string $class_name
     * @return array|bool|mixed
     */
    public function query($statement, $attributes = null, $one = false, $class_name = null){


        if($attributes){

            return $this->db->prepare(
                $statement,
                $attributes,
                $class_name,
                $one
            );
        } else {
            return $this->db->query(
                $statement,
                str_replace('Model', 'Entity', get_class($this)),
                $one
            );
        }
    }
}