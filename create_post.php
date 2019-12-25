<?php
require_once './private/init.php';

//include_once './includes/header.php';
$user = new User();
$blog = new Blog();

if ($user->hasPermission('profesor')) {
    if (Session::exists('home')) {
        echo '<p>' . Session::msg('home') . '</p>';
    }
    if (Token::check(Input::get('token'))) {
        //proveri input unose
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'naslov' => array(
                'min' => 8,
                'required' => true
            ),
            'text' => array(
                'min' => 50,
                'max' => 1550,
                'required' => true
            )
        ));
        if ($validation->passed()) {
            //proveri unose fajlova
            $validate1 = new Validate();
            $validation1 = $validate1->checkImg($_POST, $_FILES['slika'], 1280, 720, 400000);
            if ($validation1) {
                //proveri da li postoji file za upload
                if (!empty($_FILES)) {
                    $target = "/img/blog/" . basename($_FILES['slika']['name']);
                    move_uploaded_file($_FILES['slika']['tmp_name'], $target);
                    if (isset($_POST['published'])) {
                        $blog->_db->insert('ets_blog', array(
                            'kategorija' => $_POST['kategorija'],
                            'naslov' => $_POST['naslov'],
                            'tekst' => $_POST['text'],
                            'image' => $_FILES['slika']['name'],
                            'korisnikID' => $_POST['userid'],
                            'published' => 1
                        ));
                        Session::msg('home', 'Uspesno!');
                        Redirect::to('create_post.php');
                    } elseif (!isset($_POST['published'])) {
                        $blog->_db->insert('ets_blog', array(
                            'kategorija' => $_POST['kategorija'],
                            'naslov' => $_POST['naslov'],
                            'tekst' => $_POST['text'],
                            'korisnikID' => $_POST['userid'],
                            'image' => $_FILES['slika']['name']
                        ));
                        Session::msg('home', 'Uspesno!');
                        Redirect::to('create_post.php');
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
    <div class="forms-fix">
        <h3>Novi clanak</h3>
        <form action="create_post.php" method="post" enctype="multipart/form-data">
            <div class="field">
                <label for="naslov">Naslov</label>
                <input type="text" name="naslov" id="naslov" value="" placeholder="Naslov teksta" autocomplete="off" required>
            </div>

            <div class="field">
                <label for="text">Tekst</label>
                <textarea name="text" id="text" value="" placeholder="Unesite tekst..." autocomplete="off" rows="4" cols="50" required=""></textarea>
            </div>
            <div class="field">
                <label for="slika">Slika: </label>
                <input type="file" name="slika" id="slika" value="" required="">
            </div>
            <input type="hidden" name="userid" id="id" value="<?php echo $user->data()->korisnikID ?>">
            <div class="field">
                <label for="kategorija">Kategorija</label>
                <select name="kategorija" id="kategorija">
                    <option value="javno">Javno</option>
                    <option value="ucenici">Ucenici</option>
                    <option value="profesori">Profesori</option>
                </select>
            </div>
            <div class="field">
                <label for="published">Objavljeno</label>
                <input type="checkbox" name="published" id="published" value="1">
            </div>
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="submit" value="Registruj">
        </form>
    </div>
    <?php
} else {
    Redirect::to(404);
}
?>