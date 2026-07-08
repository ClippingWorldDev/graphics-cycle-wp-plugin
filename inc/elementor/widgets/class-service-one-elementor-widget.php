<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Service_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_service_one';
    }

    public function get_title()
    {
        return esc_html__('GC Service One', 'softro-core');
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

    /**
     * Sanitize editor content for use inside a single theme <p> tag.
     *
     * @param string $content
     * @return string
     */
    private function get_paragraph_inner_content($content)
    {
        if ('' === trim((string) $content)) {
            return '';
        }

        $content = wp_kses_post($content);
        $content = preg_replace('#<(script|style)[^>]*>.*?</\\1>#is', '', $content);
        $content = str_ireplace(['<p>', '</p>'], ['', '<br>'], $content);
        $content = preg_replace('#(<br\s*/?\s*>)+$#i', '', trim($content));

        return $content;
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
            'gc_service_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_service_sub_title',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Our Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_title_line_one',
            [
                'label'       => esc_html__('Title Line One', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Graphics Cycle\'s Core Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_title_line_two',
            [
                'label'       => esc_html__('Title Line Two', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Ecosystem', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'Understand which editing technique is right for your products and image requirements.',
                    'softro-core'
                ),
            ]
        );

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control(
            'service_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Graphics Solutions', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_title_url',
            [
                'label'       => esc_html__('Title URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $service_repeater->add_control(
            'service_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('service/service-1.jpg'),
                ],
            ]
        );

        $service_repeater->add_control(
            'service_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $service_repeater->add_control(
            'service_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'Professional graphic design builds brand and drives engagement for US and European markets.',
                    'softro-core'
                ),
            ]
        );

        $service_repeater->add_control(
            'service_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View Details', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_button_url',
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

        $service_repeater->add_control(
            'service_button_icon_image',
            [
                'label'   => esc_html__('Button Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $service_repeater->add_control(
            'service_button_icon',
            [
                'label'       => esc_html__('Button Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'far fa-arrow-right',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_service_items_section',
            [
                'label' => esc_html__('Service Cards', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_service_items',
            [
                'label'       => esc_html__('Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_repeater->get_controls(),
                'default'     => [
                    [
                        'service_title'       => esc_html__('Graphics Solutions', 'softro-core'),
                        'service_description' => esc_html__(
                            'Professional graphic design builds brand and drives engagement for US and European markets.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-1.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                    [
                        'service_title'       => esc_html__('Web & App Solutions', 'softro-core'),
                        'service_description' => esc_html__(
                            'Custom software, app, and website development services are provided, ensuring 100% quality and a modern touch.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-2.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                    [
                        'service_title'       => esc_html__('Marketing Solutions', 'softro-core'),
                        'service_description' => esc_html__(
                            'We have a data-driven marketing strategy that brings more visitors, qualified leads, and measurable ROI.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-3.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                    [
                        'service_title'       => esc_html__('Video and 3D Solutions', 'softro-core'),
                        'service_description' => esc_html__(
                            'We create high-impact video content and 3D animations to boost engagement and drive conversions.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-1.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                    [
                        'service_title'       => esc_html__('PPC Marketing Service', 'softro-core'),
                        'service_description' => esc_html__(
                            'We create high-impact video content and 3D animations to boost engagement and drive conversions.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-2.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                    [
                        'service_title'       => esc_html__('Photo Retouching', 'softro-core'),
                        'service_description' => esc_html__(
                            'We have a data-driven marketing strategy that brings more visitors, qualified leads, and measurable ROI.',
                            'softro-core'
                        ),
                        'service_icon_image'  => ['url' => $this->get_theme_img_url('service/service-3.jpg')],
                        'service_title_url'   => ['url' => '#'],
                        'service_button_url'  => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ service_title }}}',
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
            'gc_service_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_service_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_service_second_row_spacing',
            [
                'label'      => esc_html__('Second Row Top Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 120,
                    ],
                ],
                'default'    => [
                    'size' => 30,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-section-13 .fade-wrapper.pt-30' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_service_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .service-section-13',
            ]
        );

        $this->add_responsive_control(
            'gc_service_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-section-13' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-section-13' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_subtitle_typography',
                'selector' => '{{WRAPPER}} .section-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_service_subtitle_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_subtitle_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_subtitle_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_title_typography',
                'selector' => '{{WRAPPER}} .section-heading .section-title',
            ]
        );

        $this->add_control(
            'gc_service_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_desc_typography',
                'selector' => '{{WRAPPER}} .section-heading .gc-bg-removal-techniques-desc',
            ]
        );

        $this->add_control(
            'gc_service_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .gc-bg-removal-techniques-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .gc-bg-removal-techniques-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_desc_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .section-heading .gc-bg-removal-techniques-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_card',
            [
                'label' => esc_html__('Service Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_service_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .service-item-13-border' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_hover_bg',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_card_title',
            [
                'label' => esc_html__('Card Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_card_title_typography',
                'selector' => '{{WRAPPER}} .service-item-13 .content .title',
            ]
        );

        $this->add_control(
            'gc_service_card_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_title_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13:hover .content .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_card_icon',
            [
                'label' => esc_html__('Card Icon / Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_icon_width',
            [
                'label'      => esc_html__('Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 400,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_icon_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 400,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .icon img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_icon_font_size',
            [
                'label'      => esc_html__('Icon Font Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .service-item-13 .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .service-item-13 .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_icon_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_icon_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_card_desc',
            [
                'label' => esc_html__('Card Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_card_desc_typography',
                'selector' => '{{WRAPPER}} .service-item-13 .content .dec',
            ]
        );

        $this->add_control(
            'gc_service_card_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .dec' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .content .dec' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_desc_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .content .dec' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_style_card_button',
            [
                'label' => esc_html__('Card Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_card_button_typography',
                'selector' => '{{WRAPPER}} .service-item-13 .content .rr-primary-btn',
            ]
        );

        $this->add_control(
            'gc_service_card_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_button_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_button_hover_bg',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_button_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_card_button_icon_size',
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
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_service_card_button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .service-item-13 .content .rr-primary-btn svg' => 'fill: {{VALUE}};',
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
        if ('yes' !== ($settings['gc_service_reset_elementor_spacing'] ?? 'yes')) {
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
     * @return bool
     */
    private function is_elementor_edit_mode()
    {
        return Plugin::$instance->editor->is_edit_mode();
    }

    /**
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
            .elementor-element-<?php echo $widget_id; ?>.fade-top {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
        <script>
            (function($) {
                function gcServiceEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });
                }

                gcServiceEditorPreviewFix();

                $(window).on('elementor/frontend/init', function() {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_service_one.default',
                        gcServiceEditorPreviewFix
                    );
                });
            })(jQuery);
        </script>
    <?php
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_service_icon($item)
    {
        if (!empty($item['service_icon']['value'])) {
            echo '<div class="icon">';
            Icons_Manager::render_icon($item['service_icon'], ['aria-hidden' => 'true']);
            echo '</div>';
            return;
        }

        $icon_url = $this->get_media_url($item['service_icon_image'] ?? [], 'service/service-1.jpg');

        if ($icon_url) {
            echo '<div class="icon"><img src="' . esc_url($icon_url) . '" alt="service"></div>';
        }
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_service_button_icon($item)
    {
        if (!empty($item['service_button_icon']['value'])) {
            Icons_Manager::render_icon($item['service_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['service_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt=""></i>';
        }
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_service_card($item)
    {
        $title       = $item['service_title'] ?? '';
        $title_url   = $item['service_title_url'] ?? [];
        $description = $item['service_description'] ?? '';
        $button_text = $item['service_button_text'] ?? '';
        $button_url  = $item['service_button_url'] ?? [];

        if (!$title && !$description) {
            return;
        }
    ?>
        <div class="col-lg-4 col-md-6 fade-top">
            <div class="service-item-13">
                <div class="service-item-13-border"></div>
                <div class="service-item-13-inner">
                    <div class="content">
                        <?php if ($title) : ?>
                            <h3 class="title">
                                <a<?php echo $this->get_link_attributes($title_url); ?>><?php echo esc_html($title); ?></a>
                            </h3>
                        <?php endif; ?>

                        <?php $this->render_service_icon($item); ?>

                        <?php if ($description) : ?>
                            <p class="dec"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>

                        <?php if ($button_text) : ?>
                            <a<?php echo $this->get_link_attributes($button_url); ?> class="rr-primary-btn"><?php echo esc_html($button_text); ?> <i
                                    class="fa-regular fa-arrow-right"></i></a>
                            <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $subtitle     = $settings['gc_service_sub_title'] ?? '';
        $title_line_1 = $settings['gc_service_title_line_one'] ?? '';
        $title_line_2 = $settings['gc_service_title_line_two'] ?? '';
        $description  = $settings['gc_service_description'] ?? '';
        $items        = !empty($settings['gc_service_items']) ? $settings['gc_service_items'] : [];
        $rows         = array_chunk($items, 3);
    ?>

        <section class="service-section-13 service-section-15 pt-120 pb-120 ">
            <div class="container">
                <?php if ($subtitle || $title_line_1 || $title_line_2 || $description) : ?>
                    <div class="section-heading text-center">
                        <?php if ($subtitle) : ?>
                            <h4 class="sub-heading" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
                        <?php endif; ?>
                        <?php if ($title_line_1 || $title_line_2) : ?>
                            <h2 class="section-title" data-text-animation data-split="word" data-duration="1">
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
                        <?php if ($description) : ?>
                            <p class="gc-bg-removal-techniques-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php foreach ($rows as $row_index => $row_items) :
                    $row_classes = 'row gy-lg-0 gy-4 fade-wrapper';

                    if ($row_index > 0) {
                        $row_classes .= ' pt-30';
                    }
                ?>
                    <div class="<?php echo esc_attr($row_classes); ?>">
                        <?php foreach ($row_items as $item) {
                            $this->render_service_card($item);
                        } ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Service_One_Widget());
