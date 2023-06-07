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
}

add_action('save_post_tables', 'bk_add_reservation_meta');
function bk_add_reservation_meta($ID)
{
	update_post_meta($ID, 'reserved_time', "");
}


add_action('add_meta_boxes', 'bk_add_reservation_meta_box');
function bk_add_reservation_meta_box()
{
	add_meta_box('', 'Reservations', 'reservation_cb');
}


function reservation_cb($post)
{
?>
	<ul>
		<?php
		$reservations = array_filter(explode(";", get_post_meta($post->ID, 'Reservations', true)));
		if (!empty($reservations)) :
			foreach ($reservations as $key => $value) : ?>
				<li><?php echo $key ?></li>
		<?php endforeach;
		else :
			echo "No reservations found.";
		endif;
		?>
	</ul>
<?php
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


function checkIfAvailable()
{
	$str = $_POST['data'];

	$data = [];
	foreach (explode(";", $str) as $items) {
		$pair = explode(":", $items);
		$data[$pair[0]] = $pair[1];
	}

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
				'value' => $data['restaurantID'],
				'compare' => '=',
			],
			[
				'key' => 'number_of_people',
				'value' => $data['num'],
				'compare' => '>=',
			],
		],

	];
	$all_tables = new WP_Query($args);

	$best_table_ID = $all_tables->posts[0]->ID;
	$best_table_num = get_field("number_of_people", $all_tables->posts[0]->ID);

	// = get_post_meta($ID, 'product_price', true);

	$arr  = [
		'ID' => $best_table_ID,
		'num' => $best_table_num
	];
	wp_send_json($arr);
	wp_die();
}
add_action('wp_ajax_nopriv_checkIfAvailable', 'checkIfAvailable');
add_action('wp_ajax_checkIfAvailable', 'checkIfAvailable');
