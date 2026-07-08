<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_Five_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_hero_banner_five';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner Five', 'softro-core');
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

    private function get_link_attributes($link_settings)
    {
        $url = !empty($link_settings['url']) ? $link_settings['url'] : '#';

        $attributes = ['href' => esc_url($url)];

        if (!empty($link_settings['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($link_settings['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        if (!empty($link_settings['custom_attributes'])) {
            foreach (Utils::parse_custom_attributes($link_settings['custom_attributes']) as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return $html;
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

    private function get_default_counters()
    {
        return [
            [
                'counter_number'     => 500,
                'counter_suffix'     => '+',
                'counter_label'      => esc_html__('Brands served', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-1.png')],
                'counter_card_class' => '',
            ],
            [
                'counter_number'     => 24,
                'counter_suffix'     => 'hr',
                'counter_label'      => esc_html__('Turnaround', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-2.png')],
                'counter_card_class' => '',
            ],
            [
                'counter_number'     => 10,
                'counter_suffix'     => '+',
                'counter_label'      => esc_html__('Years experience', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-3.png')],
                'counter_card_class' => '',
            ],
            [
                'counter_number'     => 200,
                'counter_suffix'     => '+',
                'counter_label'      => esc_html__('Projects complete', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-4.png')],
                'counter_card_class' => 'card-4',
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
        $this->start_controls_section('gc_hero_five_title_section', [
            'label' => esc_html__('Hero Title', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_title_main', [
            'label'       => esc_html__('Main Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Video Editing & Creative Design', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_accent_primary', [
            'label'       => esc_html__('Primary Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('That Help Products', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_accent_secondary', [
            'label'       => esc_html__('Secondary Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Services Sell.', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_content_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Built for Amazon sellers, eCommerce brands, and agencies that need scroll-stopping content — delivered fast, every time.', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_primary_btn_text', [
            'label'       => esc_html__('Primary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Start a Project', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_primary_btn_link', [
            'label'   => esc_html__('Primary Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->add_control('gc_hero_five_primary_btn_icon', [
            'label'   => esc_html__('Primary Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-rocket', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_hero_five_primary_btn_icon_image', [
            'label' => esc_html__('Primary Button Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_hero_five_secondary_btn_text', [
            'label'       => esc_html__('Secondary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('View Our Work', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_hero_five_secondary_btn_link', [
            'label'   => esc_html__('Secondary Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->end_controls_section();

        $counter_repeater = new Repeater();

        $counter_repeater->add_control('counter_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $counter_repeater->add_control('counter_icon_image', [
            'label'   => esc_html__('Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('icon/counter-1.png')],
        ]);

        $counter_repeater->add_control('counter_number', [
            'label'   => esc_html__('Number', 'softro-core'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 500,
            'min'     => 0,
        ]);

        $counter_repeater->add_control('counter_suffix', [
            'label'       => esc_html__('Suffix', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '+',
            'label_block' => true,
        ]);

        $counter_repeater->add_control('counter_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Brands served', 'softro-core'),
            'label_block' => true,
        ]);

        $counter_repeater->add_control('counter_card_class', [
            'label'   => esc_html__('Card Modifier', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                ''       => esc_html__('Default', 'softro-core'),
                'card-4' => esc_html__('Card 4', 'softro-core'),
            ],
        ]);

        $this->start_controls_section('gc_hero_five_counters_section', [
            'label' => esc_html__('Counter Stats', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_counters', [
            'label'       => esc_html__('Counters', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $counter_repeater->get_controls(),
            'default'     => $this->get_default_counters(),
            'title_field' => '{{{ counter_label }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_cards_section', [
            'label' => esc_html__('Right Column Cards', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_live_pill_text', [
            'label'       => esc_html__('Live Pill Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3 projects in production right now', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_featured_label', [
            'label'       => esc_html__('Featured Card Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Video Editing', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_hero_five_featured_title', [
            'label'       => esc_html__('Featured Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Amazon Product Video — Hero Edit', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_featured_description', [
            'label'   => esc_html__('Featured Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Lifestyle footage + motion graphics + A+ content optimization for listing conversion.', 'softro-core'),
        ]);

        $this->add_control('gc_hero_five_featured_foot_text', [
            'label'       => esc_html__('Featured Card Footer Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Delivered in 24hr', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_featured_foot_icon', [
            'label'   => esc_html__('Featured Footer Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_hero_five_featured_foot_icon_image', [
            'label' => esc_html__('Featured Footer Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_hero_five_compact_label', [
            'label'       => esc_html__('Compact Card Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D Rendering', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_hero_five_compact_title', [
            'label'       => esc_html__('Compact Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Photorealistic Product Render', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_compact_foot_text', [
            'label'       => esc_html__('Compact Card Footer Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('No photoshoot needed', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_five_stat_value', [
            'label'       => esc_html__('Stat Card Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '+64%',
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_hero_five_stat_label', [
            'label'   => esc_html__('Stat Card Label', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Avg. conversion lift across product pages', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_hero_five_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_hero_five_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_hero_five_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_hero_five_hero_bg',
            'label'     => esc_html__('Background Shape Layer', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-bg',
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_hero_bg_overlay',
            'label'    => esc_html__('Background Shape Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-bg::before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_title', [
            'label' => esc_html__('Hero Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_title_typography',
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-title',
        ]);

        $this->add_control('gc_hero_five_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_accent_primary_typography',
            'label'     => esc_html__('Primary Accent Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-accent--primary',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_accent_primary_color', [
            'label'     => esc_html__('Primary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-accent--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_accent_secondary_typography',
            'label'     => esc_html__('Secondary Accent Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-accent--secondary',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_accent_secondary_color', [
            'label'     => esc_html__('Secondary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-accent--secondary' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_desc_typography',
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-desc',
        ]);

        $this->add_control('gc_hero_five_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_buttons', [
            'label' => esc_html__('Buttons', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_primary_btn_typography',
            'label'    => esc_html__('Primary Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-btn--primary',
        ]);

        $this->add_control('gc_hero_five_primary_btn_color', [
            'label'     => esc_html__('Primary Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-btn--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_primary_btn_bg',
            'label'    => esc_html__('Primary Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-btn--primary',
        ]);

        $this->add_responsive_control('gc_hero_five_primary_btn_padding', [
            'label'      => esc_html__('Primary Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-btn--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_primary_btn_icon_size', [
            'label'      => esc_html__('Primary Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-3d-hero-btn--primary i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-hero-btn--primary svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-hero-btn--primary img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_hero_five_primary_btn_hover_color', [
            'label'     => esc_html__('Primary Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-btn--primary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_hero_five_primary_btn_hover_bg',
            'label'     => esc_html__('Primary Hover Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-btn--primary:hover',
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_secondary_btn_typography',
            'label'     => esc_html__('Secondary Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-btn--secondary',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_secondary_btn_color', [
            'label'     => esc_html__('Secondary Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-btn--secondary' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_secondary_btn_bg',
            'label'    => esc_html__('Secondary Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-btn--secondary',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_hero_five_secondary_btn_border',
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-btn--secondary',
        ]);

        $this->add_responsive_control('gc_hero_five_secondary_btn_padding', [
            'label'      => esc_html__('Secondary Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-btn--secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_hero_five_secondary_btn_hover_color', [
            'label'     => esc_html__('Secondary Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-btn--secondary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_secondary_btn_hover_bg',
            'label'    => esc_html__('Secondary Hover Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-btn--secondary:hover',
        ]);

        $this->add_responsive_control('gc_hero_five_btns_gap', [
            'label'      => esc_html__('Buttons Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-btns' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_counters', [
            'label' => esc_html__('Counter Stats', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_hero_five_counter_wrap_padding', [
            'label'      => esc_html__('Counter Wrap Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-process' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_counter_wrap_margin', [
            'label'      => esc_html__('Counter Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-process' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_hero_five_counter_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_counter_card_padding', [
            'label'      => esc_html__('Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_counter_title_typography',
            'label'     => esc_html__('Number Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-process .counter-card .content .title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_counter_title_color', [
            'label'     => esc_html__('Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card .content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_counter_label_typography',
            'label'    => esc_html__('Label Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-process .counter-card .content p',
        ]);

        $this->add_control('gc_hero_five_counter_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card .content p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_five_counter_icon_size', [
            'label'      => esc_html__('Icon Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-3d-hero-process .counter-card .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                '{{WRAPPER}} .gc-video-3d-hero-process .counter-card .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-hero-process .counter-card .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_hero_five_counter_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card .icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_hero_five_counter_icon_box_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-process .counter-card .icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_five_style_cards', [
            'label' => esc_html__('Right Cards', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_live_pill_typography',
            'label'    => esc_html__('Live Pill Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-pill--live',
        ]);

        $this->add_control('gc_hero_five_live_pill_color', [
            'label'     => esc_html__('Live Pill Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-pill--live' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_hero_five_live_pill_bg',
            'label'     => esc_html__('Live Pill Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-pill--live',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_card_label_color', [
            'label'     => esc_html__('Card Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-card-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_card_title_typography',
            'label'     => esc_html__('Card Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-card-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_card_desc_typography',
            'label'    => esc_html__('Featured Description Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-card-desc',
        ]);

        $this->add_control('gc_hero_five_card_desc_color', [
            'label'     => esc_html__('Featured Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-card-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_five_card_foot_color', [
            'label'     => esc_html__('Card Footer Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-card-foot' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_five_card_foot_muted_color', [
            'label'     => esc_html__('Muted Footer Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-card-foot--muted' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_hero_five_featured_card_bg',
            'label'     => esc_html__('Featured Card Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-card--featured',
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_hero_five_featured_card_padding', [
            'label'      => esc_html__('Featured Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-card--featured' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_compact_card_bg',
            'label'    => esc_html__('Compact Card Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-card--compact:not(.gc-video-3d-hero-card--stat)',
        ]);

        $this->add_responsive_control('gc_hero_five_compact_card_padding', [
            'label'      => esc_html__('Compact Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-hero-card--compact:not(.gc-video-3d-hero-card--stat)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_five_stat_value_typography',
            'label'     => esc_html__('Stat Value Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-hero-stat-value',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_five_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-stat-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_five_stat_label_typography',
            'label'    => esc_html__('Stat Label Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-stat-label',
        ]);

        $this->add_control('gc_hero_five_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-stat-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_stat_card_bg',
            'label'    => esc_html__('Stat Card Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-hero-card--stat',
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_hero_five_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_hero_five_theme_mode_tabs');

        $this->start_controls_tab('gc_hero_five_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-hero',
        ]);

        $this->add_control('gc_hero_five_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_accent_primary_color', [
            'label'     => esc_html__('Primary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-accent--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_accent_secondary_color', [
            'label'     => esc_html__('Secondary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-accent--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_secondary_btn_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_counter_card_bg', [
            'label'     => esc_html__('Counter Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-process .counter-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_counter_title_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-process .counter-card .content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_counter_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-process .counter-card .content p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_live_pill_color', [
            'label'     => esc_html__('Live Pill Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-pill--live' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_dark_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_hero_five_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_five_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-hero',
        ]);

        $this->add_control('gc_hero_five_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_accent_primary_color', [
            'label'     => esc_html__('Primary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-accent--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_accent_secondary_color', [
            'label'     => esc_html__('Secondary Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-accent--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_secondary_btn_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_counter_card_bg', [
            'label'     => esc_html__('Counter Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-process .counter-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_counter_title_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-process .counter-card .content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_counter_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-process .counter-card .content p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_live_pill_color', [
            'label'     => esc_html__('Live Pill Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-pill--live' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_five_light_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_hero_five_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> { margin-top: 0 !important; margin-bottom: 0 !important; }
            .elementor-element-<?php echo $widget_id; ?> > .elementor-widget-container { padding: 0 !important; margin: 0 !important; }
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
            .elementor-element-<?php echo $widget_id; ?> .overflow-hidden { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_button_icon($icon_settings, $icon_image)
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($icon_image ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function render_counter_icon($item)
    {
        if (!empty($item['counter_icon']['value'])) {
            $this->render_icon($item['counter_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['counter_icon_image'] ?? [], 'icon/counter-1.png');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="counter">';
        }
    }

    private function get_counter_card_class($modifier)
    {
        $classes = ['counter-card'];

        if ('card-4' === ($modifier ?? '')) {
            $classes[] = 'card-4';
        }

        return implode(' ', $classes);
    }

    private function render_counter_item($item)
    {
        $number   = isset($item['counter_number']) ? $item['counter_number'] : 0;
        $suffix   = $item['counter_suffix'] ?? '';
        $label    = $item['counter_label'] ?? '';
        $modifier = $item['counter_card_class'] ?? '';

        if (!$label && !$number) {
            return;
        }
        ?>
        <div class="col-6 col-lg-3">
            <div class="<?php echo esc_attr($this->get_counter_card_class($modifier)); ?>">
                <div class="icon"><?php $this->render_counter_icon($item); ?></div>
                <div class="content">
                    <h3 class="title">
                        <span class="odometer" data-count="<?php echo esc_attr($number); ?>">0</span><?php echo esc_html($suffix); ?>
                    </h3>
                    <?php if ($label) : ?>
                        <p><?php echo esc_html($label); ?></p>
                    <?php endif; ?>
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

        $title_main        = $settings['gc_hero_five_title_main'] ?? '';
        $accent_primary    = $settings['gc_hero_five_accent_primary'] ?? '';
        $accent_secondary  = $settings['gc_hero_five_accent_secondary'] ?? '';
        $description       = $settings['gc_hero_five_description'] ?? '';
        $primary_btn_text  = $settings['gc_hero_five_primary_btn_text'] ?? '';
        $primary_btn_link  = $settings['gc_hero_five_primary_btn_link'] ?? [];
        $primary_btn_icon  = $settings['gc_hero_five_primary_btn_icon'] ?? [];
        $primary_icon_img  = $settings['gc_hero_five_primary_btn_icon_image'] ?? [];
        $secondary_btn_text = $settings['gc_hero_five_secondary_btn_text'] ?? '';
        $secondary_btn_link = $settings['gc_hero_five_secondary_btn_link'] ?? [];
        $counters          = !empty($settings['gc_hero_five_counters']) ? $settings['gc_hero_five_counters'] : [];
        $live_pill         = $settings['gc_hero_five_live_pill_text'] ?? '';
        $featured_label    = $settings['gc_hero_five_featured_label'] ?? '';
        $featured_title    = $settings['gc_hero_five_featured_title'] ?? '';
        $featured_desc     = $settings['gc_hero_five_featured_description'] ?? '';
        $featured_foot     = $settings['gc_hero_five_featured_foot_text'] ?? '';
        $featured_foot_icon = $settings['gc_hero_five_featured_foot_icon'] ?? [];
        $featured_foot_img = $settings['gc_hero_five_featured_foot_icon_image'] ?? [];
        $compact_label     = $settings['gc_hero_five_compact_label'] ?? '';
        $compact_title     = $settings['gc_hero_five_compact_title'] ?? '';
        $compact_foot      = $settings['gc_hero_five_compact_foot_text'] ?? '';
        $stat_value        = $settings['gc_hero_five_stat_value'] ?? '';
        $stat_label        = $settings['gc_hero_five_stat_label'] ?? '';
        ?>

        <section class="gc-video-3d-hero fade-wrapper">
            <div class="gc-video-3d-hero-bg" aria-hidden="true"></div>
            <div class="container">
                <div class="row g-4 g-xl-5 align-items-stretch gc-video-3d-hero-row">
                    <div class="col-lg-7">
                        <div class="gc-video-3d-hero-left">
                            <div class="gc-video-3d-hero-content fade-wrapper">
                                <?php if ($title_main || $accent_primary || $accent_secondary) : ?>
                                    <h1 class="gc-video-3d-hero-title overflow-hidden" data-text-animation data-split="word" data-duration="1">
                                        <?php if ($title_main) : ?>
                                            <?php echo esc_html($title_main); ?>
                                        <?php endif; ?>
                                        <?php if ($accent_primary) : ?>
                                            <span class="gc-video-3d-hero-accent gc-video-3d-hero-accent--primary"><?php echo esc_html($accent_primary); ?></span>
                                        <?php endif; ?>
                                        <?php if ($accent_secondary) : ?>
                                            <span class="gc-video-3d-hero-accent gc-video-3d-hero-accent--secondary"><?php echo esc_html($accent_secondary); ?></span>
                                        <?php endif; ?>
                                    </h1>
                                <?php endif; ?>

                                <?php if ($description) : ?>
                                    <p class="gc-video-3d-hero-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                                <?php endif; ?>

                                <?php if ($primary_btn_text || $secondary_btn_text) : ?>
                                    <div class="gc-video-3d-hero-btns fade-top">
                                        <?php if ($primary_btn_text) : ?>
                                            <a class="gc-video-3d-hero-btn gc-video-3d-hero-btn--primary"<?php echo $this->get_link_attributes($primary_btn_link); ?>>
                                                <?php $this->render_button_icon($primary_btn_icon, $primary_icon_img); ?> <?php echo esc_html($primary_btn_text); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($secondary_btn_text) : ?>
                                            <a class="gc-video-3d-hero-btn gc-video-3d-hero-btn--secondary"<?php echo $this->get_link_attributes($secondary_btn_link); ?>><?php echo esc_html($secondary_btn_text); ?></a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if (!empty($counters)) : ?>
                                <div class="process-counter new gc-video-3d-hero-process fade-top">
                                    <div class="row g-3 g-lg-0 process-counter-wrap">
                                        <?php foreach ($counters as $counter) {
                                            $this->render_counter_item($counter);
                                        } ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="gc-video-3d-hero-cards fade-top">
                            <?php if ($live_pill) : ?>
                                <span class="gc-video-3d-hero-pill gc-video-3d-hero-pill--live">
                                    <span class="gc-video-3d-hero-pill-dot gc-video-3d-hero-pill-dot--pulse" aria-hidden="true"></span>
                                    <?php echo esc_html($live_pill); ?>
                                </span>
                            <?php endif; ?>

                            <?php if ($featured_label || $featured_title || $featured_desc || $featured_foot) : ?>
                                <article class="gc-video-3d-hero-card gc-video-3d-hero-card--featured">
                                    <?php if ($featured_label) : ?>
                                        <span class="gc-video-3d-hero-card-label"><?php echo esc_html($featured_label); ?></span>
                                    <?php endif; ?>
                                    <?php if ($featured_title) : ?>
                                        <h3 class="gc-video-3d-hero-card-title"><?php echo esc_html($featured_title); ?></h3>
                                    <?php endif; ?>
                                    <?php if ($featured_desc) : ?>
                                        <p class="gc-video-3d-hero-card-desc"><?php echo $this->get_paragraph_inner_content($featured_desc); ?></p>
                                    <?php endif; ?>
                                    <?php if ($featured_foot) : ?>
                                        <span class="gc-video-3d-hero-card-foot">
                                            <?php $this->render_button_icon($featured_foot_icon, $featured_foot_img); ?> <?php echo esc_html($featured_foot); ?>
                                        </span>
                                    <?php endif; ?>
                                </article>
                            <?php endif; ?>

                            <div class="row g-3">
                                <?php if ($compact_label || $compact_title || $compact_foot) : ?>
                                    <div class="col-sm-6">
                                        <article class="gc-video-3d-hero-card gc-video-3d-hero-card--compact">
                                            <?php if ($compact_label) : ?>
                                                <span class="gc-video-3d-hero-card-label"><?php echo esc_html($compact_label); ?></span>
                                            <?php endif; ?>
                                            <?php if ($compact_title) : ?>
                                                <h3 class="gc-video-3d-hero-card-title"><?php echo esc_html($compact_title); ?></h3>
                                            <?php endif; ?>
                                            <?php if ($compact_foot) : ?>
                                                <span class="gc-video-3d-hero-card-foot gc-video-3d-hero-card-foot--muted"><?php echo esc_html($compact_foot); ?></span>
                                            <?php endif; ?>
                                        </article>
                                    </div>
                                <?php endif; ?>

                                <?php if ($stat_value || $stat_label) : ?>
                                    <div class="col-sm-6">
                                        <article class="gc-video-3d-hero-card gc-video-3d-hero-card--compact gc-video-3d-hero-card--stat">
                                            <?php if ($stat_value) : ?>
                                                <span class="gc-video-3d-hero-stat-value"><?php echo esc_html($stat_value); ?></span>
                                            <?php endif; ?>
                                            <?php if ($stat_label) : ?>
                                                <p class="gc-video-3d-hero-stat-label"><?php echo $this->get_paragraph_inner_content($stat_label); ?></p>
                                            <?php endif; ?>
                                        </article>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_Five_Widget());
