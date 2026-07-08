<?php
/*-------------------------------------------------------
		  ** Portfolio Page  Options
--------------------------------------------------------*/

CSF::createSection($prefix, array(
  'parent' => 'custom_post_type_settings',
  'id'     => 'case_study_archive_settings',
  'title'  => esc_html__('Case Study Options', 'softro-core'),
  'icon'   => 'fa fa-folder-open',
  'fields' => array(
    // A Subheading
    array(
      'type'    => 'subheading',
      'content' => esc_html__('Case Study archive', 'softro-core'),
    ),
    array(
      'id'         => 'breadcrumb_cpt_case_heading',
      'type'       => 'text',
      'title'      => esc_html__('Breadcrumb Heading', 'softro-core'),
      'default'    => 'Solutions That Drive Real Business Growth',
    ),
    array(
      'id'         => 'breadcrumb_cpt_case_short_desc',
      'type'       => 'textarea',
      'title'      => esc_html__('Breadcrumb Short Description', 'softro-core'),
      'default'    => 'Explore how our strategies delivered measurable growth, improved customer experiences, and helped businesses scale.',
      'class'      => 'egns_desc',
    ),
    array(
      'id'      => 'case_study_posts_per_page',
      'type'    => 'number',
      'title'   => esc_html__('Posts per page', 'softro-core'),
      'default' => 9,
    ),

  ),

));
