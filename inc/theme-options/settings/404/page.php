<?php
/*-------------------------------------------------------
		   ** 404 page options
--------------------------------------------------------*/

CSF::createSection($prefix, array(
	'id'     => '404_page',
	'title'  => esc_html__('404 Page', 'softro-core'),
	'icon'   => 'fa fa-exclamation-triangle',
	'fields' => array(
		array(
			'type'    => 'subheading',
			'content' => '<h3>' . esc_html__('404 Page Options', 'softro-core') . '</h3>',
		),
		array(
			'id'      => '404_title',
			'title'   => esc_html__('Title', 'softro-core'),
			'type'    => 'text',
			'info'    => wp_kses(__('you can change <mark>404</mark> text of 404 page', 'softro-core'), wp_kses_allowed_html('softro-core')),
			'default' => wp_kses(__("Sorry! Page Not Found.", 'softro-core'), wp_kses_allowed_html('post')),
		),
		array(
			'id'      => '404_button_text',
			'title'   => esc_html__('Button label', 'softro-core'),
			'type'    => 'text',
			'info'    => wp_kses(__('you can change <mark>button text</mark> of 404 page', 'softro-core'), wp_kses_allowed_html('softro-core')),
			'default' => esc_html__('Go Back Home', 'softro-core')
		),
		array(
			'id'      => '404_image',
			'type'    => 'media',
			'title'   => esc_html__('Error Image', 'softro-core'),
			'library' => 'image',
			'default' => array(
				'url'       => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/error/error.png'),
				'id'        => '404_image',
				'thumbnail' => esc_url(EGNS_CORE_THEME_OPTIONS_IMAGES . '/error/error.png'),
				'alt'       => esc_attr('404 image'),
				'title'     => esc_html('404 image'),
			),
		),
		array(
			'id'      => '404_content',
			'title'   => esc_html__('Description', 'softro-core'),
			'type'    => 'textarea',
			'info'    => wp_kses(__('you can change <mark>Content</mark> text of 404 page', 'softro-core'), wp_kses_allowed_html('softro-core')),
			'default' => esc_html__("Oops! The page you’re looking for doesn’t exist or has been moved.", 'softro-core')
		),

	)
));
