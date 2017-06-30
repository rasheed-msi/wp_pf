<?php

class MrtDbbase {

    public function get($id = null) {
        if(is_null($id)){
            $id = $this->id;
        }
        return $this->link->get_row("SELECT * FROM {$this->table} WHERE {$this->pkey} = {$id}", ARRAY_A);
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
