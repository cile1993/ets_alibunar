<?php
require_once './private/init.php';


if (Input::exists()) {
    // provera da li postoji token i da li je dobar
    if(Token::check(Input::get('token'))) {
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
                'max' => 45,
                'unique' => 'ets_korisnici'
            ),
            'password' => array(
                'name' => 'lozinka',
                'required' => true,
                'min' => 8,
                'max' => 30
            ),
            'password_again' => array(
                'name' => 'ponovi lozinku',
                'required' => true,
                'matches' => 'password'
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
            ),
            'uloga' => array()
        ));

        if ($validation->passed()) {
            $user = new User();
            try {
                $user->create(array(
                    'ime' => Input::get('ime'),
                    'prezime' => Input::get('prezime'),
                    'email' => Input::get('email'),
                    'lozinka' => Hash::make(Input::get('password')),
                    'smer_predmet' => Input::get('smer'),
                    'telefon' => Input::get('telefon'),
                    'pristup' => 1
                ));
                //poruka za registraciju
                Session::msg('home', 'Uspesno ste se registrovali!');
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
?>

<form action="" method="post">
    <div class="field">
        <label for="ime">Ime</label>
        <input type="text" name="ime" id="ime" value="<?php echo escape(Input::get('ime')); ?>" autocomplete="off" required="">
    </div>

    <div class="field">
        <label for="prezime">Prezime</label>
        <input type="text" name="prezime" id="prezime" value="<?php echo escape(Input::get('prezime')); ?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="" autocomplete="on">
    </div>

    <div class="field">
        <label for="password">Lozinka</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="password_again">Ponovi lozinku</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <div class="field">
        <label for="smer">Smer</label>
        <input type="smer" name="smer" id="smer" value="<?php echo escape(Input::get('smer')); ?>">
    </div>

    <div class="field">
        <label for="telefon">Telefon</label>
        <input type="number" name="telefon" id="telefon" placeholder="0631122333" maxlength="10">
    </div>

    <div class="field">
        <label for="uloga">Uloga</label>
        <select name="uloga" id="uloga">
            <option value="korisnik">Korisnik</option>
            <option value="profesor">Profesor</option>
            <option value="administrator">Administrator</option>
        </select>
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Registruj">
</form>
