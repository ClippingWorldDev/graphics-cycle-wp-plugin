<?php
/*-------------------------------------------------------
		  ** Breadcrumbs Options
--------------------------------------------------------*/

CSF::createSection($prefix, array(
	'title'  => esc_html__('Breadcrumb', 'softro-core'),
	'id'     => 'breadcrumb_options',
	'icon'   => 'fa fa-sliders',
	'fields' => array(
		array(
			'type'    => 'subheading',
			'content' => '<h3>' . esc_html__('Breadcrumb Options', 'softro-core') . '</h3>'
		),
		array(
			'id'      => 'breadcrumb_enable',
			'title'   => esc_html__('Enable Breadcrumb', 'softro-core'),
			'type'    => 'switcher',
			'desc'    => wp_kses(__('You can turn <mark>ON/OFF</mark> to show, hide breadcrumb globally', 'softro-core'), wp_kses_allowed_html('post')),
			'default' => true,
		),
		array(
			'id'         => 'breadcrumb_heading',
			'type'       => 'text',
			'title'      => esc_html__('Heading', 'softro-core'),
			'default'    => 'Fresh Ideas, Trends, and Industry Stories',
			'dependency' => array('breadcrumb_enable', '==', 'true'),
		),
		array(
			'id'         => 'breadcrumb_short_desc',
			'type'       => 'textarea',
			'title'      => esc_html__('Short Description', 'softro-core'),
			'default'    => 'Explore the latest strategies, innovations, and agency thought leadership.',
			'class'      => 'egns_desc',
			'dependency' => array('breadcrumb_enable', '==', 'true'),
		),
		array(
			'id'         => 'breadcrumb_background_color',
			'type'       => 'color',
			'title'      => 'Background Color',
			'desc'       => esc_html__('set the banner background color', 'softro-core'),
			'dependency' => array('breadcrumb_enable', '==', 'true'),
		),
		array(
			'id'         => 'breadcrumb_bg_image',
			'type'       => 'media',
			'title'      => esc_html__('Background Image', 'softro-core'),
			'desc'       => esc_html__('set the banner background image', 'softro-core'),
			'dependency' => array('breadcrumb_enable', '==', 'true'),
		),
	)
));
