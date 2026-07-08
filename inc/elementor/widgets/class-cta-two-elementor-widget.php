<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_CTA_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_cta_two';
    }

    public function get_title()
    {
        return esc_html__('GC CTA Two', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_cta_two_content_section', [
            'label' => esc_html__('Content', 'softro-core'),
        ]);

        $this->add_control('gc_cta_two_banner_text', [
            'label'   => esc_html__('Banner Text', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__("Don't worry, we turn your marketing challenges into massive growth opportunities. Our AI data-driven strategies increase online visibility, qualified leads, improve revenue, and stay on top.", 'softro-core'),
        ]);

        $this->add_control('gc_cta_two_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_cta_two_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => [
                'url'         => '#',
                'is_external' => false,
                'nofollow'    => false,
            ],
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_cta_two_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_cta_two_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_cta_two_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top'    => '130',
                'right'  => '0',
                'bottom' => '130',
                'left'   => '0',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_two_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-cta-banner',
        ]);

        $this->add_control('gc_cta_two_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-cta-banner' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_two_section_overlay',
            'label'     => esc_html__('Section Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-cta-banner::before',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_two_style_inner', [
            'label' => esc_html__('Inner Box', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_inner_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_responsive_control('gc_cta_two_inner_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em', '%'],
            'default'    => [
                'top'    => '64',
                'right'  => '60',
                'bottom' => '64',
                'left'   => '60',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_inner_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner-inner' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_inner_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'    => '20',
                'right'  => '20',
                'bottom' => '20',
                'left'   => '20',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_cta_two_inner_border',
            'selector' => '{{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_cta_two_inner_shadow',
            'selector' => '{{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_two_inner_overlay',
            'label'     => esc_html__('Inner Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-cta-banner-inner::before',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_two_style_text', [
            'label' => esc_html__('Banner Text', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_cta_two_text_typography',
            'selector' => '{{WRAPPER}} .ai-marketing-cta-banner-text',
        ]);

        $this->add_control('gc_cta_two_text_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-cta-banner-text' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_text_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 920, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner-text' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_text_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top'    => '0',
                'right'  => '0',
                'bottom' => '36',
                'left'   => '0',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-banner-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_two_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_cta_two_btn_typography',
            'selector' => '{{WRAPPER}} .ai-marketing-cta-btn',
        ]);

        $this->add_control('gc_cta_two_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-cta-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_btn_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-cta-btn',
        ]);

        $this->add_responsive_control('gc_cta_two_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default'    => [
                'top'    => '18',
                'right'  => '36',
                'bottom' => '18',
                'left'   => '36',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_btn_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_two_btn_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-cta-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_cta_two_btn_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-cta-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_two_btn_hover_bg',
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-cta-btn:hover',
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'      => 'gc_cta_two_btn_hover_shadow',
            'label'     => esc_html__('Hover Box Shadow', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-cta-btn:hover',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_cta_two_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_cta_two_theme_mode_tabs');

        $this->start_controls_tab('gc_cta_two_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-banner',
        ]);

        $this->add_control('gc_cta_two_dark_section_border_color', [
            'label'     => esc_html__('Section Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-cta-banner' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-banner::before',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_inner_bg',
            'label'    => esc_html__('Inner Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_control('gc_cta_two_dark_inner_border_color', [
            'label'     => esc_html__('Inner Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-cta-banner-inner' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_cta_two_dark_inner_shadow',
            'label'    => esc_html__('Inner Box Shadow', 'softro-core'),
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_inner_overlay',
            'label'    => esc_html__('Inner Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-banner-inner::before',
        ]);

        $this->add_control('gc_cta_two_dark_text_color', [
            'label'     => esc_html__('Banner Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-cta-banner-text' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_two_dark_btn_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-cta-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_btn_bg',
            'label'    => esc_html__('Button Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-btn',
        ]);

        $this->add_control('gc_cta_two_dark_btn_hover_color', [
            'label'     => esc_html__('Button Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-cta-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_dark_btn_hover_bg',
            'label'    => esc_html__('Button Hover Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-cta-btn:hover',
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_cta_two_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-banner',
        ]);

        $this->add_control('gc_cta_two_light_section_border_color', [
            'label'     => esc_html__('Section Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-cta-banner' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-banner::before',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_inner_bg',
            'label'    => esc_html__('Inner Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_control('gc_cta_two_light_inner_border_color', [
            'label'     => esc_html__('Inner Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-cta-banner-inner' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_cta_two_light_inner_shadow',
            'label'    => esc_html__('Inner Box Shadow', 'softro-core'),
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-banner-inner',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_inner_overlay',
            'label'    => esc_html__('Inner Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-banner-inner::before',
        ]);

        $this->add_control('gc_cta_two_light_text_color', [
            'label'     => esc_html__('Banner Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-cta-banner-text' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_two_light_btn_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-cta-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_btn_bg',
            'label'    => esc_html__('Button Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-btn',
        ]);

        $this->add_control('gc_cta_two_light_btn_hover_color', [
            'label'     => esc_html__('Button Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-cta-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_two_light_btn_hover_bg',
            'label'    => esc_html__('Button Hover Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-cta-btn:hover',
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_cta_two_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> [data-text-animation] { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $banner_text = $settings['gc_cta_two_banner_text'] ?? '';
        $button_text = $settings['gc_cta_two_button_text'] ?? '';
        $button_link = $settings['gc_cta_two_button_link'] ?? [];
        ?>

        <section class="ai-marketing-cta-banner fade-wrapper">
            <div class="container">
                <div class="ai-marketing-cta-banner-inner fade-top">
                    <?php if ($banner_text) : ?>
                        <h2 class="ai-marketing-cta-banner-text"><?php echo $this->get_paragraph_inner_content($banner_text); ?></h2>
                    <?php endif; ?>
                    <?php if ($button_text) : ?>
                        <a class="rr-primary-btn ai-marketing-cta-btn"<?php echo $this->get_link_attributes($button_link); ?>><?php echo esc_html($button_text); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_CTA_Two_Widget());
