<?php
require_once './private/init.php';

$user = new User();
$db = DB::getInstance();

if (Session::exists('home')) {
    echo '<p>' . Session::msg('home') . '</p>';
}

if (!$user->isLoggedIn()) {
    Redirect::to('index.php');
}

if (Input::exists()) {
    if (Token::check(Input::get('token'))) {
        //proveri input unose
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'lozinka' => array(
                'required' => true
            ),
            'lozinka_nova' => array(
                'min' => 8,
                'max' => 30
            ),
            'lozinka_nova_ponovo' => array(
                'min' => 8,
                'max' => 30,
                'matches' => 'lozinka_nova'
            )
        ));
        if ($validation->passed()) {
            //proveri unose fajlova
            $validate1 = new Validate();
            $validation1 = $validate1->checkImg($_POST, $_FILES['avatar']);
            if ($validation1) {
                //provera trenutne lozinke
                if (Hash::make(Input::get('lozinka')) !== $user->data()->lozinka) {
                    echo 'Pogresna trenutna lozinka';
                } else {
                    //proveri da li postoji file za upload
                    if (!empty($_FILES)) {
                        move_uploaded_file($_FILES['avatar']['tmp_name'], "img/avatars/" . $_FILES['avatar']['name']);
                        $db->updateFirst('ets_korisnici', 'avatar', $_FILES['avatar']['name'], $user->data()->korisnikID);
                    }
                    //proveri da li postoji nesto u poljima za novu lozinku
                    if (!empty(Input::get('lozinka_nova')) && !empty(Input::get('lozinka_nova_ponovo'))) {
                        $user->update(array(
                            'lozinka' => Hash::make(Input::get('lozinka_nova'))
                        ));
                    }

                    Session::msg('home', 'Uspesno promenjeno');
                    Redirect::to('index.php');
                }
            }
        } else {
            foreach ($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
}
?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="field">
        <label for="lozinka">Trenutna lozinka: </label>
        <input type="password" name="lozinka" id="lozinka" value="" autocomplete="on" required>
    </div>

    <div class="field">
        <label for="lozinka_nova">Nova lozinka: </label>
        <input type="password" name="lozinka_nova" id="lozinka_nova" value="">
    </div>

    <div class="field">
        <label for="lozinka_nova_ponovo">Ponovite novu lozinku: </label>
        <input type="password" name="lozinka_nova_ponovo" id="lozinka_nova_ponovo" value="" autocomplete="off">
    </div>

    <div class="field">
        <label for="avatar">Avatar: </label>
        <input type="file" name="avatar" id="avatar" value="">
    </div>
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
    <input type="submit" value="Potvrdi">
</form>