<?php
require_once './private/init.php';

$predmet = new Predmet();
$user = new User();
$db = DB::getInstance();

if ($user->isLoggedIn()) {
    //provera da li je korisnik admin
    if ($user->hasPermission('admin')) {
        if (Input::exists()) {
            // provera da li postoji token i da li je dobar
            if (Token::check(Input::get('token'))) {
                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'naziv' => array(
                        'required' => true,
                        'min' => 8,
                        'max' => 45,
                        'unique' => 'ets_predmeti'
                    )
                ));

                if ($validation->passed()) {
                    $predmet = new Predmet(); // zbog ovoga vidis da je objekat iz predmeta,da ali zar ako ne nadje metodu tu posto je instancirana klasa ne treba odande?
                    try {
                        $predmet->create(array(
                            'naziv' => Input::get('predmet')
                        ));
                        //poruka za registraciju
                        Session::msg('home', 'Uspesno ste dodali novi predmet!');
                        //redirektuj
                        Redirect::to('index.php');
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

<form action="" method="post">

    <div class="field">
        <?php
        echo '<pre>';
        print_r($predmet->_db->getAll('ets_predmeti')->results()); //kao da trazi results metodu u predmet a ja hocu iz DB
        echo '</pre>';
        ?>
        <label for="naziv">Izaberi predmet: </label><br />
        <select name="naziv" id="naziv">
            <?php
            foreach ($predmet->_db->getAll('ets_predmeti')->results() as $id) {
                echo "<option value={$id->predmetID}>{$id->naziv}</option>";
            }
            ?>
        </select>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Potvrdi">
</form>

