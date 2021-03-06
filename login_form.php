<?php
require_once './private/init.php';
$usert = new User();

if (Session::exists('reset')) {
    echo '<p>' . Session::msg('reset') . '</p>';
}

if (!$usert->isLoggedIn()) {
    if (Input::exists()) {
        if (Token::check(Input::get('token'))) {
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'email' => array('required' => true),
                'password' => array('required' => true)
            ));

            //Ako prodje validaciju pravi korisnika
            if ($validation->passed()) {
                $user = new User();
                $remember = (Input::get('remember') === 'on' ) ? true : false;


                //Pokupi unose sa polja i provuci kroz login funkciju
                $login = $user->login(Input::get('email'), Input::get('password'), $remember);

                //Odradi na osnovu da li je login funkcija vratila true ili false
                if ($login) {
                    //echo 'Uspesno ste se prijavili!';
                    Redirect::to('index.php');
                } else {
                    echo '<p>Pogresan email/password</p>';
                }
            } else
                foreach ($validation->errors() as $error) {
                    echo $error, '<br>';
                }
        }
    }
} else {
    Redirect::to('index.php');
}
?>


<!-- Start login form -->
<div class="landing">
    <div class="home-wrap">
        <div class="home-inner">
        </div>
    </div>
</div>
<form class="caption d-flex justify-content-center" action="" method="post">
    <div class="card">
        <div class="card-header">
            <h3>Prijavite se</h3>
        </div>
        <div class="card-body">
            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="email" name="email" class="form-control" placeholder="Email" required>

            </div>
            <div class="input-group form-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Lozinka" autocomplete="" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Potvrdi" class="btn float-right login_btn">
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="remember" id="remember" value="on">
                <label class="form-check-label" for="remember">Zapamti me</label>
            </div>
        </div>
        <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                <a href="reset.php">Zaboravljena lozinka?</a>
            </div>
        </div>
    </div>
</form>
<!-- End login form -->