<?php
if (class_exists('CSF')) {

  /*-----------------------------------
	    PAGE METABOX SECTION
	------------------------------------*/
  CSF::createMetabox("EGNS_CASESTUDY_META_ID", array(
    'id'              => 'casestudy_meta_option',
    'title'           => esc_html__('Case Study Informations', 'softro-core'),
    'post_type'       => 'case-study',
    'context'         => 'normal',
    'priority'        => 'high',
    'show_restore'    => true,
    'enqueue_webfont' => true,
    'async_webfont'   => false,
    'output_css'      => false,
    'nav'             => 'normal',
    'theme'           => 'dark',
  ));


  /*-----------------------------------
		REQUIRE META FILES
	------------------------------------*/

  CSF::createSection("EGNS_CASESTUDY_META_ID", array(
    'parent' => 'casestudy_meta_option',
    'title'  => esc_html__('General', 'softro-core'),
    'fields' => array(
      // array(
      //   'id'    => 'portfolio_thumb_gallery',
      //   'type'  => 'gallery',
      //   'title' => esc_html__('Gallery', 'softro-core'),
      // ),
      // array(
      //   'id'      => 'portfolio_feature_video_source',
      //   'type'    => 'radio',
      //   'title'   => esc_html__('Featured Video Source', 'softro-core'),
      //   'options' => array(
      //     'url'    => 'URL',
      //     'upload' => 'Upload',
      //   ),
      //   'inline'  => true,
      //   'default' => 'url',
      // ),
      // array(
      //   'id'         => 'portfolio_feature_video_uplaod',
      //   'type'       => 'media',
      //   'title'      => esc_html__('Upload Video', 'softro-core'),
      //   'library'    => 'video',
      //   'dependency' => array('portfolio_feature_video_source', '==', 'upload')
      // ),
      // array(
      //   'id'          => 'portfolio_feature_video_link',
      //   'type'        => 'text',
      //   'title'       => esc_html__('Video Link', 'softro-core'),
      //   'placeholder' => 'http://www.example.com/uploads/home1-banner-video.mp4',
      //   'dependency'  => array('portfolio_feature_video_source', '==', 'url')
      // ),

      array(
        'id'     => 'case_information_list',
        'type'   => 'repeater',
        'title'  => 'Information List',
        'fields' => array(
          array(
            'id'      => 'info_list_label',
            'type'    => 'text',
            'title'   => 'Label',
            'default' => 'Industry',
          ),
          array(
            'id'      => 'info_list_value',
            'type'    => 'text',
            'title'   => 'Value',
            'default' => 'SaaS / B2B Software',
          ),
        ),
        'default'   => array(
          array(
            'info_list_label' => 'Client',
            'info_list_value' => 'Mr. Daniel',
          ),
          array(
            'info_list_label' => 'Industry',
            'info_list_value' => 'SaaS / B2B Software',
          ),
        )
      ),
      array(
        'id'     => 'case_study_metrics_list',
        'type'   => 'repeater',
        'title'  => 'Metrics List',
        'fields' => array(
          array(
            'id'      => 'case_study_metrics_list_label',
            'type'    => 'text',
            'title'   => 'Label',
            'default' => 'Revenue Boost',
          ),
          array(
            'id'      => 'case_study_metrics_list_value',
            'type'    => 'text',
            'title'   => 'Value',
            'default' => '27',
          ),
          array(
            'id'      => 'case_study_metrics_list_suffix',
            'type'    => 'text',
            'title'   => 'Suffix',
            'default' => '%',
          ),
        ),
        'default'   => array(
          array(
            'case_study_metrics_list_label' => 'Revenue Boost',
            'case_study_metrics_list_value' => '27',
            'case_study_metrics_list_suffix' => '%',
          ),
          array(
            'case_study_metrics_list_label' => 'Months Upgrade',
            'case_study_metrics_list_value' => '2.5',
            'case_study_metrics_list_suffix' => 'M',
          ),
        )
      ),
    )
  ));
}
