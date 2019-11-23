<?php
require_once './private/init.php';

$user = new User();
$user->logout();

Redirect::to('index.php');

?>