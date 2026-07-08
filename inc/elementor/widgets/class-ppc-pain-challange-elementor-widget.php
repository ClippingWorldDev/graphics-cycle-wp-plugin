<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_PPC_Pain_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_ppc_pain';
    }

    public function get_title()
    {
        return esc_html__('GC PPC Pain Challenge', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    private function get_theme_img_url($path)
    {
        return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($path, '/'));
    }

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

    private function render_card_icon(array $item, array $settings)
    {
        if (!empty($item['card_icon']['value'])) {
            $this->render_icon($item['card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['card_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_ppc_pain_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_ppc_pain_default_icon']['value'])) {
            $this->render_icon($settings['gc_ppc_pain_default_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-light fa-check" aria-hidden="true"></i>';
        }
    }

    private function get_default_cards()
    {
        return [
            [
                'card_style'      => '1',
                'card_icon'       => ['value' => 'fa-light fa-sack-dollar', 'library' => 'fa-light'],
                'card_title'      => esc_html__('Wasted ad spend', 'softro-core'),
                'card_description' => esc_html__(
                    'Paying for clicks without conversions drains your budget fast and leaves little room to scale what actually works.',
                    'softro-core'
                ),
            ],
            [
                'card_style'      => '2',
                'card_icon'       => ['value' => 'fa-light fa-eye-slash', 'library' => 'fa-light'],
                'card_title'      => esc_html__('Poor visibility', 'softro-core'),
                'card_description' => esc_html__(
                    'Without clear reporting and attribution, you never know which campaigns, keywords, or audiences drive real revenue.',
                    'softro-core'
                ),
            ],
            [
                'card_style'      => '3',
                'card_icon'       => ['value' => 'fa-light fa-chart-line-down', 'library' => 'fa-light'],
                'card_title'      => esc_html__('Falling metrics', 'softro-core'),
                'card_description' => esc_html__(
                    'Declining ROI, rising CPC, and flat conversions mean your ads are costing more while delivering less business impact.',
                    'softro-core'
                ),
            ],
        ];
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_ppc_pain_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_pain_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('The Challenge', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_pain_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Spending More on Ads but Poor Results?', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_ppc_pain_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Many businesses invest heavily in PPC advertising but struggle to generate qualified leads, better conversions, and sustainable growth. Graphics Cycle may be the best choice here. We provide AI powered result driven PPC marketing that can easily meet your demand (save cost, better ROI, ROAS, growth).',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_defaults_section', [
            'label' => esc_html__('Card Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_pain_default_icon', [
            'label'   => esc_html__('Default Card Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-sack-dollar', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_ppc_pain_default_icon_image', [
            'label'       => esc_html__('Default Card Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_style', [
            'label'   => esc_html__('Card Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => '1',
            'options' => [
                '1' => esc_html__('Style 1', 'softro-core'),
                '2' => esc_html__('Style 2', 'softro-core'),
                '3' => esc_html__('Style 3', 'softro-core'),
            ],
        ]);

        $card_repeater->add_control('card_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Wasted ad spend', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Card description goes here.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_ppc_pain_cards_section', [
            'label' => esc_html__('Pain Cards', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_pain_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_pain_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_ppc_pain_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_ppc_pain_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_pain_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-pain-section',
        ]);

        $this->add_responsive_control('gc_ppc_pain_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_section_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-section' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_heading', [
            'label' => esc_html__('Section Heading', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_pain_heading_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_heading_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_pain_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-eyebrow',
        ]);

        $this->add_control('gc_ppc_pain_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_pain_eyebrow_line_color', [
            'label'     => esc_html__('Decorative Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-ppc-pain-eyebrow::before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} .gc-ppc-pain-eyebrow::after'  => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_ppc_pain_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_pain_title_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-heading .section-title',
        ]);

        $this->add_control('gc_ppc_pain_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-heading .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_pain_desc_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-desc',
        ]);

        $this->add_control('gc_ppc_pain_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_cards_grid', [
            'label' => esc_html__('Cards Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_pain_cards_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-cards' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_cards_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-cards' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_card', [
            'label' => esc_html__('Pain Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_pain_card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-pain-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_ppc_pain_card_border',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-card',
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_ppc_pain_card_top_line_color', [
            'label'     => esc_html__('Top Accent Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card::after' => 'background: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_pain_card_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_ppc_pain_card_hover_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_pain_card_hover_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_card_icon', [
            'label' => esc_html__('Card Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_pain_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 24, 'max' => 120]],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_icon_font_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 48]],
            'selectors'  => [
                '{{WRAPPER}} .gc-ppc-pain-card-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-pain-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-pain-card-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_ppc_pain_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-ppc-pain-card-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-ppc-pain-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_ppc_pain_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_ppc_pain_icon_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_ppc_pain_icon_hover_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-ppc-pain-card:hover .gc-ppc-pain-card-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-ppc-pain-card:hover .gc-ppc-pain-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_pain_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-card-title',
        ]);

        $this->add_control('gc_ppc_pain_card_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_pain_card_title_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card:hover .gc-ppc-pain-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_pain_style_card_desc', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_pain_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-pain-card-desc',
        ]);

        $this->add_control('gc_ppc_pain_card_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-pain-card-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_pain_card_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-pain-card-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_ppc_pain_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_ppc_pain_theme_mode_tabs');

        $this->start_controls_tab('gc_ppc_pain_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_pain_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-ppc-pain-section',
        ]);

        $this->add_control('gc_ppc_pain_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_card_border', [
            'label'     => esc_html__('Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-ppc-pain-card-icon i'   => 'color: {{VALUE}};',
                '.gc-ppc-pain-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_ppc_pain_dark_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-pain-card-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_ppc_pain_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_pain_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-ppc-pain-section',
        ]);

        $this->add_control('gc_ppc_pain_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_card_border', [
            'label'     => esc_html__('Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_pain_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-ppc-pain-card-icon i'   => 'color: {{VALUE}};',
                '.gc-ppc-pain-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_ppc_pain_light_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-pain-card-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_ppc_pain_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_shape_backgrounds($settings)
    {
        $section_shape = $this->get_media_url($settings['gc_ppc_pain_section_shape'] ?? [], '');

        if (!$section_shape) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-ppc-pain-section {
                background-image: url('<?php echo esc_url($section_shape); ?>');
                background-repeat: no-repeat;
            }
        </style>
        <?php
    }

    private function render_pain_card(array $item, array $settings)
    {
        $title       = trim((string) ($item['card_title'] ?? ''));
        $description = $item['card_description'] ?? '';
        $style       = sanitize_key($item['card_style'] ?? '1');

        if (!in_array($style, ['1', '2', '3'], true)) {
            $style = '1';
        }

        if ('' === $title && '' === trim(wp_strip_all_tags((string) $description))) {
            return;
        }
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="gc-ppc-pain-card gc-ppc-pain-card--<?php echo esc_attr($style); ?> fade-top">
                <div class="gc-ppc-pain-card-icon" aria-hidden="true">
                    <?php $this->render_card_icon($item, $settings); ?>
                </div>
                <?php if ('' !== $title) : ?>
                    <h3 class="gc-ppc-pain-card-title"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                    <p class="gc-ppc-pain-card-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $eyebrow     = $settings['gc_ppc_pain_eyebrow'] ?? '';
        $title       = $settings['gc_ppc_pain_title'] ?? '';
        $description = $settings['gc_ppc_pain_description'] ?? '';
        $cards       = $settings['gc_ppc_pain_cards'] ?? [];

        if (empty($cards)) {
            $cards = $this->get_default_cards();
        }

        $this->render_elementor_spacing_fix($settings);
        $this->render_shape_backgrounds($settings);
        ?>

        <section class="gc-ppc-pain-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <?php if ('' !== trim((string) $eyebrow) || '' !== trim((string) $title) || '' !== trim(wp_strip_all_tags((string) $description))) : ?>
                    <div class="section-heading text-center gc-ppc-pain-heading">
                        <?php if ('' !== trim((string) $eyebrow)) : ?>
                            <h4 class="sub-heading gc-ppc-pain-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                        <?php endif; ?>
                        <?php if ('' !== trim((string) $title)) : ?>
                            <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                            <p class="gc-ppc-pain-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($cards)) : ?>
                    <div class="row gy-4 gc-ppc-pain-cards">
                        <?php foreach ($cards as $card) {
                            $this->render_pain_card($card, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_PPC_Pain_Widget());
