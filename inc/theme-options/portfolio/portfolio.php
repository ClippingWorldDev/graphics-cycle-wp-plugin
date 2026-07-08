<?php
if (class_exists('CSF')) {

  /*-----------------------------------
	    PAGE METABOX SECTION
	------------------------------------*/
  CSF::createMetabox("EGNS_PORTFOLIO_META_ID", array(
    'id'              => 'portfolio_meta_option',
    'title'           => esc_html__('Portfolio Informations', 'softro-core'),
    'post_type'       => 'portfolio',
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

  CSF::createSection("EGNS_PORTFOLIO_META_ID", array(
    'parent' => 'portfolio_meta_option',
    'title'  => esc_html__('General', 'softro-core'),
    'fields' => array(
      array(
        'id'    => 'portfolio_gallery',
        'type'  => 'gallery',
        'title' => esc_html__('Gallery', 'softro-core'),
      ),
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
        'id'     => 'portfolio_info_list',
        'type'   => 'repeater',
        'title'  => 'Portfolio Info List',
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
            'default' => 'Technology / SaaS',
          ),
        ),
        'default'   => array(
          array(
            'info_list_label' => 'Industry',
            'info_list_value' => 'Technology / SaaS',
          ),
          array(
            'info_list_label' => 'Duration',
            'info_list_value' => '6 Months',
          ),
          array(
            'info_list_label' => 'Services',
            'info_list_value' => 'UI/UX Design',
          ),
          array(
            'info_list_label' => 'Client',
            'info_list_value' => 'Mr. Daniel',
          ),
        )
      ),
      array(
        'id'     => 'portfolio_metrics_list',
        'type'   => 'repeater',
        'title'  => 'Metrics List',
        'fields' => array(
          array(
            'id'      => 'metrics_list_label',
            'type'    => 'text',
            'title'   => 'Label',
            'default' => 'Organic Traffic',
          ),
          array(
            'id'      => 'metrics_list_value',
            'type'    => 'text',
            'title'   => 'Value',
            'default' => '333',
          ),
          array(
            'id'      => 'metrics_list_suffix',
            'type'    => 'text',
            'title'   => 'Suffix',
            'default' => '%',
          ),
        ),
        'default'   => array(
          array(
            'metrics_list_label'  => 'Organic Traffic',
            'metrics_list_value'  => '333',
            'metrics_list_suffix' => '%',
          ),
          array(
            'metrics_list_label'  => 'Online Revenue',
            'metrics_list_value'  => '2.5',
            'metrics_list_suffix' => 'M',
          ),
        )
      ),




    )
  ));
}
