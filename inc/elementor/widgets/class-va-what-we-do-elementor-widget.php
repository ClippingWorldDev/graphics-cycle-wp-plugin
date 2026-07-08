<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_VA_What_We_Do_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_va_what_we_do';
    }

    public function get_title()
    {
        return esc_html__('GC VA What We Do', 'softro-core');
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

    private function render_item_icon(array $item, array $settings)
    {
        if (!empty($item['item_icon']['value'])) {
            $this->render_icon($item['item_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['item_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_va_wwd_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_va_wwd_default_icon']['value'])) {
            $this->render_icon($settings['gc_va_wwd_default_icon'], ['aria-hidden' => 'true']);
            return;
        }

        echo '<i class="fa-light fa-eye" aria-hidden="true"></i>';
    }

    private function render_label_icon(array $settings)
    {
        if (!empty($settings['gc_va_wwd_visual_label_icon']['value'])) {
            $this->render_icon($settings['gc_va_wwd_visual_label_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_va_wwd_visual_label_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-light fa-video" aria-hidden="true"></i>';
    }

    private function get_default_list_items()
    {
        return [
            [
                'item_icon'        => ['value' => 'fa-light fa-eye', 'library' => 'fa-light'],
                'item_title'       => esc_html__('Show what photography cannot', 'softro-core'),
                'item_description' => esc_html__(
                    'Reveal interiors, motion, and scale that flat photos or live shoots can\'t capture.',
                    'softro-core'
                ),
            ],
            [
                'item_icon'        => ['value' => 'fa-light fa-chart-line-up', 'library' => 'fa-light'],
                'item_title'       => esc_html__('Improve product clarity', 'softro-core'),
                'item_description' => esc_html__(
                    'Highlight features, materials, and use cases with controlled lighting and perfect consistency.',
                    'softro-core'
                ),
            ],
            [
                'item_icon'        => ['value' => 'fa-light fa-globe', 'library' => 'fa-light'],
                'item_title'       => esc_html__('Scale across every channel', 'softro-core'),
                'item_description' => esc_html__(
                    'Use one 3D asset for websites, Amazon listings, paid ads, pitch decks, and trade show screens.',
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
        $this->start_controls_section('gc_va_wwd_content_section', [
            'label' => esc_html__('Content', 'softro-core'),
        ]);

        $this->add_control('gc_va_wwd_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('What We Do', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wwd_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('What is ', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wwd_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D animation', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wwd_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__(' and why does your brand need it?', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wwd_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                '3D animation turns complex ideas, products, and services into clear, engaging visuals that build trust and drive action. From product demos to brand films, it helps customers understand value before they buy — on web, social, ads, and presentations.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_defaults_section', [
            'label' => esc_html__('List Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_va_wwd_default_icon', [
            'label'   => esc_html__('Default Item Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-eye', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_va_wwd_default_icon_image', [
            'label'       => esc_html__('Default Item Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $item_repeater = new Repeater();

        $item_repeater->add_control('item_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-eye', 'library' => 'fa-light'],
        ]);

        $item_repeater->add_control('item_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $item_repeater->add_control('item_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Show what photography cannot', 'softro-core'),
            'label_block' => true,
        ]);

        $item_repeater->add_control('item_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Item description goes here.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_va_wwd_list_section', [
            'label' => esc_html__('List Items', 'softro-core'),
        ]);

        $this->add_control('gc_va_wwd_list_items', [
            'label'       => esc_html__('Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $item_repeater->get_controls(),
            'default'     => $this->get_default_list_items(),
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_visual_section', [
            'label' => esc_html__('Visual', 'softro-core'),
        ]);

        $this->add_control('gc_va_wwd_visual_image', [
            'label'       => esc_html__('Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
        ]);

        $this->add_control('gc_va_wwd_visual_image_alt', [
            'label'       => esc_html__('Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D animation explainer visual for product marketing', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_wwd_visual_label_text', [
            'label'       => esc_html__('Label Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Explainer visual / infographic', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_va_wwd_visual_label_icon', [
            'label'   => esc_html__('Label Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-video', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_va_wwd_visual_label_icon_image', [
            'label'       => esc_html__('Label Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_va_wwd_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_wwd_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_va_wwd_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-we-do' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-we-do' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wwd_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-we-do',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_content_column', [
            'label' => esc_html__('Content Column', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wwd_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-eyebrow',
        ]);

        $this->add_control('gc_va_wwd_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-title',
        ]);

        $this->add_control('gc_va_wwd_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_accent', [
            'label' => esc_html__('Accent', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_accent_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-accent',
        ]);

        $this->add_control('gc_va_wwd_accent_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-accent' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-desc',
        ]);

        $this->add_control('gc_va_wwd_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_list_item', [
            'label' => esc_html__('List Item', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wwd_list_gap', [
            'label'      => esc_html__('List Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-list' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_list_margin', [
            'label'      => esc_html__('List Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_item_gap', [
            'label'      => esc_html__('Item Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-item' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_item_margin', [
            'label'      => esc_html__('Item Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_list_icon', [
            'label' => esc_html__('List Item Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wwd_list_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_list_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-what-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-what-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-what-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_va_wwd_list_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-what-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-what-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_va_wwd_list_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_list_icon_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_list_title', [
            'label' => esc_html__('List Item Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_list_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-item-copy h3',
        ]);

        $this->add_control('gc_va_wwd_list_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-item-copy h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_list_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-item-copy h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_list_desc', [
            'label' => esc_html__('List Item Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_wwd_list_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-item-copy p',
        ]);

        $this->add_control('gc_va_wwd_list_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-item-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_list_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-item-copy p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_visual_wrap', [
            'label' => esc_html__('Visual Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_wrap_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_wrap_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_visual_frame', [
            'label' => esc_html__('Visual Frame / Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_va_wwd_visual_frame_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-visual-frame',
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_frame_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-frame' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_image_radius', [
            'label'      => esc_html__('Image Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-frame img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_va_wwd_visual_frame_shadow',
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-visual-frame',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_visual_overlay', [
            'label' => esc_html__('Visual Overlay / Label', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wwd_visual_overlay_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-visual-overlay',
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_overlay_padding', [
            'label'      => esc_html__('Overlay Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-overlay' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_va_wwd_visual_label_typography',
            'label'     => esc_html__('Label Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-3d-anim-what-visual-label',
            'separator' => 'before',
        ]);

        $this->add_control('gc_va_wwd_visual_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-visual-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_wwd_visual_label_bg', [
            'label'     => esc_html__('Label Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-what-visual-label' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_wwd_visual_label_icon_color', [
            'label'     => esc_html__('Label Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-what-visual-label i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-what-visual-label svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_label_icon_size', [
            'label'      => esc_html__('Label Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-what-visual-label i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-what-visual-label svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-what-visual-label img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_label_padding', [
            'label'      => esc_html__('Label Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_label_radius', [
            'label'      => esc_html__('Label Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_wwd_style_visual_glow', [
            'label' => esc_html__('Visual Glow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wwd_visual_glow_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-what-visual-glow',
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_glow_width', [
            'label'      => esc_html__('Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-glow' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_glow_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-glow' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_glow_offset_x', [
            'label'      => esc_html__('Offset X', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-glow' => 'left: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_wwd_visual_glow_offset_y', [
            'label'      => esc_html__('Offset Y', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-what-visual-glow' => 'top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_va_wwd_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_va_wwd_theme_mode_tabs');

        $this->start_controls_tab('gc_va_wwd_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wwd_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-what-we-do',
        ]);

        $this->add_control('gc_va_wwd_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_list_title_color', [
            'label'     => esc_html__('List Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-item-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_list_desc_color', [
            'label'     => esc_html__('List Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-item-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_list_icon_color', [
            'label'     => esc_html__('List Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-what-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-what-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wwd_dark_list_icon_bg', [
            'label'     => esc_html__('List Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_visual_label_color', [
            'label'     => esc_html__('Visual Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-visual-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_visual_label_bg', [
            'label'     => esc_html__('Visual Label Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-visual-label' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_dark_visual_label_icon_color', [
            'label'     => esc_html__('Visual Label Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-what-visual-label i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-what-visual-label svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wwd_dark_visual_glow_bg', [
            'label'     => esc_html__('Visual Glow Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-what-visual-glow' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_va_wwd_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_wwd_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-what-we-do',
        ]);

        $this->add_control('gc_va_wwd_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_list_title_color', [
            'label'     => esc_html__('List Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-item-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_list_desc_color', [
            'label'     => esc_html__('List Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-item-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_list_icon_color', [
            'label'     => esc_html__('List Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-what-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-what-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wwd_light_list_icon_bg', [
            'label'     => esc_html__('List Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_visual_label_color', [
            'label'     => esc_html__('Visual Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-visual-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_visual_label_bg', [
            'label'     => esc_html__('Visual Label Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-visual-label' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_wwd_light_visual_label_icon_color', [
            'label'     => esc_html__('Visual Label Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-what-visual-label i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-what-visual-label svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_wwd_light_visual_glow_bg', [
            'label'     => esc_html__('Visual Glow Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-what-visual-glow' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_va_wwd_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_list_item(array $item, array $settings)
    {
        $title       = trim((string) ($item['item_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($item['item_description'] ?? '');

        if ('' === $title && '' === $description) {
            return;
        }
        ?>
        <li class="gc-3d-anim-what-item">
            <span class="gc-3d-anim-what-icon" aria-hidden="true"><?php $this->render_item_icon($item, $settings); ?></span>
            <div class="gc-3d-anim-what-item-copy">
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== $description) : ?>
                    <p><?php echo wp_kses($description, ['br' => []]); ?></p>
                <?php endif; ?>
            </div>
        </li>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow      = trim((string) ($settings['gc_va_wwd_eyebrow'] ?? ''));
        $title_before = $settings['gc_va_wwd_title_before'] ?? '';
        $title_accent = $settings['gc_va_wwd_title_accent'] ?? '';
        $title_after  = $settings['gc_va_wwd_title_after'] ?? '';
        $description  = $this->get_paragraph_inner_content($settings['gc_va_wwd_description'] ?? '');
        $list_items   = !empty($settings['gc_va_wwd_list_items']) ? $settings['gc_va_wwd_list_items'] : $this->get_default_list_items();
        $visual_url   = $this->get_media_url($settings['gc_va_wwd_visual_image'] ?? [], 'new-update/hero-img-1.png');
        $visual_alt   = $settings['gc_va_wwd_visual_image_alt'] ?? '';
        $label_text   = trim((string) ($settings['gc_va_wwd_visual_label_text'] ?? ''));
        ?>

        <section class="gc-3d-anim-what-we-do pt-130 pb-130">
            <div class="container">
                <div class="row g-4 g-xl-5 align-items-center gc-3d-anim-what-row">
                    <div class="col-lg-6">
                        <div class="gc-3d-anim-what-content">
                            <?php if ('' !== $eyebrow) : ?>
                                <span class="gc-3d-anim-what-eyebrow"><?php echo esc_html($eyebrow); ?></span>
                            <?php endif; ?>
                            <?php if ($title_before || $title_accent || $title_after) : ?>
                                <h2 class="gc-3d-anim-what-title"><?php echo esc_html($title_before); ?><?php if ($title_accent) : ?><span class="gc-3d-anim-what-accent"><?php echo esc_html($title_accent); ?></span><?php endif; ?><?php echo esc_html($title_after); ?></h2>
                            <?php endif; ?>
                            <?php if ('' !== $description) : ?>
                                <p class="gc-3d-anim-what-desc"><?php echo wp_kses($description, ['br' => []]); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($list_items)) : ?>
                                <ul class="gc-3d-anim-what-list">
                                    <?php foreach ($list_items as $item) {
                                        $this->render_list_item($item, $settings);
                                    } ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gc-3d-anim-what-visual">
                            <div class="gc-3d-anim-what-visual-frame">
                                <?php if ($visual_url) : ?>
                                    <img src="<?php echo esc_url($visual_url); ?>"
                                        alt="<?php echo esc_attr($visual_alt); ?>">
                                <?php endif; ?>
                                <?php if ('' !== $label_text) : ?>
                                    <div class="gc-3d-anim-what-visual-overlay">
                                        <span class="gc-3d-anim-what-visual-label">
                                            <?php $this->render_label_icon($settings); ?>
                                            <?php echo esc_html($label_text); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span class="gc-3d-anim-what-visual-glow" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_VA_What_We_Do_Widget());
