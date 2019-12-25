<?php

class Predmet {

    public  $_db;
    private $_user,
            $_data;

    public function __construct($smer = null) {
        $this->_db = DB::getInstance();
    }

    public function create($fields = array()) {
        if ($this->_db->insert('ets_predmeti', $fields)) {
            echo 'Uspesno ste se registrovali';
        }
    }

    public function delete($id) {
        if ($this->_db->delete('ets_predmeti', array('predmetID', '=', $id))) {
            echo 'Smer je uspesno obrisan';
        } else {
            print_r($this);
        }
    }

}
