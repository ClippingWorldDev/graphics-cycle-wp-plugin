<?php
CSF::createSection($prefix, array(
    'parent' => 'theme_general_options',
    'title'  => esc_html__('Colors', 'softro-core'),
    'id'     => 'color_scheme_options',
    'icon'   => 'fas fa-paint-brush fa-fw',
    'fields' => array(
        array(
            'type'    => 'subheading',
            'content' => '<h3>' . esc_html__('Colors ', 'softro-core') . '</h3>',
        ),
        array(
            'id'      => 'primary_theme_color',
            'type'    => 'color',
            'title'   => esc_html__('Primary Color', 'softro-core'),
            'desc'    => wp_kses(__("Pick a primary color to define your website’s look and feel.", 'softro-core'), wp_kses_allowed_html('post')),
        ),
        array(
            'id'      => 'primary_theme_color_opc',
            'type'    => 'color',
            'title'   => esc_html__('RGB Primary Color', 'softro-core'),
            'desc'    => wp_kses(__("Pick a solid color — <mark>RGB</mark> conversion is applied automatically.", 'softro-core'), wp_kses_allowed_html('post')),
        ),
    ),
));
