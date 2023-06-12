<?php get_header() ?>


<!----------------------------- Search section ----------------------------->
<section class="text-center py-5 mb-3 shadow gradient d-flex flex-column justify-content-center" style="height: 40vh" data-aos="fade-in" data-aos-once="true">
    <h2 class="mb-3 text-light"><?php bloginfo('description') ?></h2>

    <form role="search" method="GET" action="<?php echo esc_url(home_url('/')) ?>" class="fs-5 d-flex justify-content-center align-items-center bk-search-form" id="advanced-searchform">
        <div class="d-flex align-items-center justify-content-center">
            On <input class="p-2 m-2" type="date" name="date" id="date" disabled>
            at <input class="p-2 m-2" type="time" name="time" id="time" disabled> for
            <input class="p-2 m-2" type="number" name="num" id="num" style="width:50px" disabled> people in
            Find restaurants of
            <select class="p-2 m-2" name="cities" id="cities">
                <!-- <option value="any">Any City</option> -->
                <?php
                $parent_cities = get_terms([
                    'taxonomy' => 'cities',
                    'hide_empty' => false,
                    'parent' => 0,
                ]);
                ?>

                <?php foreach ($parent_cities as $city) : ?>
                    <option name="cities" value="<?php echo $city->slug ?>"><?php echo $city->name ?></option>
                    <?php
                    $children = get_terms([
                        'taxonomy' => 'cities',
                        'parent' => $city->term_id,
                        'hide_empty' => false
                    ]);
                    ?>

                    <?php foreach ($children as $child_city) : ?>
                        <option name="cities" value="<?php echo $child_city->slug ?>">&nbsp;&nbsp;&nbsp;<?php echo $child_city->name ?></option>
                    <?php endforeach ?>

                <?php endforeach; ?>
            </select>

            <input type="hidden" name="search" value="advanced">

            <div class="input-group-append ms-3">
                <button class="btn btn-light fs-5" type="submit" id="searchsubmit">Find <!--Tables!--></button>
            </div>
        </div>
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