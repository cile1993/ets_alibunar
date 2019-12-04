<?php

class Predmet {

    public  $_db;
    private $_user,
            $_data;

    public function __construct($predmet = null) {
        $this->_db = DB::getInstance();
    }

}
