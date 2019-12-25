<?php
require_once './private/init.php';

$predmet = new Predmet();
$user = new User();
$db = DB::getInstance();

if ($user->isLoggedIn()) {
    //provera da li je korisnik admin
    if ($user->hasPermission('admin')) {
        if (Session::exists('home')) {
            echo '<p>' . Session::msg('home') . '</p>';
        }
        if (Input::exists()) {
            // provera da li postoji token i da li je dobar
            if (Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'Predmet' => array(
                        'required' => true,
                        'min' => 5,
                        'max' => 45
                    )
                ));

                if ($validation->passed()) {
                    $predmet = new Predmet();
                    try {
                        $predmet->create(array(
                            'naziv' => Input::get('Predmet')
                        ));
                        //poruka za registraciju
                        Session::msg('home', 'Uspesno ste dodali novi smer!');
                        //redirektuj
                        Redirect::to('smer.php');
                    } catch (Exception $ex) {
                        die($ex->getMessage());
                    }
                } else {
                    foreach ($validation->errors() as $error) {
                        echo $error, '</br>';
                    }
                }
            }
        }
    }
} else {
    Redirect::to(404);
}
?>
<div class="field">
    <label for="naziv">Lista smerova: </label><br />
    <table name="naziv" id="naziv">
        <tr><th>Naziv</th></tr>
        <?php
        foreach ($predmet->_db->getAll('ets_predmeti')->results() as $id) {
            echo "<tr><td>{$id->naziv}</td><td>";
            ?>
            <form action="delete.php" method="post"><input type="hidden" name="id" value="<?php echo $id->predmetID ?>"><input type="submit" name="delete" value="Obrisi"></form></td></tr>
            <?php
        }
        ?>
    </table>
</div>
<br><br>
<form action="" method="post">
    <div class="field">
        <label for="novi">Dodaj novi smer: </label>
        <input type="text" name="Predmet" id="novi" value="" required>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Potvrdi">
</form>
