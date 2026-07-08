<?php
/*-------------------------------------------------------
		  ** Portfolio Page  Options
--------------------------------------------------------*/

CSF::createSection($prefix, array(
  'parent' => 'custom_post_type_settings',
  'id'     => 'portfolio_archive_settings',
  'title'  => esc_html__('Portfolio Options', 'softro-core'),
  'icon'   => 'fa fa-folder-open',
  'fields' => array(
    // A Subheading
    array(
      'type'    => 'subheading',
      'content' => esc_html__('Portfolio archive', 'softro-core'),
    ),
    array(
      'id'         => 'breadcrumb_cpt_pt_heading',
      'type'       => 'text',
      'title'      => esc_html__('Breadcrumb Heading', 'softro-core'),
      'default'    => 'Explore Our Work Across Industries and Mediums <h2>Selected Projects (2013-2026)</h2>',
    ),
    array(
      'id'         => 'breadcrumb_cpt_pt_short_desc',
      'type'       => 'textarea',
      'title'      => esc_html__('Breadcrumb Short Description', 'softro-core'),
      'default'    => 'Where imagination meets execution, and concepts turn into powerful experiences.',
      'class'      => 'egns_desc',
    ),
    array(
      'id'      => 'portfolio_posts_per_page',
      'type'    => 'number',
      'title'   => esc_html__('Posts per page', 'softro-core'),
      'default' => 8,
    ),

  ),

));
