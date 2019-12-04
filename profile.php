<?php
require_once './private/init.php';

$userp = new User();

//proveri da li je ulogovan korisnik
if ($userp->isLoggedIn()) {

    if (!$email = Input::get('user')) {
        Redirect::to('index.php');
    } else {
        $user = new User($email);
        if (!$user->exists()) {
            Redirect::to(404);
        } else {
            $data = $user->data();
        }
        ?>

        <h3> <?php echo escape($data->ime); ?></h3>
        <p>Ime: <?php echo escape($data->ime); ?></p>
        <p>Prezime: <?php echo escape($data->prezime); ?></p>
        <p>Smer: <?php echo escape($data->smer_predmet); ?></p>
        <?php
        $usera = new User();
        //ako je ulogovan i ima dozvolu prikazi mu vise podataka
        if ($usera->hasPermission('profesor') && $usera->isLoggedIn()) {
            ?>
            <p>Email: <?php echo escape($data->email); ?></p>
            <p>Kontakt: <?php echo escape($data->telefon); ?></p>

            <?php
        }
        ?>
        <p>Avatar: <br /> <img src="<?php echo AVATARS . $data->avatar; ?>" /></p>
        <?php
    }
} else {
    //ako nije ulogovan redirektuj na 404
    Redirect::to(404);
}
