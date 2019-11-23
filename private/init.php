<?php

session_start();

$GLOBALS['config'] = array(
    'mysql' => array(
        'host' => 'localhost',
        'dbname' => 'ekonomska',
        'username' => 'root',
        'password' => ''
    ),
    'remember' => array(
        'cookie_name' => 'hash',
        'cookie_expiry' => 604800 // nedelju dana
    ),
    'session' => array(
        'session_name' => 'user',
        'token_name' => 'token'
    )
);

spl_autoload_register(function($class) {
    require_once 'classes/' . $class . '.php';
});

require_once ('functions.php');

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $hash = Cookie::get(Config::get('remember/cookie_name'));
    $hashCheck = DB::getInstance()->get('ets_sesije', array('hash', '=', $hash));

    if ($hashCheck->count()) {
        $user = new User($hashCheck->first()->korisnikID);
        $user->login();
    }
}












//// Dodavanje web putanje, chrome ne moze da otvori lokalnu adresu
//define("WEB_ROOT", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : (isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '_UNKNOWN_'));
//
//// Dodavanja putanja u konstante
//define("PRIVATE_PATH", dirname(__FILE__));
//define("PROJECT_PATH", dirname(PRIVATE_PATH));
//define("PUBLIC_PATH", PROJECT_PATH . '/public');
//define("SHARED_PATH", PRIVATE_PATH . '/shared');
//define("WWW_ROOT",  $_SERVER['DOCUMENT_ROOT']);
//
//// Ucitavanje fajlova bitnih za rad stranice
//require ('db_connect.php');
//
//// Testiranje da li su fajlovi kroz require ucitani
////if ( basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"]) ) {
////  echo "called directly";
////} else {
////  echo "included/required";
////}
//
//?>