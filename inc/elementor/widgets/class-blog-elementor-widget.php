<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Egns_Core\Egns_Helper;

class Softro_Blog_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_blog';
    }

    public function get_title()
    {
        return esc_html__('EG Blog', 'softro-core');
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
        $this->register_layout_controls();

        $this->register_style_one_content_controls();
        $this->register_style_two_content_controls();
        $this->register_style_three_content_controls();
        $this->register_style_four_content_controls();

        $this->register_style_controls();
    }

    private function register_layout_controls()
    {
        $this->start_controls_section(
            'softro_blog_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
            ]
        );

        $this->add_control(
            'softro_blog_genaral_style_selection',
            [
                'label'   => esc_html__('Select Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'style_one'   => esc_html__('Style One', 'softro-core'),
                    'style_two'   => esc_html__('Style Two', 'softro-core'),
                    'style_three' => esc_html__('Style Three', 'softro-core'),
                    'style_four'  => esc_html__('Style Four', 'softro-core'),
                ],
                'default' => 'style_one',
            ]
        );

        $this->add_control(
            'softro_blog_shape_displacement_image',
            [
                'label'   => esc_html__('Shape Displacement Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_asset_url('assets/image/start-up/hover-img-shape2.png'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_one_content_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_one_content',
            [
                'label'     => esc_html__('Style One Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_heading_title',
            [
                'label' => esc_html__('Heading', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_heading',
            [
                'label'        => esc_html__('Show Heading', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('News', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Fresh Stories From Our Innovation Hub', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_right_content',
            [
                'label'        => esc_html__('Show Right Content', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_one_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_description',
            [
                'label'       => esc_html__('Right Description', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('News, ideas, and breakthroughs from our team', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_heading'       => 'yes',
                    'softro_blog_style_one_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_view_more_button',
            [
                'label'        => esc_html__('Show View More Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_one_show_heading'       => 'yes',
                    'softro_blog_style_one_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_view_more_text',
            [
                'label'       => esc_html__('View More Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View More', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_heading'          => 'yes',
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_view_more_link',
            [
                'label'   => esc_html__('View More Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_heading'          => 'yes',
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_card_elements_heading',
            [
                'label'     => esc_html__('Card Elements', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_category',
            [
                'label'        => esc_html__('Show Category', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_date',
            [
                'label'        => esc_html__('Show Date', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_date_format',
            [
                'label'       => esc_html__('Date Format', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'd F, Y',
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_one_show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_show_icon',
            [
                'label'        => esc_html__('Show Card Icon', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_empty_text',
            [
                'label'       => esc_html__('Empty Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('No blog posts found.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->register_common_query_controls('style_one', 3);

        $this->end_controls_section();
    }

    private function register_style_two_content_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_two_content',
            [
                'label'     => esc_html__('Style Two Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_heading_title',
            [
                'label' => esc_html__('Heading', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_heading',
            [
                'label'        => esc_html__('Show Heading', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('News', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Fresh Stories From Our Innovation Hub', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_right_content',
            [
                'label'        => esc_html__('Show Right Content', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_two_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_description',
            [
                'label'       => esc_html__('Right Description', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('News, ideas, and breakthroughs from our team', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_heading'       => 'yes',
                    'softro_blog_style_two_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_view_more_button',
            [
                'label'        => esc_html__('Show View More Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_two_show_heading'       => 'yes',
                    'softro_blog_style_two_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_view_more_text',
            [
                'label'       => esc_html__('View More Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View More', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_heading'          => 'yes',
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_view_more_link',
            [
                'label'   => esc_html__('View More Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_heading'          => 'yes',
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_elements_heading',
            [
                'label'     => esc_html__('Card Elements', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_category',
            [
                'label'        => esc_html__('Show Category', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_date',
            [
                'label'        => esc_html__('Show Date', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_date_format',
            [
                'label'       => esc_html__('Date Format', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'd F, Y',
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_two_show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_show_icon',
            [
                'label'        => esc_html__('Show Card Icon', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_read_more_text',
            [
                'label'       => esc_html__('Read More Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Read More', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_blog_style_two_empty_text',
            [
                'label'       => esc_html__('Empty Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('No blog posts found.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->register_common_query_controls('style_two', 3);

        $this->end_controls_section();
    }

    private function register_style_three_content_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_three_content',
            [
                'label'     => esc_html__('Style Three Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_heading_title',
            [
                'label' => esc_html__('Heading', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_three_show_heading',
            [
                'label'        => esc_html__('Show Heading', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Article', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_three_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Insights & Trends In The World', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_three_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_show_view_more_button',
            [
                'label'        => esc_html__('Show View All Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_three_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_view_more_text',
            [
                'label'       => esc_html__('View All Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View All', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_three_show_heading'          => 'yes',
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_view_more_link',
            [
                'label'   => esc_html__('View All Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_three_show_heading'          => 'yes',
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_elements_heading',
            [
                'label'     => esc_html__('Card Elements', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_show_category',
            [
                'label'        => esc_html__('Show Category', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_show_date',
            [
                'label'        => esc_html__('Show Date', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_date_format',
            [
                'label'       => esc_html__('Date Format', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'd F, Y',
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_three_show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_show_icon',
            [
                'label'        => esc_html__('Show Card Icon', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_enable_inner_card_pattern',
            [
                'label'        => esc_html__('Enable Inner Card Pattern', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__('Adds "inner-blog-card blog-card-top" classes on card indices 2, 5, 8, 11...', 'softro-core'),
            ]
        );

        $this->add_control(
            'softro_blog_style_three_read_more_text',
            [
                'label'       => esc_html__('Read More Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Read More', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_blog_style_three_empty_text',
            [
                'label'       => esc_html__('Empty Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('No blog posts found.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->register_common_query_controls('style_three', 3);

        $this->end_controls_section();
    }

    private function register_style_four_content_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_four_content',
            [
                'label'     => esc_html__('Style Four Content', 'softro-core'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_four',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_heading_title',
            [
                'label' => esc_html__('Heading', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_four_show_heading',
            [
                'label'        => esc_html__('Show Heading', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Article', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Agency Journal', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_description',
            [
                'label'       => esc_html__('Description', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('A one-liner about what visitors will find latest design talks', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_show_view_more_button',
            [
                'label'        => esc_html__('Show View All Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_blog_style_four_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_view_more_text',
            [
                'label'       => esc_html__('View All Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View All', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_heading'          => 'yes',
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_view_more_link',
            [
                'label'   => esc_html__('View All Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_heading'          => 'yes',
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_elements_heading',
            [
                'label'     => esc_html__('Card Elements', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_show_category',
            [
                'label'        => esc_html__('Show Category', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_show_date',
            [
                'label'        => esc_html__('Show Date', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_date_format',
            [
                'label'       => esc_html__('Date Format', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'd F, Y',
                'label_block' => true,
                'condition'   => [
                    'softro_blog_style_four_show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_read_more_text',
            [
                'label'       => esc_html__('Read More Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Read More', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_blog_style_four_empty_text',
            [
                'label'       => esc_html__('Empty Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('No blog posts found.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->register_common_query_controls('style_four', 2);

        $this->end_controls_section();
    }

    private function register_common_query_controls($style_key, $default_posts = 3)
    {
        $prefix = 'softro_blog_' . $style_key;

        $this->add_control(
            $prefix . '_posts_per_page',
            [
                'label'   => esc_html__('Posts Per Page', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => $default_posts,
                'min'     => 1,
            ]
        );

        $this->add_control(
            $prefix . '_show_pagination',
            [
                'label'        => esc_html__('Show Pagination', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            $prefix . '_selected_category',
            [
                'label'       => esc_html__('Select Categories', 'softro-core'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => Egns_Helper::get_categories_for_select(),
            ]
        );

        $this->add_control(
            $prefix . '_selected_post',
            [
                'label'       => esc_html__('Select Posts', 'softro-core'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => Egns_Helper::get_blog_post_options(),
            ]
        );

        $this->add_control(
            $prefix . '_orderby',
            [
                'label'   => esc_html__('Order By', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'ID'         => esc_html__('Post ID', 'softro-core'),
                    'author'     => esc_html__('Post Author', 'softro-core'),
                    'title'      => esc_html__('Title', 'softro-core'),
                    'date'       => esc_html__('Date', 'softro-core'),
                    'rand'       => esc_html__('Random', 'softro-core'),
                    'menu_order' => esc_html__('Menu Order', 'softro-core'),
                ],
            ]
        );

        $this->add_control(
            $prefix . '_order',
            [
                'label'   => esc_html__('Order', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC'  => esc_html__('Ascending', 'softro-core'),
                    'DESC' => esc_html__('Descending', 'softro-core'),
                ],
            ]
        );
    }

    private function register_style_controls()
    {
        $this->register_style_one_section_style_controls();
        $this->register_style_one_heading_style_controls();
        $this->register_style_one_card_style_controls();
        $this->register_pagination_style_controls('style_one', esc_html__('Style One Pagination', 'softro-core'));

        $this->register_style_two_section_style_controls();
        $this->register_style_two_heading_style_controls();
        $this->register_style_two_card_style_controls();
        $this->register_pagination_style_controls('style_two', esc_html__('Style Two Pagination', 'softro-core'));

        $this->register_style_three_section_style_controls();
        $this->register_style_three_heading_style_controls();
        $this->register_style_three_card_style_controls();
        $this->register_pagination_style_controls('style_three', esc_html__('Style Three Pagination', 'softro-core'));

        $this->register_style_four_section_style_controls();
        $this->register_style_four_heading_style_controls();
        $this->register_style_four_card_style_controls();
        $this->register_pagination_style_controls('style_four', esc_html__('Style Four Pagination', 'softro-core'));
    }

    private function register_pagination_style_controls($style_key, $section_label)
    {
        $prefix = 'softro_blog_' . $style_key;

        $this->start_controls_section(
            $prefix . '_pagination_style',
            [
                'label'     => $section_label,
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => $style_key,
                    $prefix . '_show_pagination'          => 'yes',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_area_heading',
            [
                'label' => esc_html__('Pagination Area', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            $prefix . '_pagination_margin_top',
            [
                'label'      => esc_html__('Top Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                    '%'  => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_pagination_gap',
            [
                'label'      => esc_html__('Items Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_numbers_heading',
            [
                'label'     => esc_html__('Page Numbers', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $prefix . '_pagination_number_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a',
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_color',
            [
                'label'     => esc_html__('Number Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_bg',
            [
                'label'     => esc_html__('Number Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_border_color',
            [
                'label'     => esc_html__('Number Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_pagination_number_radius',
            [
                'label'      => esc_html__('Number Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_hover_color',
            [
                'label'     => esc_html__('Number Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_hover_bg',
            [
                'label'     => esc_html__('Number Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_hover_border_color',
            [
                'label'     => esc_html__('Number Hover Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_active_color',
            [
                'label'     => esc_html__('Number Active Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item.active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_active_bg',
            [
                'label'     => esc_html__('Number Active Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item.active a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_number_active_border_color',
            [
                'label'     => esc_html__('Number Active Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations .page-item.active a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_heading',
            [
                'label'     => esc_html__('Prev/Next Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $prefix . '_pagination_button_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a',
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_color',
            [
                'label'     => esc_html__('Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_icon_color',
            [
                'label'     => esc_html__('Button Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_bg',
            [
                'label'     => esc_html__('Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_border_color',
            [
                'label'     => esc_html__('Button Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_pagination_button_radius',
            [
                'label'      => esc_html__('Button Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            $prefix . '_pagination_button_padding',
            [
                'label'      => esc_html__('Button Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_hover_icon_color',
            [
                'label'     => esc_html__('Button Hover Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a:hover svg'      => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a:hover svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_hover_bg',
            [
                'label'     => esc_html__('Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            $prefix . '_pagination_button_hover_border_color',
            [
                'label'     => esc_html__('Button Hover Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .pagination-area .paginations-button a:hover' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_one_section_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_one_section_style',
            [
                'label'     => esc_html__('Style One Section', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_section_heading',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_blog_style_one_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .home1-blog-section',
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_one_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_one_heading_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_one_heading_style',
            [
                'label'     => esc_html__('Style One Heading', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_one',
                    'softro_blog_style_one_show_heading'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_subtitle_heading_style',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_one_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_subtitle_border_color',
            [
                'label'     => esc_html__('Subtitle Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three > span' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_one_subtitle_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.three > span',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_one_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.three h2',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_description_heading_style',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_one_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_description_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_one_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_one_description_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .section-title.three .right-content p',
                'condition' => [
                    'softro_blog_style_one_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_button_heading_style',
            [
                'label'     => esc_html__('Top Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn .arrow' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_button_line_color',
            [
                'label'     => esc_html__('Button Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_one_button_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn',
                'condition' => [
                    'softro_blog_style_one_show_right_content'    => 'yes',
                    'softro_blog_style_one_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_one_card_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_one_card_style',
            [
                'label'     => esc_html__('Style One Card', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_image_heading_style',
            [
                'label' => esc_html__('Image', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_one_image_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .blog-img'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .blog-img img'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .blog-img canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_blog_style_one_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .blog-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );


        $this->add_control(
            'softro_blog_style_one_icon_heading_style',
            [
                'label'     => esc_html__('Card Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_one_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_icon_bg_color',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_one_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-image-wrap .icon > a svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_one_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_meta_heading_style',
            [
                'label'     => esc_html__('Meta', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_meta_color',
            [
                'label'     => esc_html__('Meta Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content .blog-meta li'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content .blog-meta li a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content .blog-meta li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_meta_hover_color',
            [
                'label'     => esc_html__('Meta Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content .blog-meta li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_one_meta_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card .blog-content .blog-meta li a',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_card_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_one_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content h2 a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_one_title_hover_color',
            [
                'label'     => esc_html__('Title Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content h2 a'       => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 0px 1.5px; background-position: right 95%;',
                    '{{WRAPPER}} .home1-blog-section .blog-card .blog-content h2 a:hover' => 'background-size: 100% 1.5px; background-position: 0 95%;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_one_card_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card .blog-content h2 a',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_two_section_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_two_section_style',
            [
                'label'     => esc_html__('Style Two Section', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_section_heading',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_blog_style_two_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .home1-blog-section',
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_two_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_two_heading_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_two_heading_style',
            [
                'label'     => esc_html__('Style Two Heading', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_two',
                    'softro_blog_style_two_show_heading'  => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_subtitle_heading_style',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_two_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .left-content > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_subtitle_border_color',
            [
                'label'     => esc_html__('Subtitle Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .left-content > span' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_two_subtitle_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.three .left-content > span',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .left-content h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_two_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.three .left-content h2',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_description_heading_style',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_two_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_description_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_two_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_two_description_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .section-title.three .right-content p',
                'condition' => [
                    'softro_blog_style_two_show_right_content' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_button_heading_style',
            [
                'label'     => esc_html__('Top Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn .arrow' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_button_line_color',
            [
                'label'     => esc_html__('Button Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_two_button_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .section-title.three .right-content .view-more-btn',
                'condition' => [
                    'softro_blog_style_two_show_right_content'    => 'yes',
                    'softro_blog_style_two_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_two_card_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_two_card_style',
            [
                'label'     => esc_html__('Style Two Card', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_wrapper_heading_style',
            [
                'label' => esc_html__('Card Wrapper', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_bg_color',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_border_color',
            [
                'label'     => esc_html__('Card Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_two_card_radius',
            [
                'label'      => esc_html__('Card Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_image_heading_style',
            [
                'label'     => esc_html__('Image', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_two_image_radius',
            [
                'label'      => esc_html__('Image Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .blog-img'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .blog-img img'    => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .blog-img canvas' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_blog_style_two_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 1000,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .blog-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );


        $this->add_control(
            'softro_blog_style_two_icon_heading_style',
            [
                'label'     => esc_html__('Card Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_two_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_icon_bg_color',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_two_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-image-wrap .icon > a svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_two_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_meta_heading_style',
            [
                'label'     => esc_html__('Meta', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_meta_color',
            [
                'label'     => esc_html__('Meta Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .blog-meta li'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .blog-meta li a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .blog-meta li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_meta_hover_color',
            [
                'label'     => esc_html__('Meta Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .blog-meta li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_two_meta_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .blog-meta li',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content h2 a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_title_hover_color',
            [
                'label'     => esc_html__('Title Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content h2 a'       => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 0px 1.5px; background-position: right 95%;',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content h2 a:hover' => 'background-size: 100% 1.5px; background-position: 0 95%;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_two_card_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content h2 a',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_button_heading_style',
            [
                'label'     => esc_html__('Read More Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .view-details-btn'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .view-details-btn .arrow' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_two_card_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .view-details-btn:hover'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .view-details-btn:hover .arrow' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_two_card_button_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2 .blog-content .view-details-btn',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_three_section_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_three_section_style',
            [
                'label'     => esc_html__('Style Three Section', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_section_heading',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_blog_style_three_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .home1-blog-section',
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_three_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_three_heading_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_three_heading_style',
            [
                'label'     => esc_html__('Style Three Heading', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection'  => 'style_three',
                    'softro_blog_style_three_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_subtitle_heading_style',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_three_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home3-section-title.three > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_subtitle_border_color',
            [
                'label'     => esc_html__('Subtitle Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home3-section-title.three > span' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_three_subtitle_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.home3-section-title.three > span',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home3-section-title.three h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_three_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.home3-section-title.three h2',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_button_heading_style',
            [
                'label'     => esc_html__('Top Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2 .borders' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_blog_style_three_button_line_color',
            [
                'label'     => esc_html__('Button Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2 .borders' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_three_button_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2',
                'condition' => [
                    'softro_blog_style_three_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_three_card_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_three_card_style',
            [
                'label'     => esc_html__('Style Three Card', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_wrapper_heading_style',
            [
                'label' => esc_html__('Card Wrapper', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_bg_color',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_border_color',
            [
                'label'     => esc_html__('Card Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_three_card_radius',
            [
                'label'      => esc_html__('Card Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_images_heading_style',
            [
                'label'     => esc_html__('Card Images', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'softro_blog_style_three_image_radius',
            [
                'label'      => esc_html__('Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .blog-card.style-2 .blog-image-wrap .blog-img'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .blog-card.style-2 .blog-image-wrap .blog-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_blog_style_three_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem'],

                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 1500,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .blog-card.style-2 .blog-image-wrap .blog-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );
        $this->add_control(
            'softro_blog_style_three_icon_heading_style',
            [
                'label'     => esc_html__('Card Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_three_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_icon_bg_color',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-image-wrap .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_three_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-image-wrap .icon > a svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_three_show_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_meta_heading_style',
            [
                'label'     => esc_html__('Meta', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_meta_color',
            [
                'label'     => esc_html__('Meta Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .blog-meta li'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .blog-meta li a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .blog-meta li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_meta_hover_color',
            [
                'label'     => esc_html__('Meta Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .blog-meta li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_three_meta_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .blog-meta li a',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content h2 a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_title_hover_color',
            [
                'label'     => esc_html__('Title Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content h2 a'       => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 0px 1.5px; background-position: right 95%;',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content h2 a:hover' => 'background-size: 100% 1.5px; background-position: 0 95%;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_three_card_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content h2 a',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_heading_style',
            [
                'label'     => esc_html__('Read More Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .content'       => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_content_bg_color',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .icon'    => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_content_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .content' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .icon'    => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .content'       => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .icon svg path' => 'stroke: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_content_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .content' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .icon'    => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_three_card_button_content_hover_border_color',
            [
                'label'     => esc_html__('Content Hover Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .content' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent:hover .icon'    => 'border-color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_three_card_button_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home3-blog-card .blog-content .primary-btn2.transparent .content',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_four_section_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_four_section_style',
            [
                'label'     => esc_html__('Style Four Section', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_four',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_section_heading',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_blog_style_four_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .home1-blog-section',
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_four_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_four_heading_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_four_heading_style',
            [
                'label'     => esc_html__('Style Four Heading', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_four',
                    'softro_blog_style_four_show_heading' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_subtitle_heading_style',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_blog_style_four_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title > span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_subtitle_border_color',
            [
                'label'     => esc_html__('Subtitle Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title > span' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_subtitle_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title > span',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title .left-content h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title .left-content h2',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_description_heading_style',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_description_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title .left-content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_description_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .section-title.home4-section-title .left-content p',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_button_heading_style',
            [
                'label'     => esc_html__('Top Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2'          => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2 .borders' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'softro_blog_style_four_button_line_color',
            [
                'label'     => esc_html__('Button Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2 .borders' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_blog_style_four_button_typography',
                'selector'  => '{{WRAPPER}} .home1-blog-section .right-content .view-more-btn.style-2',
                'condition' => [
                    'softro_blog_style_four_show_view_more_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_four_card_style_controls()
    {
        $this->start_controls_section(
            'softro_blog_style_four_card_style',
            [
                'label'     => esc_html__('Style Four Card', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_blog_genaral_style_selection' => 'style_four',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_image_heading_style',
            [
                'label' => esc_html__('Image', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'softro_blog_style_four_image_radius',
            [
                'label'      => esc_html__('Image Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'unit'     => 'px',
                    'top'      => '',
                    'right'    => '',
                    'bottom'   => '',
                    'left'     => '',
                    'isLinked' => true,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-image-wrap .blog-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-image-wrap .blog-img'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_blog_style_four_image_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh', 'em', 'rem'],

                'range'      => [
                    'px' => [
                        'min' => 1,
                        'max' => 1500,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                    'rem' => [
                        'min' => 1,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-image-wrap .blog-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover; object-position: center;',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_meta_heading_style',
            [
                'label'     => esc_html__('Meta', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_meta_color',
            [
                'label'     => esc_html__('Meta Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .blog-meta li'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .blog-meta li a'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .blog-meta li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_meta_hover_color',
            [
                'label'     => esc_html__('Meta Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .blog-meta li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_meta_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .blog-meta li a',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_title_heading_style',
            [
                'label'     => esc_html__('Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content h2 a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_title_hover_color',
            [
                'label'     => esc_html__('Title Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content h2 a'       => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 0px 1.5px; background-position: right 95%;',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content h2 a:hover' => 'background-size: 100% 1.5px; background-position: 0 95%;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_card_title_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content h2 a',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_button_heading_style',
            [
                'label'     => esc_html__('Read More Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .view-details-btn'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .view-details-btn .arrow' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_blog_style_four_card_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .view-details-btn:hover'        => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .view-details-btn:hover .arrow' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_blog_style_four_card_button_typography',
                'selector' => '{{WRAPPER}} .home1-blog-section .blog-card.style-2.home4-blog-card .blog-content .view-details-btn',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $style    = !empty($settings['softro_blog_genaral_style_selection']) ? $settings['softro_blog_genaral_style_selection'] : 'style_one';

        if ($style === 'style_two') {
            $this->render_style_two($settings);
            return;
        }

        if ($style === 'style_three') {
            $this->render_style_three($settings);
            return;
        }

        if ($style === 'style_four') {
            $this->render_style_four($settings);
            return;
        }

        $this->render_style_one($settings);
    }

    private function render_style_one($settings)
    {
        $query = $this->get_query('style_one', $settings);

        $show_heading       = !empty($settings['softro_blog_style_one_show_heading']) && $settings['softro_blog_style_one_show_heading']             === 'yes';
        $subtitle           = !empty($settings['softro_blog_style_one_subtitle']) ? $settings['softro_blog_style_one_subtitle'] : '';
        $title              = !empty($settings['softro_blog_style_one_title']) ? $settings['softro_blog_style_one_title'] : '';
        $show_right_content = !empty($settings['softro_blog_style_one_show_right_content']) && $settings['softro_blog_style_one_show_right_content'] === 'yes';
        $description        = !empty($settings['softro_blog_style_one_description']) ? $settings['softro_blog_style_one_description'] : '';

        $show_button = !empty($settings['softro_blog_style_one_show_view_more_button']) && $settings['softro_blog_style_one_show_view_more_button'] === 'yes';
        $button_text = !empty($settings['softro_blog_style_one_view_more_text']) ? $settings['softro_blog_style_one_view_more_text'] : '';
        $button_link = $this->get_link_data(!empty($settings['softro_blog_style_one_view_more_link']) ? $settings['softro_blog_style_one_view_more_link'] : []);

        $show_icon          = !empty($settings['softro_blog_style_one_show_icon']) && $settings['softro_blog_style_one_show_icon'] === 'yes';
        $empty_text         = !empty($settings['softro_blog_style_one_empty_text']) ? $settings['softro_blog_style_one_empty_text'] : '';
        $show_pagination    = $this->is_pagination_enabled('style_one', $settings);
        $shape_displacement = $this->get_shape_displacement_url($settings);

?>
        <div class="home1-blog-section">
            <div class="container">
                <?php if ($show_heading): ?>
                    <div class="row">
                        <div class="col-lg-12 mb-60">
                            <div class="section-title three">
                                <?php if (!empty($subtitle)): ?>
                                    <span><?php echo esc_html($subtitle); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($title)): ?>
                                    <h2 class="text-anim"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                                <?php if ($show_right_content): ?>
                                    <div class="right-content">
                                        <?php if (!empty($description)): ?>
                                            <p class="text-anim"><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                        <?php if ($show_button && !empty($button_text)): ?>
                                            <?php $this->render_top_button_arrow($button_text, $button_link); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row gy-5 gx-xl-4 gx-3">
                    <?php if ($query->have_posts()): ?>
                        <?php $index = 0; ?>
                        <?php while ($query->have_posts()): $query->the_post(); ?>
                            <?php
                            $post_id    = get_the_ID();
                            $permalink  = get_permalink($post_id);
                            $post_title = get_the_title($post_id);

                            $image_data = $this->get_post_image_data($post_id);
                            $delay      = $this->get_fade_delay($index);
                            ?>
                            <div class="col-lg-4 col-md-6 fade_anim" data-delay="<?php echo esc_attr($delay); ?>">
                                <div class="blog-card">
                                    <div class="blog-image-wrap">
                                        <a class="blog-img shape-hover-item" href="<?php echo esc_url($permalink); ?>">
                                            <div class="shape-hover-img" data-displacement="<?php echo esc_url($shape_displacement); ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
                                                <img src="<?php echo esc_url($image_data['url']); ?>" alt="<?php echo esc_attr($image_data['alt']); ?>">
                                            </div>
                                        </a>
                                        <?php if ($show_icon): ?>
                                            <div class="icon">
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <?php $this->render_card_icon_arrow(); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-content">
                                        <?php $this->render_post_meta($post_id, $settings, 'style_one', false); ?>
                                        <h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post_title); ?></a></h2>
                                    </div>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php elseif (!empty($empty_text)): ?>
                        <div class="col-12">
                            <p><?php echo esc_html($empty_text); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($show_pagination): ?>
                    <?php $this->render_pagination($query); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    private function render_style_two($settings)
    {
        $query = $this->get_query('style_two', $settings);

        $show_heading       = !empty($settings['softro_blog_style_two_show_heading']) && $settings['softro_blog_style_two_show_heading']             === 'yes';
        $subtitle           = !empty($settings['softro_blog_style_two_subtitle']) ? $settings['softro_blog_style_two_subtitle'] : '';
        $title              = !empty($settings['softro_blog_style_two_title']) ? $settings['softro_blog_style_two_title'] : '';
        $show_right_content = !empty($settings['softro_blog_style_two_show_right_content']) && $settings['softro_blog_style_two_show_right_content'] === 'yes';
        $description        = !empty($settings['softro_blog_style_two_description']) ? $settings['softro_blog_style_two_description'] : '';

        $show_button = !empty($settings['softro_blog_style_two_show_view_more_button']) && $settings['softro_blog_style_two_show_view_more_button'] === 'yes';
        $button_text = !empty($settings['softro_blog_style_two_view_more_text']) ? $settings['softro_blog_style_two_view_more_text'] : '';
        $button_link = $this->get_link_data(!empty($settings['softro_blog_style_two_view_more_link']) ? $settings['softro_blog_style_two_view_more_link'] : []);

        $show_icon          = !empty($settings['softro_blog_style_two_show_icon']) && $settings['softro_blog_style_two_show_icon'] === 'yes';
        $read_more_text     = !empty($settings['softro_blog_style_two_read_more_text']) ? $settings['softro_blog_style_two_read_more_text'] : '';
        $empty_text         = !empty($settings['softro_blog_style_two_empty_text']) ? $settings['softro_blog_style_two_empty_text'] : '';
        $show_pagination    = $this->is_pagination_enabled('style_two', $settings);
        $shape_displacement = $this->get_shape_displacement_url($settings);

    ?>
        <div class="home1-blog-section">
            <div class="container">
                <?php if ($show_heading): ?>
                    <div class="row">
                        <div class="col-lg-12 mb-60">
                            <div class="section-title three">
                                <div class="left-content">
                                    <?php if (!empty($subtitle)): ?>
                                        <span><?php echo esc_html($subtitle); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($title)): ?>
                                        <h2 class="text-anim"><?php echo esc_html($title); ?></h2>
                                    <?php endif; ?>
                                </div>
                                <?php if ($show_right_content): ?>
                                    <div class="right-content">
                                        <?php if (!empty($description)): ?>
                                            <p class="text-anim"><?php echo esc_html($description); ?></p>
                                        <?php endif; ?>
                                        <?php if ($show_button && !empty($button_text)): ?>
                                            <?php $this->render_top_button_arrow($button_text, $button_link); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="row gy-4 gx-xxl-4 gx-3">
                    <?php if ($query->have_posts()): ?>
                        <?php $index = 0; ?>
                        <?php while ($query->have_posts()): $query->the_post(); ?>
                            <?php
                            $post_id    = get_the_ID();
                            $permalink  = get_permalink($post_id);
                            $post_title = get_the_title($post_id);
                            $image_data = $this->get_post_image_data($post_id);
                            $delay      = $this->get_fade_delay($index);
                            ?>
                            <div class="col-lg-4 col-md-6 fade_anim" data-delay="<?php echo esc_attr($delay); ?>">
                                <div class="blog-card style-2">
                                    <div class="blog-image-wrap">
                                        <a class="blog-img shape-hover-item" href="<?php echo esc_url($permalink); ?>">
                                            <div class="shape-hover-img" data-displacement="<?php echo esc_url($shape_displacement); ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
                                                <img src="<?php echo esc_url($image_data['url']); ?>" alt="<?php echo esc_attr($image_data['alt']); ?>">
                                            </div>
                                        </a>
                                        <?php if ($show_icon): ?>
                                            <div class="icon">
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <?php $this->render_card_icon_arrow(); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-content">
                                        <?php $this->render_post_meta($post_id, $settings, 'style_two', false); ?>
                                        <h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post_title); ?></a></h2>
                                        <?php if (!empty($read_more_text)): ?>
                                            <a href="<?php echo esc_url($permalink); ?>" class="view-details-btn">
                                                <?php echo esc_html($read_more_text); ?>
                                                <?php $this->render_read_more_arrow(); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php elseif (!empty($empty_text)): ?>
                        <div class="col-12">
                            <p><?php echo esc_html($empty_text); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($show_pagination): ?>
                    <?php $this->render_pagination($query); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    private function render_style_three($settings)
    {
        $query = $this->get_query('style_three', $settings);

        $show_heading = !empty($settings['softro_blog_style_three_show_heading']) && $settings['softro_blog_style_three_show_heading'] === 'yes';
        $subtitle     = !empty($settings['softro_blog_style_three_subtitle']) ? $settings['softro_blog_style_three_subtitle'] : '';
        $title        = !empty($settings['softro_blog_style_three_title']) ? $settings['softro_blog_style_three_title'] : '';

        $show_button = !empty($settings['softro_blog_style_three_show_view_more_button']) && $settings['softro_blog_style_three_show_view_more_button'] === 'yes';
        $button_text = !empty($settings['softro_blog_style_three_view_more_text']) ? $settings['softro_blog_style_three_view_more_text'] : '';
        $button_link = $this->get_link_data(!empty($settings['softro_blog_style_three_view_more_link']) ? $settings['softro_blog_style_three_view_more_link'] : []);

        $show_icon          = !empty($settings['softro_blog_style_three_show_icon']) && $settings['softro_blog_style_three_show_icon'] === 'yes';
        $enable_inner_card_pattern = !empty($settings['softro_blog_style_three_enable_inner_card_pattern']) && $settings['softro_blog_style_three_enable_inner_card_pattern'] === 'yes';
        $read_more_text     = !empty($settings['softro_blog_style_three_read_more_text']) ? $settings['softro_blog_style_three_read_more_text'] : '';
        $empty_text         = !empty($settings['softro_blog_style_three_empty_text']) ? $settings['softro_blog_style_three_empty_text'] : '';
        $show_pagination    = $this->is_pagination_enabled('style_three', $settings);
        $shape_displacement = $this->get_shape_displacement_url($settings);

    ?>
        <div class="home1-blog-section">
            <div class="container">
                <?php if ($show_heading): ?>
                    <div class="row gy-3 mb-60 justify-content-between">
                        <div class="col-lg-7">
                            <div class="section-title home3-section-title three">
                                <?php if (!empty($subtitle)): ?>
                                    <span><?php echo esc_html($subtitle); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($title)): ?>
                                    <h2 class="text-anim"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($show_button && !empty($button_text)): ?>
                            <div class="col-lg-5">
                                <div class="right-content">
                                    <?php $this->render_top_button_line($button_text, $button_link); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="row gy-5 gx-xxl-4 gx-3">
                    <?php if ($query->have_posts()): ?>
                        <?php $index = 0; ?>
                        <?php while ($query->have_posts()): $query->the_post(); ?>
                            <?php
                            $post_id    = get_the_ID();
                            $permalink  = get_permalink($post_id);
                            $post_title = get_the_title($post_id);
                            $image_data = $this->get_post_image_data($post_id);
                            $delay      = $this->get_fade_delay($index);
                            $card_extra_classes = '';

                            if ($enable_inner_card_pattern && (($index + 1) % 3 === 2)) {
                                $card_extra_classes = ' inner-blog-card blog-card-top';
                            }
                            ?>
                            <div class="col-lg-4 col-md-6 fade_anim" data-delay="<?php echo esc_attr($delay); ?>">
                                <div class="blog-card style-2 home3-blog-card<?php echo esc_attr($card_extra_classes); ?>">
                                    <div class="blog-image-wrap">
                                        <a class="blog-img shape-hover-item" href="<?php echo esc_url($permalink); ?>">
                                            <div class="shape-hover-img" data-displacement="<?php echo esc_url($shape_displacement); ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
                                                <img src="<?php echo esc_url($image_data['url']); ?>" alt="<?php echo esc_attr($image_data['alt']); ?>">
                                            </div>
                                        </a>
                                        <?php if ($show_icon): ?>
                                            <div class="icon">
                                                <a href="<?php echo esc_url($permalink); ?>">
                                                    <?php $this->render_card_icon_arrow(); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-content">
                                        <?php $this->render_post_meta($post_id, $settings, 'style_three', false); ?>
                                        <h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post_title); ?></a></h2>
                                        <?php if (!empty($read_more_text)): ?>
                                            <a class="primary-btn2 transparent" href="<?php echo esc_url($permalink); ?>">
                                                <?php $this->render_primary_btn2_icon(); ?>
                                                <span class="content"><?php echo esc_html($read_more_text); ?></span>
                                                <span class="icon two">
                                                    <?php $this->render_primary_btn2_icon_path(); ?>
                                                </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php elseif (!empty($empty_text)): ?>
                        <div class="col-12">
                            <p><?php echo esc_html($empty_text); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($show_pagination): ?>
                    <?php $this->render_pagination($query); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    private function render_style_four($settings)
    {
        $query = $this->get_query('style_four', $settings);

        $show_heading = !empty($settings['softro_blog_style_four_show_heading']) && $settings['softro_blog_style_four_show_heading'] === 'yes';
        $subtitle     = !empty($settings['softro_blog_style_four_subtitle']) ? $settings['softro_blog_style_four_subtitle'] : '';
        $title        = !empty($settings['softro_blog_style_four_title']) ? $settings['softro_blog_style_four_title'] : '';
        $description  = !empty($settings['softro_blog_style_four_description']) ? $settings['softro_blog_style_four_description'] : '';

        $show_button = !empty($settings['softro_blog_style_four_show_view_more_button']) && $settings['softro_blog_style_four_show_view_more_button'] === 'yes';
        $button_text = !empty($settings['softro_blog_style_four_view_more_text']) ? $settings['softro_blog_style_four_view_more_text'] : '';
        $button_link = $this->get_link_data(!empty($settings['softro_blog_style_four_view_more_link']) ? $settings['softro_blog_style_four_view_more_link'] : []);

        $read_more_text     = !empty($settings['softro_blog_style_four_read_more_text']) ? $settings['softro_blog_style_four_read_more_text'] : '';
        $empty_text         = !empty($settings['softro_blog_style_four_empty_text']) ? $settings['softro_blog_style_four_empty_text'] : '';
        $show_pagination    = $this->is_pagination_enabled('style_four', $settings);
        $shape_displacement = $this->get_shape_displacement_url($settings);

    ?>
        <div class="home1-blog-section">
            <div class="container">
                <?php if ($show_heading): ?>
                    <div class="row gy-3 mb-60 justify-content-between">
                        <div class="col-lg-7">
                            <div class="section-title home4-section-title">
                                <?php if (!empty($subtitle)): ?>
                                    <span><?php echo esc_html($subtitle); ?></span>
                                <?php endif; ?>
                                <div class="left-content">
                                    <?php if (!empty($title)): ?>
                                        <h2 class="text-anim"><?php echo esc_html($title); ?></h2>
                                    <?php endif; ?>
                                    <?php if (!empty($description)): ?>
                                        <p class="text-anim"><?php echo esc_html($description); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php if ($show_button && !empty($button_text)): ?>
                            <div class="col-lg-5">
                                <div class="right-content">
                                    <?php $this->render_top_button_line($button_text, $button_link); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="row gy-5">
                    <?php if ($query->have_posts()): ?>
                        <?php $index = 0; ?>
                        <?php while ($query->have_posts()): $query->the_post(); ?>
                            <?php
                            $post_id    = get_the_ID();
                            $permalink  = get_permalink($post_id);
                            $post_title = get_the_title($post_id);
                            $image_data = $this->get_post_image_data($post_id);
                            $delay      = $this->get_fade_delay($index);
                            $extra_col  = ($index % 2 !== 0) ? ' d-lg-flex justify-content-lg-end' : '';
                            ?>
                            <div class="col-lg-6 col-sm-6 fade_anim<?php echo esc_attr($extra_col); ?>" data-delay="<?php echo esc_attr($delay); ?>">
                                <div class="blog-card style-2 home4-blog-card">
                                    <div class="blog-image-wrap">
                                        <a class="blog-img shape-hover-item" href="<?php echo esc_url($permalink); ?>">
                                            <div class="shape-hover-img" data-displacement="<?php echo esc_url($shape_displacement); ?>" data-intensity="0.6" data-speedin="1" data-speedout="1">
                                                <?php the_post_thumbnail('blog-tall') ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <?php $this->render_post_meta($post_id, $settings, 'style_four', true); ?>
                                        <h2><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post_title); ?></a></h2>
                                        <?php if (!empty($read_more_text)): ?>
                                            <a href="<?php echo esc_url($permalink); ?>" class="view-details-btn">
                                                <?php echo esc_html($read_more_text); ?>
                                                <?php $this->render_read_more_arrow(); ?>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $index++; ?>
                        <?php endwhile; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php elseif (!empty($empty_text)): ?>
                        <div class="col-12">
                            <p><?php echo esc_html($empty_text); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($show_pagination): ?>
                    <?php $this->render_pagination($query); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php
    }

    private function is_pagination_enabled($style_key, $settings)
    {
        $control_key = 'softro_blog_' . $style_key . '_show_pagination';

        return !empty($settings[$control_key]) && $settings[$control_key] === 'yes';
    }

    private function get_query($style_key, $settings)
    {
        $prefix = 'softro_blog_' . $style_key;

        $limit = !empty($settings[$prefix . '_posts_per_page']) ? absint($settings[$prefix . '_posts_per_page']) : 3;
        if ($limit < 1) {
            $limit = 1;
        }

        $orderby = !empty($settings[$prefix . '_orderby']) ? $settings[$prefix . '_orderby'] : 'date';
        $orderby = in_array($orderby, ['ID', 'author', 'title', 'date', 'rand', 'menu_order'], true) ? $orderby : 'date';

        $order = !empty($settings[$prefix . '_order']) ? strtoupper($settings[$prefix . '_order']) : 'DESC';
        $order = in_array($order, ['ASC', 'DESC'], true) ? $order : 'DESC';

        $selected_posts = $this->sanitize_ids(!empty($settings[$prefix . '_selected_post']) ? $settings[$prefix . '_selected_post'] : []);
        $selected_cats  = $this->sanitize_ids(!empty($settings[$prefix . '_selected_category']) ? $settings[$prefix . '_selected_category'] : []);

        $args = [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'orderby'             => $orderby,
            'order'               => $order,
            'paged'               => $this->get_current_paged(),
            'ignore_sticky_posts' => true,
        ];

        if (!empty($selected_posts)) {
            $args['post__in'] = $selected_posts;
        }

        if (!empty($selected_cats)) {
            $args['category__in'] = $selected_cats;
        }

        return new \WP_Query($args);
    }

    private function get_current_paged()
    {
        $paged = get_query_var('paged');

        if (empty($paged)) {
            $paged = get_query_var('page');
        }

        $paged = absint($paged);

        return $paged > 0 ? $paged : 1;
    }

    private function render_pagination($query)
    {
        if (!($query instanceof \WP_Query)) {
            return;
        }

        $total_pages = (int) $query->max_num_pages;

        if ($total_pages < 2) {
            return;
        }

        $current_page = $this->get_current_paged();

        if ($current_page > $total_pages) {
            $current_page = $total_pages;
        }

        $prev_url = $current_page > 1 ? get_pagenum_link($current_page - 1) : '';
        $next_url = $current_page < $total_pages ? get_pagenum_link($current_page + 1) : '';
    ?>
        <div class="row justify-content-center mt-100">
            <div class="col-lg-8">
                <div class="pagination-area">
                    <?php if (!empty($prev_url)) : ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url($prev_url); ?>">
                                <svg width="7" height="14" viewBox="0 0 7 14" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 7.00008L7 0L2.54545 7.00008L7 14L0 7.00008Z" />
                                </svg>
                                <?php echo esc_html__('Prev', 'softro-core'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <ul class="paginations">
                        <?php for ($page_number = 1; $page_number <= $total_pages; $page_number++) : ?>
                            <?php
                            $is_active = $page_number === $current_page;
                            $page_url  = get_pagenum_link($page_number);
                            ?>
                            <li class="page-item<?php echo $is_active ? ' active' : ''; ?>">
                                <a href="<?php echo esc_url($page_url); ?>"><?php echo esc_html(sprintf('%02d', $page_number)); ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>

                    <?php if (!empty($next_url)) : ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url($next_url); ?>">
                                <?php echo esc_html__('Next', 'softro-core'); ?>
                                <svg width="7" height="14" viewBox="0 0 7 14" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 7.00008L0 0L4.45455 7.00008L0 14L7 7.00008Z" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    }

    private function render_post_meta($post_id, $settings, $style_key, $use_small_line_divider = false)
    {
        $show_category = !empty($settings['softro_blog_' . $style_key . '_show_category']) && $settings['softro_blog_' . $style_key . '_show_category'] === 'yes';
        $show_date     = !empty($settings['softro_blog_' . $style_key . '_show_date']) && $settings['softro_blog_' . $style_key . '_show_date']         === 'yes';

        $date_format = !empty($settings['softro_blog_' . $style_key . '_date_format']) ? $settings['softro_blog_' . $style_key . '_date_format'] : 'd F, Y';

        $category = $this->get_primary_category($post_id);
        $date     = get_the_date($date_format, $post_id);

        $can_show_category = $show_category && !empty($category);
        $can_show_date     = $show_date && !empty($date);
        $category_link     = '';

        if ($can_show_category) {
            $term_link = get_term_link($category);
            if (!is_wp_error($term_link)) {
                $category_link = $term_link;
            }
        }

        if (!$can_show_category && !$can_show_date) {
            return;
        }

    ?>
        <ul class="blog-meta">
            <?php if ($can_show_category): ?>
                <li>
                    <?php if (!empty($category_link)): ?>
                        <a href="<?php echo esc_url($category_link); ?>"><?php echo esc_html($category->name); ?></a>
                    <?php else: ?>
                        <span><?php echo esc_html($category->name); ?></span>
                    <?php endif; ?>
                </li>
            <?php endif; ?>

            <?php if ($can_show_category && $can_show_date): ?>
                <li>
                    <?php if ($use_small_line_divider): ?>
                        <?php $this->render_meta_divider_line_icon(); ?>
                    <?php else: ?>
                        <?php $this->render_meta_divider_arrow_icon(); ?>
                    <?php endif; ?>
                </li>
            <?php endif; ?>

            <?php if ($can_show_date): ?>
                <li>
                    <a href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html($date); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    <?php
    }

    private function render_top_button_arrow($text, $link_data)
    {
    ?>
        <a href="<?php echo esc_url($link_data['url']); ?>" class="view-more-btn" <?php if ($link_data['is_external']) : ?> target="_blank" <?php endif; ?><?php if (!empty($link_data['rel'])) : ?> rel="<?php echo esc_attr($link_data['rel']); ?>" <?php endif; ?>>
            <?php echo esc_html($text); ?>
            <svg class="arrow" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <g>
                    <path d="M6.36416 4.94965C6.37964 5.4563 8.04642 6.14443 8.42737 6.1533L12.6752 6.1533L4.95937 13.8691C4.68614 14.1423 4.68613 14.5853 4.95937 14.8586C5.23261 15.1318 5.67561 15.1318 5.94884 14.8586L13.6646 7.14277L13.6646 11.3906C13.6647 11.7769 14.4631 13.4349 14.8494 13.4349C15.2358 13.4349 15.0638 11.7769 15.0638 11.3905L15.0638 5.45369C15.0637 5.06735 14.7506 4.75418 14.3642 4.7541L8.42738 4.7541C8.0235 4.75902 6.35447 4.48622 6.36416 4.94965Z" />
                </g>
            </svg>
        </a>
    <?php
    }

    private function render_top_button_line($text, $link_data)
    {
    ?>
        <a href="<?php echo esc_url($link_data['url']); ?>" class="view-more-btn style-2" <?php if ($link_data['is_external']) : ?> target="_blank" <?php endif; ?><?php if (!empty($link_data['rel'])) : ?> rel="<?php echo esc_attr($link_data['rel']); ?>" <?php endif; ?>>
            <svg class="borders" width="88" height="1" viewBox="0 0 88 1" xmlns="http://www.w3.org/2000/svg">
                <rect width="88" height="1" />
            </svg>
            <?php echo esc_html($text); ?>
        </a>
    <?php
    }

    private function render_read_more_arrow()
    {
    ?>
        <svg class="arrow" width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <g>
                <path d="M6.36416 4.94946C6.37964 5.45612 8.04642 6.14424 8.42737 6.15312L12.6752 6.15311L4.95937 13.8689C4.68614 14.1421 4.68613 14.5851 4.95937 14.8584C5.23261 15.1316 5.67561 15.1316 5.94884 14.8584L13.6646 7.14259L13.6646 11.3904C13.6647 11.7767 14.4631 13.4347 14.8494 13.4347C15.2358 13.4347 15.0638 11.7767 15.0638 11.3904L15.0638 5.45351C15.0637 5.06717 14.7506 4.754 14.3642 4.75392L8.42738 4.75392C8.0235 4.75884 6.35447 4.48604 6.36416 4.94946Z" />
            </g>
        </svg>
    <?php
    }

    private function render_card_icon_arrow()
    {
    ?>
        <svg width="23" height="23" viewBox="0 0 23 23" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.48587 4.91838C6.55342 5.54426 8.67525 6.53409 9.146 6.57738L14.3857 6.93811L5.60611 15.7944C5.2952 16.108 5.33757 16.6541 5.70074 17.0142C6.06392 17.3742 6.61038 17.4118 6.92129 17.0982L15.7009 8.24191L16.1072 13.4783C16.1442 13.9546 17.2876 16.0662 17.7642 16.0991C18.2408 16.1319 17.87 14.0734 17.8331 13.5971L17.2653 6.27854C17.2283 5.80227 16.812 5.38962 16.3354 5.35671L9.01219 4.85254C8.51447 4.8243 6.42959 4.34627 6.48587 4.91838Z" />
        </svg>
    <?php
    }

    private function render_meta_divider_arrow_icon()
    {
    ?>
        <svg width="37" height="6" viewBox="0 0 37 6" xmlns="http://www.w3.org/2000/svg">
            <path d="M32 3.38672L37 5.77347L37 -3.26633e-05L32 2.38672L32 3.38672ZM5 2.38672L2.18264e-07 -3.54609e-05L-2.18264e-07 5.77347L5 3.38672L5 2.38672ZM32.5 2.88672L32.5 2.38672L4.5 2.38672L4.5 2.88672L4.5 3.38672L32.5 3.38672L32.5 2.88672Z" />
        </svg>
    <?php
    }

    private function render_meta_divider_line_icon()
    {
    ?>
        <svg width="38" height="1" viewBox="0 0 38 1" xmlns="http://www.w3.org/2000/svg">
            <path d="M0.5 0.999997C0.223858 0.999997 1.69256e-08 0.77614 3.78045e-08 0.499997C5.86833e-08 0.223855 0.223858 -2.85621e-06 0.5 -2.83533e-06L37.5 -3.78045e-08C37.7761 -1.69256e-08 38 0.223858 38 0.5C38 0.776142 37.7761 1 37.5 1L0.5 0.999997Z" />
        </svg>
    <?php
    }

    private function render_primary_btn2_icon()
    {
    ?>
        <span class="icon">
            <?php $this->render_primary_btn2_icon_path(); ?>
        </span>
    <?php
    }

    private function render_primary_btn2_icon_path()
    {
    ?>
        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 9L9 1M9 1C7.22222 1.33333 3.33333 2 1 1M9 1C8.66667 2.66667 8 6.33333 9 9" stroke-width="1.5" stroke-linecap="round" />
        </svg>
<?php
    }

    private function get_link_data($link)
    {
        $data = [
            'url'         => '#',
            'is_external' => false,
            'rel'         => '',
        ];

        if (!is_array($link)) {
            return $data;
        }

        if (!empty($link['url'])) {
            $data['url'] = $link['url'];
        }

        $data['is_external'] = !empty($link['is_external']);

        $rels = [];
        if ($data['is_external']) {
            $rels[] = 'noopener';
        }
        if (!empty($link['nofollow'])) {
            $rels[] = 'nofollow';
        }

        if (!empty($rels)) {
            $data['rel'] = implode(' ', array_unique($rels));
        }

        return $data;
    }

    private function sanitize_ids($ids)
    {
        if (!is_array($ids)) {
            return [];
        }

        $clean = array_map('absint', $ids);
        $clean = array_filter($clean);

        return array_values(array_unique($clean));
    }

    private function get_primary_category($post_id)
    {
        $categories = get_the_category($post_id);

        if (!empty($categories) && !is_wp_error($categories)) {
            return $categories[0];
        }

        return null;
    }

    private function get_post_image_data($post_id)
    {
        $fallback = Utils::get_placeholder_image_src();
        $url      = get_the_post_thumbnail_url($post_id, 'blog-grid');
        $url      = !empty($url) ? $url : $fallback;

        $thumb_id = get_post_thumbnail_id($post_id);
        $alt      = '';

        if (!empty($thumb_id)) {
            $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
        }

        if (empty($alt)) {
            $alt = get_the_title($post_id);
        }

        return [
            'url' => $url,
            'alt' => $alt,
        ];
    }

    private function get_fade_delay($index)
    {
        $delay = ($index + 1) * 0.1;
        $value = number_format($delay, 1, '.', '');

        if (strpos($value, '0.') === 0) {
            $value = substr($value, 1);
        }

        return $value;
    }

    private function get_shape_displacement_url($settings)
    {
        $default = $this->get_asset_url('assets/image/start-up/hover-img-shape2.png');

        if (empty($settings['softro_blog_shape_displacement_image']) || !is_array($settings['softro_blog_shape_displacement_image'])) {
            return $default;
        }

        return !empty($settings['softro_blog_shape_displacement_image']['url'])
            ? $settings['softro_blog_shape_displacement_image']['url']
            :  $default;
    }

    private function get_asset_url($relative_path)
    {
        return trailingslashit(get_template_directory_uri()) . ltrim($relative_path, '/');
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Blog_Widget());
