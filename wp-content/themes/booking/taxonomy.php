<?php get_header() ?>
<section>
    <h2>Explore Restaurants of <?php single_term_title() ?></h2>


    <?php if (have_posts()) :
        while (have_posts()) :
            the_post();
            $wait = 0;
    ?>
            <div class="card my-5 mx-5 p-3" data-aos="fade-zoom-in" data-aos-once="true" data-aos-delay="<?php echo $wait ?>00">
                <div class="row no-gutters">
                    <div class="col-auto h-100">
                        <img src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" class=" card-img" alt="<?php echo get_field('photo')['alt'] ?>">
                    </div>
                    <div class="col">
                        <div class="card-block p-4 d-flex flex-column justify-content-between h-100">
                            <div>
                                <h4 class="card-title"><a style="text-decoration: none" href="<?php echo get_permalink() ?>"><?php the_title() ?></a></h4>
                                <p class="card-text"><?php echo get_field('description') ?></p>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Atrodas: <?php the_terms($post->ID, 'cities') ?>, <?php echo get_field('location') ?></span>
                                <a href="<?php the_permalink() ?>" class="btn bk-button">More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php $wait++;
        endwhile;
    else : ?>
        <p class="text-danger fs-5 mt-3 mb-5"><?php esc_html_e('No results found.'); ?></p>
</section>
<section>
    <h4 class="my-3">Maybe check out other restaurants:</h4>


    <?php get_template_part('random-three-restaurants', null, ['wait' => 0]) ?>
</section>

<?php endif; ?>




<?php get_footer() ?>