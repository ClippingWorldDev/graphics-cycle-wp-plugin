<?php

/**
 * Custom Post Type
 * Author EgensLab
 * @since 1.0.0
 * */

if (!defined('ABSPATH')) {
	exit();  //exit if access directly
}
if (!class_exists('Softro_Custom_Post_Type')) {
	class Softro_Custom_Post_Type
	{

		//$instance variable
		private static $instance;

		public function __construct()
		{
			//register post type
			add_action('init', array($this, 'register_custom_post_type'));
		}

		/**
		 * get Instance
		 * @since  2.0.0
		 * */
		public static function getInstance()
		{
			if (null == self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Register Custom Post Type
		 * @since  2.0.0
		 * */
		public function register_custom_post_type()
		{
			$all_post_type = array(

				// Custom Post Career
				// [
				// 	'post_type' => 'career',
				// 	'args'      => array(
				// 		'label'       => esc_html__('Careers', 'softro-core'),
				// 		'description' => esc_html__('Careers', 'softro-core'),
				// 		'menu_icon'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
				// 		'labels'      => array(
				// 			'name'               => esc_html_x('Careers', 'Post Type General Name', 'softro-core'),
				// 			'singular_name'      => esc_html_x('Career', 'Post Type Singular Name', 'softro-core'),
				// 			'menu_name'          => esc_html__('Careers', 'softro-core'),
				// 			'all_items'          => esc_html__('All Careers', 'softro-core'),
				// 			'view_item'          => esc_html__('View Career', 'softro-core'),
				// 			'add_new_item'       => esc_html__('Add Career', 'softro-core'),
				// 			'add_new'            => esc_html__('Add Career', 'softro-core'),
				// 			'edit_item'          => esc_html__('Edit Career', 'softro-core'),
				// 			'update_item'        => esc_html__('Update Career', 'softro-core'),
				// 			'search_items'       => esc_html__('Search Career', 'softro-core'),
				// 			'not_found'          => esc_html__('Not Found', 'softro-core'),
				// 			'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
				// 		),
				// 		'supports'            => array('title', 'editor', 'excerpt', 'thumbnail'),
				// 		'hierarchical'        => true,
				// 		'public'              => true,
				// 		'has_archive'         => true,
				// 		"publicly_queryable"  => true,
				// 		'show_ui'             => true,
				// 		"rewrite"             => array('slug' => 'career', 'with_front' => true),
				// 		'exclude_from_search' => false,
				// 		'can_export'          => true,
				// 		'capability_type'     => 'post',
				// 		'query_var'           => true,
				// 		"show_in_rest"        => true,
				// 		'menu_position'       => 37,
				// 	)
				// ],

				// Custom Post Portfolio
				// [
				// 	'post_type' => 'portfolio',
				// 	'args'      => array(
				// 		'label'       => esc_html__('Portfolios', 'softro-core'),
				// 		'description' => esc_html__('Portfolios', 'softro-core'),
				// 		'menu_icon'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
				// 		'labels'      => array(
				// 			'name'               => esc_html_x('Portfolios', 'Post Type General Name', 'softro-core'),
				// 			'singular_name'      => esc_html_x('Portfolio', 'Post Type Singular Name', 'softro-core'),
				// 			'menu_name'          => esc_html__('Portfolios', 'softro-core'),
				// 			'all_items'          => esc_html__('All Portfolios', 'softro-core'),
				// 			'view_item'          => esc_html__('View Portfolio', 'softro-core'),
				// 			'add_new_item'       => esc_html__('Add Portfolio', 'softro-core'),
				// 			'add_new'            => esc_html__('Add Portfolio', 'softro-core'),
				// 			'edit_item'          => esc_html__('Edit Portfolio', 'softro-core'),
				// 			'update_item'        => esc_html__('Update Portfolio', 'softro-core'),
				// 			'search_items'       => esc_html__('Search Portfolio', 'softro-core'),
				// 			'not_found'          => esc_html__('Not Found', 'softro-core'),
				// 			'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
				// 		),
				// 		'supports'            => array('title', 'editor', 'excerpt', 'thumbnail'),
				// 		'hierarchical'        => true,
				// 		'public'              => true,
				// 		'has_archive'         => true,
				// 		"publicly_queryable"  => true,
				// 		'show_ui'             => true,
				// 		"rewrite"             => array('slug' => 'portfolio', 'with_front' => true),
				// 		'exclude_from_search' => false,
				// 		'can_export'          => true,
				// 		'capability_type'     => 'post',
				// 		'query_var'           => true,
				// 		"show_in_rest"        => true,
				// 		'menu_position'       => 37,
				// 	)
				// ],

				// Custom Post Case Study
				// [
				// 	'post_type' => 'case-study',
				// 	'args'      => array(
				// 		'label'       => esc_html__('Case Studies', 'softro-core'),
				// 		'description' => esc_html__('Case Studies', 'softro-core'),
				// 		'menu_icon'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
				// 		'labels'      => array(
				// 			'name'               => esc_html_x('Case Studies', 'Post Type General Name', 'softro-core'),
				// 			'singular_name'      => esc_html_x('Case Study', 'Post Type Singular Name', 'softro-core'),
				// 			'menu_name'          => esc_html__('Case Studies', 'softro-core'),
				// 			'all_items'          => esc_html__('All Case Studys', 'softro-core'),
				// 			'view_item'          => esc_html__('View Case Study', 'softro-core'),
				// 			'add_new_item'       => esc_html__('Add Case Study', 'softro-core'),
				// 			'add_new'            => esc_html__('Add Case Study', 'softro-core'),
				// 			'edit_item'          => esc_html__('Edit Case Study', 'softro-core'),
				// 			'update_item'        => esc_html__('Update Case Study', 'softro-core'),
				// 			'search_items'       => esc_html__('Search Case Study', 'softro-core'),
				// 			'not_found'          => esc_html__('Not Found', 'softro-core'),
				// 			'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
				// 		),
				// 		'supports'            => array('title', 'editor', 'excerpt', 'thumbnail'),
				// 		'hierarchical'        => true,
				// 		'public'              => true,
				// 		'has_archive'         => true,
				// 		"publicly_queryable"  => true,
				// 		'show_ui'             => true,
				// 		"rewrite"             => array('slug' => 'case-study', 'with_front' => true),
				// 		'exclude_from_search' => false,
				// 		'can_export'          => true,
				// 		'capability_type'     => 'post',
				// 		'query_var'           => true,
				// 		"show_in_rest"        => true,
				// 		'menu_position'       => 37,
				// 	)
				// ],

				// Custom post Mega Menu
				[
					'post_type' => 'mega-menu',
					'args'      => array(
						'label'       => esc_html__('Mega Menu', 'softro-core'),
						'description' => esc_html__('Mega Menu', 'softro-core'),
						'menu_icon'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
						'labels'      => array(
							'name'               => esc_html_x('Mega Menus', 'Post Type General Name', 'softro-core'),
							'singular_name'      => esc_html_x('Mega Menu', 'Post Type Singular Name', 'softro-core'),
							'menu_name'          => esc_html__('Mega Menu', 'softro-core'),
							'all_items'          => esc_html__('All Mega Menu', 'softro-core'),
							'view_item'          => esc_html__('View', 'softro-core'),
							'add_new_item'       => esc_html__('Add New', 'softro-core'),
							'add_new'            => esc_html__('Add New', 'softro-core'),
							'edit_item'          => esc_html__('Edit', 'softro-core'),
							'update_item'        => esc_html__('Update', 'softro-core'),
							'search_items'       => esc_html__('Search', 'softro-core'),
							'not_found'          => esc_html__('Not Found', 'softro-core'),
							'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
						),
						'supports'            => array('title', 'editor'),
						'hierarchical'        => true,
						'public'              => true,
						'has_archive'         => false,
						"publicly_queryable"  => true,
						'show_ui'             => true,
						"rewrite"             => array('slug' => 'mega-menu', 'with_front' => true),
						'exclude_from_search' => true,
						'can_export'          => true,
						'capability_type'     => 'post',
						'query_var'           => true,
						"show_in_rest"        => false,
						'menu_position'       => 37,
					)
				],

				// Custom Header Block
				[
					'post_type' => 'header-blocks',
					'args'      => array(
						'label'         => esc_html__('Header Templates', 'softro-core'),
						'description'   => esc_html__('Add softro header block here', 'softro-core'),
						'menu_icon'     => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
						"menu_position" => 60,
						'labels'        => array(
							'name'               => esc_html_x('Header Templates', 'Post Type General Name', 'softro-core'),
							'singular_name'      => esc_html_x('Header Template', 'Post Type Singular Name', 'softro-core'),
							'menu_name'          => esc_html__('Header Template', 'softro-core'),
							'all_items'          => esc_html__('All Header Templates', 'softro-core'),
							'view_item'          => esc_html__('View', 'softro-core'),
							'add_new_item'       => esc_html__('Add New', 'softro-core'),
							'add_new'            => esc_html__('Add New', 'softro-core'),
							'edit_item'          => esc_html__('Edit', 'softro-core'),
							'update_item'        => esc_html__('Update', 'softro-core'),
							'search_items'       => esc_html__('Search', 'softro-core'),
							'not_found'          => esc_html__('Not Found', 'softro-core'),
							'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
						),
						'supports'            => array('title', 'editor'),
						'hierarchical'        => true,
						'public'              => true,
						'has_archive'         => false,
						"publicly_queryable"  => true,
						'show_ui'             => true,
						"rewrite"             => array('slug' => 'header-blocks', 'with_front' => true),
						'exclude_from_search' => true,
						'can_export'          => true,
						'capability_type'     => 'post',
						'query_var'           => true,
						"show_in_rest"        => true,
						'menu_position'       => 38,
					)
				],

				// Custom Footer Block
				[
					'post_type' => 'footer-blocks',
					'args'      => array(
						'label'         => esc_html__('Footer Templates', 'softro-core'),
						'description'   => esc_html__('Add softro footer block here', 'softro-core'),
						'menu_icon'     => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/small-icon.svg'),
						"menu_position" => 60,
						'labels'        => array(
							'name'               => esc_html_x('Footer Templates', 'Post Type General Name', 'softro-core'),
							'singular_name'      => esc_html_x('Footer Template', 'Post Type Singular Name', 'softro-core'),
							'menu_name'          => esc_html__('Footer Template', 'softro-core'),
							'all_items'          => esc_html__('All Footer Templates', 'softro-core'),
							'view_item'          => esc_html__('View', 'softro-core'),
							'add_new_item'       => esc_html__('Add New', 'softro-core'),
							'add_new'            => esc_html__('Add New', 'softro-core'),
							'edit_item'          => esc_html__('Edit', 'softro-core'),
							'update_item'        => esc_html__('Update', 'softro-core'),
							'search_items'       => esc_html__('Search', 'softro-core'),
							'not_found'          => esc_html__('Not Found', 'softro-core'),
							'not_found_in_trash' => esc_html__('Not found in Trash', 'softro-core'),
						),
						'supports'            => array('title', 'editor'),
						'hierarchical'        => true,
						'public'              => true,
						'has_archive'         => false,
						"publicly_queryable"  => true,
						'show_ui'             => true,
						"rewrite"             => array('slug' => 'footer-blocks', 'with_front' => true),
						'exclude_from_search' => true,
						'can_export'          => true,
						'capability_type'     => 'post',
						'query_var'           => true,
						"show_in_rest"        => true,
						'menu_position'       => 39,
					)
				],




			);

			if (!empty($all_post_type) && is_array($all_post_type)) {
				foreach ($all_post_type as $post_type) {
					call_user_func_array('register_post_type', $post_type);
				}
			}

			/**
			 * Custom Taxonomy Register
			 */
			$all_custom_taxonmy = array(


				// career category
				array(
					'taxonomy'    => 'career-category',
					'object_type' => 'career',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Categories", 'softro-core'),
							"singular_name" => esc_html__("Categories", 'softro-core'),
							"menu_name"     => esc_html__("Categories", 'softro-core'),
							"all_items"     => esc_html__("All Career Categories", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Category", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => true,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'career-category', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
					)
				),

				// Tag for career post
				array(
					'taxonomy'    => 'career-tag',
					'object_type' => 'career',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Tags", 'softro-core'),
							"singular_name" => esc_html__("Tags", 'softro-core'),
							"menu_name"     => esc_html__("Tags", 'softro-core'),
							"all_items"     => esc_html__("All Tags", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Tags", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => false,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'career-tag', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
						'meta_box_cb'        => 'post_tags_meta_box',
					)
				),


				//portfolio category
				array(
					'taxonomy'    => 'portfolio-category',
					'object_type' => 'portfolio',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Categories", 'softro-core'),
							"singular_name" => esc_html__("Categories", 'softro-core'),
							"menu_name"     => esc_html__("Categories", 'softro-core'),
							"all_items"     => esc_html__("All Career Categories", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Category", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => true,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'portfolio-category', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
					)
				),

				// Tag for portfolio post
				array(
					'taxonomy'    => 'portfolio-tag',
					'object_type' => 'portfolio',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Tags", 'softro-core'),
							"singular_name" => esc_html__("Tags", 'softro-core'),
							"menu_name"     => esc_html__("Tags", 'softro-core'),
							"all_items"     => esc_html__("All Tags", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Tags", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => false,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'portfolio-tag', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
						'meta_box_cb'        => 'post_tags_meta_box',
					)
				),


				//case study category
				array(
					'taxonomy'    => 'case-study-category',
					'object_type' => 'case-study',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Categories", 'softro-core'),
							"singular_name" => esc_html__("Categories", 'softro-core'),
							"menu_name"     => esc_html__("Categories", 'softro-core'),
							"all_items"     => esc_html__("All Career Categories", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Category", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => true,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'case-study-category', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
					)
				),

				// Tag for case study post
				array(
					'taxonomy'    => 'case-study-tag',
					'object_type' => 'case-study',
					'args'        => array(
						"labels"  => array(
							"name"          => esc_html__("Tags", 'softro-core'),
							"singular_name" => esc_html__("Tags", 'softro-core'),
							"menu_name"     => esc_html__("Tags", 'softro-core'),
							"all_items"     => esc_html__("All Tags", 'softro-core'),
							"add_new_item"  => esc_html__("Add New Tags", 'softro-core')
						),
						"public"             => true,
						"hierarchical"       => false,
						'has_archive'        => true,
						"show_ui"            => true,
						"show_in_menu"       => true,
						"show_in_nav_menus"  => true,
						"rewrite"            => array('slug' => 'case-study-tag', 'with_front' => true),
						"query_var"          => true,
						"show_admin_column"  => true,
						"show_in_rest"       => true,
						"show_in_quick_edit" => true,
						'meta_box_cb'        => 'post_tags_meta_box',
					)
				),


			);

			if (is_array($all_custom_taxonmy) && !empty($all_custom_taxonmy)) {
				foreach ($all_custom_taxonmy as $taxonomy) {
					call_user_func_array('register_taxonomy', $taxonomy);
				}
			}

			flush_rewrite_rules();
		}
	} //end class

	if (class_exists('Softro_Custom_Post_Type')) {
		Softro_Custom_Post_Type::getInstance();
	}
}
