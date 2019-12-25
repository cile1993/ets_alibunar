<?php
require_once './private/init.php';

$user = new User();

if ($user->isLoggedIn() && $user->hasPermission('profesor')) {
    ?>
    <div class="row">
        <ul>
            <li><a href="create_post.php">Kreiraj novi post</a></li>
            <li><a href="acp_posts.php">Uredi postojecu objavu</a></li>
            <?php if ($user->hasPermission('admin')) { ?>
                <li><a href="register.php">Kreiraj novog korisnika</a></li>
                <li><a href="acp_users.php">Izmeni podatke o korisniku</a></li>
            </ul>
        </div>
        <?php
    }
} else {
    Redirect::to(404);
}
?>
