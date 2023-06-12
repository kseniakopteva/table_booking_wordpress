<?php

add_action('admin_post_nopriv_modal-form', 'bk_modal_form_handler');
add_action('admin_post_modal-form', 'bk_modal_form_handler');
function bk_modal_form_handler()
{
    $name = wp_strip_all_tags($_POST['name']);
    $phone = wp_strip_all_tags($_POST['tel']);
    $number_of_people = wp_strip_all_tags($_POST['num']);
    $date = wp_strip_all_tags($_POST['date']);
    $time = wp_strip_all_tags($_POST['time']);
    $restaurant_id = wp_strip_all_tags($_POST['restaurantID']);

    $bk_post_id = wp_insert_post(wp_slash([
        'post_title' => 'Reservation ',
        'post_type' => 'reservations',
        'post_status' => 'publish',
        'meta_input' => [
            'bk_name' => $name,
            'bk_phone' => $phone,
            'bk_num' => $number_of_people,
            'bk_table' => bk_find_table($restaurant_id, $number_of_people, $date, $time)['ID'],
            'bk_date' => $date,
            'bk_time' => $time,
            'bk_restaurantID' => $restaurant_id,
        ]
    ]));

    if ($bk_post_id !== 0) {
        wp_update_post([
            'ID' => $bk_post_id,
            'post_title' => 'Reservation ' . $bk_post_id,
        ]);
    }

    // header('Location: ' . home_url());
    header('Location: ' . wp_get_referer());
}

// 1. get all restaurants.
// 2. for each restaurant get all available tables.
// 3. for each table, get all reservations.
// 4. for each reservation, check if it makes this table unavailable
// 5. if it does - delete table. if doesn't - do nothing.
// 6. if tables are left - show this restaurant.


function searchfilter($query)
{
    if ($query->is_search && !is_admin()) {
        if (isset($_GET['post_type'])) {
            $type = $_GET['post_type'];
            if ($type == 'restaurants') {
                $query->set('post_type', ['restaurants']);
            }
        }
    }
    return $query;
}
add_filter('pre_get_posts', 'searchfilter');


/*

function bk_filter_restaurants($query)
{
    if (!is_admin() && $query->is_main_query() && $query->is_tax('cities')) {
        // $query->set('meta_key', 'META_KEY_NAME');
        // //for example: $q->set( 'post_status', 'publish' );
        // $query->set('meta_value', 'META_VALUE_VALUE');
        // // Rest of your arguments to set
    }
}

add_action('admin_post_nopriv_search-form', 'bk_search_form_handler');
add_action('admin_post_search-form', 'bk_search_form_handler');

function bk_search_form_handler()
{
    $number_of_people = wp_strip_all_tags($_POST['num']);
    $date = wp_strip_all_tags($_POST['date']);
    $time = wp_strip_all_tags($_POST['time']);
    $cities = wp_strip_all_tags($_POST['cities']);

    $restaurants = (new WP_Query([
        'post_type' => 'restaurants',
        'numberposts' => -1,

    ]))->posts;


    add_action('pre_get_posts', 'bk_filter_restaurants');


    header('Location: ' . get_post_type_archive_link('restaurants'));
}
*/