<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_CTA_Three_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_cta_three';
    }

    public function get_title()
    {
        return esc_html__('GC CTA Three', 'softro-core');
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_cta_three_content_section', [
            'label' => esc_html__('Content', 'softro-core'),
        ]);

        $this->add_control('gc_cta_three_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Graphics Cycle', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_cta_three_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Need both video editing and 3D design?', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_cta_three_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We handle everything under one roof — from cinematic video production to photorealistic 3D renders. Save time, maintain consistency, and scale your content effortlessly.', 'softro-core'),
        ]);

        $this->add_control('gc_cta_three_primary_btn_text', [
            'label'       => esc_html__('Primary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get started today', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_cta_three_primary_btn_link', [
            'label'   => esc_html__('Primary Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->add_control('gc_cta_three_secondary_btn_text', [
            'label'       => esc_html__('Secondary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('View our portfolio', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_cta_three_secondary_btn_link', [
            'label'   => esc_html__('Secondary Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_cta_three_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_cta_three_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_cta_three_section_padding', [
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
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_three_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_three_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_three_section_overlay',
            'label'     => esc_html__('Section Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta::before',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_three_style_banner', [
            'label' => esc_html__('Banner Box', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_banner_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-banner',
        ]);

        $this->add_responsive_control('gc_cta_three_banner_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-banner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_three_banner_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'    => '24',
                'right'  => '24',
                'bottom' => '24',
                'left'   => '24',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_cta_three_banner_border',
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-banner',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_cta_three_banner_shadow',
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-banner',
        ]);

        $this->add_control('gc_cta_three_circle_border_color', [
            'label'     => esc_html__('Decorative Circle Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-circle' => 'border-color: {{VALUE}};'],
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_three_circle_bg', [
            'label'     => esc_html__('Decorative Circle Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-circle' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_three_style_content', [
            'label' => esc_html__('Content', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_cta_three_eyebrow_typography',
            'label'    => esc_html__('Eyebrow Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-eyebrow',
        ]);

        $this->add_control('gc_cta_three_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_three_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_three_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_cta_three_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_three_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta-desc',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_three_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_cta_three_desc_max_width', [
            'label'      => esc_html__('Description Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 620, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-desc' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_cta_three_style_buttons', [
            'label' => esc_html__('Buttons', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_cta_three_actions_gap', [
            'label'      => esc_html__('Buttons Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-actions' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_cta_three_actions_max_width', [
            'label'      => esc_html__('Actions Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 320, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-actions' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_three_primary_btn_typography',
            'label'     => esc_html__('Primary Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta-btn--primary',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_three_primary_btn_color', [
            'label'     => esc_html__('Primary Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-btn--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_primary_btn_bg',
            'label'    => esc_html__('Primary Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-btn--primary',
        ]);

        $this->add_responsive_control('gc_cta_three_primary_btn_padding', [
            'label'      => esc_html__('Primary Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-btn--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_cta_three_primary_btn_hover_color', [
            'label'     => esc_html__('Primary Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-btn--primary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_cta_three_primary_btn_hover_bg',
            'label'     => esc_html__('Primary Hover Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta-btn--primary:hover',
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_cta_three_secondary_btn_typography',
            'label'     => esc_html__('Secondary Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-cta-btn--secondary',
            'separator' => 'before',
        ]);

        $this->add_control('gc_cta_three_secondary_btn_color', [
            'label'     => esc_html__('Secondary Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-btn--secondary' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_secondary_btn_bg',
            'label'    => esc_html__('Secondary Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-btn--secondary',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_cta_three_secondary_btn_border',
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-btn--secondary',
        ]);

        $this->add_responsive_control('gc_cta_three_secondary_btn_padding', [
            'label'      => esc_html__('Secondary Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-cta-btn--secondary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_cta_three_secondary_btn_hover_color', [
            'label'     => esc_html__('Secondary Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-cta-btn--secondary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_secondary_btn_hover_bg',
            'label'    => esc_html__('Secondary Hover Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-cta-btn--secondary:hover',
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_cta_three_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_cta_three_theme_mode_tabs');

        $this->start_controls_tab('gc_cta_three_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_dark_banner_bg',
            'label'    => esc_html__('Banner Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-cta-banner',
        ]);

        $this->add_control('gc_cta_three_dark_banner_border_color', [
            'label'     => esc_html__('Banner Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-banner' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_dark_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_dark_secondary_btn_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-cta-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_cta_three_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-cta',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_cta_three_light_banner_bg',
            'label'    => esc_html__('Banner Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-cta-banner',
        ]);

        $this->add_control('gc_cta_three_light_banner_border_color', [
            'label'     => esc_html__('Banner Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-banner' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_light_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_cta_three_light_secondary_btn_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-cta-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_cta_three_reset_elementor_spacing'] ?? 'yes')) {
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

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow            = $settings['gc_cta_three_eyebrow'] ?? '';
        $title              = $settings['gc_cta_three_title'] ?? '';
        $description        = $settings['gc_cta_three_description'] ?? '';
        $primary_btn_text   = $settings['gc_cta_three_primary_btn_text'] ?? '';
        $primary_btn_link   = $settings['gc_cta_three_primary_btn_link'] ?? [];
        $secondary_btn_text = $settings['gc_cta_three_secondary_btn_text'] ?? '';
        $secondary_btn_link = $settings['gc_cta_three_secondary_btn_link'] ?? [];
        ?>

        <section class="gc-video-3d-cta pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-video-3d-cta-banner fade-top">
                    <div class="gc-video-3d-cta-circles" aria-hidden="true">
                        <span class="gc-video-3d-cta-circle gc-video-3d-cta-circle--1"></span>
                        <span class="gc-video-3d-cta-circle gc-video-3d-cta-circle--2"></span>
                        <span class="gc-video-3d-cta-circle gc-video-3d-cta-circle--3"></span>
                        <span class="gc-video-3d-cta-circle gc-video-3d-cta-circle--4"></span>
                    </div>
                    <div class="row align-items-center g-4 g-xl-5">
                        <div class="col-lg-7">
                            <div class="gc-video-3d-cta-content">
                                <?php if ($eyebrow) : ?>
                                    <span class="gc-video-3d-cta-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <h2 class="gc-video-3d-cta-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                                <?php if ($description) : ?>
                                    <p class="gc-video-3d-cta-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <?php if ($primary_btn_text || $secondary_btn_text) : ?>
                                <div class="gc-video-3d-cta-actions fade-top">
                                    <?php if ($primary_btn_text) : ?>
                                        <a class="gc-video-3d-cta-btn gc-video-3d-cta-btn--primary"<?php echo $this->get_link_attributes($primary_btn_link); ?>><?php echo esc_html($primary_btn_text); ?></a>
                                    <?php endif; ?>
                                    <?php if ($secondary_btn_text) : ?>
                                        <a class="gc-video-3d-cta-btn gc-video-3d-cta-btn--secondary"<?php echo $this->get_link_attributes($secondary_btn_link); ?>><?php echo esc_html($secondary_btn_text); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_CTA_Three_Widget());
