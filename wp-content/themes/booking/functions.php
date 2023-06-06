<?php

add_action('wp_enqueue_scripts', 'bk_scripts');
add_action('init', 'bk_register_types');

function bk_scripts()
{
	wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
	wp_enqueue_style('bk-style', 'style.css');

	wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
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
		'has_archive'         => true
	]);
}

add_action('save_post_tables', 'bk_save_hours');
function bk_save_hours($ID)
{
	update_post_meta($ID, 'hours', [true, true, true, true, true, true, true, true, true, true, true, true]);
}


add_action('add_meta_boxes', 'add_hours_meta_box');
function add_hours_meta_box()
{
	add_meta_box('', 'hours', 'hours_cb');
}

function hours_cb($post)
{
?>
	<table>
		<?php
		foreach (get_post_meta($post->ID, 'hours', true) as $key => $value) : ?>
			<td style="padding: 1rem; color: white; background-color: <?php echo $value ? 'green' : 'red' ?>">
				<?php echo ($key + 9) . ':00<br>' . ($value ? 'FREE' : 'RESERVED'); ?></td>
		<?php endforeach; ?>
	</table>
<?php
}
