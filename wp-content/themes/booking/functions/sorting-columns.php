<?php

if (is_admin() && 'edit.php' == $pagenow && 'MY_POST_TYPE' == $_GET['post_type']) {
    add_action('pre_get_posts', 'bk_sort_reservation_column_query');
}

function bk_set_sortable_reservation_columns($columns)
{
    $columns['date_and_time'] = 'date_and_time';
    return $columns;
}
add_filter('manage_edit-reservations_sortable_columns', 'bk_set_sortable_reservation_columns');

function bk_sort_reservation_column_query($query)
{
    $orderby = $query->get('orderby');

    if ('date_and_time' == $orderby) {

        $meta_query = array(
            'relation' => 'OR',
            array(
                'key' => 'date_and_time',
                'compare' => 'NOT EXISTS', // see note above
            ),
            array(
                'key' => 'date_and_time',
            ),
        );

        $query->set('meta_query', $meta_query);
        $query->set('orderby', 'meta_value');
    }
}
