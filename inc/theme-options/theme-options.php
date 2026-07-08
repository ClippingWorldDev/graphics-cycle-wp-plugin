<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('CSF')) {
    return;
}

// Control core classes for avoid errors

add_action('csf_loaded', function () {

    // Set a unique slug-like ID
    $prefix = 'egns_theme_options';

    // Options Informations
    $info       = wp_get_theme();
    $name       = $info->get('Name');
    $version    = $info->get('Version');
    /* translators: %s: get version */
    $version    = '<small>' . sprintf(__('- Version %s', 'softro-core') . '</small>', $version);
    $author     = $info->get('Author');
    $author_uri = $info->get('AuthorURI');
    $author_uri = '<small>' . esc_html__('by', 'softro-core') . ' <a target="_blank" href="' . esc_url($author_uri) . '">' . esc_html($author) . '</a></small>';
    $theme_uri  = $info->get('ThemeURI');

    // Create options
    CSF::createOptions($prefix, array(

        /*--------------------------
            FRAMEWORK TITLE
            ---------------------------*/
        /* translators: 1: get name 2: get version 3: author url */
        'framework_title' => sprintf(__('%1$s option panel %2$s %3$s', 'softro-core'), $name, $version, $author_uri),
        'framework_class' => 'softro-core',

        /*--------------------------
            MENU SETTINGS
            ---------------------------*/
        'menu_title'      => esc_html__('Softro Options', 'softro-core'),
        'menu_slug'       => 'theme_options',
        'menu_type'       => 'menu',
        'menu_capability' => 'manage_options',
        'menu_position'   => 60,
        'menu_icon'       => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/themeLogo.svg'),
        'menu_hidden'     => false,

        /*--------------------------
            FOOTER
            ---------------------------*/
        /* translators: %s: author url */
        'footer_credit'  => sprintf(__('Credited %s', 'softro-core'), $author_uri),
        /* translators: %s: author url */
        'footer_text'    => sprintf(__('Made with love %s', 'softro-core'), $author_uri),
        'footer_after'   => '',
        'transient_time' => 0,

        /*--------------------------
            TYPOGRAPHY OPTIONS
            ---------------------------*/
        'enqueue_webfont' => true,
        'async_webfont'   => true,

        /*--------------------------
            OTHERS
            ---------------------------*/
        'output_css' => true,
        'theme'      => 'light',
    ));

    // All Options
    $includes_files = array(

        // Settings
        array(
            'file-name'   => 'settings',
            'folder-name' => EGNS_CORE_INC . '/theme-options/settings/'
        ),

        // Page Options
        array(
            'file-name'   => 'page-settings',
            'folder-name' => EGNS_CORE_INC . '/theme-options/page-options/'
        ),

        // Career 
        array(
            'file-name'   => 'career',
            'folder-name' => EGNS_CORE_INC . '/theme-options/career/'
        ),

        // Portfolio 
        array(
            'file-name'   => 'portfolio',
            'folder-name' => EGNS_CORE_INC . '/theme-options/portfolio/'
        ),
        // Case Study 
        array(
            'file-name'   => 'case-study',
            'folder-name' => EGNS_CORE_INC . '/theme-options/case-study/'
        ),

    );

    if (is_array($includes_files) && !empty($includes_files)) {
        foreach ($includes_files as $file) {
            if (file_exists($file['folder-name'] . '/' . $file['file-name'] . '.php')) {
                require_once $file['folder-name'] . '/' . $file['file-name'] . '.php';
            }
        }
    }
});
