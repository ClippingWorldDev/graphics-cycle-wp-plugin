<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Quote_Banner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_quote_banner';
    }

    public function get_title()
    {
        return esc_html__('GC Quote Banner', 'softro-core');
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
            'gc_quote_bg_section',
            [
                'label' => esc_html__('Background', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_bg_image',
            [
                'label'   => esc_html__('Background Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/ai-quote-banner-bg.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_quote_aria_label',
            [
                'label'       => esc_html__('Section Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get a free project quote', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_content_section',
            [
                'label' => esc_html__('Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_badge',
            [
                'label'       => esc_html__('Badge Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Free Project Quote', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_quote_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Tell Us Your Project & Get A Free Quote Within 12 Hours', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'gc_quote_subtitle',
            [
                'label'   => esc_html__('Subtitle', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Contact us and discuss whatever project you have', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $feature_repeater = new Repeater();

        $feature_repeater->add_control(
            'feature_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $feature_repeater->add_control(
            'feature_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fas fa-circle-check',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $feature_repeater->add_control(
            'feature_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Graphics & Web Design', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_quote_features_section',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_features',
            [
                'label'       => esc_html__('Features', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $feature_repeater->get_controls(),
                'default'     => [
                    [
                        'feature_text' => esc_html__('Graphics & Web Design', 'softro-core'),
                        'feature_icon' => [
                            'value'   => 'fas fa-circle-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'feature_text' => esc_html__('Custom Software Development', 'softro-core'),
                        'feature_icon' => [
                            'value'   => 'fas fa-circle-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'feature_text' => esc_html__('AI-Driven App Development', 'softro-core'),
                        'feature_icon' => [
                            'value'   => 'fas fa-circle-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'feature_text' => esc_html__('AI-Powered Digital Marketing', 'softro-core'),
                        'feature_icon' => [
                            'value'   => 'fas fa-circle-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_button_section',
            [
                'label' => esc_html__('Button', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact for Service', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_quote_button_url',
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
            'gc_quote_button_icon_image',
            [
                'label'   => esc_html__('Button Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_button_icon',
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

        $this->end_controls_section();
    }

    /**
     * Style tab controls.
     */
    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_quote_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_quote_reset_elementor_spacing',
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
            'gc_quote_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-banner-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-banner-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_content_padding',
            [
                'label'      => esc_html__('Content Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_background',
            [
                'label' => esc_html__('Background Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_quote_bg_image_width',
            [
                'label'      => esc_html__('Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 2000,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-bg-image' => 'width: {{SIZE}}{{UNIT}}; max-width: none;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_bg_image_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vh'],
                'range'      => [
                    'px' => [
                        'min' => 100,
                        'max' => 1200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-bg-image' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_overlay_color',
            [
                'label'     => esc_html__('Overlay Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-overlay' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_overlay_opacity',
            [
                'label'     => esc_html__('Overlay Opacity', 'softro-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.05,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-overlay' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_badge',
            [
                'label' => esc_html__('Badge', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_badge_typography',
                'selector' => '{{WRAPPER}} .ai-quote-badge',
            ]
        );

        $this->add_control(
            'gc_quote_badge_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_badge_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_badge_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_badge_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_title_typography',
                'selector' => '{{WRAPPER}} .ai-quote-title',
            ]
        );

        $this->add_control(
            'gc_quote_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_subtitle_typography',
                'selector' => '{{WRAPPER}} .ai-quote-subtitle',
            ]
        );

        $this->add_control(
            'gc_quote_subtitle_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-subtitle' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_subtitle_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_subtitle_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_features',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_features_typography',
                'selector' => '{{WRAPPER}} .ai-quote-features li',
            ]
        );

        $this->add_control(
            'gc_quote_features_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_list_margin',
            [
                'label'      => esc_html__('List Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_list_padding',
            [
                'label'      => esc_html__('List Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_item_margin',
            [
                'label'      => esc_html__('Item Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-features li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_item_padding',
            [
                'label'      => esc_html__('Item Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-features li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_features_icon_heading',
            [
                'label'     => esc_html__('Feature Icon', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_icon_size',
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
                    '{{WRAPPER}} .ai-quote-features li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ai-quote-features li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_icon_image_size',
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
                    '{{WRAPPER}} .ai-quote-features li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_features_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-features li i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ai-quote-features li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_features_icon_margin',
            [
                'label'      => esc_html__('Icon Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-features li i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_style_button',
            [
                'label' => esc_html__('Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_button_typography',
                'selector' => '{{WRAPPER}} .ai-quote-btn',
            ]
        );

        $this->add_control(
            'gc_quote_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_button_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_button_hover_bg',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_button_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .ai-quote-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_button_icon_size',
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
                    '{{WRAPPER}} .ai-quote-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ai-quote-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ai-quote-btn i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ai-quote-btn i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .ai-quote-btn svg' => 'fill: {{VALUE}};',
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
        if ('yes' !== ($settings['gc_quote_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            .elementor-element-<?php echo $widget_id; ?> > .elementor-widget-container {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-top {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
        <script>
            (function ($) {
                function gcQuoteEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });
                }

                gcQuoteEditorPreviewFix();

                $(window).on('elementor/frontend/init', function () {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_quote_banner.default',
                        gcQuoteEditorPreviewFix
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
    private function render_feature_icon($item)
    {
        if (!empty($item['feature_icon']['value'])) {
            Icons_Manager::render_icon($item['feature_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['feature_icon_image'] ?? [], '');

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
        if (!empty($settings['gc_quote_button_icon']['value'])) {
            Icons_Manager::render_icon($settings['gc_quote_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_quote_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt=""></i>';
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $aria_label  = $settings['gc_quote_aria_label'] ?? esc_html__('Get a free project quote', 'softro-core');
        $bg_url      = $this->get_media_url($settings['gc_quote_bg_image'] ?? [], 'new-update/ai-quote-banner-bg.png');
        $badge       = $settings['gc_quote_badge'] ?? '';
        $title       = $settings['gc_quote_title'] ?? '';
        $subtitle    = $settings['gc_quote_subtitle'] ?? '';
        $features    = !empty($settings['gc_quote_features']) ? $settings['gc_quote_features'] : [];
        $button_text = $settings['gc_quote_button_text'] ?? '';
        $button_url  = $settings['gc_quote_button_url'] ?? [];
        ?>

        <section class="ai-quote-banner-11 fade-wrapper" aria-label="<?php echo esc_attr($aria_label); ?>">
            <div class="ai-quote-banner-bg" aria-hidden="true">
                <?php if ($bg_url) : ?>
                    <img class="ai-quote-bg-image" src="<?php echo esc_url($bg_url); ?>" alt="">
                <?php endif; ?>
                <div class="ai-quote-overlay"></div>
            </div>
            <div class="container">
                <div class="ai-quote-content fade-top">
                    <?php if ($badge) : ?>
                        <span class="ai-quote-badge" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($badge); ?></span>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="ai-quote-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($subtitle) : ?>
                        <p class="ai-quote-subtitle" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($subtitle); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($features)) : ?>
                        <ul class="ai-quote-features">
                            <?php foreach ($features as $item) :
                                $feature_text = $item['feature_text'] ?? '';

                                if (!$feature_text) {
                                    continue;
                                }
                                ?>
                                <li class="fade-top"><?php $this->render_feature_icon($item); ?> <?php echo esc_html($feature_text); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <?php if ($button_text) : ?>
                        <a<?php echo $this->get_link_attributes($button_url); ?> class="rr-primary-btn ai-quote-btn fade-top"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($settings); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Quote_Banner_Widget());
