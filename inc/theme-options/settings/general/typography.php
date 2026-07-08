<?php
CSF::createSection($prefix, array(
    'parent' => 'theme_general_options',
    'title'  => esc_html__('Typography', 'softro-core'),
    'id'     => 'typography_options',
    'icon'   => 'fas fa-pen-nib',
    'fields' => array(
        array(
            'type'    => 'subheading',
            'content' => '<h3>' . esc_html__('Typography ', 'softro-core') . '</h3>',
        ),
        // Start Fonts
        array(
            'id'             => 'font_inter',
            'type'           => 'typography',
            'title'          => esc_html__('Custom Font "Inter"', 'softro-core'),
            'color'          => false,
            'font_size'      => false,
            'text_align'     => false,
            'font_style'     => false,
            'line_height'    => false,
            'letter_spacing' => false,
            'text_transform' => false,
            'preview'        => 'always',
            'desc'           => wp_kses(__("Replace Font <mark>Inter</mark>.", 'softro-core'), wp_kses_allowed_html('post')),
        ),
        array(
            'id'             => 'font_spaceGrotesk',
            'type'           => 'typography',
            'title'          => esc_html__('Custom Font "Space Grotesk"', 'softro-core'),
            'color'          => false,
            'font_size'      => false,
            'text_align'     => false,
            'font_style'     => false,
            'line_height'    => false,
            'letter_spacing' => false,
            'text_transform' => false,
            'preview'        => 'always',
            'desc'           => wp_kses(__("Replace Font <mark>Space Grotesk</mark>.", 'softro-core'), wp_kses_allowed_html('post')),
        ),
        array(
            'id'             => 'font_funnel',
            'type'           => 'typography',
            'title'          => esc_html__('Custom Font "Funnel Display"', 'softro-core'),
            'color'          => false,
            'font_size'      => false,
            'text_align'     => false,
            'font_style'     => false,
            'line_height'    => false,
            'letter_spacing' => false,
            'text_transform' => false,
            'preview'        => 'always',
            'desc'           => wp_kses(__("Replace Font <mark>Funnel Display</mark>.", 'softro-core'), wp_kses_allowed_html('post')),
        ),
        // End Fonts 

    ),
));
