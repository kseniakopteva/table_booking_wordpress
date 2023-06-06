<?php get_header() ?>
<h2 class="my-4"><?php the_title() ?></h2>
<div class="row">
    <img class="col-3" src="<?php echo get_field('photo')['sizes']['thumbnail'] ?>" alt="<?php echo get_field('photo')['alt'] ?>">
    <div class="col d-flex justify-content-between flex-column">
        <div>
            <p><?php echo get_field('description') ?></p>
            <p>Atrodas: <?php the_terms($post->ID, 'cities') ?>, <?php echo get_field('location') ?></p>
        </div>
        <div><button class="btn btn-primary">Rezervēt galdiņu</button></div>
    </div>

</div>
<?php get_footer() ?>