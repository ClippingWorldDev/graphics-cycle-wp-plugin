<?php

/**
 * One Click Install
 * @return Import Demos - Needed Import Demo's
 */
function egens_core_import_files()
{
	return array(
		array(
			'import_file_name'           => 'Softro-Import',
			'import_file_url'            => trailingslashit(EGNS_CORE_ROOT_URL) . 'demo-data-import/demo-data/contents.xml',
			'import_widget_file_url'     => trailingslashit(EGNS_CORE_ROOT_URL) . 'demo-data-import/demo-data/widgets.wie',
			'import_customizer_file_url' => trailingslashit(EGNS_CORE_ROOT_URL) . 'demo-data-import/demo-data/customizer.dat',
			'import_notice'              => __('Import process may take 3-5 minutes, please be patient. It\'s really based on your network speed.', 'softro-core'),
			'preview_url'                => '#',
			'import_preview_image_url'   => trailingslashit(EGNS_CORE_ROOT_URL) . 'demo-data-import/images/main.png', // Add your preview image URL here
		)
	);
}
add_filter('ocdi/import_files', 'egens_core_import_files');


// Empty sidebar before import demo data 
function softro_before_demo_import()
{
	$sidebars_widgets = get_option('sidebars_widgets');

	if (isset($sidebars_widgets['blog_sidebar'])) {
		$sidebars_widgets['blog_sidebar'] = array();
		update_option('sidebars_widgets', $sidebars_widgets);
	}
}
add_action('ocdi/before_widgets_import', 'softro_before_demo_import');


function egens_core_after_import_setup()
{

	// Assign menus to their locations.
	$main_menu = get_term_by('name', 'Main Menu', 'nav_menu');

	set_theme_mod(
		'nav_menu_locations',
		array(
			'primary-menu' => $main_menu->term_id,
		)
	);

	// Get the front page.
	$front_page = get_posts(
		[
			'post_type'              => 'page',
			'title'                  => 'Startup',
			'post_status'            => 'all',
			'numberposts'            => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		]
	);

	if (! empty($front_page)) {
		update_option('page_on_front', $front_page[0]->ID);
	}

	// Get the blog page.
	$blog_page = get_posts(
		[
			'post_type'              => 'page',
			'title'                  => 'Blog',
			'post_status'            => 'all',
			'numberposts'            => 1,
			'update_post_term_cache' => false,
			'update_post_meta_cache' => false,
		]
	);

	if (! empty($blog_page)) {
		update_option('page_for_posts', $blog_page[0]->ID);
	}

	if (! empty($blog_page) || ! empty($front_page)) {
		update_option('show_on_front', 'page');
	}

	$options_file = trailingslashit(EGNS_CORE_ROOT_PATH) . 'demo-data-import/demo-data/theme_option.json';
	if (file_exists($options_file) && is_readable($options_file)) {
		$options_data = file_get_contents($options_file);
		$out          = wp_kses_post_deep(json_decode(trim((string) $options_data), true));
		update_option('egns_theme_options', $out);
	}


	// Update Elementor settings 
	Egns_Core\Egns_Helper::return_defaul_elementor_settings('post', 'page', 'case-study', 'portfolio', 'mega-menu', 'header-blocks', 'footer-blocks');

	// Disable Elementor Default Colors
	update_option('elementor_disable_color_schemes', 'yes');

	// Disable Elementor Default Fonts
	update_option('elementor_disable_typography_schemes', 'yes');

	// (Optional) Regenerate CSS so changes fully apply
	if (class_exists('\Elementor\Plugin')) {
		\Elementor\Plugin::$instance->files_manager->clear_cache();
	}

	// Update permalink
	if (isset($GLOBALS['wp_rewrite']) && $GLOBALS['wp_rewrite'] instanceof WP_Rewrite) {
		$wp_rewrite = $GLOBALS['wp_rewrite'];
		$wp_rewrite->set_permalink_structure('/%postname%/');
		update_option('rewrite_rules', false);
		$wp_rewrite->flush_rules(true);
	}
}
add_action('ocdi/after_import', 'egens_core_after_import_setup');





// Model Popup - Width Increased
function egens_core_ocdi_confirmation_dialog_options($options)
{
	return array_merge($options, array(
		'width'       => 600,
		'dialogClass' => 'wp-dialog',
		'resizable'   => false,
		'height'      => 'auto',
		'modal'       => true,
	));
}
add_filter('ocdi/confirmation_dialog_options', 'egens_core_ocdi_confirmation_dialog_options', 10, 1);

// Disable the branding notice - Softro Themes
add_filter('ocdi/disable_pt_branding', '__return_true');

function ocdi_plugin_intro_text($default_text)
{
	$default_text .= '<h1>Import Demo</h1>
    <div class="egens_toolkit_intro-text dl-demo-one-click">
    <div id="poststuff">
      <div class="postbox important-notes">
        <h3><span>Important notes: </span></h3>
        <div class="inside">
          <ol>
            <li>Please note, this import process will take time. So, please be patient.</li>
			<li>Please make sure you\'ve installed recommended plugins before you import this content.</li>
            <li>All images are demo purposes only. So, images may repeat in your site content.</li>
          </ol>
        </div>
      </div>
    </div>
  </div>';

	return $default_text;
}
add_filter('ocdi/plugin_intro_text', 'ocdi_plugin_intro_text');
