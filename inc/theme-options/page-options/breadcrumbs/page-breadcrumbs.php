<?php
/*-----------------------------------
    PAGE BARNER SECTION
------------------------------------*/

CSF::createSection(
	$prefix,
	array(
		'title'  => esc_html__('Breadcrumb', 'softro-core'),
		'parent' => 'page_meta_option',
		'fields' => array(
			array(
				'type'    => 'subheading',
				'content' => esc_html__('Breadcrumb Options', 'softro-core'),
			),
			array(
				'id'      => 'breadcrumb_enable_page',
				'type'    => 'switcher',
				'title'   => esc_html__('Enable Breadcrumb', 'softro-core'),
				'desc'    => esc_html__('If you want to show or hide page banner you can toggle ( ON / OFF ).', 'softro-core'),
				'default' => true,
			),
			array(
				'id'         => 'breadcrumb_page_heading',
				'type'       => 'text',
				'title'      => esc_html__('Heading', 'softro-core'),
				'dependency' => array('breadcrumb_enable_page', '==', 'true'),
			),
			array(
				'id'         => 'breadcrumb_page_short_desc',
				'type'       => 'textarea',
				'title'      => esc_html__('Short Description', 'softro-core'),
				'class'      => 'egns_desc',
				'dependency' => array('breadcrumb_enable_page', '==', 'true'),
			),
			array(
				'id'         => 'breadcrumb_page_bg_color',
				'type'       => 'color',
				'title'      => esc_html__('Background Color', 'softro-core'),
				'dependency' => array('breadcrumb_enable_page', '==', 'true'),
			),
			array(
				'id'         => 'breadcrumb_page_bg_image',
				'type'       => 'media',
				'title'      => esc_html__('Breadcrumb Background Image', 'softro-core'),
				'desc'       => esc_html__('Set the banner background image', 'softro-core'),
				'dependency' => array('breadcrumb_enable_page', '==', 'true'),
			),
		)
	)
);
