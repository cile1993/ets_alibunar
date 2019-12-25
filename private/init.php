<?php
ob_start();
session_start();

function getBaseUrl() 
{
    $currentPath = $_SERVER['PHP_SELF'];
    $pathInfo = pathinfo($currentPath);
    $hostName = $_SERVER['HTTP_HOST'];
    $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
    define('URL', $protocol.'://'.$hostName.$pathInfo['dirname']."/");
    return URL;
}

define('PRIVATE_PATH', __DIR__);
define('PUBLIC_PATH', dirname(PRIVATE_PATH));
define('INC', PUBLIC_PATH . '/includes/');
define('AVATARS', getBaseUrl().'img/avatars/');

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
    ),
    'avatar' => array(
        'file_size' => 200000,
        'file_type' => array('jpg', 'jpeg', 'png', 'gif'),
        'width' => 300,
        'height' => 200
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

date_default_timezone_set('Europe/Belgrade');












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