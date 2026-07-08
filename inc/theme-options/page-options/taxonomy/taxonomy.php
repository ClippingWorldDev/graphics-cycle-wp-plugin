<?php
// Control core classes for avoid errors
if (class_exists('CSF')) {

    $prefix = 'egns_taxonomy_cat';

    // Create taxonomy options
    CSF::createTaxonomyOptions($prefix, array(
        'title'     => 'Text',
        'taxonomy'  => 'category',
        'data_type' => 'serialize',
    ));


    // Create a section
    CSF::createSection($prefix, array(
        'fields' => array(

            array(
                'id'      => 'post_category_logo',
                'type'    => 'media',
                'title'   => esc_html__('Logo', 'softro-core'),
                'desc'    => esc_html__('Upload category logo', 'softro-core'),
                'library' => 'image',
            ),

            array(
                'id'      => 'post_category_thumb',
                'type'    => 'media',
                'title'   => esc_html__('Thumbnail', 'softro-core'),
                'desc'    => esc_html__('Upload category thumbnail', 'softro-core'),
                'library' => 'image',
            ),

        )
    ));
}
