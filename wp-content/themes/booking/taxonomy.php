<?php get_header() ?>
<h2>Explore Restaurants of <?php single_term_title() ?></h2>


<?php if (have_posts()) :
    while (have_posts()) :
        the_post();
?>
        <div class="card my-5 mx-2">
            <div class="row no-gutters">
                <div class="col-auto">
                    <img src="//placehold.it/200" class="img-fluid" alt="">
                </div>
                <div class="col">
                    <div class="card-block p-4">
                        <h4 class="card-title"><?php the_title() ?></h4>
                        <p class="card-text">Description</p>
                        <a href="#" class="btn btn-primary">BUTTON</a>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile;
else : ?>
    <p><?php esc_html_e('No results found.'); ?></p>
<?php endif; ?>




<?php get_footer() ?>