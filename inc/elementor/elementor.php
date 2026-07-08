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

if (!class_exists('Egns_Elementor')) {

	class Egns_Elementor
	{

		/*
		* $instance
		* @since 1.0.0
		* */
		private static $instance;



		/*
		* construct()
		* @since 1.0.0
		* */
		public function __construct()
		{
			//elementor widget category registered
			add_action('elementor/elements/categories_registered', array($this, '_widget_categories'));

			//elementor widget registered
			add_action('elementor/widgets/register', array($this, '_widget_registered'));

			// Enqueue stylesheets in editor page and frontend
			add_action('elementor/editor/before_enqueue_styles', array($this, 'softro_enqueue_style'));
			add_action('elementor/frontend/before_enqueue_styles', array($this, 'softro_enqueue_style'));
			add_action('elementor/editor/after_enqueue_scripts', array($this, 'softro_enqueue_editor_script'));
			add_action('elementor/preview/enqueue_scripts', array($this, 'softro_enqueue_preview_script'));

			//add custom icons to elementor new controls
			add_filter('elementor/icons_manager/additional_tabs', array($this, 'add_custom_icon_to_elementor_icons'));
		}
		/*
	   * getInstance()
	   * @since 1.0.0
	   * */
		public static function getInstance()
		{
			if (null == self::$instance) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		// Custom widgets css 
		public function softro_enqueue_style()
		{
			wp_enqueue_style('wp-blocks-library', includes_url('css/dist/block-library/style.min.css'));
			wp_enqueue_style('softro-widgets', EGNS_CORE_THEME_CSS . '/el-widgets.css', null, filemtime(get_template_directory() . '/assets/css/el-widgets.css'));
		}

		// Custom widgets editor scripts
		public function softro_enqueue_editor_script()
		{
			$this->softro_register_preview_dependencies();

			$editor_deps = array('jquery');
			if (wp_script_is('elementor-editor', 'registered') || wp_script_is('elementor-editor', 'enqueued')) {
				$editor_deps[] = 'elementor-editor';
			}

			$team_tabs_script_path = EGNS_CORE_ROOT_PATH . '/inc/assets/js/elementor-team-style-three-tabs.js';
			$team_tabs_script_ver  = file_exists($team_tabs_script_path) ? filemtime($team_tabs_script_path) : EGNS_CORE_VERSION;
			wp_enqueue_script(
				'softro-elementor-team-style-three-tabs',
				EGNS_CORE_INC_ASSETS . '/js/elementor-team-style-three-tabs.js',
				$editor_deps,
				$team_tabs_script_ver,
				true
			);

			$faq_tabs_script_path = EGNS_CORE_ROOT_PATH . '/inc/assets/js/elementor-faq-tabs.js';
			$faq_tabs_script_ver  = file_exists($faq_tabs_script_path) ? filemtime($faq_tabs_script_path) : EGNS_CORE_VERSION;
			wp_enqueue_script(
				'softro-elementor-faq-tabs',
				EGNS_CORE_INC_ASSETS . '/js/elementor-faq-tabs.js',
				$editor_deps,
				$faq_tabs_script_ver,
				true
			);

			$this->enqueue_preview_script();
		}

		public function softro_enqueue_preview_script()
		{
			$this->softro_register_preview_dependencies();

			$this->enqueue_preview_script();
		}

		private function enqueue_preview_script()
		{
			$preview_script_path = EGNS_CORE_ROOT_PATH . '/inc/assets/js/elementor-preview.js';
			$preview_script_ver  = file_exists($preview_script_path) ? filemtime($preview_script_path) : EGNS_CORE_VERSION;
			$preview_script_deps = array('jquery', 'swiper');

			if (wp_script_is('elementor-frontend', 'registered') || wp_script_is('elementor-frontend', 'enqueued')) {
				$preview_script_deps[] = 'elementor-frontend';
			}

			wp_enqueue_script(
				'softro-elementor-preview',
				EGNS_CORE_INC_ASSETS . '/js/elementor-preview.js',
				$preview_script_deps,
				$preview_script_ver,
				true
			);
		}

		private function softro_register_preview_dependencies()
		{
			if (!wp_script_is('swiper', 'registered') && !wp_script_is('swiper', 'enqueued')) {
				$swiper_script_path = get_template_directory() . '/assets/js/swiper-bundle.min.js';
				$swiper_script_ver  = file_exists($swiper_script_path) ? filemtime($swiper_script_path) : EGNS_CORE_VERSION;

				wp_register_script(
					'swiper',
					get_template_directory_uri() . '/assets/js/swiper-bundle.min.js',
					array(),
					$swiper_script_ver,
					true
				);
			}
		}

		/**
		 * _widget_categories()
		 * @since 1.0.0
		 * */
		public function _widget_categories($elements_manager)
		{
			$elements_manager->add_category(
				'softro_widgets',
				[
					'title' => esc_html__('Softro Widgets', 'softro-core'),
					'icon'  => 'fa fa-plug',
				]
			);
		}


		/**
		 * _widget_registered()
		 * @since 1.0.0
		 * */
		public function _widget_registered()
		{

			if (!class_exists('Elementor\Widget_Base')) {
				return;
			}

			$elementor_widgets = array(
				//Elementor Widgets
				'hero-banner-one',
				'counter',
				'about',
				'service-one',
				'quote-banner',
				'industries',
				'delivery-speed',
				'faq',
				'testimonial',
				'header-two',
				'blog-two',
				'hero-banner-two',
				'service-two',
				'process-one',
				'choose-us-one',
				'portfolio-gallary',
				'price-calculator',
				'quote-section',
				'cta-one',
				'hero-banner-three',
				'hero-banner-four',
				'hero-banner-five',
				'about-web',
				'service-web',
				'3d-stack',
				'tech-stack',
				'partner',
				'service-three',
				'marketing-info',
				'marketing-service-one',
				'ai-marketing-result',
				'cta-two',
				'process-two',
				'video-3d-challange',
				'process-three', 
				'cta-three',
				'cta-four',
				'hero-banner-six',
				'about-photo-retouching',
				'photo-service',
				'removal-technique',
				'why-choose-with-video',
				'ppc-hero-banner',
				'ppc-pain-challange',
				'ppc-quote-banner',
				'search-hero-banner',
				'search-service',
				'counter-two',
				'search-service-two',
				'search-experience-section',
				'about-social-media',
				'project-section',
				'email-hero-banner',
				'email-why-choose',
				'va-animation-hero',
				'va-what-we-do',
				'va-why-choose',
				'what-we-animate',
				'va-process',
				'what-we-receive',
				'3d-anim-stack',
				'title-hero-banner',
				'3d-hero-banner',

				//footer
				'footer-one',
			
			);

			$elementor_widgets = apply_filters('softro_widgets', $elementor_widgets);

			if (is_array($elementor_widgets) && !empty($elementor_widgets)) {

				foreach ($elementor_widgets as $widget) {

					if (file_exists(EGNS_CORE_INC . '/elementor/widgets/class-' . $widget . '-elementor-widget.php')) {
						require_once EGNS_CORE_INC . '/elementor/widgets/class-' . $widget . '-elementor-widget.php';
					}
				}
			}
		} // End _widget_registered


		/**
		 * elementor custom icons library
		 * @since 1.0.0
		 * */
		public function add_custom_icon_to_elementor_icons($icons)
		{

			$icons['bootstrap'] = [
				'name'          => 'bootstrap',
				'label'         => esc_html__('Bootstrap Icons', 'softro-core'),
				'url'           => EGNS_CORE_INC_ASSETS . '/css/bootstrap-icons.css',
				'enqueue'       => [EGNS_CORE_INC_ASSETS . '/css/bootstrap-icons.css'],
				'prefix'        => 'bi-',
				'displayPrefix' => 'bi',
				'labelIcon'     => 'bi bi-bootstrap-fill',
				'ver'           => '1.11.3',
				'fetchJson'     => EGNS_CORE_INC_ASSETS . '/js/bootstrap-icons.json',
				'native'        => true,
			];

			$icons['boxicons-regular'] = [
				'name'          => 'boxicons-regular',
				'label'         => esc_html__('Boxicons-Regular', 'softro-core'),
				'url'           => EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css',
				'enqueue'       => [EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css'],
				'prefix'        => 'bx-',
				'displayPrefix' => 'bx',
				'labelIcon'     => 'bi bi-box-seam-fill',
				'ver'           => '2.1.4',
				'fetchJson'     => EGNS_CORE_INC_ASSETS . '/js/boxicons.json',
				'native'        => true,
			];
			$icons['boxicons-solid'] = [
				'name'          => 'boxicons-solid',
				'label'         => esc_html__('Boxicons-Solid', 'softro-core'),
				'url'           => EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css',
				'enqueue'       => [EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css'],
				'prefix'        => 'bxs-',
				'displayPrefix' => 'bx',
				'labelIcon'     => 'bi bi-box-seam-fill',
				'ver'           => '2.1.4',
				'fetchJson'     => EGNS_CORE_INC_ASSETS . '/js/boxicons-bxs.json',
				'native'        => true,
			];
			$icons['boxicons-logos'] = [
				'name'          => 'boxicons-logos',
				'label'         => esc_html__('Boxicons-Logos', 'softro-core'),
				'url'           => EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css',
				'enqueue'       => [EGNS_CORE_INC_ASSETS . '/css/boxicons.min.css'],
				'prefix'        => 'bxl-',
				'displayPrefix' => 'bx',
				'labelIcon'     => 'bi bi-box-seam-fill',
				'ver'           => '2.1.4',
				'fetchJson'     => EGNS_CORE_INC_ASSETS . '/js/boxicons-bxl.json',
				'native'        => true,
			];

			return $icons;
		}
		// end custom icons 



	}
	if (class_exists('Egns_Elementor')) {
		Egns_Elementor::getInstance();
	}
} //end if
