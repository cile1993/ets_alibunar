<?php
require_once './private/init.php';

$user = new User();
$db = DB::getInstance();
$key = Input::get('key');
if (!$user) {
    Redirect::to(404);
} else {
    if (Session::exists('reset')) {
        echo '<p>' . Session::msg('reset') . '</p>';
    }
    ?>
    <form class="caption d-flex justify-content-center" action="" method="post">
        <?php
        if (!$key) {
            //proveri da li se email prosledjen u get nalazi u reset password tabeli
            if (Input::exists()) {
                if (Token::check(Input::get('token'))) {
                    $validate = new Validate();
                    $validation = $validate->check($_POST, array(
                        'email' => array(
                            'required' => true,
                            'min' => 8,
                            'max' => 45
                    )));
                    $email = filter_var(Input::get('email'), FILTER_SANITIZE_EMAIL);
                }
                if ($user->isRegistered($email) && $validation->passed()) {
                    //kreiraj token
                    $tok = Token::tokenMake();
                    //napravi vreme sa 1h unapred u tableli tako da kljuc traje samo 1h
                    $expFormat = mktime(date("H") + 1, date("i"), date("s"), date("m"), date("d"), date("Y"));
                    $expDate = date("Y-m-d H:i:s", $expFormat);
                    //ako vec postoji zapis za korisnika kreiraj mu novi link
                    if ($db->get('ets_reset_password', array('email', '=', $email))) {
                        $db->delete('ets_reset_password', array('email', '=', $email));
                    }
                    $db->insert('ets_reset_password', array('email' => $email, 'resetkey' => $tok, 'expire' => $expDate));
                    $kljuc = $db->action('select *', 'ets_reset_password', ['email', '=', $email])->first()->resetkey;

                    // Instantiation and passing `true` enables exceptions
                    $mail = new PHPMailer(true);

                    try {
                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                        $mail->isSMTP();                                            // Send using SMTP
                        $mail->Host = 'mboxhosting.com';                    // Set the SMTP server to send through
                        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
                        $mail->Username = 'noreply@negujmoalibunar.mypressonline.com';                     // SMTP username
                        $mail->Password = 'mixed:var1';                               // SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
                        $mail->Port = 587;                                    // TCP port to connect to
                        //Recipients
                        $mail->setFrom('noreply@negujmoalibunar.mypressonline.com', 'ETS Alibunar');
                        $mail->addAddress($email);
                        // Content
                        $body= 'Da bi ste resetovali lozinku kliknite na sledeci link:<br>'.URL.'reset.php?key='.$kljuc.'<br>Napominjemo da link vazi 1h!';
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = 'Resetovanje lozinke';
                        $mail->Body = $body;
                        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

                        $mail->send();
                        echo 'Message has been sent';
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }

//                    $to_email = $email;
//                    $subject = 'Resetovanje lozinke';
//                    $message = 'Da bi ste resetovali lozinku kliknite na sledeci link:<br>'.URL.'reset.php?key='.$kljuc . '<br>Napominjemo da link vazi 1h!';
//                    $headers = 'From: noreply@negujmoalibunar.mypressonline.com';
//                    mail($to_email, $subject, $message, $headers);

                    Session::msg('reset', 'Poslat vam je link na email i vazi 1h!');
                    Redirect::to('reset.php');
                }
            }
            ?>
            <!-- reset form -->
            <!-- ako nije prosledjen token -->
            <div class="card">
                <div class="card-header">
                    <h3>Resetuj lozinku</h3>
                </div>
                <div class="card-body">
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        Email:  <input type="email" name="email" class="form-control" placeholder="user@example.com" required>
                    </div>
                </div>
            </div>
            <input type="submit" value="Potvrdi" class="btn float-right login_btn">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </form>
        <!-- end ako nije prosledjen token -->
        <?php
    } elseif ($key) {
        @$data = $db->get('ets_reset_password', array('resetkey', '=', $key))->first();
        if (@($key !== $data->resetkey)) {
            Redirect::to(404);
        }
        if ($key === $data->resetkey) {

            $currentDate = date('Y-m-d H:i:s', time());
            //obrisi zapis ukoliko je istekao kljuc kako bi se mogao napraviti novi
            if ($data->resetkey === $key && $data->expire <= $currentDate) {
                $db->delete('ets_reset_password', array('resetkey', '=', $data->resetkey));
            }
            //proveri da li kljuc postoji u reset tabeli, da li nije istekao i da li se poklapaju emailovi
            if ($data->resetkey === $key && $data->expire >= $currentDate) {
                //proveri da li se poklapaju email adrese iz reset i korisnici
                if ($user->find($data->email)) {
                    //odradi menjanje lozinke
                    $user = new User($data->email);

                    //
                    //proveri da li ima unosa u polju da ne posalje prazno
                    if (Input::exists()) {
                        //proveri da li se token poklapa zbog csr
                        if (Token::check(Input::get('token'))) {

                            $validate = new Validate();
                            $validation = $validate->check($_POST, array(
                                'password' => array(
                                    'required' => true,
                                    'min' => 8,
                                    'max' => 30
                                ),
                                'password_again' => array(
                                    'required' => true,
                                    'min' => 8,
                                    'max' => 30,
                                    'matches' => 'password'
                                )
                            ));

                            if ($validation->passed()) {
                                echo $data->email;
                                try {
                                    $user->reset(array(
                                        'lozinka' => Hash::make(escape(Input::get('password')))
                                            ), $data->email);
                                    $db->delete('ets_reset_password', array('resetkey', '=', $data->resetkey));
                                    Session::msg('reset', 'Lozinka je uspesno promenjena!');
                                    Redirect::to('login_form.php');
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
                ?>
                <!-- ako je prosledjen token -->
                <div class="card">
                    <div class="card-header">
                        <h3>Resetuj lozinku</h3>
                    </div>
                    <div class="card-body">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            Nova lozinka:  <input type="password" name="password" class="form-control" placeholder="" >

                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            Ponovite:  <input type="password" name="password_again" class="form-control" placeholder="" autocomplete="" >
                        </div>
                        <div class="form-group">
                <?php
            }
        }
        ?>
                    <input type="submit" value="Potvrdi" class="btn float-right login_btn">
                </div>
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
        </div>
        </form>
        <!-- end reset form -->
        <?php
    }
}
?>