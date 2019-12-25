<?php
require_once './private/init.php';

$user = new User();

// ako nije ulogovan preusmeri na pocetnu
if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}
if ($user->hasPermission('admin')) {
    $email = Input::get('user');
    $user = new User($email);
    if ($user->exists()) {
        $data = $user->data();

//proveri da li ima unosa u polju da ne posalje prazno
        if (Input::exists()) {
            //proveri da li se token poklapa zbog csr
            if (Token::check(Input::get('token'))) {

                $validate = new Validate();
                $validation = $validate->check($_POST, array(
                    'ime' => array(
                        'name' => 'ime',
                        'required' => true,
                        'min' => 3,
                        'max' => 20
                    ),
                    'prezime' => array(
                        'name' => 'prezime',
                        'required' => true,
                        'min' => 3,
                        'max' => 25
                    ),
                    'email' => array(
                        'name' => 'email',
                        'required' => true,
                        'min' => 8,
                        'max' => 45
                    ),
                    'smer' => array(
                        'name' => 'smer',
                        'min' => 5,
                        'max' => 35
                    ),
                    'telefon' => array(
                        'name' => 'telefon',
                        'required' => false,
                        'min' => 6,
                        'max' => 10
                    )
                ));
                $select = $_POST['uloga'];
                if ($validation->passed()) {
                    try {
                        $user->updateAdmin(array(
                            'ime' => escape(Input::get('ime')),
                            'prezime' => escape(Input::get('prezime')),
                            'email' => escape(Input::get('email')),
                            'smer_predmet' => escape(Input::get('smer')),
                            'telefon' => escape(Input::get('telefon')),
                            'pristup' => escape($select)
                        ));

                        Session::msg('home', 'Podaci su azurirani!');
                        Redirect::to('index.php');
                    } catch (Exception $exc) {
                        die($exc->getMessage());
                    }
                } else {
                    foreach ($validation->errors() as $error) {
                        echo $error, '<br>';
                    }
                }
            }
        }
    }
} else {
    Redirect::to(404);
}
?>

<!-- Start login form -->
<form action="" method="post">
    <div class="field">
        <label for="ime">Ime</label>
        <input type="text" name="ime" id="ime" value="<?php echo escape($user->data()->ime); ?>" autocomplete="off" required="">
    </div>

    <div class="field">
        <label for="prezime">Prezime</label>
        <input type="text" name="prezime" id="prezime" value="<?php echo escape($user->data()->prezime); ?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo escape($user->data()->email); ?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="smer">Smer</label>
        <input type="smer" name="smer" id="smer" value="<?php echo escape($user->data()->smer_predmet); ?>">
    </div>

    <div class="field">
        <label for="telefon">Telefon</label>
        <input type="number" name="telefon" id="telefon" value="<?php echo escape($user->data()->telefon); ?>" maxlength="10">
    </div>

    <div class="field">
        <label for="uloga">Uloga</label>
        <select name="uloga" id="uloga">
            <option value="1">Korisnik</option>
            <option value="2">Profesor</option>
            <option value="3">Administrator</option>
        </select>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Registruj">
</form>