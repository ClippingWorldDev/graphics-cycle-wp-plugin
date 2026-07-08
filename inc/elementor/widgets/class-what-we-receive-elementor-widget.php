<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_What_We_Receive_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_what_we_recieve';
    }

    public function get_title()
    {
        return esc_html__('GC What We Receive', 'softro-core');
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

        if (!empty($settings['gc_wwr_default_card_icon']['value'])) {
            $this->render_icon($settings['gc_wwr_default_card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $default_icon_url = $this->get_media_url($settings['gc_wwr_default_card_icon_image'] ?? []);

        if ($default_icon_url) {
            echo '<img src="' . esc_url($default_icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-light fa-video" aria-hidden="true"></i>';
    }

    private function get_default_cards()
    {
        return [
            [
                'card_icon'        => ['value' => 'fa-light fa-video', 'library' => 'fa-light'],
                'card_title'       => esc_html__('MP4 / MOV video', 'softro-core'),
                'card_description' => esc_html__(
                    'Up to 4K resolution, multiple aspect ratios (16:9, 9:16, 1:1)',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-image', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Still renders', 'softro-core'),
                'card_description' => esc_html__(
                    'PNG / TIFF stills at any frame for use in banners, ads, and print',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-photo-film', 'library' => 'fa-light'],
                'card_title'       => esc_html__('WebM / GIF', 'softro-core'),
                'card_description' => esc_html__(
                    'Lightweight looping formats for web embedding and social stories',
                    'softro-core'
                ),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-download', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Source files', 'softro-core'),
                'card_description' => esc_html__(
                    '3D project files (on request) — Blender, Cinema 4D, or 3ds Max',
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
        $this->start_controls_section('gc_wwr_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_wwr_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('What You Receive', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_wwr_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('What you get with every project', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_wwr_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Every 3D animation project includes a complete set of files and formats — no extra charges for exports.',
                'softro-core'
            ),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-video', 'library' => 'fa-light'],
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('MP4 / MOV video', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Up to 4K resolution, multiple aspect ratios (16:9, 9:16, 1:1)',
                'softro-core'
            ),
        ]);

        $this->start_controls_section('gc_wwr_cards_section', [
            'label' => esc_html__('Deliverables Cards', 'softro-core'),
        ]);

        $this->add_control('gc_wwr_default_card_icon', [
            'label'   => esc_html__('Default Card Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-video', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_wwr_default_card_icon_image', [
            'label'       => esc_html__('Default Card Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_wwr_cards', [
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
        $this->start_controls_section('gc_wwr_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_wwr_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_wwr_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-grid' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_column_gap', [
            'label'      => esc_html__('Column Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-grid' => 'column-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwr_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_wwr_eyebrow_heading', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwr_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-eyebrow',
        ]);

        $this->add_control('gc_wwr_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wwr_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwr_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-title',
        ]);

        $this->add_control('gc_wwr_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wwr_intro_heading', [
            'label'     => esc_html__('Intro', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwr_intro_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-intro',
        ]);

        $this->add_control('gc_wwr_intro_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_intro_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_header_margin', [
            'label'      => esc_html__('Header Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'separator'  => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_grid', [
            'label' => esc_html__('Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wwr_grid_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_card', [
            'label' => esc_html__('Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwr_card_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-card',
        ]);

        $this->add_responsive_control('gc_wwr_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_wwr_card_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_wwr_card_shadow',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-card',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_card_icon', [
            'label' => esc_html__('Card Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wwr_card_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-deliverables-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-deliverables-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-deliverables-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_wwr_card_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-deliverables-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-deliverables-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_wwr_card_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_icon_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_icon_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwr_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-card h3',
        ]);

        $this->add_control('gc_wwr_card_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-card h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-card h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwr_style_card_description', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwr_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-deliverables-card p',
        ]);

        $this->add_control('gc_wwr_card_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-deliverables-card p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwr_card_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-deliverables-card p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_wwr_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_wwr_theme_mode_tabs');

        $this->start_controls_tab('gc_wwr_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwr_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-deliverables',
        ]);

        $this->add_control('gc_wwr_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_card_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-deliverables-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-deliverables-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wwr_dark_card_icon_bg', [
            'label'     => esc_html__('Card Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-deliverables-card p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_wwr_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwr_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-deliverables',
        ]);

        $this->add_control('gc_wwr_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_card_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-deliverables-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-deliverables-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wwr_light_card_icon_bg', [
            'label'     => esc_html__('Card Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwr_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-deliverables-card p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_wwr_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_deliverable_card(array $card, array $settings)
    {
        $title       = trim((string) ($card['card_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($card['card_description'] ?? '');

        if ('' === $title && '' === $description) {
            return;
        }
        ?>
        <div class="col-md-6 col-lg-3">
            <article class="gc-3d-anim-deliverables-card fade-top">
                <span class="gc-3d-anim-deliverables-icon" aria-hidden="true"><?php $this->render_card_icon($card, $settings); ?></span>
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

        $eyebrow = trim((string) ($settings['gc_wwr_eyebrow'] ?? ''));
        $title   = trim((string) ($settings['gc_wwr_title'] ?? ''));
        $intro   = $this->get_paragraph_inner_content($settings['gc_wwr_intro'] ?? '');
        $cards   = !empty($settings['gc_wwr_cards']) ? $settings['gc_wwr_cards'] : $this->get_default_cards();
        ?>

        <section class="gc-3d-anim-deliverables pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-3d-anim-deliverables-header">
                    <?php if ('' !== $eyebrow) : ?>
                        <span class="gc-3d-anim-deliverables-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ('' !== $title) : ?>
                        <h2 class="gc-3d-anim-deliverables-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== $intro) : ?>
                        <p class="gc-3d-anim-deliverables-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo wp_kses($intro, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($cards)) : ?>
                    <div class="row g-4 gc-3d-anim-deliverables-grid">
                        <?php foreach ($cards as $card) {
                            $this->render_deliverable_card($card, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_What_We_Receive_Widget());
