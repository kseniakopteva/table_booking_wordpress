<?php get_header() ?>
<section class="row px-5">
    <h2 class="my-4"><?php the_title() ?></h2>
    <img class="col-3" src="<?php echo get_field('photo')['sizes']['large'] ?>" alt="<?php echo get_field('photo')['alt'] ?>">
    <div class="col d-flex justify-content-between flex-column">
        <div>
            <p><?php echo get_field('description') ?></p>
            <p>Atrodas: <?php echo get_field('location') ?>, <?php the_terms($post->ID, 'cities') ?></p>
        </div>
        <div class="align-self-end">
            <button type="button" class="btn bk-button" data-bs-toggle="modal" data-bs-target="#modalCenter">Rezervēt galdiņu</button>
        </div>

    </div>


    <?php get_template_part('modal') ?>

</section>





<?php get_footer() ?>