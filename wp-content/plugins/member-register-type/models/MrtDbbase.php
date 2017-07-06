<?php

class MrtDbbase {

    private static $objects = array();

    public static function get_object($class = null) {
        if (!class_exists($class))
            return null;

        if (!isset(self::$objects[$class]))
            self::$objects[$class] = new $class;

        return self::$objects[$class];
    }

    public static function find($id, $key = null, $class = null) {
        $obj = new $class;
        $key = (is_null($key)) ? $obj->pkey : $key;
        $obj->data = $obj->get($id, $key);
        if (!is_null($obj->data)) {
            $obj->id = $obj->data[$obj->pkey];
            return $obj;
        }
        return $obj;
    }

    public function get($id = null, $where_key = null) {

        $where_key = (is_null($where_key)) ? $this->pkey : $where_key;
        $id = (is_null($id)) ? $this->id : $id;

        if (is_null($id)) {
            return [];
        }

        return $this->link->get_row("SELECT * FROM {$this->table} WHERE {$where_key} = {$id}", ARRAY_A);
    }

    public function getValue($key) {
        if (isset($this->input[$key])) {
            return $this->input[$key];
        } else {
            return $this->get_default($key);
        }
    }

    public function get_default($key) {
        return isset($this->field_default[$key]) ? $this->field_default[$key] : NULL;
    }

    public function insert($inputs) {

        $insert = [];

        foreach ($inputs as $key => $value) {
            if (in_array($key, $this->fields)) {
                $insert[$key] = ($value != '') ? $value : $this->get_default($key);
            }
        }

        $this->link->insert(
                $this->table, $insert
        );

        return $this->link->insert_id;
    }

    function update($inputs, $where_key = null, $where_value = null) {

        $update = [];

        if (is_null($where_key)) {
            $where_key = $this->pkey;
        }

        if (is_null($where_value)) {
            $where_value = $this->id;
        }

        foreach ($inputs as $key => $value) {
            if (in_array($key, $this->fields)) {
                $update[$key] = ($value != '') ? $value : $this->get_default($key);
            }
        }


        $result = $this->link->update(
                $this->table, $update, [$where_key => $where_value]
        );

        return $result;
    }

    public function delete($where_key = null, $where_value = null) {
        if (is_null($where_key)) {
            $where_key = $this->pkey;
        }

        if (is_null($where_value)) {
            $where_value = $this->id;
        }
        $this->link->delete($this->table, [$where_key => $where_value]);
    }

}
