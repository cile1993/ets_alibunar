<?php
require_once './private/init.php';

if (!$username = Input::get('user')) {
    Redirect::to('index.php');
} else {
    $user = new User($username);
    if (!$user->exists()) {
        Redirect::to(404);
    } else {
        $data = $user->data();
    }
    ?>

    <h3> <?php echo escape($data->ime);?></h3>
    <p>Ime: <?php echo escape($data->ime);?></p>

    <?php
}
