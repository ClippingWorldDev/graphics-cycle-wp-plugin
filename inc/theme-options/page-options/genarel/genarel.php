<?php
/*-----------------------------------
PAGE MENU SECTION
------------------------------------*/
CSF::createSection(
    $prefix,
    array(
        'title'  => esc_html__('Header', 'softro-core'),
        'parent' => 'page_meta_option',
        'fields' => array(
            //Page Header Options
            array(
                'type'    => 'subheading',
                'content' => esc_html__('Header Options', 'softro-core'),
            ),
            array(
                'id'      => 'page_main_header_enable',
                'type'    => 'select',
                'title'   => esc_html__('Main Header', 'softro-core'),
                'desc'    => wp_kses(__('you can enable/disable <mark>Main Header </mark> for header section', 'softro-core'), wp_kses_allowed_html('post')),
                'options' => array(
                    'enable'  => esc_html('Enable'),
                    'disable' => esc_html('Disable'),
                ),
                'default' => 1
            ),
            array(
                'id'      => 'page_header_menu_style',
                'title'   => esc_html__('Select Style', 'softro-core'),
                'type'    => 'image_select',
                'class'   => 'egns_header_select',
                'options' => array(
                    'default'      => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/default.png'),
                    'header_one'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-one.png'),
                    'header_two'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-two.png'),
                    'header_three' => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-three.png'),
                    'header_four'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-four.png'),
                    'header_five'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-five.png'),
                    'header_six'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-six.png'),
                ),
                'desc'       => wp_kses(__('you can select <mark>Header Style </mark> for header section', 'softro-core'), wp_kses_allowed_html('post')),
                'default'    => 'default',
                'dependency' => array('page_main_header_enable', '==', 'enable'),
            ),
        ),
    )
);

// Footer Options

CSF::createSection(
    $prefix,
    array(
        'title'  => esc_html__('Footer', 'softro-core'),
        'parent' => 'page_meta_option',
        'fields' => array(
            //Page Footer Options
            array(
                'type'    => 'subheading',
                'content' => esc_html__('Footer Options', 'softro-core'),
            ),

            array(
                'id'      => 'page_main_footer_enable',
                'type'    => 'select',
                'title'   => esc_html__('Main footer', 'softro-core'),
                'desc'    => wp_kses(__('You can enable/disable <mark>Main Footer </mark> for this page', 'softro-core'), wp_kses_allowed_html('post')),
                'options' => array(
                    'enable'  => esc_html('Enable'),
                    'disable' => esc_html('Disable'),
                ),
                'default' => 1
            ),
            array(
                'id'      => 'page_footer_layout',
                'title'   => esc_html__('Select Style', 'softro-core'),
                'type'    => 'image_select',
                'class'   => 'egns_header_select',
                'options' => array(
                    'default'      => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/default.png'),
                    'footer_one'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/footer/footer-one.png'),
                    'footer_two'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/footer/footer-two.png'),
                    'footer_three' => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/footer/footer-three.png'),
                    'footer_four'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/footer/footer-four.png'),
                    'footer_five'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/footer/footer-five.png'),
                ),
                'desc'       => wp_kses(__('You can select <mark>Footer Style </mark> for this page', 'softro-core'), wp_kses_allowed_html('post')),
                'default'    => 'default',
                'dependency' => array('page_main_footer_enable', '==', 'enable'),
            ),

        ),
    )
);
