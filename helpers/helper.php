<?php

namespace Egns_Core;

/**
 * All Elementor widget init
 * 
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
	exit();  // exit if access directly
}

if (!class_exists('Egns_Helper')) {

	class Egns_Helper
	{


		/**
		 * Helper Class constructor
		 */
		function __construct()
		{
			// call only public function here 
		}


		/**
		 * Menu links.
		 *
		 * @param   string $theme_location menu type.
		 * @param   string $container_class main class.
		 * @param   string $after icon tag.
		 * @param   string $menu_class .
		 * @param   string $depth.
		 * @since   1.0.0
		 */
		public static function egns_get_theme_menu($theme_location = 'primary-menu', $container_class = '', $link_before = '', $link_after = '', $after = '<i class="bi bi-plus dropdown-icon"></i>', $menu_class = 'menu-list', $depth = 3, $echo = true)
		{
			if (has_nav_menu($theme_location)) {
				wp_nav_menu(
					array(
						'theme_location'  => $theme_location,
						'container'       => false, // This will prevent any container div from being added
						'container_class' => $container_class,
						'link_before'     => $link_before . '<span>', // Add opening span tag
						'link_after'      => '</span>' . $link_after, // Add closing span tag
						'after'           => $after,
						'container_id'    => '',
						'menu_class'      => $menu_class,
						'fallback_cb'     => '',
						'menu_id'         => '',
						'depth'           => $depth,
						// Conditionally add the walker
						'walker'          => class_exists('CSF') ? new \Egns_Menu_Walker() : null,
					)
				);
			} else {
				if (is_user_logged_in()) { ?>
					<div class="set-menu">
						<h4>
							<a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">
								<?php echo esc_html__('Set Menu Here...', 'softro-core'); ?>
							</a>
						</h4>
					</div>
				<?php }
			}
		}


		/**
		 * Get all created menus with ID
		 *
		 * @since 1.0.0
		 */
		public static function list_all_menus()
		{
			// Get all registered menus
			$menus = get_terms('nav_menu', array('hide_empty' => true));

			// Initialize array and set default option
			$menu_names = array(
				'' => __('Select Menu', 'softro-core')
			);

			// Check if there are any menus
			if (!empty($menus) && !is_wp_error($menus)) {
				foreach ($menus as $menu) {
					$menu_names[$menu->slug] = $menu->name;
				}
			}

			return $menu_names;
		}


		/**
		 * Get all Elementor setting default value
		 * After that merge with custom value
		 *
		 * @since   1.0.0
		 */
		public static function return_defaul_elementor_settings(...$custom_value)
		{
			// Get default value, ensure it's an array
			$elementor = get_option('elementor_cpt_support');
			$elementor = is_array($elementor) ? $elementor : [];

			// Merge and remove duplicates
			$merged = array_unique(array_merge($elementor, $custom_value));

			// Update the option
			update_option('elementor_cpt_support', $merged);
		}



		/**
		 * Get theme options.
		 *
		 * @param string $opts Required. Option name.
		 * @param string $key Required. Option key.
		 * @param string $default Optional. Default value.
		 * @since   1.0.0
		 */

		public static function egns_get_theme_option($key, $key2 = '', $default = '')
		{
			$egns_theme_options = get_option('egns_theme_options');

			if (!empty($key2)) {
				return isset($egns_theme_options[$key][$key2]) ? $egns_theme_options[$key][$key2] : $default;
			} else {
				return isset($egns_theme_options[$key]) ? $egns_theme_options[$key] : $default;
			}
		}

		/**
		 * Return Career Page Option Value Based on Given Page Option ID.
		 *
		 * @since 1.0.0
		 *
		 * @param string $page_option_key Optional. Page Option id. By Default It will return all value.
		 * 
		 * @return mixed Page Option Value.
		 */
		public static function  egns_career_option_value($key1, $key2 = '', $default = '')
		{

			$page_options = get_post_meta(get_the_ID(), 'EGNS_CAREER_META_ID', true);

			if (isset($page_options[$key1][$key2])) {

				return $page_options[$key1][$key2];
			} else {
				if (isset($page_options[$key1])) {

					return  $page_options[$key1];
				} else {
					return $default;
				}
			}
		}


		/**
		 * Get any post list with ID
		 *
		 * $post_type post type name
		 * 
		 * @return array
		 */
		public static function get_list_by_post_type($post_type)
		{

			$post_lists = get_posts(array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'post_status'    => 'publish',
			));

			$options = array();
			foreach ($post_lists as $post) {
				$options[$post->ID] = $post->post_title;
			}

			return $options;
		}

		public static function  egns_project_value($key1, $key2 = '', $key3 = '', $default = '')
		{

			$page_options = get_post_meta(get_the_ID(), 'EGNS_PROJECT_META_ID', true);

			if (isset($page_options[$key1][$key2][$key3])) {
				return $page_options[$key1][$key2][$key3];
			} elseif (isset($page_options[$key1][$key2])) {
				return $page_options[$key1][$key2];
			} elseif (isset($page_options[$key1])) {
				return $page_options[$key1];
			} else {
				return $default;
			}
		}

		public static function get_post_list_by_post_type($post_type)
		{
			$return_val = [];
			$args       = array(
				'post_type'      => $post_type,
				'posts_per_page' => -1,
				'post_status'    => 'publish',

			);
			$all_post = new \WP_Query($args);

			while ($all_post->have_posts()) {
				$all_post->the_post();
				$return_val[get_the_ID()] = get_the_title();
			}
			wp_reset_postdata();
			return $return_val;
		}

		public static function get_all_post_key($post_type)
		{
			$return_val = [];
			$args       = array(
				'post_type'      => $post_type,
				'posts_per_page' => 6,
				'post_status'    => 'publish',

			);
			$all_post = new \WP_Query($args);

			while ($all_post->have_posts()) {
				$all_post->the_post();
				$return_val[] = get_the_ID();
			}
			wp_reset_postdata();
			return $return_val;
		}


		/**
		 * Get WooCommerce product categories
		 *
		 * @return array|WP_Error
		 */
		public static function get_woocommerce_product_categories()
		{
			// Get product categories
			$product_categories = get_terms('product_cat', array('hide_empty' => true));

			// Check if there are no categories
			if (empty($product_categories)) {
				// Handle the case where there are no categories (return a default value, show a message, etc.)
				return [];  // or return some default value
			}

			// Initialize an empty array to store category options
			$category_options = [];

			// Loop through each category
			foreach ($product_categories as $category) {
				// Build the associative array with term ID as key and category name as value
				$category_options[$category->slug] = $category->name;
			}

			// Return the category options array
			return $category_options;
		}

		public static function get_blog_categories()
		{
			$categories       = get_categories();  // Get all categories.
			$category_options = [];
			foreach ($categories as $category) {
				$category_options[$category->slug] = $category->name;
			}

			return $category_options;
		}

		/**
		 * filtering posts by title
		 *
		 * @return void
		 */
		public static function get_blog_post_options()
		{
			$posts   = get_posts(['post_type' => 'post', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		/**
		 * filtering posts by title
		 *
		 * @return void
		 */
		public static function get_project_post_options()
		{
			$posts   = get_posts(['post_type' => 'project', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		/**
		 * filtering posts by title
		 *
		 * @return void
		 */
		public static function get_materials_post_options()
		{
			$posts   = get_posts(['post_type' => 'materials', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		/**
		 * filtering posts by title
		 *
		 * @return void
		 */
		public static function get_career_post_options()
		{
			$posts   = get_posts(['post_type' => 'career', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}


		/**
		 * Get all taxonomy as options
		 *
		 * @return array
		 */
		public static function get_taxonomy_options($taxonomy)
		{
			$terms = get_terms([
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			]);

			$options = [];

			if (!is_wp_error($terms) && !empty($terms)) {
				foreach ($terms as $term) {
					$options[$term->term_id] = $term->name;
				}
			}

			return $options;
		}

		/**
		 * Filtering posts by title and multiple categories
		 *
		 * @param array|null $selected_category_ids Optional. Array of Category IDs to filter posts. Default is null (no filter).
		 * @return array Associative array of post IDs and their titles.
		 */
		public static function get_blog_post_cat_options($selected_category_ids = null)
		{
			// Initialize query arguments
			$query_args = [
				'post_type'      => 'post',
				'posts_per_page' => -1,
			];

			// If category IDs are provided, add them to the query arguments
			if (!empty($selected_category_ids)) {
				$query_args['category__in'] = $selected_category_ids;
			}

			// Get posts based on the query arguments
			$posts   = get_posts($query_args);
			$options = [];

			// Loop through posts and build options array
			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}




		/**
		 * Return taxonomy name with link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $taxonomy . give your taxonomy.
		 * 
		 * @param string $icon_class . give your icon class here.
		 * 
		 * @return mixed return taxonomy name with link.
		 */
		public static function term_with_link($icon_class, $taxonomy)
		{

			$terms = wp_get_object_terms(get_the_ID(), $taxonomy);
			if ($terms ?? ''):

				foreach ((array) $terms as $term): ?>
					<a href="<?php echo esc_url(get_term_link($term->slug, $taxonomy)); ?>"><i class="<?php echo esc_attr($icon_class); ?>"></i>
						<?php echo esc_html($term->name); ?>
					</a>
				<?php endforeach;

			endif;
		}

		/**
		 * Return taxonomy name with link.
		 *
		 * @since 1.0.0
		 *
		 * @param string $taxonomy . give your taxonomy.
		 * 
		 * @param string $icon_class . give your icon class here.
		 * 
		 * @return mixed return taxonomy name with link.
		 */
		public static function term_without_link($icon_class, $taxonomy)
		{

			$terms = wp_get_object_terms(get_the_ID(), $taxonomy);
			if ($terms ?? ''):
				?>

				<span><i class="<?php echo esc_attr($icon_class); ?>"></i>
					<?php
					foreach ((array) $terms as $term):
						echo esc_html($term->name);
					endforeach;
					?>
				</span>
			<?php
			endif;
		}

		/**
		 * Return term link value.
		 *
		 * @since 1.0.0
		 * 
		 * @return mixed Post type option value.
		 */
		public static function get_any_term_link($taxonomy)
		{
			$term = get_the_terms(get_the_ID(), $taxonomy);
			$link = get_term_link($term[0]->slug, $taxonomy);
			return $link;
		}

		/**
		 * filtering product by title
		 *
		 * @return void
		 */
		public static function get_product_lists()
		{
			$posts   = get_posts(['post_type' => 'product', 'posts_per_page' => -1]);
			$options = [];

			foreach ($posts as $post) {
				$options[$post->ID] = get_the_title($post->ID);
			}

			return $options;
		}
		/**
		 * clean special chars, spaces with hyphens
		 *
		 * @since   1.0.0
		 */
		public static function clean($string)
		{
			$string = str_replace(' ', '', $string);                  // Replaces all spaces with hyphens.
			$string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);  // Removes special chars.

			return preg_replace('/-+/', '', $string);  // Replaces multiple hyphens with single one.
		}

		/**
		 * Return Elementor header footer plugin post list
		 *
		 * @return data
		 */
		public static function get_custom_template_list($template = 'footer-blocks')
		{
			$args = array(
				'post_type'   => $template,
				'order'       => 'asc',
				'numberposts' => 999,
			);

			$latest_books = get_posts($args);
			$array        = [];
			foreach ($latest_books as $value) {
				$array[$value->post_name] = $value->post_title;
			}
			return $array;
		}

		/**
		 * Return Elementor header post ID or default area
		 *
		 * @param string $slug The slug of the header.
		 * @return string The shortcode for the header or default area.
		 */
		public static function get_header_data($slug)
		{
			$post = get_page_by_path($slug, OBJECT, 'header-blocks');

			if ($post) {
				// Check if the post is built with Elementor
				$document = \Elementor\Plugin::$instance->documents->get($post->ID);
				if ($document && $document->is_built_with_elementor()) {
					// Render Elementor content
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post->ID);
				} else {
					// Return default WordPress content (from the editor)
					return apply_filters('the_content', $post->post_content);
				}
			} else {
				// If no post is found, return a default fallback
				return self::default_header_area();
			}
		}

		/**
		 * Generate the HTML for the default footer area
		 *
		 * @return string The HTML for the default footer area.
		 */
		public static function default_header_area()
		{
			ob_start();  // Start output buffering
			?>

			<header class="header-area home4-header inner-header">
				<div class="header-area-wrap">
					<div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
						<div class="company-logo">
							<?php
							if (has_custom_logo()) {
								the_custom_logo();
							} else {
							?>
								<div class="site-title">
									<h3><a href="<?php echo esc_url(home_url('/')) ?>"><?php echo esc_html(get_bloginfo('name')); ?></a></h3>
								</div>
							<?php
							}
							?>
						</div>
						<div class="main-menu">
							<div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
								<?php
								if (has_custom_logo()) {
									the_custom_logo();
								} else {
								?>
									<div class="site-title">
										<h3><a href="<?php echo esc_url(home_url('/')) ?>"><?php echo esc_html(get_bloginfo('name')); ?></a></h3>
									</div>
								<?php
								}
								?>
								<div class="menu-close-btn">
									<i class="bi bi-x"></i>
								</div>
							</div>
							<!-- Main Menu -->
							<?php self::egns_get_theme_menu('primary-menu', '', '', '', '<i class="d-lg-flex d-none bi-caret-right-fill dropdown-icon"></i><i class="bi bi-plus dropdown-icon"></i>', 'menu-list', 3); ?>

						</div>

						<div class="right-area">
							<div class="nav-right">
								<div class="sidebar-button mobile-menu-btn">
									<svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
										<g>
											<path d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z"></path>
											<path d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z"></path>
											<path d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z"></path>
										</g>
									</svg>
								</div>
							</div>
						</div>
					</div>
				</div>
			</header>

		<?php
			$output = ob_get_clean();  // Get and clean the buffered output
			return $output;
		}

		/**
		 * Return Elementor header footer post ID or default footer area
		 *
		 * @param string $slug The slug of the header/footer.
		 * @return string The shortcode for the header/footer or default footer area.
		 */
		public static function get_footer_data($slug)
		{
			$post = get_page_by_path($slug, OBJECT, 'footer-blocks');

			if ($post) {
				// Check if the post is built with Elementor
				$document = \Elementor\Plugin::$instance->documents->get($post->ID);
				if ($document && $document->is_built_with_elementor()) {
					// Render Elementor content
					return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post->ID);
				} else {
					// Return default WordPress content (from the editor)
					return apply_filters('the_content', $post->post_content);
				}
			} else {
				// If no post is found, return a default fallback
				return self::default_footer_area_sec();
			}
		}



		/**
		 * Generate the HTML for the default footer area
		 *
		 * @return string The HTML for the default footer area.
		 */
		public static function default_footer_area_sec()
		{
			ob_start();  // Start output buffering
		?>

			<footer class="footer-section">
				<div class="container">
					<div class="copyright-and-social-area justify-content-center">
						<p>©
							<?php echo esc_html__('Copyright ', 'softro-core'); ?> <?php echo esc_html(wp_date('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?> | <?php echo esc_html__('All Rights Reserved.', 'softro-core'); ?>
						</p>
					</div>
				</div>
			</footer>

<?php
			$output = ob_get_clean();  // Get and clean the buffered output
			return $output;
		}


		/**
		 * calculating reading times
		 *
		 * @return void
		 */
		public static function calculate_reading_time($content)
		{
			// Count the number of words in the content.
			$word_count = str_word_count(strip_tags($content));
			// Minimum reading time is 1 minute.
			$reading_time = max(1, round($word_count / 100));
			return $reading_time;
		}


		/**
		 * Get post tags for select
		 *
		 * @return array
		 */
		public static function get_tags_for_select()
		{
			$tags    = get_tags();
			$options = [];
			foreach ($tags as $tag) {
				$options[$tag->term_id] = $tag->name;
			}
			return $options;
		}


		/**
		 * filtering posts by authors
		 *
		 * @return void
		 */
		public static function get_blog_authors()
		{
			// Define an array of roles you want to include
			$roles_to_include = ['author', 'administrator', 'subscriber', 'contributor', 'editor'];

			// Retrieve users based on the defined roles
			$users          = get_users(['role__in' => $roles_to_include]);
			$author_options = ['all' => esc_html__('All Authors', 'softro-core')];

			foreach ($users as $user) {
				$author_options[$user->ID] = $user->display_name;
			}

			return $author_options;
		}


		/**
		 * get post categories for select
		 *
		 * @return void 
		 */

		public static function get_categories_for_select()
		{
			$categories = get_categories();
			$options    = [];
			foreach ($categories as $category) {
				$options[$category->term_id] = $category->name;
			}
			return $options;
		}



		/**
		 * @return [string] video url for video post
		 */
		public static function egns_embeded_video($width = '', $height = '')
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_video_url', 1));
			if (!empty($url)) {
				return wp_oembed_get($url, array('width' => $width, 'height' => $height));
			}
		}


		/**
		 * @return [string] Has embed video for video post.
		 */
		public static function has_egns_embeded_video()
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_video_url', 1));
			if (!empty($url)) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * 
		 * @return [string] audio url for audio post
		 */
		public static function egns_embeded_audio($width, $height)
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_audio_url', 1));
			if (!empty($url)) {
				return '<div class="post-media">' . wp_oembed_get($url, array('width' => $width, 'height' => $height)) . '</div>';
			}
		}

		/**
		 * @return [string] Checks For Embed Audio In The Post.
		 */
		public static function egns_has_embeded_audio()
		{
			$url = esc_url(get_post_meta(get_the_ID(), 'egns_audio_url', 1));
			if (!empty($url)) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Check if the podcast is enabled for the post.
		 *
		 * @return bool True if podcast is enabled, false otherwise.
		 */
		public static function egns_is_podcast_enabled()
		{
			return (bool) get_post_meta(get_the_ID(), 'egns_podcast', true);
		}

		/**
		 * Get the podcast audio URL.
		 *
		 * @return string The podcast audio URL.
		 */
		public static function egns_get_podcast_audio_url()
		{
			return esc_url(get_post_meta(get_the_ID(), 'egns_podcast_audio_url', true));
		}

		/**
		 * Get the podcast audio URL.
		 *
		 * @return string The podcast audio URL.
		 */
		public static function egns_get_podcast_audio__main_url()
		{
			return esc_url(get_post_meta(get_the_ID(), 'egns_audio_url', true));
		}


		/**
		 * Get the podcast video URL.
		 *
		 * @return string The podcast audio URL.
		 */
		public static function egns_get_podcast_video_url()
		{
			return esc_url(get_post_meta(get_the_ID(), 'egns_video_url', true));
		}



		/**
		 * Get the podcast platform list.
		 *
		 * @return array The list of podcast platforms.
		 */
		public static function egns_get_podcast_platform_list()
		{
			$platforms = get_post_meta(get_the_ID(), 'egns_podcast_platform', true);
			return is_array($platforms) ? $platforms : array();
		}


		/**
		 * @return [string] Has Gallery for Gallery post.
		 */
		public static function has_egns_gallery()
		{
			$images = get_post_meta(get_the_ID(), 'egns_gallery_images', 1);
			if (!empty($images)) {
				return true;
			} else {
				return false;
			}
		}


		/**
		 * @return string get the attachment image.
		 */
		public static function egns_thumb_image()
		{
			$image = get_post_meta(get_the_ID(), 'egns_thumb_images', 1);
			echo '<a href="' . get_the_permalink() . '"><img src="' . $image['url'] . '" alt="' . esc_attr("image") . ' "class="img-fluid wp-post-image"></a>';
		}

		/**
		 * @return [quote] text for quote post
		 */
		public static function egns_quote_content()
		{
			$text = get_post_meta(get_the_ID(), 'egns_quote_text', 1);
			if (!empty($text)) {
				return sprintf("%s", $text);
			}
		}
	} //End Main Class


}//end if
