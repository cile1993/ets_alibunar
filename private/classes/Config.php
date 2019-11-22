<?php
class Config {
    public static function get($path = null){
        if($path) {
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            
            foreach ($path as $part){
                if(isset($config[$part])){
                    $config = $config[$part];
                }
            }
            
            return $config;
        }
    }
}
