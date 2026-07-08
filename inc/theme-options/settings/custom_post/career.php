<?php
/*-------------------------------------------------------
		  ** Project Page  Options
--------------------------------------------------------*/

CSF::createSection($prefix, array(
  'parent' => 'custom_post_type_settings',
  'id'     => 'career_archive_settings',
  'title'  => esc_html__('Career Options', 'softro-core'),
  'icon'   => 'fa fa-briefcase',
  'fields' => array(
    // A Subheading
    array(
      'type'    => 'subheading',
      'content' => esc_html__('Career archive', 'softro-core'),
    ),
    array(
      'id'         => 'breadcrumb_cpt_cr_heading',
      'type'       => 'text',
      'title'      => esc_html__('Breadcrumb Heading', 'softro-core'),
      'default'    => 'Shape Your Success With Our Team',
    ),
    array(
      'id'         => 'breadcrumb_cpt_cr_short_desc',
      'type'       => 'textarea',
      'title'      => esc_html__('Breadcrumb Short Description', 'softro-core'),
      'default'    => 'We’re always looking for passionate, creative, and driven individuals to join our team and make an impact.',
      'class'      => 'egns_desc',
    ),
    array(
      'id'      => 'career_posts_per_page',
      'type'    => 'number',
      'title'   => esc_html__('Posts per page', 'softro-core'),
      'default' => 9,
    ),

  ),



));
