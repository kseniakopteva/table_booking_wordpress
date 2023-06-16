<?php


add_action('add_meta_boxes', 'bk_reservations_meta_boxes');
function bk_reservations_meta_boxes()
{
    $fields = [
        'bk_creation_date' => 'Reservation Creation Date',
        'bk_name' => 'Name',
        'bk_phone' => 'Phone',
        'bk_num' => 'Number of People',
        'bk_table' => 'Table',
        'bk_date' => 'Date',
        'bk_time' => 'Time',
        'bk_restaurantID' => 'Restaurant',
    ];
    global $pagenow;
    if ($pagenow !== 'post-new.php')
        foreach ($fields as $slug => $text) {
            add_meta_box(
                $slug,
                $text,
                'bk_reservations_meta_cb',
                'reservations',
                'advanced',
                'default',
                $slug
            );
        }
    else {
        add_meta_box(
            'msg',
            'Warning',
            function () {
                echo '<p style="background-color: yellow">Please enter new reservations through the form on the website!</p>';
            },
            'reservations',
            'advanced',
            'default',
            'msg'
        );
    }
}

function bk_reservations_meta_cb($post_obj, $slug)
{
    $slug = $slug['args'];
    $data = '';

    switch ($slug) {
        case 'bk_creation_date':
            $data = $post_obj->post_date;
            break;
        case 'bk_restaurantID':
            $id = get_post_meta($post_obj->ID, $slug, true);
            $data = get_the_title($id);
            break;
        case 'bk_table':
            $id = get_post_meta($post_obj->ID, $slug, true);
            $data = get_the_title($id);
            break;
        default:
            $data = get_post_meta($post_obj->ID, $slug, true);
            $data = $data ? $data : 'No data';
            break;
    }
    echo '<p>' . $data . '</p>';
}
