<!DOCTYPE html>
<?php
require_once './private/init.php';

//echo Session::get(Config::get('session/session_name'));
$user = new User();
echo '<br>' . is_object($user);
echo '<br>' . is_a($user, 'User');
echo '<pre>';
print_r($user);
echo '</pre>';
echo $user->data()->korisnikID;

if($user->isLoggedIn()) {
    echo 'Ulogovan';
} else {
    echo 'Nisi ulogovan';
}
//include ('header.php');
//include ('slider.php');
//include ('blog.php');
//include ('stuff.php');
//include ('about.php');
//include ('footer.php');
//include ('scripts.php');
?>