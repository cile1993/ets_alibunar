<?php

class Input {

    public static function exists($type = 'post') {
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
                break;
            case 'get':
                return (!empty($_GET)) ? true : false;
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($item) {
        if(isset($_POST[$item])) {
            return $_POST[$item];
        } elseif(isset($_GET[$item])) {
            return $_GET[$item];
        }
        return '';
    }
    
    public static function isPost($param) {
        $type = array('POST', 'post');
        if (!in_array($param, $type)) {
            return false;
        }
        return true;
    }
    
}
