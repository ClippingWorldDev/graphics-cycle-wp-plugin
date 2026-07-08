<?php
/*-----------------------------------
		Custom scripts Options
------------------------------------*/

CSF::createSection($prefix, array(
    'parent' => 'custom_scripts',
    'title'  => esc_html__('Custom Code', 'softro-core'),
    'id'     => 'custom_css',
    'icon'   => 'fa fa-file-code-o',
    'fields' => array(

        array(
            'type'    => 'subheading',
            'content' => esc_html__('Custom CSS ( All Device )', 'softro-core'),
        ),
        array(
            'id'       => 'custom_css',
            'type'     => 'code_editor',
            'desc'     => esc_html__('Write custom css here with css selector. this css will be applied in all pages and post.', 'softro-core'),
            'settings' => array(
                'mode'        => 'css',
                'theme'       => 'dracula',
                'tabSize'     => 4,
                'smartIndent' => true,
                'autocorrect' => true,
            ),
        ),
        array(
            'type'    => 'subheading',
            'content' => esc_html__('Custom Javascript', 'softro-core'),
        ),
        array(
            'id'       => 'custom_javascript',
            'type'     => 'code_editor',
            'settings' => array(
                'theme'  => 'monokai',
                'mode'   => 'javascript',
            ),
            'desc'     => esc_html__('Write custom Javascript here with selector. this css will be applied in all pages and post.', 'softro-core'),
        ),


    ),

));
