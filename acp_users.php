<?php
require_once './private/init.php';

$user = new User();

if ($user->isLoggedIn() && $user->hasPermission('admin')) {
    $users = DB::getInstance()->action('SELECT korisnikID, ime, prezime, email', 'ets_korisnici', ['korisnikID', '>', '0'])->results();
    //var_dump($users[0]);
    ?>
    <table>
        <tr>
            <th>Ime</th>
            <th>Prezime</th>
            <th>Email</th>
            <th>Izmena</th>
        </tr>
        <?php
        foreach ($users as $key => $value) {
            ?>
            <tr>
                <td><?php echo $value->ime ?></td>
                <td><?php echo $value->prezime ?></td>
                <td><?php echo $value->email ?></td>
                <td><a href=update.php?user=<?php echo $value->email ?>>Izmeni</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
} else {
    Redirect::to(404);
}