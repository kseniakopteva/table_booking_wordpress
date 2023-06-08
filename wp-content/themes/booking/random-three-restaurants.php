<div class="row mx-3">
    <?php
    $the_query = new WP_Query([
        'post_type' => 'restaurants',
        'orderby'   => 'rand',
        'posts_per_page' => 3,
    ]);

    if ($the_query->have_posts()) : ?>
        <?php while ($the_query->have_posts()) : $the_query->the_post() ?>

            <div class="col-6 col-xl-4">

                <div class="card my-3" style="max-width: 540px;">
                    <div class="row  no-gutters">
                        <div class="col-md-5">
                            <img src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" class="card-img object-fit-cover" alt="<?php echo get_field('photo')['alt'] ?>">
                        </div>
                        <div class="col-md-7">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title"><a style="text-decoration: none" href="<?php echo get_permalink() ?>"><?php the_title() ?></a></h5>
                                    <p class="card-text"><?php bk_get_first_sentence(get_field('description')) ?></p>
                                    <span class="text-muted"><?php the_terms($post->ID, 'cities') ?>, <?php echo get_field('location') ?></span>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <a href="<?php the_permalink() ?>" class="btn btn-primary">More</a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endwhile ?>
    <?php wp_reset_postdata();
    else :
    ?>
        <p>Sorry! No restaurants found:/</p>
    <?php endif; ?>
</div>