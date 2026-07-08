<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_CTA_Four_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_cta_four';
    }

    public function get_title()
    {
        return esc_html__('GC CTA Four', 'softro-core');
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

    private function render_icon_or_image($icon_settings, $icon_image, $args = [])
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, $args);
            return;
        }

        $icon_url = $this->get_media_url($icon_image ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function get_default_trust_items()
    {
        return [
            [
                'trust_icon' => ['value' => 'fa-light fa-shield-check', 'library' => 'fa-light'],
                'trust_text' => esc_html__('Free Trial Available', 'softro-core'),
            ],
            [
                'trust_icon' => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
                'trust_text' => esc_html__('24–48 Hour Turnaround', 'softro-core'),
            ],
            [
                'trust_icon' => ['value' => 'fa-light fa-badge-check', 'library' => 'fa-light'],
                'trust_text' => esc_html__('Quality Checked Before Delivery', 'softro-core'),
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
        $this->start_controls_section('gc_cta_four_content_section', [
            'label' => esc_html__('Content', 'softro-core'),
        ]);

        $this->add_control('gc_cta_four_main_icon', [
            'label'   => esc_html__('Main Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-paper-plane', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_cta_four_main_icon_image', [
            'label' => esc_html__('Main Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_cta_four_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Ready to Transform Your Product Images?', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_cta_four_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Send us your images and receive a free quote within hours. No upfront payment required.', 'softro-core'),
        ]);

        $this->add_control('gc_cta_four_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get Free Quote', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_cta_four_button_link', [
            'label'   => esc_html__('Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->add_control('gc_cta_four_button_icon', [
            'label'   => esc_html__('Button Left Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-file-lines', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_cta_four_button_icon_image', [
            'label' => esc_html__('Button Left Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_cta_four_button_arrow_icon', [
            'label'   => esc_html__('Button Arrow Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_cta_four_button_arrow_icon_image', [
            'label' => esc_html__('Button Arrow Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $trust_repeater = new Repeater();

        $trust_repeater->add_control('trust_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-shield-check', 'library' => 'fa-light'],
        ]);

        $trust_repeater->add_control('trust_icon_image', [
            'label' => esc_html__('Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $trust_repeater->add_control('trust_text', [
            'label'       => esc_html__('Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Free Trial Available', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_cta_four_trust_aria_label', [
            'label'       => esc_html__('Trust List Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Service guarantees', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_cta_four_trust_items', [
            'label'       => esc_html__('Trust Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $trust_repeater->get_controls(),
            'default'     => $this->get_default_trust_items(),
            'title_field' => '{{{ trust_text }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_cta_four_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_cta_four_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_cta_four_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top'    => '130',
                'right'  => '0',
                'bottom' => '130',
                'left'   => '0',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_four_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-cta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_four_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_four_section_overlay',
            'label'     => esc_html__('Section Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-bg-removal-cta::before',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_four_dots_color', [
            'label'     => esc_html__('Corner Dots Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-cta-dots' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_four_style_panel', [
            'label' => esc_html__('Panel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_panel_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-cta-panel',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_four_panel_glow',
            'label'     => esc_html__('Panel Glow / Shape', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-bg-removal-cta-glow',
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_cta_four_inner_padding', [
            'label'      => esc_html__('Inner Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-cta-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_four_main_icon_size', [
            'label'      => esc_html__('Main Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-cta-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-cta-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-cta-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_cta_four_main_icon_color', [
            'label'     => esc_html__('Main Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-cta-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-cta-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_four_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-cta-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_four_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-cta-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_cta_four_desc_typography',
            'label'    => esc_html__('Description Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-bg-removal-cta-desc',
        ]);

        $this->add_control('gc_cta_four_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-cta-desc' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_four_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_cta_four_btn_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-cta-btn-text',
        ]);

        $this->add_control('gc_cta_four_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-cta-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_btn_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-cta-btn',
        ]);

        $this->add_responsive_control('gc_cta_four_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-cta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_four_btn_icon_size', [
            'label'      => esc_html__('Button Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-cta-btn-icon i, {{WRAPPER}} .gc-bg-removal-cta-btn-arrow i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-cta-btn-icon svg, {{WRAPPER}} .gc-bg-removal-cta-btn-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-cta-btn-icon img, {{WRAPPER}} .gc-bg-removal-cta-btn-arrow img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_four_trust_typography',
            'label'     => esc_html__('Trust List Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-cta-trust li',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_four_trust_color', [
            'label'     => esc_html__('Trust List Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-cta-trust li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_cta_four_trust_icon_color', [
            'label'     => esc_html__('Trust Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-cta-trust li i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-cta-trust li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_cta_four_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_cta_four_theme_mode_tabs');

        $this->start_controls_tab('gc_cta_four_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_dark_panel_bg',
            'label'    => esc_html__('Panel Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-cta-panel',
        ]);

        $this->add_control('gc_cta_four_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-cta-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-cta-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_dark_btn_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-cta-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_dark_trust_color', [
            'label'     => esc_html__('Trust List Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-cta-trust li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_dark_main_icon_color', [
            'label'     => esc_html__('Main Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-cta-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-cta-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_cta_four_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_four_light_panel_bg',
            'label'    => esc_html__('Panel Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-cta-panel',
        ]);

        $this->add_control('gc_cta_four_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-cta-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-cta-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_light_btn_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-cta-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_light_trust_color', [
            'label'     => esc_html__('Trust List Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-cta-trust li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_four_light_main_icon_color', [
            'label'     => esc_html__('Main Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-cta-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-cta-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_cta_four_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_trust_item($item)
    {
        $text = $item['trust_text'] ?? '';

        if (!$text) {
            return;
        }
        ?>
        <li>
            <?php $this->render_icon_or_image($item['trust_icon'] ?? [], $item['trust_icon_image'] ?? [], ['aria-hidden' => 'true']); ?>
            <?php echo esc_html($text); ?>
        </li>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $title         = $settings['gc_cta_four_title'] ?? '';
        $description   = $settings['gc_cta_four_description'] ?? '';
        $button_text   = $settings['gc_cta_four_button_text'] ?? '';
        $button_link   = $settings['gc_cta_four_button_link'] ?? [];
        $main_icon     = $settings['gc_cta_four_main_icon'] ?? [];
        $main_icon_img = $settings['gc_cta_four_main_icon_image'] ?? [];
        $btn_icon      = $settings['gc_cta_four_button_icon'] ?? [];
        $btn_icon_img  = $settings['gc_cta_four_button_icon_image'] ?? [];
        $arrow_icon    = $settings['gc_cta_four_button_arrow_icon'] ?? [];
        $arrow_icon_img = $settings['gc_cta_four_button_arrow_icon_image'] ?? [];
        $trust_aria    = $settings['gc_cta_four_trust_aria_label'] ?? esc_html__('Service guarantees', 'softro-core');
        $trust_items   = !empty($settings['gc_cta_four_trust_items']) ? $settings['gc_cta_four_trust_items'] : [];
        ?>

        <section class="gc-bg-removal-cta pt-130 pb-130 fade-wrapper">
            <span class="gc-bg-removal-cta-dots gc-bg-removal-cta-dots--tl" aria-hidden="true"></span>
            <span class="gc-bg-removal-cta-dots gc-bg-removal-cta-dots--br" aria-hidden="true"></span>
            <div class="container">
                <div class="gc-bg-removal-cta-panel fade-top">
                    <div class="gc-bg-removal-cta-glow" aria-hidden="true"></div>
                    <div class="gc-bg-removal-cta-inner text-center">
                        <div class="gc-bg-removal-cta-icon-wrap" aria-hidden="true">
                            <span class="gc-bg-removal-cta-icon-ring gc-bg-removal-cta-icon-ring--1"></span>
                            <span class="gc-bg-removal-cta-icon-ring gc-bg-removal-cta-icon-ring--2"></span>
                            <span class="gc-bg-removal-cta-icon"><?php $this->render_icon_or_image($main_icon, $main_icon_img); ?></span>
                        </div>
                        <?php if ($title) : ?>
                            <h2 class="gc-bg-removal-cta-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ($description) : ?>
                            <p class="gc-bg-removal-cta-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>
                        <?php if ($button_text) : ?>
                            <a class="gc-bg-removal-cta-btn fade-top"<?php echo $this->get_link_attributes($button_link); ?>>
                                <span class="gc-bg-removal-cta-btn-icon" aria-hidden="true"><?php $this->render_icon_or_image($btn_icon, $btn_icon_img); ?></span>
                                <span class="gc-bg-removal-cta-btn-text"><?php echo esc_html($button_text); ?></span>
                                <span class="gc-bg-removal-cta-btn-arrow" aria-hidden="true"><?php $this->render_icon_or_image($arrow_icon, $arrow_icon_img); ?></span>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($trust_items)) : ?>
                            <ul class="gc-bg-removal-cta-trust fade-top" aria-label="<?php echo esc_attr($trust_aria); ?>">
                                <?php foreach ($trust_items as $item) {
                                    $this->render_trust_item($item);
                                } ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_CTA_Four_Widget());
