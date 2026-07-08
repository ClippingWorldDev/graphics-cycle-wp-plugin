<?php
/*
Plugin Name: Softro Core
Plugin URI: https://www.egenslab.com/
Description: Softro core plugin is a elementor widget based plugin. 
Author: Egens Lab
Author URI: https://www.egenslab.com/
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Version: 1.0.0
Text Domain: softro-core
*/

if (!defined('ABSPATH')) {
    exit;
}

/**
 * The main plugin class
 */
final class Egns_Core
{

    /**
     * Plugin version
     *
     * @var string
     */
    const version = '1.0.0';

    /**
     * Class construcotr
     */
    private function __construct()
    {
        $this->egns_core_define_constants();
        $this->egns_core_require_files();

        register_activation_hook(__FILE__, [$this, 'egns_core_activate']);

        add_action('plugins_loaded', [$this, 'egns_core_init']);
    }

    /**
     * Initializes a singleton instance
     *
     * @return \Egns_Core
     */
    public static function init()
    {
        static $instance = false;

        if (!$instance) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function egns_core_define_constants()
    {
        define('EGNS_CORE_ENV', true);
        define('EGNS_CORE_ROOT_PATH', plugin_dir_path(__FILE__));
        define('EGNS_CORE_ROOT_URL', plugin_dir_url(__FILE__));
        define('EGNS_CORE_VERSION', '1.0.0');
        define('EGNS_CORE_INC', EGNS_CORE_ROOT_PATH . '/inc');
        define('EGNS_CORE_LIB', EGNS_CORE_ROOT_PATH . '/lib');
        define('EGNS_CORE_INC_ASSETS', EGNS_CORE_ROOT_URL . '/inc/assets');
        define('EGNS_CORE_THEME_OPTIONS', EGNS_CORE_INC . '/theme-options');
        define('EGNS_CORE_DEMO_IMPORT', EGNS_CORE_ROOT_PATH . '/demo-data-import');
        define('EGNS_CORE_THEME_OPTIONS_IMAGES', EGNS_CORE_ROOT_URL . '/inc/theme-options/images');

        // Include the theme stylesheets
        define('EGNS_CORE_THEME_CSS', get_template_directory_uri() . '/assets/css');
    }

    /**
     * Include all require files
     *
     * @return void
     */
    public function egns_core_require_files()
    {

        $includes_files = array(

            // Egns Helper
            array(
                'file-name'   => 'functions',
                'folder-name' => EGNS_CORE_ROOT_PATH . '/helpers'
            ),

            // Egns Helper
            array(
                'file-name'   => 'helper',
                'folder-name' => EGNS_CORE_ROOT_PATH . '/helpers'
            ),

            // Codestar Framework
            array(
                'file-name'   => 'codestar-framework',
                'folder-name' => EGNS_CORE_LIB . '/codestar-framework'
            ),

            // Elementor Widgets
            array(
                'file-name'   => 'elementor',
                'folder-name' => EGNS_CORE_INC . '/elementor',
            ),

            // Custom Post Type
            array(
                'file-name'   => 'cpt',
                'folder-name' => EGNS_CORE_INC . '/custom-post',
            ),

            // Theme Options
            array(
                'file-name'   => 'theme-options',
                'folder-name' => EGNS_CORE_INC . '/theme-options',
            ),

            // Menu Options
            array(
                'file-name'   => 'menu',
                'folder-name' => EGNS_CORE_INC . '/theme-options/megamenu',
            ),

            // Custom CSS
            array(
                'file-name'   => 'custom-css',
                'folder-name' => EGNS_CORE_THEME_OPTIONS . '/custom-css',
            ),

            // Custom Widget
            array(
                'file-name'   => 'class-search-widget',
                'folder-name' => EGNS_CORE_INC . '/wp-widget',
            ),
            array(
                'file-name'   => 'class-popular-post-widget',
                'folder-name' => EGNS_CORE_INC . '/wp-widget',
            ),
            array(
                'file-name'   => 'class-category-widget',
                'folder-name' => EGNS_CORE_INC . '/wp-widget',
            ),
            array(
                'file-name'   => 'class-blog-tag-widget',
                'folder-name' => EGNS_CORE_INC . '/wp-widget',
            ),

            // Register Widget
            array(
                'file-name'   => 'widget',
                'folder-name' => EGNS_CORE_INC . '/widget',
            ),

            // Demo Import Data
            array(
                'file-name'   => 'demo-importer',
                'folder-name' => EGNS_CORE_DEMO_IMPORT,
            ),

        );

        if (is_array($includes_files) && !empty($includes_files)) {
            foreach ($includes_files as $file) {
                if (file_exists($file['folder-name'] . '/' . $file['file-name'] . '.php')) {
                    require_once $file['folder-name'] . '/' . $file['file-name'] . '.php';
                }
            }
        }
    }

    /**
     * Initialize the plugin
     *
     * @return void
     */
    public function egns_core_init()
    {
        new Egns_Core\Egns_Elementor();
    }

    /**
     * Do stuff upon plugin activation
     *
     * @return void
     */
    public function egns_core_activate()
    {
        // do something when activate this plugin
    }
}

/**
 * Initializes the main plugin
 *
 * @return \Egns_Core
 */
function egns_core()
{
    return Egns_Core::init();
}

// start doing amazing things
egns_core();
