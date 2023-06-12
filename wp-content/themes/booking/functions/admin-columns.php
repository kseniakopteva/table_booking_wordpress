<?php

function bk_add_tables_acf_columns($columns)
{
    return array_merge($columns, [
        'restaurant' => __('Restaurant'),
        'number_of_people'   => __('Number of People')
    ]);
}
add_filter('manage_tables_posts_columns', 'bk_add_tables_acf_columns');

function bk_tables_custom_column($column, $post_id)
{
    switch ($column) {
        case 'restaurant':
            echo get_the_title(get_post_meta($post_id, 'restaurant', true));
            break;
        case 'number_of_people':
            echo get_post_meta($post_id, 'number_of_people', true);
            break;
    }
}
add_action('manage_tables_posts_custom_column', 'bk_tables_custom_column', 1, 2);


function bk_add_restaurants_acf_columns($columns)
{
    return array_merge($columns, [
        'location' => __('Location'),
        'description'   => __('Description')
    ]);
}
add_filter('manage_restaurants_posts_columns', 'bk_add_restaurants_acf_columns');

function bk_restaurants_custom_column($column, $post_id)
{
    switch ($column) {
        case 'location':
            echo get_post_meta($post_id, 'location', true);
            break;
        case 'description':
            echo bk_get_excerpt(get_post_meta($post_id, 'description', true)) . ' ... ';
            break;
    }
}
add_action('manage_restaurants_posts_custom_column', 'bk_restaurants_custom_column', 1, 2);


function bk_add_reservations_acf_columns($columns)
{
    return array_merge($columns, [
        'date_and_time' => __('Reservation date and time'),
        'restaurant' => __('Restaurant'),
        'table' => __('Table'),
        'num' => __('Number of People')
    ]);
}
add_filter('manage_reservations_posts_columns', 'bk_add_reservations_acf_columns');

function bk_reservations_custom_column($column, $post_id)
{
    switch ($column) {
        case 'date_and_time':
            echo get_post_meta($post_id, 'bk_date', true) . ' ' . get_post_meta($post_id, 'bk_time', true);
            break;
        case 'restaurant':
            echo get_post(get_post_meta($post_id, 'bk_restaurantID', true))->post_title;
            break;
        case 'table':
            echo get_post(get_post_meta($post_id, 'bk_table', true))->post_title . ' (for ' . get_field('number_of_people', get_post(get_post_meta($post_id, 'bk_table', true))) . ' people)';
            break;
        case 'num':
            echo get_post_meta($post_id, 'bk_num', true);
            break;
    }
}
add_action('manage_reservations_posts_custom_column', 'bk_reservations_custom_column', 1, 2);
