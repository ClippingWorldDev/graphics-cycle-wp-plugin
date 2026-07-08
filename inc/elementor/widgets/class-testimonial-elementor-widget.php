<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Testimonial_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_testimonial';
    }

    public function get_title()
    {
        return esc_html__('GC Testimonial', 'softro-core');
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

    private function get_theme_mode_selector($mode, $selector)
    {
        return sprintf('[data-theme=%s] {{WRAPPER}} %s', $mode, $selector);
    }

    private function get_theme_mode_selectors($mode, array $selectors)
    {
        $output = [];

        foreach ($selectors as $selector => $property) {
            $output[$this->get_theme_mode_selector($mode, $selector)] = $property;
        }

        return $output;
    }

    private function normalize_fontawesome_class($icon_value)
    {
        $icon_value = trim((string) $icon_value);

        if ('' === $icon_value) {
            return '';
        }

        $replacements = [
            'fas ' => 'fa-solid ',
            'far ' => 'fa-regular ',
            'fab ' => 'fa-brands ',
            'fal ' => 'fa-light ',
            'fat ' => 'fa-thin ',
        ];

        foreach ($replacements as $search => $replace) {
            if (0 === strpos($icon_value, $search)) {
                return $replace . substr($icon_value, strlen($search));
            }
        }

        return $icon_value;
    }

    private function should_use_elementor_icon_renderer($icon_settings)
    {
        if (empty($icon_settings['value'])) {
            return false;
        }

        $library = $icon_settings['library'] ?? '';

        return 'eicons' === $library || 'svg' === $library || false !== strpos($library, 'svg');
    }

    private function render_icon($icon_settings, $args = [])
    {
        if (empty($icon_settings['value'])) {
            return;
        }

        $args = wp_parse_args($args, ['aria-hidden' => 'true']);

        if ($this->should_use_elementor_icon_renderer($icon_settings)) {
            Icons_Manager::render_icon($icon_settings, $args);
            return;
        }

        $icon_class = $this->normalize_fontawesome_class($icon_settings['value']);

        if ('' === $icon_class) {
            return;
        }

        $attributes = '';

        foreach ($args as $key => $value) {
            $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        echo '<i class="' . esc_attr($icon_class) . '"' . $attributes . '></i>';
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
            'gc_testimonial_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_testimonial_sub_title',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Testimonial', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_testimonial_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Our Customer Feedback', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_stars_section',
            [
                'label' => esc_html__('Star Rating', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_testimonial_star_icon_image',
            [
                'label'   => esc_html__('Star Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_testimonial_star_icon',
            [
                'label'       => esc_html__('Star Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fa-sharp fa-solid fa-star',
                    'library' => 'fa-sharp fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        $testimonial_repeater = new Repeater();

        $testimonial_repeater->add_control(
            'testimonial_rating',
            [
                'label'   => esc_html__('Star Count', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 5,
                'min'     => 1,
                'max'     => 5,
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_star_icon_image',
            [
                'label'   => esc_html__('Star Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_star_icon',
            [
                'label'       => esc_html__('Star Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('Overrides the global star icon for this slide.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_quote',
            [
                'label'   => esc_html__('Quote', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    '“According to the council of supply chain professionals the council of logistics management logistics is the process of planning, implementing and controlling procedures”',
                    'softro-core'
                ),
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_author_image',
            [
                'label'   => esc_html__('Author Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('testi/testi-author-2.png'),
                ],
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_author_name',
            [
                'label'       => esc_html__('Author Name', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Ethan Henry', 'softro-core'),
                'label_block' => true,
            ]
        );

        $testimonial_repeater->add_control(
            'testimonial_author_role',
            [
                'label'       => esc_html__('Author Role', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Manager', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_testimonial_items_section',
            [
                'label' => esc_html__('Testimonials', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_testimonial_items',
            [
                'label'       => esc_html__('Slides', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $testimonial_repeater->get_controls(),
                'default'     => [
                    [
                        'testimonial_author_name'  => esc_html__('Ethan Henry', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-2.png')],
                    ],
                    [
                        'testimonial_author_name'  => esc_html__('Ethan Henry', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-2.png')],
                    ],
                    [
                        'testimonial_author_name'  => esc_html__('Charles Edward', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-3.png')],
                    ],
                    [
                        'testimonial_author_name'  => esc_html__('Freddie Joseph', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-4.png')],
                    ],
                    [
                        'testimonial_author_name'  => esc_html__('Andrew D. Smith', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-5.png')],
                    ],
                    [
                        'testimonial_author_name'  => esc_html__('Alexander Samuel', 'softro-core'),
                        'testimonial_author_image' => ['url' => $this->get_theme_img_url('testi/testi-author-6.png')],
                    ],
                ],
                'title_field' => '{{{ testimonial_author_name }}}',
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
            'gc_testimonial_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_testimonial_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .testimonial-section-7' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .testimonial-section-7' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_testimonial_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .testimonial-section-7',
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testimonial-section-7' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testimonial-section-7' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_testimonial_subtitle_typography',
                'selector' => '{{WRAPPER}} .section-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_testimonial_subtitle_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_subtitle_margin',
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
            'gc_testimonial_subtitle_padding',
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
            'gc_testimonial_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_testimonial_title_typography',
                'selector' => '{{WRAPPER}} .section-heading .section-title',
            ]
        );

        $this->add_control(
            'gc_testimonial_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_title_margin',
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
            'gc_testimonial_title_padding',
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
            'gc_testimonial_style_card',
            [
                'label' => esc_html__('Testimonial Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_testimonial_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testi-item-7' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_card_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_carousel_margin',
            [
                'label'      => esc_html__('Carousel Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-carousel-5' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_stars',
            [
                'label' => esc_html__('Star Rating', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_star_size',
            [
                'label'      => esc_html__('Icon Font Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 .review li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .testi-item-7 .review li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_star_image_size',
            [
                'label'      => esc_html__('Icon Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 .review li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                    '{{WRAPPER}} .testi-item-7 .review li img'     => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_testimonial_star_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testi-item-7 .review li i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .testi-item-7 .review li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_star_list_margin',
            [
                'label'      => esc_html__('List Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 .review' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_star_item_margin',
            [
                'label'      => esc_html__('Star Item Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 .review li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_quote',
            [
                'label' => esc_html__('Quote', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_testimonial_quote_typography',
                'selector' => '{{WRAPPER}} .testi-item-7 > p',
            ]
        );

        $this->add_control(
            'gc_testimonial_quote_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testi-item-7 > p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_quote_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 > p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_quote_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-item-7 > p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_author',
            [
                'label' => esc_html__('Author', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_testimonial_author_name_typography',
                'label'    => esc_html__('Name Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .testi-author-box .name',
            ]
        );

        $this->add_control(
            'gc_testimonial_author_name_color',
            [
                'label'     => esc_html__('Name Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testi-author-box .name' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_testimonial_author_role_typography',
                'label'    => esc_html__('Role Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .testi-author-box .content span',
            ]
        );

        $this->add_control(
            'gc_testimonial_author_role_color',
            [
                'label'     => esc_html__('Role Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .testi-author-box .content span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_box_margin',
            [
                'label'      => esc_html__('Author Box Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_box_padding',
            [
                'label'      => esc_html__('Author Box Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_testimonial_style_author_image',
            [
                'label' => esc_html__('Author Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_image_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box .author-img img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box .author-img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .testi-author-box .author-img img' => 'width: 100%; height: 100%; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box .author-img img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_testimonial_author_image_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .testi-author-box .author-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_testimonial_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_testimonial_theme_mode_color_tabs');

        $this->start_controls_tab('gc_testimonial_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_testimonial_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .testimonial-section-7',
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.section-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.section-heading .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.testi-item-7' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_star_color',
            [
                'label'     => esc_html__('Star Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.testi-item-7 .review li i' => 'color: {{VALUE}};',
                    '.testi-item-7 .review li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_quote_color',
            [
                'label'     => esc_html__('Quote Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.testi-item-7 > p' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_author_name_color',
            [
                'label'     => esc_html__('Author Name Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.testi-author-box .name' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_dark_author_role_color',
            [
                'label'     => esc_html__('Author Role Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.testi-author-box .content span' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_testimonial_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_testimonial_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .testimonial-section-7',
            ]
        );

        $this->add_control(
            'gc_testimonial_light_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.section-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.section-heading .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.testi-item-7' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_star_color',
            [
                'label'     => esc_html__('Star Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.testi-item-7 .review li i' => 'color: {{VALUE}};',
                    '.testi-item-7 .review li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_quote_color',
            [
                'label'     => esc_html__('Quote Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.testi-item-7 > p' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_author_name_color',
            [
                'label'     => esc_html__('Author Name Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.testi-author-box .name' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_testimonial_light_author_role_color',
            [
                'label'     => esc_html__('Author Role Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.testi-author-box .content span' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_testimonial_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_star_icon($item, $settings)
    {
        if (!empty($item['testimonial_star_icon']['value'])) {
            $this->render_icon($item['testimonial_star_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['testimonial_star_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_testimonial_star_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_testimonial_star_icon']['value'])) {
            $this->render_icon($settings['gc_testimonial_star_icon'], ['aria-hidden' => 'true']);
        }
    }

    /**
     * @param array $item
     * @param array $settings
     * @return void
     */
    private function render_review_stars($item, $settings)
    {
        $rating = isset($item['testimonial_rating']) ? (int) $item['testimonial_rating'] : 5;
        $rating = max(1, min(5, $rating));
    ?>
        <ul class="review">
            <?php for ($i = 0; $i < $rating; $i++) : ?>
                <li><?php $this->render_star_icon($item, $settings); ?></li>
            <?php endfor; ?>
        </ul>
    <?php
    }

    /**
     * @param array $item
     * @param array $settings
     * @return void
     */
    private function render_testimonial_slide($item, $settings)
    {
        $quote      = $item['testimonial_quote'] ?? '';
        $author_name = $item['testimonial_author_name'] ?? '';
        $author_role = $item['testimonial_author_role'] ?? '';
        $author_img  = $this->get_media_url($item['testimonial_author_image'] ?? [], 'testi/testi-author-2.png');
    ?>
        <div class="swiper-slide">
            <div class="testi-item-7">
                <?php $this->render_review_stars($item, $settings); ?>
                <?php if ($quote) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($quote); ?></p>
                <?php endif; ?>
                <div class="testi-author-box">
                    <?php if ($author_img) : ?>
                        <div class="author-img"><img src="<?php echo esc_url($author_img); ?>" alt="testi"></div>
                    <?php endif; ?>
                    <div class="content">
                        <?php if ($author_name) : ?>
                            <h4 class="name"><?php echo esc_html($author_name); ?></h4>
                        <?php endif; ?>
                        <?php if ($author_role) : ?>
                            <span><?php echo esc_html($author_role); ?></span>
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

        $subtitle = $settings['gc_testimonial_sub_title'] ?? '';
        $title    = $settings['gc_testimonial_title'] ?? '';
        $items    = !empty($settings['gc_testimonial_items']) ? $settings['gc_testimonial_items'] : [];
    ?>

        <?php if (is_admin()) : ?>
            <script>
                var swiperTesti = new Swiper(".testi-carousel-5", {
                    slidesPerView: 1,
                    spaceBetween: 24,
                    slidesPerGroup: 1,
                    loop: true,
                    autoplay: true,
                    grabcursor: true,
                    speed: 600,
                    navigation: {
                        nextEl: ".testi-top .swiper-prev",
                        prevEl: ".testi-top .swiper-next",
                    },
                    breakpoints: {
                        320: {
                            slidesPerView: 1,
                            slidesPerGroup: 1,
                            spaceBetween: 25,
                        },
                        767: {
                            slidesPerView: 2,
                            slidesPerGroup: 1,
                            spaceBetween: 30,
                        },
                        1024: {
                            slidesPerView: 3,
                            slidesPerGroup: 1,
                        },
                    },
                });
            </script>
        <?php endif; ?>

        <section class="testimonial-section-7 pt-130 pb-130 overflow-hidden">
            <div class="container">
                <?php if ($subtitle || $title) : ?>
                    <div class="section-heading text-center">
                        <?php if ($subtitle) : ?>
                            <h4 class="sub-heading" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
                        <?php endif; ?>
                        <?php if ($title) : ?>
                            <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="testi-carousel-5 swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($items as $item) {
                            $this->render_testimonial_slide($item, $settings);
                        } ?>
                    </div>
                </div>
            </div>
        </section>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Testimonial_Widget());
