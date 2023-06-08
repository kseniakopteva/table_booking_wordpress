<?php

add_action('wp_enqueue_scripts', 'bk_scripts');
add_action('init', 'bk_register_types');

function bk_scripts()
{
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
	wp_enqueue_style('bk-style', get_template_directory_uri() . '/style.css');

	wp_enqueue_script('bootstrap-jquery', 'https://code.jquery.com/jquery-3.2.1.slim.min.js');
	wp_enqueue_script('bootstrap-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', ['bootstrap-jquery']);
	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', ['bootstrap-jquery', 'bootstrap-popper']);

	wp_enqueue_script('jquery-js', 'https://code.jquery.com/jquery-3.7.0.js', [], time(), true);

	wp_enqueue_script('main-js', get_template_directory_uri() . '/script.js', ['jquery-js'], time(), true);
}

function bk_register_types()
{
	register_post_type('tables', [
		'labels' => [
			'name'               => 'Tables',
			'singular_name'      => 'Table',
			'add_new'            => 'Add tables',
			'add_new_item'       => 'Add tables',
			'edit_item'          => 'Edit table',
			'new_item'           => 'New table',
			'view_item'          => 'View table',
			'search_items'       => 'Search tables',
			'not_found'          => 'No tables found',
			'not_found_in_trash' => 'No tables in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Tables',
		],
		'public'                 => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-clipboard',
		'hierarchical'        => false,
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true
	]);

	register_post_type('restaurants', [
		'labels' => [
			'name'               => 'Restaurants',
			'singular_name'      => 'Restaurant',
			'add_new'            => 'Add restaurants',
			'add_new_item'       => 'Add restaurants',
			'edit_item'          => 'Edit restaurant',
			'new_item'           => 'New restaurant',
			'view_item'          => 'View restaurant',
			'search_items'       => 'Search restaurants',
			'not_found'          => 'No restaurants found',
			'not_found_in_trash' => 'No restaurants in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Restaurants',
		],
		'public'                 => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-store',
		'hierarchical'        => false,
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true
	]);

	register_taxonomy('cities', ['restaurants'], [
		'labels'                => [
			'name'              => 'Cities',
			'singular_name'     => 'City',
			'search_items'      => 'Search cities',
			'all_items'         => 'All cities',
			'view_item '        => 'View city',
			'edit_item'         => 'Edit city',
			'update_item'       => 'Update city',
			'add_new_item'      => 'Add new city',
			'new_item_name'     => 'New city name',
			'menu_name'         => 'Cities'
		],
		'public'                => true,
		'hierarchical'          => true,
		'has_archive'         => true,
		'show_admin_column' => true
	]);

	register_post_type('reservations', [
		'labels' => [
			'name'               => 'Reservations',
			'singular_name'      => 'Reservation',
			'add_new'            => 'Add reservation',
			'add_new_item'       => 'Add reservation',
			'edit_item'          => 'Edit reservation',
			'new_item'           => 'New reservation',
			'view_item'          => 'View reservations',
			'search_items'       => 'Search reservations',
			'not_found'          => 'No reservations found',
			'not_found_in_trash' => 'No reservations in trash',
			'parent_item_colon'  => '',
			'menu_name'          => 'Reservations',
		],
		'public'                 => false,
		'show_ui'				=> true,
		'show_in_menu' => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-calendar-alt',
		'hierarchical'        => false,
		'supports'            => ['title'], // 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
		'has_archive'         => true,
		/*'capabilities' => [
			'create_posts' => 'do_not_allow'
		]*/
	]);
}

add_action('add_meta_boxes', 'bk_meta_boxes');
function bk_meta_boxes()
{
	$fields = [
		'bk_creation_date' => 'Reservation Creation Date',
		'bk_name' => 'Name',
		'bk_phone' => 'Phone',
		'bk_num' => 'Number of People',
		'bk_date' => 'Date',
		'bk_time' => 'Time',
		'bk_restaurantID' => 'Restaurant',
	];
	foreach ($fields as $slug => $text) {
		add_meta_box(
			$slug,
			$text,
			'bk_meta_cb',
			'reservations',
			'advanced',
			'default',
			$slug
		);
	}
}

function bk_meta_cb($post_obj, $slug)
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
		default:
			$data = get_post_meta($post_obj->ID, $slug, true);
			$data = $data ? $data : 'No data';
			break;
	}
	echo '<p>' . $data . '</p>';
}


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
			'bk_table' => bk_find_table($restaurant_id, $number_of_people)['ID'],
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

	header('Location: ' . home_url());
}


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
					<td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_table')[0] ?></td>
					<td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_date')[0] ?></td>
					<td style="border: 1px solid #bababa;"><?php echo get_post_meta($res->ID, 'bk_time')[0] ?></td>
				</tr>
			<?php endforeach ?>
		</table>
	<?php else : ?>
		<p>No reservations found.</p>
<?php endif;
}





function bk_get_first_two_sentences($text)
{
	$position = stripos($text, '. '); //find first dot position

	if ($position) { //if there's a dot in our soruce text do
		$offset = $position + 1; //prepare offset
		$position2 = stripos($text, '. ', $offset); //find second dot using offset
		$first_two = substr($text, 0, $position2); //put two first sentences under $first_two

		echo $first_two . '.'; //add a dot
	} else {  //if there are no dots
		//do nothing
	}
}

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
			echo bk_get_first_two_sentences(get_post_meta($post_id, 'description', true)) . ' ... ';
			break;
	}
}
add_action('manage_restaurants_posts_custom_column', 'bk_restaurants_custom_column', 1, 2);




function bk_find_table($restaurant_id, $number_of_people)
{

	// check if available:
	// --- if there are tables with people = or >
	// ----- if there are reservations on this date
	// ------- at this time



	$args = [
		'post_type' => 'tables',
		'numerposts' => -1,
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
			],
		],

	];
	$all_tables = new WP_Query($args);

	$best_table['ID'] = $all_tables->posts[0]->ID;
	$best_table['num'] = get_field("number_of_people", $all_tables->posts[0]->ID);

	return $best_table;
}



function checkIfAvailable()
{
	$str = $_POST['data'];

	$data = [];
	foreach (explode(";", $str) as $items) {
		$pair = explode(":", $items);
		$data[$pair[0]] = $pair[1];
	}

	$best_table = bk_find_table($data['restaurantID'], $data['num']); // ADD DATE, TIME

	// = get_post_meta($ID, 'product_price', true);

	wp_send_json($best_table);
	wp_die();
}
add_action('wp_ajax_nopriv_checkIfAvailable', 'checkIfAvailable');
add_action('wp_ajax_checkIfAvailable', 'checkIfAvailable');
