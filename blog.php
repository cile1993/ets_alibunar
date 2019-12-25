<?php
require_once './private/init.php';

$blog = new Blog();
$user = new User();

include_once './includes/header.php';

if (!$user->isLoggedIn()) {
    foreach ($blog->categoryPosts('javno') as $key => $value) {
        ?>
        <div id="blog" class="offset">
            <div class="col-12 narrow text-center mt-5 pt-5">
                <h1><?php echo $value->naslov ?></h1>
                <span class="lead"><?php echo date('F j, Y', strtotime($value->datum)); ?> in <?php echo $value->kategorija ?></span>
                <img class="img-fluid" src="<?php echo URL . 'img/blog/' . $value->image ?>" />
                <p><?php echo $value->tekst ?></p>
                <a class="btn btn-secondary btn-md" href="single_post.php?id=<?php echo $value->blogID ?>">Vise...</a>
            </div>
            <?php
        }
    }
if ($user->isLoggedIn())
foreach ($blog->publishedPosts(1) as $key => $value) {
        if (($value->kategorija === 'profesori' && !$user->hasPermission('profesor')) || $value->kategorija === 'ucenici' && !$user->hasPermission('ucenik')) {
            continue;
        }
        ?>
        <div id="blog" class="offset">
            <div class="col-12 narrow text-center mt-5 pt-5">
                <h1><?php echo $value->naslov ?></h1>
                <span class="lead"><?php echo date('F j, Y', strtotime($value->datum)); ?> in <?php echo $value->kategorija ?></span>
                <img class="img-fluid" src="<?php echo URL . 'img/blog/' . $value->image ?>" />
                <p><?php echo $value->tekst ?></p>
                <a class="btn btn-secondary btn-md" href="single_post.php?id=<?php echo $value->blogID ?>">Vise...</a>
            </div>
            <?php
        }
include_once './includes/footer.php';
include_once './includes/scripts.php';
        ?>
        
        <!-- Start blog 
        <div class="col-md-4">
                <div class="card-content">
                    <div class="card-img">
                        <img src="https://placeimg.com/380/230/nature" alt="">
                    </div>
                    <div class="card-desc">
                        <h3>Heading</h3>
                        <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Laboriosam, voluptatum! Dolor quo, perspiciatis
                            voluptas totam</p>
                            <a href="#" class="btn-card">Read</a>   
                    </div>
                </div>
            </div>

            <!-- Start gallery -->
            <!--    <div class="row pt-4">
                    <div class="col-sm-6 mb20">
                        <img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/500/170?image=2" alt="">
                        <br>
                        <div class="row">
                            <div class="col-sm-6 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/300/185?image=3" alt=""></div>
                            <div class="col-sm-6 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/300/185?image=4" alt=""></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/300/300?image=5" alt=""></div>
                            <div class="col-sm-4 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/300/300?image=6" alt=""></div>
                            <div class="col-sm-4 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/300/300?image=7" alt=""></div>
                        </div>
                    </div>
                    <div class="col-sm-6 mb20"><img class="img-fluid m-x-auto d-block img-responsive" src="https://unsplash.it/600/600?image=1" alt=""></div>
                </div>
            </div>-->
            <!-- End gallery -->
            <!-- End blog -->

