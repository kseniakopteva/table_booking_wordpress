<div class="row">
    <?php
    $the_query = new WP_Query([
        'post_type' => 'restaurants',
        'orderby'   => 'rand',
        'posts_per_page' => 3,
    ]);

    if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post() ?>

            <div class="col-6 col-xl-4  d-flex align-items-stretch" data-aos="fade-zoom-in" data-aos-once="true" data-aos-delay="<?php echo $args['wait'] ?>00">
                <div class="card my-3 p-2 ps-3">
                    <div class="row no-gutters h-100">
                        <div class="col-md-5 h-100 d-flex flex-column justify-content-center">
                            <img src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" class="card-img" alt="<?php echo get_field('photo')['alt'] ?>">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body p-2 d-flex flex-column justify-content-between h-100 ">
                                <div>
                                    <h5 class="card-title"><a style="text-decoration: none" href="<?php echo get_permalink() ?>"><?php the_title() ?></a></h5>
                                    <p class="card-text"><small><?php bk_get_first_sentence(get_field('description')) ?></small></p>
                                    <address class="text-muted mb-0"><small><?php the_terms($post->ID, 'cities') ?>, <?php echo get_field('location') ?></small></address>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <a href="<?php the_permalink() ?>" class="btn bk-button px-3"><small>More</small></a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php $args['wait']++;
        endwhile ?>
    <?php wp_reset_postdata();
    else :
    ?>
        <p>Sorry! No restaurants found:/</p>
    <?php endif; ?>
</div>