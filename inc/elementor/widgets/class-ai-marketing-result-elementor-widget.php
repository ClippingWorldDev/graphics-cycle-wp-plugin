<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_AI_Marketing_Result_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_ai_marketing_result';
    }

    public function get_title()
    {
        return esc_html__('GC AI Marketing Result', 'softro-core');
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

    private function get_default_result_cards()
    {
        return [
            [
                'result_value'   => '+320%',
                'result_metric'  => esc_html__('Organic Traffic — 90 days', 'softro-core'),
                'result_client'  => esc_html__('E-commerce client, Dhaka', 'softro-core'),
            ],
            [
                'result_value'   => '4.2x',
                'result_metric'  => esc_html__('ROAS on Google Ads', 'softro-core'),
                'result_client'  => esc_html__('Fashion brand, Chittagong', 'softro-core'),
            ],
            [
                'result_value'   => '$840K',
                'result_metric'  => esc_html__('Revenue via Email AI', 'softro-core'),
                'result_client'  => esc_html__('SaaS company, Remote', 'softro-core'),
            ],
            [
                'result_value'   => '-62%',
                'result_metric'  => esc_html__('Cost per Lead reduced', 'softro-core'),
                'result_client'  => esc_html__('Real estate, Sylhet', 'softro-core'),
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
        $this->start_controls_section('gc_ai_result_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_ai_result_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Proven Results', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ai_result_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Real Growth Our Clients Achieve', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ai_result_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Data-backed outcomes from AI-driven campaigns across SEO, paid ads, email, and lead generation for businesses worldwide.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('result_value', [
            'label'       => esc_html__('Result Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '+320%',
            'label_block' => true,
        ]);

        $card_repeater->add_control('result_metric', [
            'label'       => esc_html__('Metric Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Organic Traffic — 90 days', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('result_client', [
            'label'       => esc_html__('Client Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('E-commerce client, Dhaka', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_ai_result_cards_section', [
            'label' => esc_html__('Result Cards', 'softro-core'),
        ]);

        $this->add_control('gc_ai_result_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_result_cards(),
            'title_field' => '{{{ result_metric }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_ai_result_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_ai_result_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_ai_result_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_row_gap', [
            'label'      => esc_html__('Cards Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-section .row.gy-4' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ai_result_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ai_result_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-results-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_ai_result_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-results-section::before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ai_result_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ai_result_heading_margin', [
            'label'      => esc_html__('Heading Row Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ai_result_eyebrow_typography',
            'label'    => esc_html__('Eyebrow Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .ai-marketing-results-heading .sub-heading',
        ]);

        $this->add_control('gc_ai_result_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-results-heading .sub-heading' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_ai_result_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-results-heading .section-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_ai_result_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-results-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_ai_result_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-results-desc',
            'separator' => 'before',
        ]);

        $this->add_control('gc_ai_result_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-results-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_desc_margin', [
            'label'      => esc_html__('Description Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-results-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ai_result_style_card', [
            'label' => esc_html__('Result Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_ai_result_card_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-result-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-result-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ai_result_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-result-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_ai_result_card_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_ai_result_value_typography',
            'label'     => esc_html__('Value Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-result-card .result-value',
            'separator' => 'before',
        ]);

        $this->add_control('gc_ai_result_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card .result-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ai_result_value_hover_color', [
            'label'     => esc_html__('Value Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card:hover .result-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_ai_result_metric_typography',
            'label'     => esc_html__('Metric Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-result-card .result-metric',
            'separator' => 'before',
        ]);

        $this->add_control('gc_ai_result_metric_color', [
            'label'     => esc_html__('Metric Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card .result-metric' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_ai_result_client_typography',
            'label'     => esc_html__('Client Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-result-card .result-client',
            'separator' => 'before',
        ]);

        $this->add_control('gc_ai_result_client_color', [
            'label'     => esc_html__('Client Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-result-card .result-client' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_ai_result_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_ai_result_theme_mode_tabs');

        $this->start_controls_tab('gc_ai_result_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ai_result_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-results-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ai_result_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-results-section::before',
        ]);

        $this->add_control('gc_ai_result_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-results-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-results-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-results-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card .result-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_value_hover_color', [
            'label'     => esc_html__('Value Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card:hover .result-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_metric_color', [
            'label'     => esc_html__('Metric Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card .result-metric' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_dark_client_color', [
            'label'     => esc_html__('Client Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-result-card .result-client' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_ai_result_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ai_result_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-results-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ai_result_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-results-section::before',
        ]);

        $this->add_control('gc_ai_result_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-results-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-results-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-results-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card .result-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_value_hover_color', [
            'label'     => esc_html__('Value Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card:hover .result-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_metric_color', [
            'label'     => esc_html__('Metric Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card .result-metric' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ai_result_light_client_color', [
            'label'     => esc_html__('Client Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-result-card .result-client' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_ai_result_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_result_card($card)
    {
        $value  = $card['result_value'] ?? '';
        $metric = $card['result_metric'] ?? '';
        $client = $card['result_client'] ?? '';

        if (!$value && !$metric) {
            return;
        }
        ?>
        <div class="col-lg-6">
            <div class="ai-marketing-result-card fade-top">
                <?php if ($value) : ?>
                    <span class="result-value"><?php echo esc_html($value); ?></span>
                <?php endif; ?>
                <?php if ($metric) : ?>
                    <h3 class="result-metric"><?php echo esc_html($metric); ?></h3>
                <?php endif; ?>
                <?php if ($client) : ?>
                    <p class="result-client"><?php echo esc_html($client); ?></p>
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

        $eyebrow     = $settings['gc_ai_result_eyebrow'] ?? '';
        $title       = $settings['gc_ai_result_title'] ?? '';
        $description = $settings['gc_ai_result_description'] ?? '';
        $cards       = !empty($settings['gc_ai_result_cards']) ? $settings['gc_ai_result_cards'] : [];
        ?>

        <section class="ai-marketing-results-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="ai-marketing-results-heading row align-items-end gy-4">
                    <div class="col-lg-5">
                        <?php if ($eyebrow) : ?>
                            <h4 class="sub-heading" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                        <?php endif; ?>
                        <?php if ($title) : ?>
                            <h2 class="section-title mb-0" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                    </div>
                    <?php if ($description) : ?>
                        <div class="col-lg-7">
                            <p class="ai-marketing-results-desc mb-0" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cards)) : ?>
                    <div class="row gy-4">
                        <?php foreach ($cards as $card) {
                            $this->render_result_card($card);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_AI_Marketing_Result_Widget());
