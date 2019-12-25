<?php
require_once './private/init.php';
?>

<!DOCTYPE html>
<html lang="sr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ETS Dositej Obradovic</title>
        <link rel="stylesheet" href="<?php echo URL . '/bootstrap-4.1.3-dist/css/bootstrap.min.css' ?>">
        <link rel="stylesheet" href="<?php echo URL . '/css/style.css' ?>">
        <link rel="stylesheet" href="<?php echo URL . '/css/fixed.css' ?>">
    </head>

    <body data-spy="scroll" data-target="#navbarResponsive">
        <!-- Start navigation -->
        <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
            <span class="navbar-brand"><img src="<?php echo URL . '/img/logo-ets.png' ?>" /></span><span class="desc">ETS Dositej Obradovic</span>
            <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarResponsive">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Pocetna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo URL . 'blog.php' ?>">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profesori">Profesori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#onama">O nama</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontakt">Kontakt</a>
                    </li>
                    <?php
                    $user = new User();
                    if (!$user->isLoggedIn()) {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL . 'login.php'; ?>"><i class="fas fa-sign-in-alt"></a></i>
                        </li>
                        <?php
                    } else {
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo URL . 'logout.php'; ?>">Logout</a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </nav>
        <!-- End navigation -->
        <?php
        $user = new User();
        if ($user->isLoggedIn()) {
            ?>
            <p class="mt-5 pt-5">Pozdrav <a href="profile.php?user=<?php echo escape($user->data()->email); ?>"><?php echo escape($user->data()->ime); ?></a>!
                <a href="edit_profile.php">User panel</a>
                <?php
                if ($user->hasPermission('admin')) {
                    ?>
                - <a href="update.php">Admin panel</a></p>
                    <?php
                }
            }
            if (Session::exists('home')) {
                echo '<p>' . Session::msg('home') . '</p>';
            }
            ?>