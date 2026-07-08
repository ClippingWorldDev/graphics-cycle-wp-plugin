<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Quote_Section_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_quote_section';
    }

    public function get_title()
    {
        return esc_html__('GC Quote Section', 'softro-core');
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
        $this->start_controls_section(
            'gc_quote_section_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_section_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Free Quote', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_quote_section_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get Your Free Quote Now', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_quote_section_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Fill out this form, and we\'ll get back to you in 45 minutes or less with your customized quote.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_section_aria_label',
            [
                'label'       => esc_html__('Section Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get your free quote', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_form_section',
            [
                'label' => esc_html__('Contact Form', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_quote_section_form_shortcode',
            [
                'label'       => esc_html__('Form Shortcode', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'placeholder' => esc_html__('[contact-form-7 id="123" title="Free Quote"]', 'softro-core'),
                'description' => esc_html__('Paste your Contact Form 7 shortcode here.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_quote_section_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_quote_section_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_quote_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-free-quote-section',
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_quote_section_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_title_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-title',
            ]
        );

        $this->add_control(
            'gc_quote_section_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_heading_margin',
            [
                'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_title_margin',
            [
                'label'      => esc_html__('Title Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_desc_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-desc',
            ]
        );

        $this->add_control(
            'gc_quote_section_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_card',
            [
                'label' => esc_html__('Form Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_quote_section_card_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'gc_quote_section_card_border',
                'selector' => '{{WRAPPER}} .gc-free-quote-card',
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'gc_quote_section_card_shadow',
                'selector' => '{{WRAPPER}} .gc-free-quote-card',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_form_labels',
            [
                'label' => esc_html__('Form Labels', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_form_label_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-form label, {{WRAPPER}} .gc-free-quote-form .wpcf7-form-control-wrap > label',
            ]
        );

        $this->add_control(
            'gc_quote_section_form_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-form label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control-wrap > label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_form_label_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-form label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_form_fields',
            [
                'label' => esc_html__('Form Fields', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_form_field_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-form .form-control, {{WRAPPER}} .gc-free-quote-form input, {{WRAPPER}} .gc-free-quote-form textarea, {{WRAPPER}} .gc-free-quote-form select, {{WRAPPER}} .gc-free-quote-form .wpcf7-form-control',
            ]
        );

        $this->add_control(
            'gc_quote_section_form_field_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form select' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_section_form_field_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form select' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_section_form_field_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form select' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_form_field_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form select' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_form_field_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form select' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_form_field_min_height',
            [
                'label'      => esc_html__('Field Min Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-form .form-control' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit):not(textarea)' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_form_textarea_min_height',
            [
                'label'      => esc_html__('Textarea Min Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-form .gc-quote-textarea' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form textarea' => 'min-height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-textarea' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_quote_section_style_submit',
            [
                'label' => esc_html__('Submit Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_quote_section_submit_typography',
                'selector' => '{{WRAPPER}} .gc-free-quote-submit, {{WRAPPER}} .gc-free-quote-form .wpcf7-submit, {{WRAPPER}} .gc-free-quote-form input[type="submit"]',
            ]
        );

        $this->add_control(
            'gc_quote_section_submit_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-submit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-submit' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form input[type="submit"]' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_quote_section_submit_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-free-quote-submit' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-submit' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .gc-free-quote-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_submit_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_quote_section_submit_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-free-quote-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .gc-free-quote-form input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_quote_section_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_quote_section_theme_mode_color_tabs');

        $this->start_controls_tab('gc_quote_section_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_quote_section_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-free-quote-section',
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_card_bg',
            [
                'label'     => esc_html__('Form Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_form_label_color',
            [
                'label'     => esc_html__('Form Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-form label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_form_field_color',
            [
                'label'     => esc_html__('Form Field Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-form .form-control' => 'color: {{VALUE}};',
                    '.gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'color: {{VALUE}};',
                    '.gc-free-quote-form textarea' => 'color: {{VALUE}};',
                    '.gc-free-quote-form select' => 'color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_form_field_bg',
            [
                'label'     => esc_html__('Form Field Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-form .form-control' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form textarea' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form select' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_submit_color',
            [
                'label'     => esc_html__('Submit Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-submit' => 'color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-submit' => 'color: {{VALUE}};',
                    '.gc-free-quote-form input[type="submit"]' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_dark_submit_bg',
            [
                'label'     => esc_html__('Submit Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-free-quote-submit' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-submit' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_quote_section_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_quote_section_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-free-quote-section',
            ]
        );

        $this->add_control(
            'gc_quote_section_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_card_bg',
            [
                'label'     => esc_html__('Form Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_form_label_color',
            [
                'label'     => esc_html__('Form Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-form label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_form_field_color',
            [
                'label'     => esc_html__('Form Field Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-form .form-control' => 'color: {{VALUE}};',
                    '.gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'color: {{VALUE}};',
                    '.gc-free-quote-form textarea' => 'color: {{VALUE}};',
                    '.gc-free-quote-form select' => 'color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_form_field_bg',
            [
                'label'     => esc_html__('Form Field Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-form .form-control' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form input:not([type="submit"]):not([type="checkbox"]):not([type="radio"])' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form textarea' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form select' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-form-control:not(.wpcf7-submit)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_submit_color',
            [
                'label'     => esc_html__('Submit Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-submit' => 'color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-submit' => 'color: {{VALUE}};',
                    '.gc-free-quote-form input[type="submit"]' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_quote_section_light_submit_bg',
            [
                'label'     => esc_html__('Submit Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-free-quote-submit' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form .wpcf7-submit' => 'background-color: {{VALUE}};',
                    '.gc-free-quote-form input[type="submit"]' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_quote_section_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    private function render_form_shortcode($shortcode)
    {
        $shortcode = trim((string) $shortcode);

        if ('' === $shortcode) {
            if (current_user_can('edit_posts')) {
                echo '<p class="gc-free-quote-form-notice">' . esc_html__('Please add a Contact Form 7 shortcode in widget settings.', 'softro-core') . '</p>';
            }
            return;
        }

        echo do_shortcode($shortcode);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow      = $settings['gc_quote_section_eyebrow'] ?? '';
        $title        = $settings['gc_quote_section_title'] ?? '';
        $description  = $settings['gc_quote_section_description'] ?? '';
        $section_aria = $settings['gc_quote_section_aria_label'] ?? esc_html__('Get your free quote', 'softro-core');
        $form_shortcode = $settings['gc_quote_section_form_shortcode'] ?? '';
        ?>

        <section class="gc-free-quote-section pt-130 pb-130 fade-wrapper" aria-label="<?php echo esc_attr($section_aria); ?>">
            <div class="container">
                <div class="section-heading text-center gc-free-quote-heading">
                    <?php if ($eyebrow) : ?>
                        <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="section-title gc-free-quote-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="gc-free-quote-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>

                <div class="gc-free-quote-card fade-top">
                    <div class="gc-free-quote-form">
                        <?php $this->render_form_shortcode($form_shortcode); ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Quote_Section_Widget());
