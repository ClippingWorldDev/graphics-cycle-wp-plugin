<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_About_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_about';
    }

    public function get_title()
    {
        return esc_html__('GC About', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    /**
     * @param string $path
     * @return string
     */
    private function get_theme_img_url($path)
    {
        return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($path, '/'));
    }

    /**
     * @param array  $media
     * @param string $fallback_path
     * @return string
     */
    private function get_media_url($media, $fallback_path = '')
    {
        if (!empty($media['url'])) {
            return esc_url($media['url']);
        }

        if ($fallback_path) {
            return $this->get_theme_img_url($fallback_path);
        }

        return '';
    }

    /**
     * @param array $link_settings
     * @return string
     */
    private function get_link_attributes($link_settings)
    {
        $url = !empty($link_settings['url']) ? $link_settings['url'] : '#';

        $attributes = [
            'href' => esc_url($url),
        ];

        if (!empty($link_settings['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($link_settings['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        if (!empty($link_settings['custom_attributes'])) {
            $custom_attributes = Utils::parse_custom_attributes($link_settings['custom_attributes']);

            foreach ($custom_attributes as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return $html;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Content tab controls.
     */
    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_about_shapes_section',
            [
                'label' => esc_html__('Background Shapes', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_shape_one',
            [
                'label'   => esc_html__('Shape One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('shapes/about-shape-4.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_about_shape_two',
            [
                'label'   => esc_html__('Shape Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('shapes/about-shape-5.png'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_heading_section',
            [
                'label' => esc_html__('Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_sub_title',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('About Our Company', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_about_title_line_one',
            [
                'label'       => esc_html__('Title Line One', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Connecting People And', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_about_title_line_two',
            [
                'label'       => esc_html__('Title Line Two', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Build Technology', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_experience_section',
            [
                'label' => esc_html__('Experience Box', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_exp_year',
            [
                'label'   => esc_html__('Years Number', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 25,
                'min'     => 0,
            ]
        );

        $this->add_control(
            'gc_about_exp_label',
            [
                'label'       => esc_html__('Years Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Years Experience', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_about_exp_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__(
                    'Lorem ipsum dolor sit amet consectur adipiscing elit eiusmod ex tempor incididunt labore dolore magna aliquaenim ad minim veniam quis nostrud exercitation laboris.',
                    'softro-core'
                ),
                'rows'    => 4,
            ]
        );

        $this->end_controls_section();

        $list_repeater = new Repeater();

        $list_repeater->add_control(
            'list_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $list_repeater->add_control(
            'list_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'far fa-circle-check',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $list_repeater->add_control(
            'list_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Emergency Solutions Anytime', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_about_list_section',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_list_items',
            [
                'label'       => esc_html__('List Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $list_repeater->get_controls(),
                'default'     => [
                    [
                        'list_text' => esc_html__('Emergency Solutions Anytime', 'softro-core'),
                        'list_icon' => [
                            'value'   => 'far fa-circle-check',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'list_text' => esc_html__('Emergency Solutions Anytime', 'softro-core'),
                        'list_icon' => [
                            'value'   => 'far fa-circle-check',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'list_text' => esc_html__('Affordable price upto 2 years', 'softro-core'),
                        'list_icon' => [
                            'value'   => 'far fa-circle-check',
                            'library' => 'fa-regular',
                        ],
                    ],
                    [
                        'list_text' => esc_html__('Reliable & Experienced Team', 'softro-core'),
                        'list_icon' => [
                            'value'   => 'far fa-circle-check',
                            'library' => 'fa-regular',
                        ],
                    ],
                ],
                'title_field' => '{{{ list_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_button_section',
            [
                'label' => esc_html__('Button', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get Started Now', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_about_button_url',
            [
                'label'       => esc_html__('Button URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_icon_image',
            [
                'label'   => esc_html__('Button Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_icon',
            [
                'label'       => esc_html__('Button Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fa-sharp fa-regular fa-arrow-right',
                    'library' => 'fa-sharp fa-regular',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_images_section',
            [
                'label' => esc_html__('Images', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_about_image_one',
            [
                'label'   => esc_html__('Image One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('images/about-img-8.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_about_image_two',
            [
                'label'   => esc_html__('Image Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('images/about-img-9.png'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style tab controls.
     */
    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_about_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_about_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_about_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .about-section-10',
            ]
        );

        $this->add_responsive_control(
            'gc_about_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-section-10' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-section-10' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_subtitle_typography',
                'selector' => '{{WRAPPER}} .about-content-10 .sub-heading',
            ]
        );

        $this->add_control(
            'gc_about_subtitle_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-content-10 .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_subtitle_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-content-10 .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_subtitle_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-content-10 .sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_title_typography',
                'selector' => '{{WRAPPER}} .about-content-10 .section-title',
            ]
        );

        $this->add_control(
            'gc_about_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-content-10 .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-content-10 .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-content-10 .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_experience',
            [
                'label' => esc_html__('Experience Box', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_about_exp_box_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-exp-box' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_exp_box_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-exp-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_exp_box_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-exp-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_exp_year_heading',
            [
                'label'     => esc_html__('Years Number', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_exp_year_typography',
                'selector' => '{{WRAPPER}} .about-exp-box .year',
            ]
        );

        $this->add_control(
            'gc_about_exp_year_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-exp-box .year' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_exp_label_heading',
            [
                'label'     => esc_html__('Years Label', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_exp_label_typography',
                'selector' => '{{WRAPPER}} .about-exp-box p span',
            ]
        );

        $this->add_control(
            'gc_about_exp_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-exp-box p span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_exp_desc_heading',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_exp_desc_typography',
                'selector' => '{{WRAPPER}} .about-exp-box p',
            ]
        );

        $this->add_control(
            'gc_about_exp_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-exp-box p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_list',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_list_typography',
                'selector' => '{{WRAPPER}} .about-list li',
            ]
        );

        $this->add_control(
            'gc_about_list_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-list li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_item_padding',
            [
                'label'      => esc_html__('Item Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_item_margin',
            [
                'label'      => esc_html__('Item Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_wrap_margin',
            [
                'label'      => esc_html__('List Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-list-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_list_icon_heading',
            [
                'label'     => esc_html__('List Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_icon_size',
            [
                'label'      => esc_html__('Icon Font Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 60,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-list li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .about-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_icon_image_size',
            [
                'label'      => esc_html__('Icon Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 60,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-list li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_about_list_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-list li i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .about-list li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_list_icon_margin',
            [
                'label'      => esc_html__('Icon Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-list li i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_button',
            [
                'label' => esc_html__('Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_about_button_typography',
                'selector' => '{{WRAPPER}} .about-btn .rr-primary-btn',
            ]
        );

        $this->add_control(
            'gc_about_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_button_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_button_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .about-btn .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .about-btn .rr-primary-btn i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_about_button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .about-btn .rr-primary-btn i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .about-btn .rr-primary-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_shapes',
            [
                'label' => esc_html__('Shapes', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_about_shape_one_width',
            [
                'label'      => esc_html__('Shape One Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 600,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .shape.shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_shape_two_width',
            [
                'label'      => esc_html__('Shape Two Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 600,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .shape.shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_shape_one_margin',
            [
                'label'      => esc_html__('Shape One Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .shape.shape-1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_shape_two_margin',
            [
                'label'      => esc_html__('Shape Two Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .shape.shape-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_about_style_images',
            [
                'label' => esc_html__('Images', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_about_image_one_width',
            [
                'label'      => esc_html__('Image One Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .about-img .img-1' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_image_two_width',
            [
                'label'      => esc_html__('Image Two Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5 .about-img-2 .img-2' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_image_wrap_padding',
            [
                'label'      => esc_html__('Image Wrap Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_about_image_wrap_margin',
            [
                'label'      => esc_html__('Image Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .about-img-wrap-5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_about_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            .elementor-element-<?php echo $widget_id; ?>>.elementor-widget-container {
                padding: 0 !important;
                margin: 0 !important;
            }
        </style>
    <?php
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_list_icon($item)
    {
        if (!empty($item['list_icon']['value'])) {
            Icons_Manager::render_icon($item['list_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['list_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt=""></i>';
        }
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_button_icon($settings)
    {
        if (!empty($settings['gc_about_button_icon']['value'])) {
            Icons_Manager::render_icon($settings['gc_about_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_about_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt=""></i>';
        }
    }

    /**
     * @return bool
     */
    private function is_elementor_edit_mode()
    {
        return Plugin::$instance->editor->is_edit_mode();
    }

    /**
     * Keep reveal/fade animations visible inside the Elementor canvas.
     *
     * @return void
     */
    private function render_editor_preview_fix()
    {
        if (!$this->is_elementor_edit_mode()) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
    ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?>.reveal {
                visibility: visible !important;
                overflow: visible !important;
                height: auto !important;
            }

            .elementor-element-<?php echo $widget_id; ?>.about-img-wrap-5 .about-img.reveal {
                width: 80%;
            }

            .elementor-element-<?php echo $widget_id; ?>.about-img-wrap-5 .about-img-2.reveal {
                width: 100%;
            }

            .elementor-element-<?php echo $widget_id; ?>.reveal img {
                opacity: 1 !important;
                transform: none !important;
            }

            .elementor-element-<?php echo $widget_id; ?>.fade-top {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
        <script>
            (function($) {
                function gcAboutEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.reveal').css({
                        visibility: 'visible',
                        overflow: 'visible'
                    });

                    $scope.find('.reveal img').css({
                        opacity: 1,
                        transform: 'none'
                    });

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });

                    if (typeof gsap !== 'undefined') {
                        $scope.find('.reveal').each(function() {
                            gsap.set(this, {
                                autoAlpha: 1,
                                xPercent: 0,
                                clearProps: 'transform'
                            });

                            var image = this.querySelector('img');

                            if (image) {
                                gsap.set(image, {
                                    xPercent: 0,
                                    scale: 1,
                                    clearProps: 'transform'
                                });
                            }
                        });
                    }
                }

                gcAboutEditorPreviewFix();

                $(window).on('elementor/frontend/init', function() {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_about.default',
                        gcAboutEditorPreviewFix
                    );
                });
            })(jQuery);
        </script>
    <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $subtitle     = $settings['gc_about_sub_title'] ?? '';
        $title_line_1 = $settings['gc_about_title_line_one'] ?? '';
        $title_line_2 = $settings['gc_about_title_line_two'] ?? '';
        $exp_year     = $settings['gc_about_exp_year'] ?? '';
        $exp_label    = $settings['gc_about_exp_label'] ?? '';
        $exp_desc     = $settings['gc_about_exp_description'] ?? '';
        $list_items   = !empty($settings['gc_about_list_items']) ? $settings['gc_about_list_items'] : [];
        $button_text  = $settings['gc_about_button_text'] ?? '';
        $button_url   = $settings['gc_about_button_url'] ?? [];

        $shape_one_url = $this->get_media_url($settings['gc_about_shape_one'] ?? [], 'shapes/about-shape-4.png');
        $shape_two_url = $this->get_media_url($settings['gc_about_shape_two'] ?? [], 'shapes/about-shape-5.png');
        $image_one_url = $this->get_media_url($settings['gc_about_image_one'] ?? [], 'images/about-img-8.png');
        $image_two_url = $this->get_media_url($settings['gc_about_image_two'] ?? [], 'images/about-img-9.png');
    ?>

        <section class="about-section-10 pb-130 overflow-hidden">
            <div class="container">
                <div class="row gy-lg-0 gy-4">
                    <div class="col-lg-6">
                        <div class="about-content-10 about-content-7 fade-wrapper">
                            <?php if ($subtitle || $title_line_1 || $title_line_2) : ?>
                                <div class="section-heading mb-40">
                                    <?php if ($subtitle) : ?>
                                        <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($title_line_1 || $title_line_2) : ?>
                                        <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03">
                                            <?php
                                            if ($title_line_1) {
                                                echo esc_html($title_line_1);
                                            }
                                            if ($title_line_1 && $title_line_2) {
                                                echo '<br>';
                                            }
                                            if ($title_line_2) {
                                                echo esc_html($title_line_2);
                                            }
                                            ?>
                                        </h2>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ('' !== $exp_year || $exp_label || $exp_desc) : ?>
                                <div class="about-exp-box fade-top">
                                    <?php if ('' !== $exp_year && null !== $exp_year) : ?>
                                        <h3 class="year"><?php echo esc_html($exp_year); ?></h3>
                                    <?php endif; ?>
                                    <?php if ($exp_label || $exp_desc) : ?>
                                        <p>
                                            <?php if ($exp_label) : ?>
                                                <span><?php echo esc_html($exp_label); ?></span>
                                            <?php endif; ?>
                                            <?php echo esc_html($exp_desc); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($list_items)) : ?>
                                <div class="about-list-wrap fade-top">
                                    <ul class="about-list">
                                        <?php foreach ($list_items as $item) :
                                            $list_text = $item['list_text'] ?? '';
                                            if (!$list_text) {
                                                continue;
                                            }
                                        ?>
                                            <li><?php $this->render_list_icon($item); ?><?php echo esc_html($list_text); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if ($button_text) : ?>
                                <div class="about-btn fade-top">
                                    <a<?php echo $this->get_link_attributes($button_url); ?> class="rr-primary-btn"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($settings); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-img-wrap-5">
                            <?php if ($shape_one_url || $shape_two_url) : ?>
                                <div class="shapes">
                                    <?php if ($shape_one_url) : ?>
                                        <div class="shape shape-1"><img src="<?php echo esc_url($shape_one_url); ?>" alt="shape"></div>
                                    <?php endif; ?>
                                    <?php if ($shape_two_url) : ?>
                                        <div class="shape shape-2"><img src="<?php echo esc_url($shape_two_url); ?>" alt="shape"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($image_one_url) : ?>
                                <div class="about-img reveal">
                                    <img class="img-1" src="<?php echo esc_url($image_one_url); ?>" alt="img">
                                </div>
                            <?php endif; ?>
                            <?php if ($image_two_url) : ?>
                                <div class="about-img-2 reveal">
                                    <img class="img-2" src="<?php echo esc_url($image_two_url); ?>" alt="img">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_About_Widget());
