<?php get_header() ?>


<!----------------------------- Search section ----------------------------->
<section class="text-center py-5 mb-3 shadow gradient d-flex flex-column justify-content-center" style="height: 40vh" data-aos="fade-in" data-aos-once="true">
    <h2 class="mb-3"><?php bloginfo('description') ?></h2>
    <form action="" class="fs-5 flex align-items-center">
        On <input class="p-2" type="date" name="date" id="date">
        at <input class="p-2" type="time" name="time" id="time"> for
        <input class="p-2" type="number" name="number" id="number" style="width:50px"> people in
        <select class="p-2" name="cities" id="cities">

            <?php
            $parent_cities = get_terms([
                'taxonomy' => 'cities',
                'hide_empty' => false,
                'parent' => 0,
            ]);
            ?>

            <?php foreach ($parent_cities as $city) : ?>
                <option value="<?php echo $city->slug ?>"><?php echo $city->name ?></option>
                <?php
                $children = get_terms([
                    'taxonomy' => 'cities',
                    'parent' => $city->term_id,
                    'hide_empty' => false
                ]);
                ?>

                <?php foreach ($children as $child_city) : ?>
                    <option value="<?php echo $child_city->slug ?>">&nbsp;&nbsp;&nbsp;<?php echo $child_city->name ?></option>
                <?php endforeach ?>

            <?php endforeach; ?>
        </select>
        <button class="btn btn-light fs-5" type="submit">Find Tables!</button>
    </form>
</section>

<!----------------------------- Random Section ---------------------------->
<section class="container">
    <h2>Check out these restaurants</h2>
    <?php get_template_part('random-three-restaurants', null, ['wait' => 1]) ?>

</section>


<!----------------------------- Explore Section ---------------------------->
<section class="container" id="explore">
    <h2>Explore Restaurants by City</h2>

    <ul class="list-unstyled fs-5 explore-list">
        <?php foreach ($parent_cities as $city) : ?>
            <li class="m-1">
                <a href="<?php echo get_term_link($city->slug, $city->taxonomy) ?>"> <?php echo $city->name ?></a>

                <?php
                $children = get_terms([
                    'taxonomy' => 'cities',
                    'parent' => $city->term_id,
                    'hide_empty' => false
                ]);
                ?>

                <?php if (!empty($children)) : ?>
                    <ul class="list-unstyled ms-4">
                        <?php foreach ($children as $child_city) : ?>
                            <li><a href="<?php echo get_term_link($child_city->slug, $child_city->taxonomy) ?>"> <?php echo $child_city->name ?> </a></li>
                        <?php endforeach ?>
                    </ul>
            </li>
        <?php else : ?>
            </li>
        <?php endif; ?>

    <?php endforeach; ?>
    </ul>


</section>


<?php get_footer() ?>