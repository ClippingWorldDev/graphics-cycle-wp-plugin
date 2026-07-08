<?php
CSF::createSection($prefix, array(
    'parent' => 'theme_general_options',
    'title'  => esc_html__('Basic', 'softro-core'),
    'id'     => 'basic_options',
    'icon'   => 'fab fa-pied-piper-alt fa-fw',
    'fields' => array(
        array(
            'type'    => 'subheading',
            'content' => '<h3>' . esc_html__('Basic ', 'softro-core') . '</h3>',
        ),
        array(
            'id'      => 'scrolltop_enable',
            'title'   => esc_html__('Scroll Top', 'softro-core'),
            'type'    => 'switcher',
            'desc'    => wp_kses(__('You can set <mark>ON/OFF</mark> to scroll top button', 'softro-core'), wp_kses_allowed_html('post')),
            'default' => true,
        ),
        array(
            'id'      => 'header_sticky_enable',
            'title'   => esc_html__('Sticky Header', 'softro-core'),
            'type'    => 'switcher',
            'desc'    => wp_kses(__('You can set <mark>ON/OFF</mark> to sticky Header One & Four', 'softro-core'), wp_kses_allowed_html('post')),
            'default' => true,
        ),

        array(
            'id'      => 'dark_light_switch_enable',
            'title'   => esc_html__('Dark-Light Switch', 'softro-core'),
            'type'    => 'switcher',
            'desc'    => wp_kses(__('You can set <mark>ON/OFF</mark> to Dark-Light Switcher', 'softro-core'), wp_kses_allowed_html('post')),
            'default' => false,
        ),

        array(
            'id'      => 'dark_mode_enable',
            'type'    => 'select',
            'title'   => esc_html__('Dark Mode', 'softro-core'),
            'options' => array(
                'light' => 'Default',
                'dark'  => 'Dark Mode',
            ),
            'default' => 'light',
            'desc'    => esc_html__('Activate dark mode entire website ?', 'softro-core'),
        ),


    ),

));
