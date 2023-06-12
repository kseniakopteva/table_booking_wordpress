<?php

function bk_find_table($restaurant_id, $number_of_people, $date, $time)
{
    $all_suitable_tables = (new WP_Query([
        'post_type' => 'tables',
        'numberposts' => -1,
        'meta_key' => 'number_of_people',
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
        'meta_query' => [
            'relation' => 'AND',
            [
                'key' => 'restaurant',
                'value' => $restaurant_id,
                'compare' => '=',
            ],
            [
                'key' => 'number_of_people',
                'value' => $number_of_people,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ],
        ],

    ]))->posts;

    if (empty($all_suitable_tables)) {
        $best_table['num'] = -1;
        return $best_table;
    }

    $current_datetime = (new DateTime($date, new DateTimeZone('Europe/Riga')))->setTime((new DateTime($time))->format('H'), (new DateTime($time))->format('i'));

    foreach ($all_suitable_tables as $key1 => $table) {

        $all_reservations_for_this_table_on_this_day = (new WP_Query([
            'post_type' => 'reservations',
            'numberposts' => -1,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'bk_table',
                    'value' => $table->ID,
                    'compare' => '=',
                ],
                [
                    'key' => 'bk_date',
                    'value' => $date,
                    'compare' => '=',
                ],
            ],
        ]))->posts;

        if (!empty($all_reservations_for_this_table_on_this_day)) {

            foreach ($all_reservations_for_this_table_on_this_day as $key2 => $reservation) {

                $res_date = get_post_meta($reservation->ID, 'bk_date')[0];
                $res_time = get_post_meta($reservation->ID, 'bk_time')[0];

                $reserved_datetime = (new DateTime($res_date, new DateTimeZone('Europe/Riga')))
                    ->setTime((new DateTime($res_time))->format('H'), (new DateTime($res_time))->format('i'));

                $res_dt_start = new DateTime($reserved_datetime->format('Y-m-d H:i'), new DateTimeZone('Europe/Riga'));
                $res_dt_end = new DateTime($reserved_datetime->format('Y-m-d H:i'), new DateTimeZone('Europe/Riga'));

                // check if time is in range of -2 hours from current +2 from current 
                $res_dt_start->modify('-2 hour');
                $res_dt_end->modify('+2 hour');

                if ($current_datetime >= $res_dt_start && $current_datetime <= $res_dt_end) {
                    // THIS MEANS THIS TABLE IS RESERVED!!!
                    unset($all_suitable_tables[$key1]);
                    break;
                }
            }
        }
    }
    $all_suitable_tables = array_values($all_suitable_tables);


    // $best_table['ID'] = $all_suitable_tables[array_key_first($all_suitable_tables)]->ID;
    $best_table['ID'] = $all_suitable_tables[0]->ID;
    // $best_table['num'] = get_field("number_of_people", $all_suitable_tables[array_key_first($all_suitable_tables)]->ID);
    $best_table['num'] = get_field("number_of_people", $all_suitable_tables[0]->ID);
    $best_table['date'] = $current_datetime->format('F j, Y');
    $best_table['dateStr'] = $current_datetime->format('Y-m-d');
    $best_table['time'] = $current_datetime->format('H:i');
    $best_table['restaurant'] = get_the_title(get_post_meta($all_suitable_tables[0]->ID, 'restaurant', true));

    $ranges = preg_split('/\r\n|\r|\n/',  get_field('working_hours', get_post_meta($all_suitable_tables[0]->ID, 'restaurant', true)));
    $new_ranges = [];
    foreach ($ranges as $range) {
        $new_ranges[] = explode(' - ', $range);
    }
    $best_table['working_hours'] = $new_ranges;

    return $best_table;
}


function checkIfAvailable()
{
    $str = $_POST['data'];

    $data = [];
    foreach (explode("|", $str) as $items) {
        $pair = explode("^", $items);
        $data[$pair[0]] = $pair[1];
    }

    $best_table = bk_find_table($data['restaurantID'], $data['num'], $data['date'], $data['time']);

    wp_send_json($best_table);
    wp_die();
}
add_action('wp_ajax_nopriv_checkIfAvailable', 'checkIfAvailable');
add_action('wp_ajax_checkIfAvailable', 'checkIfAvailable');
