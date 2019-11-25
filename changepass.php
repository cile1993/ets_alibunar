<?php
require_once './private/init.php';

$user = new User();

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'lozinka' => array(
                'required' => true
            ),
            'lozinka_nova' => array(
                'required' => true,
                'min' => 8,
                'max' => 30
            ),
            'lozinka_nova_ponovo' => array(
                'required' => true,
                'min' => 8,
                'max' => 30,
                'matches' => 'lozinka_nova'
            )
        ));

        if ($validation->passed()) {

            //provera trenutne lozinke
            if (Hash::make(Input::get('lozinka')) !== $user->data()->lozinka) {
                echo 'Pogresna trenutna lozninka';
            } else {
                $user->update(array(
                    'lozinka' => Hash::make(Input::get('lozinka_nova'))
                ));
                
                Session::msg('home', 'Uspesno promenjena lozinka');
                Redirect::to('index.php');
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="lozinka">Trenutna lozinka: </label>
        <input type="password" name="lozinka" id="lozinka" value="" autocomplete="on" required>
    </div>

    <div class="field">
        <label for="lozinka_nova">Nova lozinka: </label>
        <input type="password" name="lozinka_nova" id="lozinka_nova" value="" required>
    </div>

    <div class="field">
        <label for="lozinka_nova_ponovo">Ponovite novu lozinku: </label>
        <input type="password" name="lozinka_nova_ponovo" id="lozinka_nova_ponovo" value="" autocomplete="off">
    </div>
    <input type="submit" value="Potvrdi">
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
</form>