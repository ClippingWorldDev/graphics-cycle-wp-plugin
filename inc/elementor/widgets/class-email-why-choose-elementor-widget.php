<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Email_Why_Choose_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_email_why_choose';
    }

    public function get_title()
    {
        return esc_html__('GC Email Why Choose', 'softro-core');
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

    private function get_default_cards()
    {
        return [
            [
                'card_modifier'    => '1',
                'card_title'     => esc_html__('Strategy-First Approach', 'softro-core'),
                'card_description' => esc_html__('We start with customer data, not templates.', 'softro-core'),
            ],
            [
                'card_modifier'    => '2',
                'card_title'     => esc_html__('Revenue-Focused Reporting', 'softro-core'),
                'card_description' => esc_html__('We track sales, not vanity metrics.', 'softro-core'),
            ],
            [
                'card_modifier'    => '3',
                'card_title'     => esc_html__('Deliverability Expertise', 'softro-core'),
                'card_description' => esc_html__('Inbox placement is part of every engagement.', 'softro-core'),
            ],
            [
                'card_modifier'    => '4',
                'card_title'     => esc_html__('Clear Monthly Roadmap', 'softro-core'),
                'card_description' => esc_html__('You always know what’s launching next.', 'softro-core'),
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
        $this->start_controls_section('gc_email_wc_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_email_wc_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Why Choose Graphics Cycle First', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_email_wc_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'We always focus on result driven marketing strategy. Our entire marketing process is unique. Here are some points that brought us more outstanding platforms than others.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_modifier', [
            'label'   => esc_html__('Card Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => '1',
            'options' => [
                '1' => esc_html__('Style 1', 'softro-core'),
                '2' => esc_html__('Style 2', 'softro-core'),
                '3' => esc_html__('Style 3', 'softro-core'),
                '4' => esc_html__('Style 4', 'softro-core'),
            ],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Strategy-First Approach', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We start with customer data, not templates.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_email_wc_cards_section', [
            'label' => esc_html__('Why Choose Cards', 'softro-core'),
        ]);

        $this->add_control('gc_email_wc_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_email_wc_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_email_wc_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_email_wc_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-grid' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_column_gap', [
            'label'      => esc_html__('Column Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-grid' => 'column-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_email_wc_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_email_wc_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-email-why-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_email_wc_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-email-why-section::before',
        ]);

        $this->add_control('gc_email_wc_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-section' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_email_wc_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_email_wc_heading_max_width', [
            'label'      => esc_html__('Heading Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 780, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-heading' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_heading_margin', [
            'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_email_wc_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-email-why-title',
        ]);

        $this->add_control('gc_email_wc_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_email_wc_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-email-why-desc',
            'separator' => 'before',
        ]);

        $this->add_control('gc_email_wc_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_desc_max_width', [
            'label'      => esc_html__('Description Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 700, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-desc' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_desc_margin', [
            'label'      => esc_html__('Description Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_email_wc_style_grid', [
            'label' => esc_html__('Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_email_wc_grid_max_width', [
            'label'      => esc_html__('Grid Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 960, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-grid' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_grid_margin', [
            'label'      => esc_html__('Grid Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_email_wc_style_card', [
            'label' => esc_html__('Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_email_wc_card_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_email_wc_card_border',
            'selector' => '{{WRAPPER}} .gc-email-why-card',
        ]);

        $this->add_control('gc_email_wc_card_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_email_wc_card_title_heading', [
            'label'     => esc_html__('Card Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_email_wc_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-email-why-card-title',
        ]);

        $this->add_control('gc_email_wc_card_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_email_wc_card_title_hover_color', [
            'label'     => esc_html__('Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card:hover .gc-email-why-card-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_card_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_email_wc_card_desc_heading', [
            'label'     => esc_html__('Card Description', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_email_wc_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-email-why-card-desc',
        ]);

        $this->add_control('gc_email_wc_card_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_email_wc_card_desc_hover_color', [
            'label'     => esc_html__('Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-email-why-card:hover .gc-email-why-card-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_email_wc_card_desc_margin', [
            'label'      => esc_html__('Description Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-email-why-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_email_wc_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_email_wc_theme_mode_tabs');

        $this->start_controls_tab('gc_email_wc_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_email_wc_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-email-why-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_email_wc_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-email-why-section::before',
        ]);

        $this->add_control('gc_email_wc_dark_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card:hover .gc-email-why-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_dark_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-email-why-card:hover .gc-email-why-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_email_wc_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_email_wc_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-email-why-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_email_wc_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-email-why-section::before',
        ]);

        $this->add_control('gc_email_wc_light_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card:hover .gc-email-why-card-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_email_wc_light_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-email-why-card:hover .gc-email-why-card-desc' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_email_wc_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_why_card(array $card)
    {
        $title = trim((string) ($card['card_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($card['card_description'] ?? '');
        $modifier = sanitize_key($card['card_modifier'] ?? '1');

        if (!in_array($modifier, ['1', '2', '3', '4'], true)) {
            $modifier = '1';
        }

        if ('' === $title && '' === $description) {
            return;
        }
        ?>
        <div class="col-lg-6 col-md-6">
            <div class="gc-email-why-card gc-email-why-card--<?php echo esc_attr($modifier); ?> fade-top">
                <?php if ('' !== $title) : ?>
                    <h3 class="gc-email-why-card-title"><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== $description) : ?>
                    <p class="gc-email-why-card-desc"><?php echo wp_kses($description, ['br' => []]); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $title       = trim((string) ($settings['gc_email_wc_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($settings['gc_email_wc_description'] ?? '');
        $cards       = !empty($settings['gc_email_wc_cards']) ? $settings['gc_email_wc_cards'] : $this->get_default_cards();
        ?>

        <section class="gc-email-why-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center gc-email-why-heading">
                    <?php if ('' !== $title) : ?>
                        <h2 class="section-title gc-email-why-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== $description) : ?>
                        <p class="gc-email-why-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo wp_kses($description, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($cards)) : ?>
                    <div class="row gy-4 gx-4 justify-content-center gc-email-why-grid">
                        <?php foreach ($cards as $card) {
                            $this->render_why_card($card);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Email_Why_Choose_Widget());
