<?php
/*-----------------------------------------
	CONTROL CORE CLASSES FOR AVOID ERRORS
------------------------------------------*/

if (class_exists('CSF')) {
    /*-------------------------------------------------------
	   ** Footer  Options
   --------------------------------------------------------*/
    CSF::createSection($prefix, array(
        'id'    => 'footer_options',
        'title' => esc_html__('Footer', 'softro-core'),
        'icon'  => 'fa fa-rss'
    ));
    /*-----------------------------------
		REQUIRE Footer FILES
	------------------------------------*/

    require_once EGNS_CORE_INC . '/theme-options/settings/footer/footer_style.php';
    require_once EGNS_CORE_INC . '/theme-options/settings/footer/footer_options.php';
}
