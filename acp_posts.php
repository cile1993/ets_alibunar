<?php

require_once './private/init.php';

$user = new User();

if ($user->isLoggedIn() && $user->hasPermission('profesor')) {
    $posts = DB::getInstance()->action('SELECT blogID, kategorija, naslov', 'ets_blog', ['blogID', '>', 0])->results();
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Kategorija</th>
            <th>Naslov</th>
            <th>Izmena</th>
        </tr>
        <?php
        foreach ($posts as $key => $value) {
            ?>
            <tr>
                <td><?php echo $value->blogID ?></td>
                <td><?php echo $value->kategorija ?></td>
                <td><?php echo $value->naslov ?></td>
                <td><a href=edit_post.php?id=<?php echo $value->blogID ?>>Izmeni</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
} else {
    Redirect::to(404);
}