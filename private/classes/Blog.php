<?php

class Blog {

    public  $_db;
    private $_user,
            $_data;

    public function __construct($blog = null) {
        $this->_db = DB::getInstance();
    }

    // objavljeni postovi
    public function publishedPosts($param = 1) {
        $this->_db->action('SELECT *', 'ets_blog', array('published', '=', $param));
        $_data = $this->_db->results();
        return $_data;
    }

    public function categoryPosts($category) {
        $this->_db->actionMulti('SELECT *', 'ets_blog', ['kategorija', '=', $category], ['published', '=', true])->results();
        $_data = $this->_db->results();
        return $_data;
    }

    //kreiranje naslova za browser
    public function makeSlug(String $string) {
        $string = strtolower($string);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        return $slug;
    }

}
