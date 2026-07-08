<?php

/*-----------------------------------------
	CONTROL CORE CLASSES FOR AVOID ERRORS
------------------------------------------*/
if (class_exists('CSF')) {

  /*-----------------------------------
	    PAGE METABOX SECTION
	------------------------------------*/
  CSF::createMetabox(
    "EGNS_CAREER_META_ID",
    array(
      'id'              => 'career_meta_option',
      'title'           => esc_html__('Job Informations', 'softro-core'),
      'post_type'       => 'career',
      'context'         => 'normal',
      'priority'        => 'high',
      'show_restore'    => true,
      'enqueue_webfont' => true,
      'async_webfont'   => false,
      'output_css'      => false,
      'nav'             => 'normal',
      'theme'           => 'dark',
    )
  );


  /*-----------------------------------
		REQUIRE META FILES
	------------------------------------*/

  CSF::createSection(
    "EGNS_CAREER_META_ID",
    array(
      'parent' => 'career_meta_option',
      'fields' => array(
        array(
          'type'    => 'subheading',
          'content' => esc_html__('Job Details', 'softro-core'),
        ),
        array(
          'id'       => 'job_posted_date',
          'type'     => 'date',
          'settings' => array(
            'dateFormat' => 'dd M, yy',
          ),
          'title' => esc_html__('Job Posted Date', 'softro-core'),
        ),
        array(
          'id'       => 'job_deadline_date',
          'type'     => 'date',
          'settings' => array(
            'dateFormat' => 'dd M, yy',
          ),
          'title' => esc_html__('Deadline Date', 'softro-core'),
        ),
        array(
          'id'      => 'job_location',
          'type'    => 'radio',
          'inline'  => true,
          'title'   => esc_html__('Location', 'softro-core'),
          'options' => array(
            'Onsite' => 'Onsite',
            'Remote' => 'Remote',
            'Hybrid' => 'Hybrid',
          ),
          'default' => 'Onsite'
        ),
        array(
          'id'      => 'job_type',
          'type'    => 'checkbox',
          'inline'  => true,
          'title'   => esc_html__('Job Types', 'softro-core'),
          'options' => array(
            'Full-time'  => 'Full-time',
            'Part-time'  => 'Part-time',
            'Contract'   => 'Contract',
            'Internship' => 'Internship',
            'Seasonal'   => 'Seasonal',
          ),
          'default' => 'Full-time'
        ),
        array(
          'id'      => 'job_experience',
          'type'    => 'text',
          'title'   => esc_html__('Experience', 'softro-core'),
          'default' => '1-3 Years',
        ),

        array(
          'id'      => 'job_vacancy',
          'type'    => 'text',
          'title'   => esc_html__('Vacancy', 'softro-core'),
          'default' => '02',
        ),

        array(
          'id'      => 'job_salary',
          'type'    => 'text',
          'title'   => esc_html__('Salary Range', 'softro-core'),
          'default' => '$90K - $170K',
        ),
        array(
          'id'      => 'apply_now_lbl',
          'type'    => 'text',
          'title'   => esc_html__('Apply Button label', 'softro-core'),
          'default' => 'Apply Now',
        ),
        array(
          'id'      => 'job_lists_btn',
          'type'    => 'link',
          'title'   => esc_html__('Job Button label & URL', 'softro-core'),
          'default' => array(
            'url'    => '/careers',
            'text'   => 'View all opening job post.',
            'target' => '_blank'
          ),
        ),
        array(
          'type'    => 'subheading',
          'content' => esc_html__('Popup From Content', 'softro-core'),
        ),
        array(
          'id'      => 'career_apply_form_shortcode',
          'type'    => 'text',
          'title'   => esc_html__('Form Shortcode', 'softro-core'),
          'default' => '[contact-form-7 title="Softro Career Form"]',
        ),

      )
    )
  );
}
