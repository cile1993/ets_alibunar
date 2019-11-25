<!DOCTYPE html>
<?php
require_once './private/init.php';

if (Session::exists('home')) {
    echo '<p>' . Session::msg('home') . '</p>';
}
$user = new User();
////echo $user->data()->username;
//// echo Session::get(Config::get('session/session_name'));
//if ($user->isLoggedIn()) {
//    ?>

      <?php
//    if ($user->hasPermission('admin')) {
//        echo 'Admin';
//    }
//} else {
//    echo '<p>Moras se <a href="login_form.php">ulogovati</a> ili <a href="register.php">registrovati</a></p>';
//}
include ('header.php');
include ('slider.php');
include ('blog.php');
include ('stuff.php');
include ('about.php');
include ('footer.php');
include ('scripts.php');
?>

<!--    <p>Pozdrav <a href="profile.php?user=//<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->ime); ?></a>!</p>

    <ul>
        <li><a href="changepass.php">promeni lozinku</a></li>
        <li><a href="logout.php">odjava</a></li>
        <li><a href="update.php">profil</a></li>
    </ul>-->