<?php
// exit if access directly
if (!defined('ABSPATH')) {
    exit();
}
function egnsCustomStyling()
{

    $custom_css         = "";
    $egns_theme_options = get_option('egns_theme_options');
    $egns_page_options  = get_post_meta(get_the_ID(), 'egns_page_options', true);

    /**************************
     * Primary Color Start
     *************************/

    $primary_main_color = $egns_theme_options['primary_theme_color'] ?? '';
    $primary_opc_color  = $egns_theme_options['primary_theme_color_opc'] ?? '';

    // Get hex color 
    $hex = $primary_opc_color;

    // Remove the '#' if present
    $hex = ltrim($hex, '#');

    // Convert the hex to RGB values
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));


    if (!empty($primary_main_color)) {
        $custom_css .= "
         :root{
                --primary-color1: $primary_main_color !important;
                --primary-color2: $primary_main_color !important;
              }
          ";
    }

    if (!empty($primary_opc_color)) {
        $custom_css .= "
         :root{
                 --primary-color1-opc: $r, $g, $b !important;
                 --primary-color2-opc: $r, $g, $b !important;
              }
          ";
    }

    /**************************
     * Primary Color End
     *************************/




    /**************************
     * Primary Fonts Start
     *************************/

    $font_inter = $egns_theme_options['font_inter']['font-family'] ?? '';
    if (!empty($font_inter)) {
        $custom_css .= "
         :root{
                 --font-inter: '$font_inter', sans-serif !important;
              }
          ";
    }

    $font_spaceGrotesk = $egns_theme_options['font_spaceGrotesk']['font-family'] ?? '';
    if (!empty($font_spaceGrotesk)) {
        $custom_css .= "
         :root{
                 --font-spaceGrotesk: '$font_spaceGrotesk', sans-serif !important;
              }
          ";
    }

    $font_funnel = $egns_theme_options['font_funnel']['font-family'] ?? '';
    if (!empty($font_funnel)) {
        $custom_css .= "
         :root{
                 --font-funnel-display: '$font_funnel', sans-serif !important;
              }
          ";
    }

    /**************************
     * Primary Fonts End
     *************************/



    /************************
     * Start Breadcrumb Style
     ************************/

    //Breadcrumb BG Color
    $breadcump_color_background = $egns_theme_options['breadcrumb_background_color'] ?? '';
    $breadcump_page_color_background   = $egns_page_options['breadcrumb_page_bg_color'] ?? '';

    if (!empty($breadcump_page_color_background)) {
        $custom_css .= "
        .breadcrumb-section {
            background-color: $breadcump_page_color_background !important;
        }
    ";
    } else {
        if (!empty($breadcump_color_background)) {
            $custom_css .= "
            .breadcrumb-section {
                background-color: $breadcump_color_background !important;
            }
        ";
        }
    }

    //Breadcrumb BG Image
    $breadcump_background_image      = $egns_theme_options['breadcrumb_bg_image']['url'] ?? '';
    $breadcump_page_background_image = $egns_page_options['breadcrumb_page_bg_image']['url'] ?? '';

    if (!empty($breadcump_page_background_image)) {
        $custom_css .= "
        .breadcrumb-section {
            background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url($breadcump_page_background_image) !important;
        }
    ";
    } else {
        if (!empty($breadcump_background_image)) {
            $custom_css .= "
            .breadcrumb-section {
                background-image: linear-gradient(180deg, rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url($breadcump_background_image) !important;
            }
        ";
        }
    }

    /*********************
     * End Breadcrumb
     *********************/




    wp_register_style('egns-stylesheet', false);
    wp_enqueue_style('egns-stylesheet', false);
    wp_add_inline_style('egns-stylesheet', $custom_css, true);
}
if (class_exists('CSF')) {
    add_action('wp_enqueue_scripts', 'egnsCustomStyling');
}
