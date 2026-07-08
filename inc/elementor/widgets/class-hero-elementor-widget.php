<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

use Elementor\core\Schemes;
use Egns_Core\Egns_Helper;

class Softro_Hero_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_hero';
    }

    public function get_title()
    {
        return esc_html__('EG Hero', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['softro_widgets'];
    }

    protected function register_controls()
    {
        // General
        $this->start_controls_section(
            'softro_hero_layout',
            [
                'label' => esc_html__('Layout', 'softro-core')
            ]
        );
        $this->add_control(
            'softro_hero_genaral_style_selection',
            [
                'label'   => esc_html__('Select Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'style_one'   => esc_html__('Style One', 'softro-core'),
                    'style_two'   => esc_html__('Style Two', 'softro-core'),
                    'style_three' => esc_html__('Style Three', 'softro-core'),
                    'style_four'  => esc_html__('Style Four', 'softro-core'),
                    'style_five'  => esc_html__('Style Five', 'softro-core'),
                ],
                'default' => 'style_one',
            ]
        );
        $this->end_controls_section();

        // Style One Content
        $this->start_controls_section(
            'softro_hero_style_one_fields',
            [
                'label'     => esc_html__('Style One', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_one',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_one_bg',
            [
                'label'   => esc_html__('Background Image', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_one_bg_dark',
            [
                'label'   => esc_html__('Background Image Dark', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_one_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Building Future-Ready Tech Solutions', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_one_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We design and develop cutting-edge software for start-ups and enterprises. We craft scalable tech solutions.', 'softro-core'),
            ]
        );
        $this->add_control(
            'style_one_banner_image',
            [
                'label' => esc_html__('Banner Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_style_one_counter_bg_image',
            [
                'label' => esc_html__('Counter One BG Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_counter_one_title',
            [
                'label'       => esc_html__('Counter One Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Customer Satisfaction', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_counter_one_number',
            [
                'label'   => esc_html__('Counter One Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '98',
            ]
        );
        $this->add_control(
            'softro_hero_counter_one_suffix',
            [
                'label'   => esc_html__('Counter One Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '%',
            ]
        );
        $this->add_control(
            'softro_hero_style_one_counter_two_bg_image',
            [
                'label' => esc_html__('Counter Two BG Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_counter_two_title',
            [
                'label'       => esc_html__('Counter Two Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Project Completed', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_counter_two_number',
            [
                'label'   => esc_html__('Counter Two Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );
        $this->add_control(
            'softro_hero_counter_two_suffix',
            [
                'label'   => esc_html__('Counter Two Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'K+',
            ]
        );
        $this->end_controls_section();

        // Style Two
        $this->start_controls_section(
            'softro_hero_style_two_fields',
            [
                'label'     => esc_html__('Style Two', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_two',
                ],
            ]
        );
        $this->add_control(
            'style_two_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Building Future- with Intelligent Innovation', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_two_video',
            [
                'label'       => esc_html__('Background Video', 'softro-core'),
                'type'        => Controls_Manager::MEDIA,
                'media_types' => ['video'],
            ]
        );

        $this->add_control(
            'softro_hero_style_two_counter_one_bg_image',
            [
                'label'     => esc_html__('Style Two Counter One BG Image', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_hero_style_two_counter_one_title',
            [
                'label'       => esc_html__('Counter One Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Project Completed', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_one_number',
            [
                'label'   => esc_html__('Counter One Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '1',
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_one_suffix',
            [
                'label'   => esc_html__('Counter One Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'K+',
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_two_bg_image',
            [
                'label'     => esc_html__('Style Two Counter Two BG Image', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_two',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_two_title',
            [
                'label'       => esc_html__('Counter Two Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Customer Satisfaction', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_two_number',
            [
                'label'   => esc_html__('Counter Two Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '98',
            ]
        );
        $this->add_control(
            'softro_hero_style_two_counter_two_suffix',
            [
                'label'   => esc_html__('Counter Two Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '%',
            ]
        );
        $this->end_controls_section();

        // Style Three
        $this->start_controls_section(
            'softro_hero_style_three_fields',
            [
                'label'     => esc_html__('Style Three', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_three',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_three_bg',
            [
                'label'   => esc_html__('Background Image', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_three_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Strategic Marketing', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_highlight',
            [
                'label'       => esc_html__('Title Highlight', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('for the Digital Age', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We help businesses reach the right audience, boost engagement, and skyrocket conversions.', 'softro-core'),
            ]
        );
        $this->add_control(
            'softro_hero_style_three_global_icon',
            [
                'label' => esc_html__('Global Icon', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $style_three_slides_repeater = new Repeater();
        $style_three_slides_repeater->add_control(
            'slide_image',
            [
                'label' => esc_html__('Slide Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_slides',
            [
                'label'       => esc_html__('Slides', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $style_three_slides_repeater->get_controls(),
                'title_field' => esc_html__('Slide', 'softro-core'),
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_one_bg_image',
            [
                'label' => esc_html__('Counter One BG Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_one_title',
            [
                'label'       => esc_html__('Counter One Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Business Growth', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_one_number',
            [
                'label'   => esc_html__('Counter One Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '2',
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_one_suffix',
            [
                'label'   => esc_html__('Counter One Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'X',
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_two_bg_image',
            [
                'label' => esc_html__('Counter Two BG Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_two_title',
            [
                'label'       => esc_html__('Counter Two Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Online Revenue', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_two_number',
            [
                'label'   => esc_html__('Counter Two Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '1.6',
            ]
        );
        $this->add_control(
            'softro_hero_style_three_counter_two_suffix',
            [
                'label'   => esc_html__('Counter Two Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => 'X',
            ]
        );
        $this->end_controls_section();

        // Style Four
        $this->start_controls_section(
            'softro_hero_style_four_fields',
            [
                'label'     => esc_html__('Style Four', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_four',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_four_bg',
            [
                'label'   => esc_html__('Background Image', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_four_bg_dark',
            [
                'label'       => esc_html__('Dark Background Image', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'description' => esc_html__('Background image used in dark mode.', 'softro-core'),
            ]
        );
        $this->add_control(
            'softro_hero_style_four_batch',
            [
                'label'       => esc_html__('Batch Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Creative Design Agency', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_four_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Inspiring Creativity, Driving Real Business Growth', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_four_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__('We craft unique digital experiences that help brands stand out, connect with audiences, and achieve measurable results.', 'softro-core'),
            ]
        );
        $this->add_control(
            'softro_hero_style_four_counter_title',
            [
                'label'       => esc_html__('Counter Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('year of experineces', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_four_counter_number',
            [
                'label'   => esc_html__('Counter Number', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '12',
            ]
        );
        $this->add_control(
            'softro_hero_style_four_counter_suffix',
            [
                'label'   => esc_html__('Counter Suffix', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => '+',
            ]
        );
        $this->add_control(
            'softro_hero_style_four_video',
            [
                'label'       => esc_html__('Background Video', 'softro-core'),
                'type'        => Controls_Manager::MEDIA,
                'media_types' => ['video'],
            ]
        );
        $this->end_controls_section();

        // Style Five
        $this->start_controls_section(
            'softro_hero_style_five_fields',
            [
                'label'     => esc_html__('Style Five', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_bg',
            [
                'label'     => esc_html__('Background Image', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_bg_dark',
            [
                'label'     => esc_html__('Background Image Dark', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_show_top_icon',
            [
                'label'        => esc_html__('Show Top Icon', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_top_icon',
            [
                'label'     => esc_html__('Top Icon', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector4.svg',
                ],
                'condition' => [
                    'softro_hero_style_five_show_top_icon' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Build Stunning Websites Without Code', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_five_description',
            [
                'label'       => esc_html__('Description', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Create, customize, and launch professional websites in minutes with our intuitive drag-and-drop page builder. No coding skills required.', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_five_show_primary_button',
            [
                'label'        => esc_html__('Show Primary Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_text',
            [
                'label'       => esc_html__('Primary Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get Started Free', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_link',
            [
                'label'         => esc_html__('Primary Button Link', 'softro-core'),
                'type'          => Controls_Manager::URL,
                'show_external' => true,
                'default'       => ['url' => '#'],
                'condition'     => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_show_secondary_button',
            [
                'label'        => esc_html__('Show Secondary Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_text',
            [
                'label'       => esc_html__('Secondary Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Watch Demo', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_link',
            [
                'label'         => esc_html__('Secondary Button Link', 'softro-core'),
                'type'          => Controls_Manager::URL,
                'show_external' => true,
                'default'       => ['url' => '#'],
                'condition'     => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );

        $style_five_feature_repeater = new Repeater();
        $style_five_feature_repeater->add_control(
            'feature_icon',
            [
                'label'   => esc_html__('Feature Icon', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector1.svg',
                ],
            ]
        );
        $style_five_feature_repeater->add_control(
            'feature_title',
            [
                'label'       => esc_html__('Feature Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Drag & Drop Builder', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_style_five_show_features',
            [
                'label'        => esc_html__('Show Features', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_features',
            [
                'label'       => esc_html__('Feature Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $style_five_feature_repeater->get_controls(),
                'default'     => [
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector1.svg'],
                        'feature_title' => esc_html__('Drag & Drop Builder', 'softro-core'),
                    ],
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector2.svg'],
                        'feature_title' => esc_html__('Beginner friendly', 'softro-core'),
                    ],
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector3.svg'],
                        'feature_title' => esc_html__('No credit card required', 'softro-core'),
                    ],
                ],
                'title_field' => '{{{ feature_title }}}',
                'condition'   => [
                    'softro_hero_style_five_show_features' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_show_banner_image',
            [
                'label'        => esc_html__('Show Bottom Banner Image', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_banner_image',
            [
                'label'     => esc_html__('Bottom Banner Image', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'default'   => [
                    'url' => EGNS_ASSETS_ROOT . '/image/sass/banner-img.png',
                ],
                'condition' => [
                    'softro_hero_style_five_show_banner_image' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_banner_image_alt',
            [
                'label'       => esc_html__('Bottom Banner Image Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Banner Image', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_hero_style_five_show_banner_image' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        // Shared Elements
        $this->start_controls_section(
            'softro_hero_shared_fields',
            [
                'label'     => esc_html__('Shared Elements', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get a Free Consultation', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_button_link',
            [
                'label'         => esc_html__('Button Link', 'softro-core'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__('https://your-link.com', 'softro-core'),
                'show_external' => true,
                'default'       => ['url' => '#'],
            ]
        );
        $this->add_control(
            'softro_hero_rating_link',
            [
                'label'         => esc_html__('Rating Area Link', 'softro-core'),
                'type'          => Controls_Manager::URL,
                'placeholder'   => esc_html__('https://your-link.com', 'softro-core'),
                'show_external' => true,
                'default'       => ['url' => '#'],
            ]
        );
        $this->add_control(
            'softro_hero_rating_label',
            [
                'label'       => esc_html__('Rating Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Review on', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_rating_text',
            [
                'label'       => esc_html__('Rating Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('(20 Reviews)', 'softro-core'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'softro_hero_rating_stars',
            [
                'label'   => esc_html__('Rating Stars', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 5,
                'min'     => 1,
                'max'     => 5,
            ]
        );
        $this->add_control(
            'softro_hero_rating_logo_light',
            [
                'label'   => esc_html__('Rating Light Logo', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo.svg'],
            ]
        );
        $this->add_control(
            'softro_hero_rating_logo_dark',
            [
                'label'   => esc_html__('Rating Dark Logo', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo-dark.svg'],
            ]
        );


        $this->add_control(
            'softro_hero_author_text',
            [
                'label'       => esc_html__('Author Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('we’ve already', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_hero_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_author_count',
            [
                'label'     => esc_html__('Author Count', 'softro-core'),
                'type'      => Controls_Manager::TEXT,
                'default'   => '20',
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_author_suffix',
            [
                'label'     => esc_html__('Author Count Suffix', 'softro-core'),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'k+',
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_author_subtext',
            [
                'label'       => esc_html__('Author Sub Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('active users', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_hero_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $author_images_repeater = new Repeater();
        $author_images_repeater->add_control(
            'author_image',
            [
                'label' => esc_html__('Author Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );
        $this->add_control(
            'softro_hero_author_images',
            [
                'label'       => esc_html__('Author Images', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $author_images_repeater->get_controls(),
                'title_field' => esc_html__('Author', 'softro-core'),
                'condition'   => [
                    'softro_hero_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->end_controls_section();

        //=====================Style=======================//

        $this->start_controls_section(
            'softro_hero_style_content',
            [
                'label'     => esc_html__('Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_hero_heading_color',
            [
                'label'     => esc_html__('Heading Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .banner-content h1'      => 'color: {{VALUE}};',
                    '{{WRAPPER}} .left-content h1'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .banner-content-wrap h1' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_heading_typography',
                'label'    => esc_html__('Heading Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .banner-content h1, {{WRAPPER}} .left-content h1, {{WRAPPER}} .banner-content-wrap h1',
            ]
        );

        $this->add_control(
            'softro_hero_batch_bg_color',
            [
                'label'     => esc_html__('Batch BG Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_four',
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content .batch' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_batch_color',
            [
                'label'     => esc_html__('Batch Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_four',
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content .batch' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_hero_batch_typography',
                'label'     => esc_html__('Batch Typography', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_four',
                ],
                'selector' => '{{WRAPPER}} .banner-content .batch',
            ]
        );

        $this->add_control(
            'softro_hero_description_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_three', 'style_four'],
                ],
                'selectors' => [
                    '{{WRAPPER}} .banner-content p'                                                         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .banner-content-wrap .para-area p'                                         => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home3-banner-section .banner-wrapper .banner-content-wrap .para-area svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_hero_description_typography',
                'label'     => esc_html__('Description Typography', 'softro-core'),
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_three', 'style_four'],
                ],
                'selector' => '{{WRAPPER}} .banner-content p, {{WRAPPER}} .banner-content-wrap .para-area p',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_button',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_hero_button_text_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1 span'               => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2 .content'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1 svg'                => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2 .icon svg path'     => 'stroke: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2 .icon.two svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_button_hover_text_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1:hover span'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2:hover .content'       => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1:hover svg'            => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2:hover .icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_hero_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1:hover::after' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2:hover::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_button_bg_color_style_three',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2 .content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2 .icon'    => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_three'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_button_hover_bg_color_style_three',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1:hover::after'   => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2:hover .content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .primary-btn2:hover .icon'    => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_three'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .banner-wrapper .primary-btn1 span, {{WRAPPER}} .banner-wrapper .primary-btn2 .content',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_rating',
            [
                'label'     => esc_html__('Rating', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_rating_border_color_style',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-banner-section .banner-wrapper .banner-content-wrap .btm-area'              => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home3-banner-section .banner-wrapper .banner-content-wrap .btm-area > li::before' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_three'],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_rating_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .banner-wrapper .rating-area.global span',
            ]
        );

        $this->add_control(
            'softro_hero_rating_text_color_style',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .rating-area.global  span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_hero_rating_star_color',
            [
                'label'     => esc_html__('Star Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .rating-area .rating .star li i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_hero_rating_star_icon_size',
            [
                'label'      => esc_html__('Star Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 48,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .global-style .banner-wrapper .rating-area .rating .star li i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_counter',
            [
                'label'     => esc_html__('Counter', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_counter_background1_color',
            [
                'label'     => esc_html__('Background Color 1', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .counter-wrap .counter-area' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three'],
                ],

            ]
        );
        $this->add_control(
            'softro_hero_counter_background2_color',
            [
                'label'     => esc_html__('Background Color 2', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .counter-wrap .counter-area.two' => 'background: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_genaral_style_selection' => ['style_one', 'style_two', 'style_three'],
                ],

            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_counter_title_typography',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content p, {{WRAPPER}} .banner-right-content .counter-area span, {{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area span',
            ]
        );
        $this->add_control(
            'softro_hero_counter_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content p'                   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .counter-wrap .banner-right-content .counter-area span'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_hero_counter_number_color',
            [
                'label'     => esc_html__('Number Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content .number h2'               => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content .number span'             => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .counter-wrap .banner-right-content .counter-area h2 strong'           => 'color: {{VALUE}};',
                    '{{WRAPPER}} .global-style .counter-wrap .banner-right-content .counter-area h2 sup'              => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area h2 strong' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area h2 sup'    => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_counter_number_typography',
                'label'    => esc_html__('Number Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content .number h2, {{WRAPPER}} .global-style .counter-wrap .counter-area .counter-content .number span, {{WRAPPER}} .global-style .counter-wrap .banner-right-content .counter-area h2 strong, {{WRAPPER}} .global-style .counter-wrap .banner-right-content .counter-area h2 sup, {{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area h2 strong, {{WRAPPER}} .home4-banner-section .banner-wrapper .banner-right-content .counter-area h2 sup',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_author',
            [
                'label'     => esc_html__('Author', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => [
                        'style_three',
                        'style_four'
                    ],
                ],
            ]
        );
        $this->add_control(
            'softro_hero_author_size',
            [
                'label'      => esc_html__('Author Font Size', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0.5,
                        'max' => 10,
                    ],
                    'rem' => [
                        'min' => 0.5,
                        'max' => 10,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .global-style .banner-wrapper .banner-left-content .author-area .author-img-grp li img '                => 'min-width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area .author-img-grp li img ' => 'min-width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_author_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-left-content .author-area .author-img-grp li img '                => 'border-color: {{VALUE}};',
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area .author-img-grp li img ' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_author_title_typography',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .banner-wrapper .banner-left-content .author-area h2, {{WRAPPER}} .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area h2',
            ]
        );
        $this->add_control(
            'softro_hero_author_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-left-content .author-area h2'                => 'color: {{VALUE}};',
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area h2' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_author_number_typography',
                'label'    => esc_html__('Number Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .global-style .banner-wrapper .banner-left-content .author-area h2 strong, {{WRAPPER}} .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area h2 strong',
            ]
        );
        $this->add_control(
            'softro_hero_author_number_color',
            [
                'label'     => esc_html__('Number Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-left-content .author-area h2 strong'                => 'color: {{VALUE}};',
                    '{{WRAPPER}}  .global-style .banner-wrapper .banner-content-wrap .btm-area > li .author-area h2 strong' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_five_section_style',
            [
                'label'     => esc_html__('Style Five Section', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_section_heading',
            [
                'label' => esc_html__('Wrapper', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_hero_style_five_wrapper_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper',
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_wrapper_padding',
            [
                'label'      => esc_html__('Wrapper Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_wrapper_min_height',
            [
                'label'      => esc_html__('Wrapper Min Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 300,
                        'max' => 1600,
                    ],
                    'vh' => [
                        'min' => 20,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_banner_image_top_margin',
            [
                'label'      => esc_html__('Bottom Image Top Margin', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => -800,
                        'max' => 200,
                    ],
                    '%' => [
                        'min' => -80,
                        'max' => 30,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-img-area' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'condition'  => [
                    'softro_hero_style_five_show_banner_image' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_five_content_style',
            [
                'label'     => esc_html__('Style Five Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_title_heading',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'softro_hero_style_five_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content h1' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_style_five_title_typography',
                'selector' => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content h1',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_description_heading',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_description_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content p' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_style_five_description_typography',
                'selector' => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content p',
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_five_button_style',
            [
                'label'     => esc_html__('Style Five Buttons', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_heading',
            [
                'label'     => esc_html__('Primary Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_hero_style_five_primary_button_typography',
                'selector'  => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent)',
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_text_color',
            [
                'label'     => esc_html__('Text/Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent)'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent) svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent)' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'softro_hero_style_five_primary_button_border',
                'selector'  => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent)',
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_primary_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_hover_text_color',
            [
                'label'     => esc_html__('Hover Text/Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent):hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent):hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent):hover::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_primary_button_hover_border_color',
            [
                'label'     => esc_html__('Hover Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1:not(.transparent):hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_primary_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_hero_style_five_secondary_button_heading',
            [
                'label'     => esc_html__('Secondary Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_hero_style_five_secondary_button_typography',
                'selector'  => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent',
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_text_color',
            [
                'label'     => esc_html__('Text/Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'softro_hero_style_five_secondary_button_border',
                'selector'  => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent',
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_secondary_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_hover_text_color',
            [
                'label'     => esc_html__('Hover Text/Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent:hover'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent:hover svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent:hover svg path' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent:hover::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_secondary_button_hover_border_color',
            [
                'label'     => esc_html__('Hover Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content .button-area .primary-btn1.transparent:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_hero_style_five_show_secondary_button' => 'yes',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_five_features_style',
            [
                'label'     => esc_html__('Style Five Features', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection' => 'style_five',
                    'softro_hero_style_five_show_features' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_feature_title_heading',
            [
                'label' => esc_html__('Feature Title', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'softro_hero_style_five_feature_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content ul li .content h2' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_hero_style_five_feature_title_typography',
                'selector' => '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content ul li .content h2',
            ]
        );
        $this->add_control(
            'softro_hero_style_five_feature_icon_heading',
            [
                'label'     => esc_html__('Feature Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_feature_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content ul li .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_feature_list_gap',
            [
                'label'      => esc_html__('Feature List Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-content-wrapper .banner-content ul' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'softro_hero_style_five_image_style',
            [
                'label'     => esc_html__('Style Five Bottom Image', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_hero_genaral_style_selection'   => 'style_five',
                    'softro_hero_style_five_show_banner_image' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_hero_style_five_image_heading',
            [
                'label' => esc_html__('Image', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_image_max_width',
            [
                'label'      => esc_html__('Max Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-img-area .banner-img img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_hero_style_five_image_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => false,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home5-banner-section .banner-img-area .banner-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function render()
    {

        $settings     = $this->get_settings_for_display();
        $rating_stars = !empty($settings['softro_hero_rating_stars']) ? absint($settings['softro_hero_rating_stars']) : 5;
        if ($rating_stars < 1) {
            $rating_stars = 1;
        } elseif ($rating_stars > 5) {
            $rating_stars = 5;
        }

        $style_three_slides = !empty($settings['softro_hero_style_three_slides']) && is_array($settings['softro_hero_style_three_slides'])
            ? $settings['softro_hero_style_three_slides']
            :  [];

        if (empty($style_three_slides)) {
            $style_three_slides = [
                ['slide_image' => ['url' => EGNS_ASSETS_ROOT . '/image/digital-marketing/banner-img-slide.png']],
                ['slide_image' => ['url' => EGNS_ASSETS_ROOT . '/image/digital-marketing/banner-img-slide2.png']],
                ['slide_image' => ['url' => EGNS_ASSETS_ROOT . '/image/digital-marketing/banner-img-slide3.png']],
            ];
        }

        $author_images = !empty($settings['softro_hero_author_images']) && is_array($settings['softro_hero_author_images'])
            ? $settings['softro_hero_author_images']
            :  [];

        if (empty($author_images)) {
            $author_images = [
                ['author_image' => ['url' => EGNS_ASSETS_ROOT . '/image/start-up/counter-people-img1.png']],
                ['author_image' => ['url' => EGNS_ASSETS_ROOT . '/image/start-up/counter-people-img2.png']],
                ['author_image' => ['url' => EGNS_ASSETS_ROOT . '/image/start-up/counter-people-img3.png']],
            ];
        }
?>
        <?php if ($settings['softro_hero_genaral_style_selection'] == 'style_one') {
            $hero_style_one_bg = !empty($settings['softro_hero_style_one_bg']['url']) ? $settings['softro_hero_style_one_bg']['url'] : '';
            $hero_style_one_bg_dark = !empty($settings['softro_hero_style_one_bg_dark']['url']) ? $settings['softro_hero_style_one_bg_dark']['url'] : '';
            $hero_style_one_inline = !empty($hero_style_one_bg) ? "background-image: url('" . esc_url_raw($hero_style_one_bg) . "');" : '';
        ?>
            <div class="home1-banner-section global-style"
                <?php if (!empty($hero_style_one_inline)) : ?>style="<?php echo esc_attr($hero_style_one_inline); ?>"<?php endif; ?>
                <?php if (!empty($hero_style_one_bg)) : ?>data-eg-bg-light="<?php echo esc_url($hero_style_one_bg); ?>"<?php endif; ?>
                <?php if (!empty($hero_style_one_bg_dark)) : ?>data-eg-bg-dark="<?php echo esc_url($hero_style_one_bg_dark); ?>"<?php endif; ?>>
                <div class="container-fluid one">
                    <div class="banner-wrapper">
                        <div class="banner-content text-center">
                            <h1><?php echo esc_html(!empty($settings['softro_hero_style_one_title']) ? $settings['softro_hero_style_one_title'] : ''); ?></h1>
                            <p><?php echo esc_html(!empty($settings['softro_hero_style_one_description']) ? $settings['softro_hero_style_one_description'] : ''); ?></p>
                            <div class="button-area">
                                <?php if (!empty($settings['softro_hero_button_text'])) { ?>
                                    <a class="primary-btn1" href="<?php echo esc_url(!empty($settings['softro_hero_button_link']['url']) ? $settings['softro_hero_button_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                        <span>
                                            <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : ''); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                        <span>
                                            <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : ''); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                <?php } ?>
                                <?php if (!empty($settings['softro_hero_rating_label'])) { ?>
                                    <a href="<?php echo esc_url(!empty($settings['softro_hero_rating_link']['url']) ? $settings['softro_hero_rating_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['nofollow']) ? 'nofollow' : ''); ?>" class="rating-area global">
                                        <div class="review">
                                            <span><?php echo esc_html(!empty($settings['softro_hero_rating_label']) ? $settings['softro_hero_rating_label'] : ''); ?></span>
                                            <img class="light-logo" src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_light']['url']) ? $settings['softro_hero_rating_logo_light']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo.svg'); ?>"
                                                alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                            <img class="dark-logo" src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_dark']['url']) ? $settings['softro_hero_rating_logo_dark']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo-dark.svg'); ?>"
                                                alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                        </div>
                                        <div class="rating">
                                            <ul class="star">
                                                <?php for ($i = 0; $i < $rating_stars; $i++) { ?>
                                                    <li><i class="bi bi-star-fill"></i></li>
                                                <?php } ?>
                                            </ul>
                                            <span><?php echo esc_html(!empty($settings['softro_hero_rating_text']) ? $settings['softro_hero_rating_text'] : ''); ?></span>
                                        </div>
                                    </a>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="banner-img-wrap">
                            <div class="banner-img">
                                <img src="<?php echo esc_url(!empty($settings['style_one_banner_image']['url']) ? $settings['style_one_banner_image']['url'] : ''); ?>" alt="<?php echo esc_attr(!empty($settings['softro_hero_style_one_title']) ? $settings['softro_hero_style_one_title'] : ''); ?>">
                            </div>
                            <div class="counter-wrap">
                                <div class="counter-area" <?php if (!empty($settings['softro_hero_style_one_counter_bg_image']['url'])) { ?> style="background-image: url('<?php echo esc_url($settings['softro_hero_style_one_counter_bg_image']['url']); ?>');" <?php } ?>>
                                    <div class="counter-content">
                                        <p><?php echo esc_html(!empty($settings['softro_hero_counter_one_title']) ? $settings['softro_hero_counter_one_title'] : ''); ?></p>
                                        <div class="number">
                                            <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_counter_one_number']) ? $settings['softro_hero_counter_one_number'] : ''); ?></h2>
                                            <span><?php echo esc_html(isset($settings['softro_hero_counter_one_suffix']) ? $settings['softro_hero_counter_one_suffix'] : ''); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="counter-area two" <?php if (!empty($settings['softro_hero_style_one_counter_two_bg_image']['url'])) { ?> style="background-image: url('<?php echo esc_url($settings['softro_hero_style_one_counter_two_bg_image']['url']); ?>');" <?php } ?>>
                                    <div class="counter-content">
                                        <p><?php echo esc_html(!empty($settings['softro_hero_counter_two_title']) ? $settings['softro_hero_counter_two_title'] : ''); ?></p>
                                        <div class="number">
                                            <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_counter_two_number']) ? $settings['softro_hero_counter_two_number'] : ''); ?></h2>
                                            <span><?php echo esc_html(isset($settings['softro_hero_counter_two_suffix']) ? $settings['softro_hero_counter_two_suffix'] : ''); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
        <?php if ($settings['softro_hero_genaral_style_selection'] == 'style_two') { ?>
            <div class="home2-banner-section global-style">
                <div class="video-area">
                    <video autoplay loop="loop" muted preload="auto">
                        <source src="<?php echo esc_url(!empty($settings['softro_hero_style_two_video']['url']) ? $settings['softro_hero_style_two_video']['url'] : ''); ?>" type="video/mp4">
                    </video>
                </div>
                <div class="banner-wrapper-wrap">
                    <div class="banner-wrapper">
                        <div class="left-content">
                            <h1><?php echo esc_html(!empty($settings['style_two_title']) ? $settings['style_two_title'] : ''); ?></h1>
                            <div class="button-area">
                                <a class="primary-btn1" href="<?php echo esc_url(!empty($settings['softro_hero_button_link']['url']) ? $settings['softro_hero_button_link']['url'] : ''); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                    <span>
                                        <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : ''); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : ''); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                                <a href="<?php echo esc_url(!empty($settings['softro_hero_rating_link']['url']) ? $settings['softro_hero_rating_link']['url'] : ''); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['nofollow']) ? 'nofollow' : ''); ?>" class="rating-area global">
                                    <div class="review">
                                        <span><?php echo esc_html(!empty($settings['softro_hero_rating_label']) ? $settings['softro_hero_rating_label'] : ''); ?></span>
                                        <img class="dark-logo" src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_light']['url']) ? $settings['softro_hero_rating_logo_light']['url'] : ''); ?>"
                                            alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                        <img class="light-logo"
                                            src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_dark']['url']) ? $settings['softro_hero_rating_logo_dark']['url'] : ''); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                    </div>
                                    <div class="rating">
                                        <ul class="star">
                                            <?php for ($i = 0; $i < $rating_stars; $i++) { ?>
                                                <li><i class="bi bi-star-fill"></i></li>
                                            <?php } ?>
                                        </ul>
                                        <span><?php echo esc_html(!empty($settings['softro_hero_rating_text']) ? $settings['softro_hero_rating_text'] : ''); ?></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="right-content">
                            <div class="counter-wrap">
                                <div class="counter-area" style="background-image: url('<?php echo esc_url(!empty($settings['softro_hero_style_two_counter_one_bg_image']['url']) ? $settings['softro_hero_style_two_counter_one_bg_image']['url'] : (!empty($settings['softro_hero_style_two_counter_bg_image']['url']) ? $settings['softro_hero_style_two_counter_bg_image']['url'] : '')); ?>');">
                                    <div class="counter-content">
                                        <p><?php echo esc_html(!empty($settings['softro_hero_style_two_counter_one_title']) ? $settings['softro_hero_style_two_counter_one_title'] : (!empty($settings['softro_hero_counter_two_title']) ? $settings['softro_hero_counter_two_title'] : '')); ?></p>
                                        <div class="number">
                                            <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_style_two_counter_one_number']) ? $settings['softro_hero_style_two_counter_one_number'] : (!empty($settings['softro_hero_counter_two_number']) ? $settings['softro_hero_counter_two_number'] : '')); ?></h2>
                                            <span><?php echo esc_html(isset($settings['softro_hero_style_two_counter_one_suffix']) ? $settings['softro_hero_style_two_counter_one_suffix'] : (isset($settings['softro_hero_counter_two_suffix']) ? $settings['softro_hero_counter_two_suffix'] : '')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="counter-area two" style="background-image: url('<?php echo esc_url(!empty($settings['softro_hero_style_two_counter_two_bg_image']['url']) ? $settings['softro_hero_style_two_counter_two_bg_image']['url'] : (!empty($settings['softro_hero_style_two_counter_bg_image']['url']) ? $settings['softro_hero_style_two_counter_bg_image']['url'] : '')); ?>');">
                                    <div class="counter-content">
                                        <p><?php echo esc_html(!empty($settings['softro_hero_style_two_counter_two_title']) ? $settings['softro_hero_style_two_counter_two_title'] : (!empty($settings['softro_hero_counter_one_title']) ? $settings['softro_hero_counter_one_title'] : '')); ?></p>
                                        <div class="number">
                                            <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_style_two_counter_two_number']) ? $settings['softro_hero_style_two_counter_two_number'] : (!empty($settings['softro_hero_counter_one_number']) ? $settings['softro_hero_counter_one_number'] : '')); ?></h2>
                                            <span><?php echo esc_html(isset($settings['softro_hero_style_two_counter_two_suffix']) ? $settings['softro_hero_style_two_counter_two_suffix'] : (isset($settings['softro_hero_counter_one_suffix']) ? $settings['softro_hero_counter_one_suffix'] : '')); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($settings['softro_hero_genaral_style_selection'] == 'style_three') {
            $hero_style_three_bg = !empty($settings['softro_hero_style_three_bg']['url']) ? $settings['softro_hero_style_three_bg']['url'] : '';
            $style = 'style="background-image: url(' . $hero_style_three_bg . ');"';
        ?>
            <div class="home3-banner-section global-style">
                <div class="container-fluid one">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="banner-wrapper" <?php echo $style; ?>>
                                <div class="banner-content-wrap">
                                    <h1>
                                        <?php echo esc_html(!empty($settings['softro_hero_style_three_title']) ? $settings['softro_hero_style_three_title'] : ''); ?>
                                        <span><?php echo esc_html(!empty($settings['softro_hero_style_three_highlight']) ? $settings['softro_hero_style_three_highlight'] : ''); ?></span>
                                    </h1>
                                    <?php if (!empty($settings['softro_hero_style_three_description'])) { ?>
                                        <div class="para-area">
                                            <div class="icon">
                                                <svg width="6" height="49" viewBox="0 0 6 49"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M2.88672 2.83718e-05L-3.26633e-05 2.88678L2.88672 5.77353L5.77347 2.88678L2.88672 2.83718e-05ZM2.88672 48.7735L5.77347 45.8868L2.88672 43L-3.26633e-05 45.8868L2.88672 48.7735ZM2.88672 2.88678H2.38672L2.38672 45.8868H2.88672H3.38672L3.38672 2.88678H2.88672Z" />
                                                </svg>
                                            </div>
                                            <p><?php echo esc_html(!empty($settings['softro_hero_style_three_description']) ? $settings['softro_hero_style_three_description'] : ''); ?></p>
                                        </div>
                                    <?php } ?>
                                    <?php if (!empty($settings['softro_hero_button_text'])) { ?>
                                        <div class="button-area">
                                            <a class="primary-btn2" href="<?php echo esc_url(!empty($settings['softro_hero_button_link']['url']) ? $settings['softro_hero_button_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                                <span class="icon">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M1 9L9 1M9 1C7.22222 1.33333 3.33333 2 1 1M9 1C8.66667 2.66667 8 6.33333 9 9"
                                                            stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </span>
                                                <span class="content"><?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : esc_html__('Get a Free Consultation', 'softro-core')); ?></span>
                                                <span class="icon two">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M1 9L9 1M9 1C7.22222 1.33333 3.33333 2 1 1M9 1C8.66667 2.66667 8 6.33333 9 9"
                                                            stroke-width="1.5" stroke-linecap="round" />
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <ul class="btm-area">
                                        <?php if (!empty($settings['softro_hero_rating_label'])) { ?>
                                            <li> <a href="<?php echo esc_url(!empty($settings['softro_hero_rating_link']['url']) ? $settings['softro_hero_rating_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['nofollow']) ? 'nofollow' : ''); ?>" class="rating-area global">
                                                    <div class="review">
                                                        <span><?php echo esc_html(!empty($settings['softro_hero_rating_label']) ? $settings['softro_hero_rating_label'] : esc_html__('Review on', 'softro-core')); ?></span>
                                                        <img class="light-logo"
                                                            src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_light']['url']) ? $settings['softro_hero_rating_logo_light']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo.svg'); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                                        <img class="dark-logo"
                                                            src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_dark']['url']) ? $settings['softro_hero_rating_logo_dark']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo-dark.svg'); ?>"
                                                            alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                                    </div>
                                                    <div class="rating">
                                                        <ul class="star">
                                                            <?php for ($i = 0; $i < $rating_stars; $i++) { ?>
                                                                <li><i class="bi bi-star-fill"></i></li>
                                                            <?php } ?>
                                                        </ul>
                                                        <span><?php echo esc_html(!empty($settings['softro_hero_rating_text']) ? $settings['softro_hero_rating_text'] : esc_html__('(20 Reviews)', 'softro-core')); ?></span>
                                                    </div>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <div class="author-area">
                                                <ul class="author-img-grp">
                                                    <?php foreach ($author_images as $author_item) { ?>
                                                        <?php $author_image_url = !empty($author_item['author_image']['url']) ? $author_item['author_image']['url'] : ''; ?>
                                                        <?php if (empty($author_image_url)) {
                                                            continue;
                                                        } ?>
                                                        <li><img src="<?php echo esc_url($author_image_url); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>"></li>
                                                    <?php } ?>
                                                </ul>
                                                <h2><?php echo esc_html(!empty($settings['softro_hero_author_text']) ? $settings['softro_hero_author_text'] : ''); ?> <strong><span
                                                            class="counter_number"><?php echo esc_html(!empty($settings['softro_hero_author_count']) ? $settings['softro_hero_author_count'] : ''); ?></span><?php echo esc_html(isset($settings['softro_hero_author_suffix']) ? $settings['softro_hero_author_suffix'] : ''); ?></strong>
                                                    <?php echo esc_html(!empty($settings['softro_hero_author_subtext']) ? $settings['softro_hero_author_subtext'] : ''); ?></h2>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="global-icon">
                                        <img src="<?php echo esc_url(!empty($settings['softro_hero_style_three_global_icon']['url']) ? $settings['softro_hero_style_three_global_icon']['url'] : EGNS_ASSETS_ROOT . '/image/digital-marketing/banner-global-icon.gif'); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                        <div class="arrow-icon">
                                            <svg width="122" height="70" viewBox="0 0 122 70"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M0 2.85022L4.97883 5.77334L5.02091 -1.14441e-05L0 2.85022ZM122 65.9581L116.675 63.727L117.405 69.4541L122 65.9581ZM6.16582 2.98659L6.14312 3.48608C7.5356 3.54934 8.89249 3.63428 10.2151 3.74L10.2549 3.24159L10.2947 2.74318C8.96043 2.63652 7.5921 2.55088 6.18851 2.48711L6.16582 2.98659ZM14.2544 3.63552L14.1959 4.13208C15.593 4.29665 16.949 4.4859 18.2657 4.69861L18.3454 4.20501L18.4252 3.71141C17.094 3.49635 15.7238 3.30514 14.3129 3.13895L14.2544 3.63552ZM22.3364 4.94462L22.2335 5.43393C23.5812 5.71716 24.8843 6.02671 26.1451 6.361L26.2733 5.87769L26.4014 5.39439C25.1234 5.05556 23.8034 4.74202 22.4392 4.45531L22.3364 4.94462ZM30.2125 7.04409L30.0564 7.5191C31.3726 7.95167 32.6388 8.41328 33.858 8.90168L34.0439 8.43753L34.2299 7.97339C32.9906 7.47699 31.7046 7.00816 30.3686 6.56909L30.2125 7.04409ZM37.7634 10.0753L37.5462 10.5257C38.7847 11.1229 39.9706 11.75 41.108 12.4038L41.3572 11.9703L41.6064 11.5369C40.448 10.871 39.2408 10.2327 37.9805 9.62493L37.7634 10.0753ZM44.8053 14.1274L44.5244 14.541C45.66 15.3122 46.7435 16.1118 47.7804 16.9362L48.0916 16.5448L48.4027 16.1535C47.3467 15.3139 46.243 14.4994 45.0862 13.7138L44.8053 14.1274ZM51.1893 19.1982L50.8506 19.566C51.8541 20.49 52.8118 21.4376 53.73 22.4046L54.0926 22.0602L54.4551 21.7159C53.5225 20.7338 52.5489 19.7704 51.528 18.8303L51.1893 19.1982ZM56.8089 25.0996L56.4267 25.4219C57.3037 26.462 58.1453 27.5179 58.959 28.5849L59.3566 28.2817L59.7542 27.9786C58.9318 26.9002 58.0799 25.8313 57.1912 24.7773L56.8089 25.0996ZM61.7638 31.5715L61.3551 31.8596C62.14 32.9732 62.9029 34.0929 63.6519 35.2136L64.0676 34.9357L64.4833 34.6579C63.7311 33.5324 62.9634 32.4056 62.1725 31.2834L61.7638 31.5715ZM66.3113 38.3421L65.8926 38.6153C66.6398 39.7605 67.3818 40.9023 68.1249 42.0316L68.5425 41.7567L68.9602 41.4819C68.2186 40.3548 67.4787 39.2162 66.7301 38.0689L66.3113 38.3421ZM70.8138 45.1445L70.4017 45.4277C71.1795 46.5597 71.969 47.674 72.7793 48.7636L73.1805 48.4652L73.5817 48.1668C72.7805 47.0895 71.9984 45.9857 71.2259 44.8614L70.8138 45.1445ZM75.6988 51.6704L75.315 51.9908C76.1955 53.0456 77.108 54.0679 78.0624 55.0504L78.421 54.702L78.7797 54.3536C77.8449 53.3913 76.9492 52.3879 76.0826 51.3499L75.6988 51.6704ZM81.3885 57.4933L81.0637 57.8735C82.1017 58.7603 83.1903 59.6007 84.3393 60.3877L84.6218 59.9752L84.9044 59.5627C83.7864 58.7969 82.7259 57.9783 81.7133 57.1132L81.3885 57.4933ZM88.1123 62.0876L87.8781 62.5294C89.0664 63.1593 90.3145 63.7365 91.6301 64.2555L91.8136 63.7903L91.9971 63.3252C90.7162 62.8199 89.502 62.2583 88.3464 61.6458L88.1123 62.0876ZM95.6808 65.0845L95.5466 65.5661C96.8256 65.9226 98.1621 66.2296 99.5616 66.4835L99.6508 65.9915L99.7401 65.4995C98.37 65.2509 97.0634 64.9508 95.8151 64.6028L95.6808 65.0845ZM103.665 66.5519L103.615 67.0494C104.923 67.1806 106.281 67.2701 107.693 67.3155L107.709 66.8157L107.725 66.316C106.335 66.2713 105 66.1833 103.715 66.0544L103.665 66.5519ZM111.783 66.8255L111.796 67.3253C113.134 67.2908 114.516 67.2203 115.944 67.1121L115.907 66.6135L115.869 66.115C114.456 66.2219 113.091 66.2916 111.771 66.3256L111.783 66.8255Z"
                                                    fill="url(#paint0_linear_1030_9383)" />
                                                <defs>
                                                    <linearGradient id="paint0_linear_1030_9383" x1="-4.93167"
                                                        y1="-3.08154" x2="149.709" y2="73.8664"
                                                        gradientUnits="userSpaceOnUse">
                                                        <stop offset="0" />
                                                        <stop offset="1" />
                                                    </linearGradient>
                                                </defs>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="banner-img-wrap">
                                    <div class="swiper home3-banner-slide">
                                        <div class="swiper-wrapper">
                                            <?php foreach ($style_three_slides as $slide_item) { ?>
                                                <?php $slide_image_url = !empty($slide_item['slide_image']['url']) ? $slide_item['slide_image']['url'] : ''; ?>
                                                <?php if (empty($slide_image_url)) {
                                                    continue;
                                                } ?>
                                                <div class="swiper-slide">
                                                    <div class="img-area">
                                                        <img src="<?php echo esc_url($slide_image_url); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="swiper-pagination1 banner-pagi"></div>
                                    <div class="counter-wrap">
                                        <div class="counter-area" style="background-image: url('<?php echo esc_url(!empty($settings['softro_hero_style_three_counter_one_bg_image']['url']) ? $settings['softro_hero_style_three_counter_one_bg_image']['url'] : EGNS_ASSETS_ROOT . '/image/digital-marketing/project-completed.png'); ?>');">
                                            <div class="counter-content">
                                                <p><?php echo esc_html(!empty($settings['softro_hero_style_three_counter_one_title']) ? $settings['softro_hero_style_three_counter_one_title'] : (!empty($settings['softro_hero_counter_one_title']) ? $settings['softro_hero_counter_one_title'] : '')); ?></p>
                                                <div class="number">
                                                    <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_style_three_counter_one_number']) ? $settings['softro_hero_style_three_counter_one_number'] : (!empty($settings['softro_hero_counter_one_number']) ? $settings['softro_hero_counter_one_number'] : '')); ?></h2>
                                                    <span><?php echo esc_html(isset($settings['softro_hero_style_three_counter_one_suffix']) ? $settings['softro_hero_style_three_counter_one_suffix'] : (isset($settings['softro_hero_counter_one_suffix']) ? $settings['softro_hero_counter_one_suffix'] : '')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="counter-area two" style="background-image: url('<?php echo esc_url(!empty($settings['softro_hero_style_three_counter_two_bg_image']['url']) ? $settings['softro_hero_style_three_counter_two_bg_image']['url'] : EGNS_ASSETS_ROOT . '/image/digital-marketing/project-completed.png'); ?>');">
                                            <div class="counter-content">
                                                <p><?php echo esc_html(!empty($settings['softro_hero_style_three_counter_two_title']) ? $settings['softro_hero_style_three_counter_two_title'] : (!empty($settings['softro_hero_counter_two_title']) ? $settings['softro_hero_counter_two_title'] : '')); ?></p>
                                                <div class="number">
                                                    <h2 class="counter"><?php echo esc_html(!empty($settings['softro_hero_style_three_counter_two_number']) ? $settings['softro_hero_style_three_counter_two_number'] : (!empty($settings['softro_hero_counter_two_number']) ? $settings['softro_hero_counter_two_number'] : '')); ?></h2>
                                                    <span><?php echo esc_html(isset($settings['softro_hero_style_three_counter_two_suffix']) ? $settings['softro_hero_style_three_counter_two_suffix'] : (isset($settings['softro_hero_counter_two_suffix']) ? $settings['softro_hero_counter_two_suffix'] : '')); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if ($settings['softro_hero_genaral_style_selection'] == 'style_four') {
            $hero_style_four_bg = !empty($settings['softro_hero_style_four_bg']['url']) ? $settings['softro_hero_style_four_bg']['url'] : '';
            $hero_style_four_bg_dark = !empty($settings['softro_hero_style_four_bg_dark']['url']) ? $settings['softro_hero_style_four_bg_dark']['url'] : '';
            $hero_style_four_inline = !empty($hero_style_four_bg) ? "background-image: url('" . esc_url_raw($hero_style_four_bg) . "');" : '';
        ?>
            <div class="home4-banner-section global-style"
                <?php if (!empty($hero_style_four_inline)) : ?>style="<?php echo esc_attr($hero_style_four_inline); ?>"<?php endif; ?>
                <?php if (!empty($hero_style_four_bg)) : ?>data-eg-bg-light="<?php echo esc_url($hero_style_four_bg); ?>"<?php endif; ?>
                <?php if (!empty($hero_style_four_bg_dark)) : ?>data-eg-bg-dark="<?php echo esc_url($hero_style_four_bg_dark); ?>"<?php endif; ?>>
                <div class="banner-wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="banner-content text-center">
                                    <?php if (!empty($settings['softro_hero_style_four_batch'])) { ?>
                                        <span class="batch"><?php echo esc_html(!empty($settings['softro_hero_style_four_batch']) ? $settings['softro_hero_style_four_batch'] : ''); ?></span>
                                    <?php } ?>
                                    <h1><?php echo esc_html(!empty($settings['softro_hero_style_four_title']) ? $settings['softro_hero_style_four_title'] : ''); ?></h1>
                                    <p><?php echo esc_html(!empty($settings['softro_hero_style_four_description']) ? $settings['softro_hero_style_four_description'] : ''); ?></p>
                                    <div class="button-area">
                                        <?php if (!empty($settings['softro_hero_button_text'])) { ?>
                                            <a class="primary-btn1" href="<?php echo esc_url(!empty($settings['softro_hero_button_link']['url']) ? $settings['softro_hero_button_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                                <span>
                                                    <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : esc_html__('Get a Free Consultation', 'softro-core')); ?>
                                                    <svg width="20" height="20" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g>
                                                            <path
                                                                d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span>
                                                    <?php echo esc_html(!empty($settings['softro_hero_button_text']) ? $settings['softro_hero_button_text'] : esc_html__('Get a Free Consultation', 'softro-core')); ?>
                                                    <svg width="20" height="20" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <g>
                                                            <path
                                                                d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        <?php } ?>
                                        <?php if (!empty($settings['softro_hero_rating_label'])) { ?>
                                            <a href="<?php echo esc_url(!empty($settings['softro_hero_rating_link']['url']) ? $settings['softro_hero_rating_link']['url'] : '#'); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_rating_link']['nofollow']) ? 'nofollow' : ''); ?>" class="rating-area global">
                                                <div class="review">
                                                    <span><?php echo esc_html(!empty($settings['softro_hero_rating_label']) ? $settings['softro_hero_rating_label'] : esc_html__('Review on', 'softro-core')); ?></span>
                                                    <img class="light-logo"
                                                        src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_light']['url']) ? $settings['softro_hero_rating_logo_light']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo.svg'); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                                    <img class="dark-logo"
                                                        src="<?php echo esc_url(!empty($settings['softro_hero_rating_logo_dark']['url']) ? $settings['softro_hero_rating_logo_dark']['url'] : EGNS_ASSETS_ROOT . '/image/start-up/vector/clutchco-logo-dark.svg'); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>">
                                                </div>
                                                <div class="rating">
                                                    <ul class="star">
                                                        <?php for ($i = 0; $i < $rating_stars; $i++) { ?>
                                                            <li><i class="bi bi-star-fill"></i></li>
                                                        <?php } ?>
                                                    </ul>
                                                    <span><?php echo esc_html(!empty($settings['softro_hero_rating_text']) ? $settings['softro_hero_rating_text'] : esc_html__('(20 Reviews)', 'softro-core')); ?></span>
                                                </div>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row gy-4 justify-content-between align-items-center">
                            <div class="col-lg-4 col-md-6 col-sm-7">
                                <div class="banner-left-content">
                                    <div class="author-area">
                                        <ul class="author-img-grp">
                                            <?php foreach ($author_images as $author_item) { ?>
                                                <?php $author_image_url = !empty($author_item['author_image']['url']) ? $author_item['author_image']['url'] : ''; ?>
                                                <?php if (empty($author_image_url)) {
                                                    continue;
                                                } ?>
                                                <li><img src="<?php echo esc_url($author_image_url); ?>" alt="<?php echo esc_attr__('image', 'softro-core') ?>"></li>
                                            <?php } ?>
                                        </ul>
                                        <h2><?php echo esc_html(!empty($settings['softro_hero_author_text']) ? $settings['softro_hero_author_text'] : ''); ?> <strong><span class="counter_number"><?php echo esc_html(!empty($settings['softro_hero_author_count']) ? $settings['softro_hero_author_count'] : ''); ?></span><?php echo esc_html(isset($settings['softro_hero_author_suffix']) ? $settings['softro_hero_author_suffix'] : ''); ?></strong>
                                            <?php echo esc_html(!empty($settings['softro_hero_author_subtext']) ? $settings['softro_hero_author_subtext'] : ''); ?></h2>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-5">
                                <div class="banner-right-content">
                                    <div class="counter-area">
                                        <h2>
                                            <strong class="counter_number"><?php echo esc_html(!empty($settings['softro_hero_style_four_counter_number']) ? $settings['softro_hero_style_four_counter_number'] : ''); ?></strong>
                                            <sup><?php echo esc_html(isset($settings['softro_hero_style_four_counter_suffix']) ? $settings['softro_hero_style_four_counter_suffix'] : ''); ?></sup>
                                        </h2>
                                        <span><?php echo esc_html(!empty($settings['softro_hero_style_four_counter_title']) ? $settings['softro_hero_style_four_counter_title'] : ''); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="home4-banner-video">
                    <video autoplay loop muted playsinline src="<?php echo esc_url(!empty($settings['softro_hero_style_four_video']['url']) ? $settings['softro_hero_style_four_video']['url'] : EGNS_ASSETS_ROOT . '/video/home4-banner-video.mp4'); ?>"></video>
                </div>
            </div>
            <div class="home4-banner-video-full"></div>
        <?php } ?>
        <?php if ($settings['softro_hero_genaral_style_selection'] == 'style_five') {
            $hero_style_five_bg = !empty($settings['softro_hero_style_five_bg']['url']) ? $settings['softro_hero_style_five_bg']['url'] : '';
            $hero_style_five_bg_dark = !empty($settings['softro_hero_style_five_bg_dark']['url']) ? $settings['softro_hero_style_five_bg_dark']['url'] : '';
            $hero_style_five_inline = !empty($hero_style_five_bg) ? "background-image: url('" . esc_url_raw($hero_style_five_bg) . "');" : '';
            $hero_style_five_show_top_icon = !empty($settings['softro_hero_style_five_show_top_icon']) && $settings['softro_hero_style_five_show_top_icon'] === 'yes';
            $hero_style_five_top_icon = !empty($settings['softro_hero_style_five_top_icon']['url']) ? $settings['softro_hero_style_five_top_icon']['url'] : EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector4.svg';
            $hero_style_five_title = !empty($settings['softro_hero_style_five_title']) ? $settings['softro_hero_style_five_title'] : '';
            $hero_style_five_description = !empty($settings['softro_hero_style_five_description']) ? $settings['softro_hero_style_five_description'] : '';
            $hero_style_five_show_primary_button = !empty($settings['softro_hero_style_five_show_primary_button']) && $settings['softro_hero_style_five_show_primary_button'] === 'yes';
            $hero_style_five_primary_button_text = !empty($settings['softro_hero_style_five_primary_button_text']) ? $settings['softro_hero_style_five_primary_button_text'] : '';
            $hero_style_five_primary_button_link = !empty($settings['softro_hero_style_five_primary_button_link']['url']) ? $settings['softro_hero_style_five_primary_button_link']['url'] : '#';
            $hero_style_five_show_secondary_button = !empty($settings['softro_hero_style_five_show_secondary_button']) && $settings['softro_hero_style_five_show_secondary_button'] === 'yes';
            $hero_style_five_secondary_button_text = !empty($settings['softro_hero_style_five_secondary_button_text']) ? $settings['softro_hero_style_five_secondary_button_text'] : '';
            $hero_style_five_secondary_button_link = !empty($settings['softro_hero_style_five_secondary_button_link']['url']) ? $settings['softro_hero_style_five_secondary_button_link']['url'] : '#';
            $hero_style_five_show_features = !empty($settings['softro_hero_style_five_show_features']) && $settings['softro_hero_style_five_show_features'] === 'yes';
            $hero_style_five_features = !empty($settings['softro_hero_style_five_features']) && is_array($settings['softro_hero_style_five_features']) ? $settings['softro_hero_style_five_features'] : [];
            if (empty($hero_style_five_features)) {
                $hero_style_five_features = [
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector1.svg'],
                        'feature_title' => esc_html__('Drag & Drop Builder', 'softro-core'),
                    ],
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector2.svg'],
                        'feature_title' => esc_html__('Beginner friendly', 'softro-core'),
                    ],
                    [
                        'feature_icon'  => ['url' => EGNS_ASSETS_ROOT . '/image/sass/vector/banner-vector3.svg'],
                        'feature_title' => esc_html__('No credit card required', 'softro-core'),
                    ],
                ];
            }
            $hero_style_five_show_banner_image = !empty($settings['softro_hero_style_five_show_banner_image']) && $settings['softro_hero_style_five_show_banner_image'] === 'yes';
            $hero_style_five_banner_image = !empty($settings['softro_hero_style_five_banner_image']['url']) ? $settings['softro_hero_style_five_banner_image']['url'] : EGNS_ASSETS_ROOT . '/image/sass/banner-img.png';
            $hero_style_five_banner_image_alt = !empty($settings['softro_hero_style_five_banner_image_alt']) ? $settings['softro_hero_style_five_banner_image_alt'] : esc_html__('Banner Image', 'softro-core');
        ?>
            <div class="home5-banner-section">
                <div class="banner-content-wrapper"
                    <?php if (!empty($hero_style_five_inline)) : ?>style="<?php echo esc_attr($hero_style_five_inline); ?>"<?php endif; ?>
                    <?php if (!empty($hero_style_five_bg)) : ?>data-eg-bg-light="<?php echo esc_url($hero_style_five_bg); ?>"<?php endif; ?>
                    <?php if (!empty($hero_style_five_bg_dark)) : ?>data-eg-bg-dark="<?php echo esc_url($hero_style_five_bg_dark); ?>"<?php endif; ?>>
                    <div class="container one">
                        <div class="banner-content text-center">
                            <?php if ($hero_style_five_show_top_icon && !empty($hero_style_five_top_icon)) { ?>
                                <div class="banner-icon">
                                    <img src="<?php echo esc_url($hero_style_five_top_icon); ?>" alt="<?php echo esc_attr($hero_style_five_title); ?>">
                                </div>
                            <?php } ?>
                            <?php if (!empty($hero_style_five_title)) { ?>
                                <h1><?php echo esc_html($hero_style_five_title); ?></h1>
                            <?php } ?>
                            <?php if (!empty($hero_style_five_description)) { ?>
                                <p><?php echo esc_html($hero_style_five_description); ?></p>
                            <?php } ?>
                            <?php if (($hero_style_five_show_primary_button && !empty($hero_style_five_primary_button_text)) || ($hero_style_five_show_secondary_button && !empty($hero_style_five_secondary_button_text))) { ?>
                                <div class="button-area">
                                    <?php if ($hero_style_five_show_primary_button && !empty($hero_style_five_primary_button_text)) { ?>
                                        <a class="primary-btn1" href="<?php echo esc_url($hero_style_five_primary_button_link); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_style_five_primary_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_style_five_primary_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                            <span>
                                                <?php echo esc_html($hero_style_five_primary_button_text); ?>
                                                <svg width="20" height="20" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <span>
                                                <?php echo esc_html($hero_style_five_primary_button_text); ?>
                                                <svg width="20" height="20" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                    <?php } ?>
                                    <?php if ($hero_style_five_show_secondary_button && !empty($hero_style_five_secondary_button_text)) { ?>
                                        <a class="primary-btn1 transparent" href="<?php echo esc_url($hero_style_five_secondary_button_link); ?>" target="<?php echo esc_attr(!empty($settings['softro_hero_style_five_secondary_button_link']['is_external']) ? '_blank' : '_self'); ?>" rel="<?php echo esc_attr(!empty($settings['softro_hero_style_five_secondary_button_link']['nofollow']) ? 'nofollow' : ''); ?>">
                                            <span>
                                                <?php echo esc_html($hero_style_five_secondary_button_text); ?>
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M9.30948 6.72633L6.40242 4.61312C6.29574 4.53578 6.15411 4.52414 6.03718 4.58423C5.91931 4.64387 5.8457 4.76499 5.8457 4.8959V9.12092C5.8457 9.25323 5.91931 9.37389 6.03718 9.43352C6.08703 9.45868 6.14153 9.47126 6.19651 9.47126C6.26825 9.47126 6.34093 9.44843 6.40242 9.40324L9.30948 7.2919C9.40126 7.22435 9.45483 7.11999 9.45483 7.00911C9.4553 6.89637 9.40033 6.79248 9.30948 6.72633Z" />
                                                        <path
                                                            d="M7.00023 0.000976562C3.13347 0.000976562 0 3.13445 0 7.00121C0 10.8666 3.13347 13.9991 7.00023 13.9991C10.8661 13.9991 14 10.8661 14 7.00121C14.0005 3.13445 10.8661 0.000976562 7.00023 0.000976562ZM7.00023 12.8312C3.78011 12.8312 1.16935 10.2218 1.16935 7.00121C1.16935 3.78202 3.78011 1.16939 7.00023 1.16939C10.2199 1.16939 12.8302 3.78155 12.8302 7.00121C12.8307 10.2218 10.2199 12.8312 7.00023 12.8312Z" />
                                                    </g>
                                                </svg>
                                            </span>
                                            <span>
                                                <?php echo esc_html($hero_style_five_secondary_button_text); ?>
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g>
                                                        <path
                                                            d="M9.30948 6.72633L6.40242 4.61312C6.29574 4.53578 6.15411 4.52414 6.03718 4.58423C5.91931 4.64387 5.8457 4.76499 5.8457 4.8959V9.12092C5.8457 9.25323 5.91931 9.37389 6.03718 9.43352C6.08703 9.45868 6.14153 9.47126 6.19651 9.47126C6.26825 9.47126 6.34093 9.44843 6.40242 9.40324L9.30948 7.2919C9.40126 7.22435 9.45483 7.11999 9.45483 7.00911C9.4553 6.89637 9.40033 6.79248 9.30948 6.72633Z" />
                                                        <path
                                                            d="M7.00023 0.000976562C3.13347 0.000976562 0 3.13445 0 7.00121C0 10.8666 3.13347 13.9991 7.00023 13.9991C10.8661 13.9991 14 10.8661 14 7.00121C14.0005 3.13445 10.8661 0.000976562 7.00023 0.000976562ZM7.00023 12.8312C3.78011 12.8312 1.16935 10.2218 1.16935 7.00121C1.16935 3.78202 3.78011 1.16939 7.00023 1.16939C10.2199 1.16939 12.8302 3.78155 12.8302 7.00121C12.8307 10.2218 10.2199 12.8312 7.00023 12.8312Z" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                            <?php if ($hero_style_five_show_features && !empty($hero_style_five_features)) { ?>
                                <ul>
                                    <?php foreach ($hero_style_five_features as $feature_item) {
                                        $feature_icon = !empty($feature_item['feature_icon']['url']) ? $feature_item['feature_icon']['url'] : '';
                                        $feature_title = !empty($feature_item['feature_title']) ? $feature_item['feature_title'] : '';
                                        if (empty($feature_icon) && empty($feature_title)) {
                                            continue;
                                        }
                                    ?>
                                        <li>
                                            <?php if (!empty($feature_icon)) { ?>
                                                <div class="icon">
                                                    <img src="<?php echo esc_url($feature_icon); ?>" alt="<?php echo esc_attr($feature_title); ?>">
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($feature_title)) { ?>
                                                <div class="content">
                                                    <h2><?php echo esc_html($feature_title); ?></h2>
                                                </div>
                                            <?php } ?>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php if ($hero_style_five_show_banner_image && !empty($hero_style_five_banner_image)) { ?>
                    <div class="banner-img-area">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12 d-flex justify-content-center">
                                    <div class="banner-img">
                                        <img src="<?php echo esc_url($hero_style_five_banner_image); ?>" alt="<?php echo esc_attr($hero_style_five_banner_image_alt); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

<?php

    }
}

Plugin::instance()->widgets_manager->register(new Softro_Hero_Widget());
