<?php

CSF::createSection($prefix, array(
    'parent' => 'header_options',
    'title'  => esc_html__('Header Options', 'softro-core'),
    'id'     => 'theme_header_style_options',
    'icon'   => 'fab fa-algolia',
    'fields' => array(
        array(
            'type'    => 'subheading',
            'content' => '<h3>' . esc_html__('Look Header Layout', 'softro-core') . '</h3>'
        ),
        array(
            'id'      => 'header_menu_style',
            'type'    => 'image_select',
            'class'   => 'egns_header_select',
            'options' => array(
                'header_one'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-one.png'),
                'header_two'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-two.png'),
                'header_three' => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-three.png'),
                'header_four'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-four.png'),
                'header_five'  => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-five.png'),
                'header_six'   => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/header/header-six.png'),
            ),
            'default' => 'header_six',
        ),
        // *************** Header content ***************
        array(
            'type'    => 'subheading',
            'content' => '<h3>' . esc_html__('Header Template', 'softro-core') . '</h3>'
        ),
        array(
            'id'          => 'header_one_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-one',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_one')
        ),
        array(
            'id'          => 'header_two_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-two',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_two')
        ),
        array(
            'id'          => 'header_three_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-three',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_three')
        ),
        array(
            'id'          => 'header_four_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-four',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_four')
        ),
        array(
            'id'          => 'header_five_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-five',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_five')
        ),
        array(
            'id'          => 'header_six_template',
            'type'        => 'select',
            'title'       => esc_html__('Select Header', 'softro-core'),
            'chosen'      => true,
            'placeholder' => esc_html__('select a header', 'softro-core'),
            'options'     => \Egns_Core\Egns_Helper::get_custom_template_list('header-blocks'),
            'default'     => 'header-six',
            'desc'        => wp_kses_post('You must select a <mark>Header</mark> for this layout. You can create a header here ( <a href="' . home_url() . '/wp-admin/edit.php?post_type=header-blocks">Header Template</a> )'),
            'dependency'  => array('header_menu_style', '==', 'header_six')
        ),

        // *************** End Header three content ***************

    )
));
