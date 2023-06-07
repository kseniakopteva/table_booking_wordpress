<?php get_header() ?>
<h2>Explore Restaurants of <?php single_term_title() ?></h2>


<?php if (have_posts()) :
    while (have_posts()) :
        the_post();
?>
        <div class="card my-5 mx-2">
            <div class="row no-gutters">
                <div class="col-auto h-100">
                    <img src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" class="img-fluid" alt="<?php echo get_field('photo')['alt'] ?>">
                </div>
                <div class="col">
                    <div class="card-block p-4 d-flex flex-column justify-content-between h-100">
                        <div>
                            <h4 class="card-title"><a style="text-decoration: none" href="<?php echo get_permalink() ?>"><?php the_title() ?></a></h4>
                            <p class="card-text"><?php echo get_field('description') ?></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center"><a href="<?php the_permalink() ?>" class="btn btn-primary">More</a> <span>Atrodas: <?php the_terms($post->ID, 'cities') ?>, <?php echo get_field('location') ?></span></div>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile;
else : ?>
    <p class="text-danger fs-5 mt-3 mb-5"><?php esc_html_e('No results found.'); ?></p>
    <h4 class="my-3">Maybe check out other restaurants:</h4>


    <?php
    $the_query = new WP_Query([
        'post_type' => 'restaurants',
        'orderby'   => 'rand',
        'posts_per_page' => 3,
    ]);

    if ($the_query->have_posts()) : ?>
        <section class="row ">
            <?php while ($the_query->have_posts()) : $the_query->the_post() ?>
                <div class="card col-3 m-4 p-0">
                    <img class="card-img-top" src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" alt="Card image cap">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="card-title"><a style="text-decoration: none" href="<?php echo get_permalink() ?>"><?php echo get_the_title() ?></a></h5>
                            <p class="card-text"><?php bk_get_first_two_sentences(get_field('description')) ?></p>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="<?php the_permalink() ?>" class="btn btn-primary">More</a>
                            <span><?php the_terms($post->ID, 'cities') ?></span>
                        </div>
                    </div>
                </div>


            <?php endwhile ?>
        </section>
    <?php wp_reset_postdata();
    else :
    ?>

        no posts found
    <?php endif; ?>


<?php endif; ?>




<?php get_footer() ?>