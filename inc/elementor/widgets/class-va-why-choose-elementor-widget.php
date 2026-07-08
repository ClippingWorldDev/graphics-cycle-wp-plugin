<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_VA_Why_Choose_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_va_why_choose';
    }

    public function get_title()
    {
        return esc_html__('GC VA Why Choose', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    private function get_media_url($media)
    {
        if (!empty($media['url'])) {
            return esc_url($media['url']);
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

    private function render_card_icon(array $card, array $settings)
    {
        if (!empty($card['card_icon']['value'])) {
            $this->render_icon($card['card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($card['card_icon_image'] ?? []);

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_va_wc_default_card_icon']['value'])) {
            $this->render_icon($settings['gc_va_wc_default_card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $default_icon_url = $this->get_media_url($settings['gc_va_wc_default_card_icon_image'] ?? []);

        if ($default_icon_url) {
            echo '<img src="' . esc_url($default_icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-light fa-clock" aria-hidden="true"></i>';
    }

    private function get_default_cards()
    {
        return [
            [
                'card_icon'        => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Fast turnaround', 'softro-core'),
                'card_description' => esc_html__(
                    'Most projects delivered within 48–72 hours. Rush delivery available for time-sensitive campaigns.',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-shield-check', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Photorealistic quality', 'softro-core'),
                'card_description' => esc_html__(
                    '4K output with physically based rendering, accurate lighting, and materials that look real.',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-arrows-rotate', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Unlimited revisions', 'softro-core'),
                'card_description' => esc_html__(
                    'We refine until you are 100% satisfied. No hidden revision charges, no limit on feedback rounds.',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-display', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Multi-format delivery', 'softro-core'),
                'card_description' => esc_html__(
                    'Receive your files in MP4, MOV, GIF, WebM, and still frames — ready for every platform.',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-users', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Dedicated B2B team', 'softro-core'),
                'card_description' => esc_html__(
                    'A team that understands ecommerce, product marketing, and brand communication at scale.',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-circle-dollar', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Transparent pricing', 'softro-core'),
                'card_description' => esc_html__(
                    'No surprise costs. Fixed project quotes before we start, with clear scope and deliverable list.',
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
        $this->start_controls_section('gc_va_wc_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_va_wc_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Why Choose Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wc_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Why clients choose ', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_va_wc_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Graphics Cycle', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wc_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__(' for 3D animation', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wc_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'We combine technical precision with creative storytelling to deliver 3D animations that work — not just look good.',
                'softro-core'
            ),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Fast turnaround', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Most projects delivered within 48–72 hours. Rush delivery available for time-sensitive campaigns.',
                'softro-core'
            ),
        ]);

        $this->start_controls_section('gc_va_wc_cards_section', [
            'label' => esc_html__('Why Choose Cards', 'softro-core'),
        ]);

        $this->add_control('gc_va_wc_default_card_icon', [
            'label'   => esc_html__('Default Card Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_va_wc_default_card_icon_image', [
            'label'       => esc_html__('Default Card Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_va_wc_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_cards(),
            'title_field' => '{{{ card_title }}}',
            'separator'   => 'before',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_va_wc_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_wc_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_va_wc_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-choose' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-choose' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-grid' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_column_gap', [
            'label'      => esc_html__('Column Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-grid' => 'column-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wc_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-choose',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_wc_eyebrow_heading', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-eyebrow',
        ]);

        $this->add_control('gc_va_wc_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_wc_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-title',
        ]);

        $this->add_control('gc_va_wc_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_wc_accent_heading', [
            'label'     => esc_html__('Accent', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_accent_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-accent',
        ]);

        $this->add_control('gc_va_wc_accent_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_wc_intro_heading', [
            'label'     => esc_html__('Intro', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_intro_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-intro',
        ]);

        $this->add_control('gc_va_wc_intro_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_intro_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_header_margin', [
            'label'      => esc_html__('Header Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'separator'  => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_grid', [
            'label' => esc_html__('Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wc_grid_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_card', [
            'label' => esc_html__('Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wc_card_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-card',
        ]);

        $this->add_responsive_control('gc_va_wc_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_va_wc_card_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_va_wc_card_shadow',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-card',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_card_icon', [
            'label' => esc_html__('Card Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wc_card_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-why-card-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-why-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-why-card-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_va_wc_card_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-why-card-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-why-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_va_wc_card_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-card-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_icon_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_icon_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-card h3',
        ]);

        $this->add_control('gc_va_wc_card_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-card h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wc_style_card_description', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wc_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-why-card p',
        ]);

        $this->add_control('gc_va_wc_card_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-why-card p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wc_card_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-why-card p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_va_wc_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_va_wc_theme_mode_tabs');

        $this->start_controls_tab('gc_va_wc_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wc_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-why-choose',
        ]);

        $this->add_control('gc_va_wc_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_card_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-why-card-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-why-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wc_dark_card_icon_bg', [
            'label'     => esc_html__('Card Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-card-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-why-card p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_va_wc_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wc_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-why-choose',
        ]);

        $this->add_control('gc_va_wc_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_card_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-why-card-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-why-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wc_light_card_icon_bg', [
            'label'     => esc_html__('Card Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-card-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wc_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-why-card p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_va_wc_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_editor_preview_fix()
    {
        if (!Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .fade-top,
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top,
            .elementor-element-<?php echo $widget_id; ?> [data-text-animation],
            .elementor-element-<?php echo $widget_id; ?> .overflow-hidden {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    private function render_why_card(array $card, array $settings)
    {
        $title       = trim((string) ($card['card_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($card['card_description'] ?? '');

        if ('' === $title && '' === $description) {
            return;
        }
        ?>
        <div class="col-md-6 col-lg-4">
            <article class="gc-3d-anim-why-card fade-top">
                <span class="gc-3d-anim-why-card-icon" aria-hidden="true"><?php $this->render_card_icon($card, $settings); ?></span>
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== $description) : ?>
                    <p><?php echo wp_kses($description, ['br' => []]); ?></p>
                <?php endif; ?>
            </article>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow      = trim((string) ($settings['gc_va_wc_eyebrow'] ?? ''));
        $title_before = $settings['gc_va_wc_title_before'] ?? '';
        $title_accent = $settings['gc_va_wc_title_accent'] ?? '';
        $title_after  = $settings['gc_va_wc_title_after'] ?? '';
        $intro        = $this->get_paragraph_inner_content($settings['gc_va_wc_intro'] ?? '');
        $cards        = !empty($settings['gc_va_wc_cards']) ? $settings['gc_va_wc_cards'] : $this->get_default_cards();
        ?>

        <section class="gc-3d-anim-why-choose pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-3d-anim-why-header">
                    <?php if ('' !== $eyebrow) : ?>
                        <span class="gc-3d-anim-why-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ($title_before || $title_accent || $title_after) : ?>
                        <h2 class="gc-3d-anim-why-title overflow-hidden" data-text-animation="fade-in-right"
                            data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title_before); ?><?php if ($title_accent) : ?><span
                                class="gc-3d-anim-why-accent"><?php echo esc_html($title_accent); ?></span><?php endif; ?><?php echo esc_html($title_after); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== $intro) : ?>
                        <p class="gc-3d-anim-why-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo wp_kses($intro, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($cards)) : ?>
                    <div class="row g-4 g-lg-4 gc-3d-anim-why-grid">
                        <?php foreach ($cards as $card) {
                            $this->render_why_card($card, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_VA_Why_Choose_Widget());
