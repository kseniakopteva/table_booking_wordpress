<?php

add_filter('show_admin_bar', '__return_false');

add_action('wp_enqueue_scripts', 'bk_scripts');
add_action('init', 'bk_register_types');

function bk_scripts()
{
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
	wp_enqueue_style('bk-style', get_template_directory_uri() . '/style.css', [], time());

	wp_enqueue_script('bootstrap-jquery', 'https://code.jquery.com/jquery-3.2.1.slim.min.js');
	wp_enqueue_script('bootstrap-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js', ['bootstrap-jquery']);
	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js', ['bootstrap-jquery', 'bootstrap-popper']);

	wp_enqueue_style('aos', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], time());
	wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', [], time(), true);

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
	]);
}

require_once(__DIR__ . '/functions/reservations-meta.php');
require_once(__DIR__ . '/functions/tables-meta.php');
require_once(__DIR__ . '/functions/admin-columns.php');
require_once(__DIR__ . '/functions/sorting-columns.php');
require_once(__DIR__ . '/functions/table-availability.php');
require_once(__DIR__ . '/functions/admin-post.php');


/* -------------------------------------------------------------------------- */
/*                           Other Helper Functions                           */
/* -------------------------------------------------------------------------- */

function bk_get_excerpt($text)
{
	echo substr($text, 0, 60);
}

add_action('pre_get_posts', 'query_set_only_author');
function query_set_only_author($query)
{
	global $pagenow;
	global $current_user;
	global $typenow;
	if ($query->is_main_query() && is_admin() && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts') && ($pagenow == 'edit.php' || $pagenow == 'upload.php')) {
		if ('reservations' != $typenow) {
			$query->set('author', $current_user->ID);
		} else {

			$this_users_restaurants =  (new WP_Query([
				'numberposts' => -1,
				'post_type' => 'restaurants',
				'author' => $current_user->ID
			]))->posts;

			$ids = [];

			foreach ($this_users_restaurants as $res) :
				// var_dump($res);
				$ids[] = $res->ID; //get_post_meta($res->ID, 'bk_restaurantID', true);
			endforeach;

			$query->set('meta_key', 'bk_restaurantID');
			$query->set('meta_value_num ', $ids);
			$query->set('meta_compare  ', 'IN');

			$meta_query = [
				'key'     => 'bk_restaurantID',
				'value'   => $ids,
				'compare' => 'IN',
			];

			// Set the meta query to the complete, altered query
			$query->set('meta_query', $meta_query);
		}
	}
}

add_filter('ajax_query_attachments_args', 'show_current_user_attachments');
function show_current_user_attachments($query)
{
	$user_id = get_current_user_id();
	if ($user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts')) {
		$query['author'] = $user_id;
	}
	return $query;
}

add_filter('acf/fields/post_object/query/name=restaurant', 'tables_restaurant_dropdown', 10, 3);
function tables_restaurant_dropdown($args, $field, $post_id)
{
	// get posts for current logged in user
	$args['author'] = get_current_user_id();

	return $args;
}
