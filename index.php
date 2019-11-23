<!DOCTYPE html>
<?php
require_once './private/init.php';

if(Session::exists('home')){
	echo '<p>' . Session::flash('home') . '</p>';
}
$user = new User();
//echo $user->data()->username;
// echo Session::get(Config::get('session/session_name'));
if($user->isLoggedIn()){
?>
	<ul>
		<li><a href="logout.php">odjava</a></li>
	</ul>
<?php
	
}else{
	echo '<p>Moras se <a href="login_form.php">ulogovati</a> ili <a href="register.php">registrovati</a></p>';
}
//include ('header.php');
//include ('slider.php');
//include ('blog.php');
//include ('stuff.php');
//include ('about.php');
//include ('footer.php');
//include ('scripts.php');
?>
