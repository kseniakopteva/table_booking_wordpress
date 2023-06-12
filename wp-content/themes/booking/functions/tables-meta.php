<?php

add_action('add_meta_boxes', 'bk_add_reservation_meta_box');
function bk_add_reservation_meta_box()
{
    add_meta_box('', 'Reservations', 'reservation_cb', 'tables');
}


function reservation_cb($post)
{
    $reservations = get_posts([
        'post_type' => 'reservations',
        'numberposts' => -1,
        'meta_key' => 'bk_table',
        'meta_value' => $post->ID
    ]);
?>
    <?php if (!empty(array_filter($reservations))) : ?>
        <table style="border-collapse: collapse;  width: 100%;">
            <tr>
                <th style="border: 1px solid #bababa; background-color: #eee">Name</th>
                <th style="border: 1px solid #bababa; background-color: #eee">Phone</th>
                <th style="border: 1px solid #bababa; background-color: #eee">Number Of People</th>
                <th style="border: 1px solid #bababa; background-color: #eee">Table</th>
                <th style="border: 1px solid #bababa; background-color: #eee">Date</th>
                <th style="border: 1px solid #bababa; background-color: #eee">Time</th>
            </tr>
            <?php foreach ($reservations as $res) :  ?>
                <tr>
                    <td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_name')[0] ?></td>
                    <td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_phone')[0] ?></td>
                    <td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_num')[0] ?></td>
                    <td style="border: 1px solid #bababa;"><?php echo get_the_title(get_post_meta($res->ID, 'bk_table')[0]) ?></td>
                    <td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_date')[0] ?></td>
                    <td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_time')[0] ?></td>
                </tr>
            <?php endforeach ?>
        </table>
    <?php else : ?>
        <p>No reservations found.</p>
<?php endif;
}
