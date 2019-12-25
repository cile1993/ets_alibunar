<?php
require_once './private/init.php';

$user = new User();
$blog = new Blog();

$id = Input::get('id');
$post = $blog->_db->get('ets_blog', array('blogID', '=', $id))->first();

if($user->isLoggedIn() && $user->hasPermission('profesor')) { ?>
    <a class="btn btn-secondary btn-md" href="edit_post.php?id=<?php echo $post->blogID ?>">Izmeni</a>
<?php
}
?>


<div id="blog" class="offset">
    <div class="col-12 narrow text-center mt-5">
        <h1><?php echo $post->naslov ?></h1>
        <img src="<?php echo URL . 'img/blog/' . $post->image ?>" />
        <span class="lead"><?php echo date('F j, Y', strtotime($post->datum)); ?> in <?php echo $post->kategorija ?></span>
        <p><?php echo $post->tekst ?></p>
    </div>