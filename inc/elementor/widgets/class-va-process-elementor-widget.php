<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_VA_Process_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_va_process';
    }

    public function get_title()
    {
        return esc_html__('GC VA Process', 'softro-core');
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

    private function get_default_steps()
    {
        return [
            [
                'step_number'       => '1',
                'is_active'         => 'yes',
                'step_title'        => esc_html__('Brief & scope', 'softro-core'),
                'step_description'  => esc_html__(
                    'You share your requirements, reference files, and goals. We confirm scope and timeline.',
                    'softro-core'
                ),
            ],
            [
                'step_number'       => '2',
                'is_active'         => '',
                'step_title'        => esc_html__('3D modeling', 'softro-core'),
                'step_description'  => esc_html__(
                    'We build or import your 3D assets, apply materials, textures, and lighting.',
                    'softro-core'
                ),
            ],
            [
                'step_number'       => '3',
                'is_active'         => '',
                'step_title'        => esc_html__('Animation', 'softro-core'),
                'step_description'  => esc_html__(
                    'Motion, camera paths, and timing are crafted. You approve a preview before rendering.',
                    'softro-core'
                ),
            ],
            [
                'step_number'       => '4',
                'is_active'         => '',
                'step_title'        => esc_html__('Render & review', 'softro-core'),
                'step_description'  => esc_html__(
                    'High-quality render is produced. You review and request any revisions needed.',
                    'softro-core'
                ),
            ],
            [
                'step_number'       => '5',
                'is_active'         => '',
                'step_title'        => esc_html__('Final delivery', 'softro-core'),
                'step_description'  => esc_html__(
                    'All final files delivered in your chosen formats — ready to publish.',
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
        $this->start_controls_section('gc_va_proc_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_va_proc_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('How It Works', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_proc_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Our ', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_va_proc_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('5-step', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_proc_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__(' production process', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_proc_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'A clear, collaborative workflow from your first brief to final delivery — with you in control at every stage.',
                'softro-core'
            ),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $step_repeater = new Repeater();

        $step_repeater->add_control('step_number', [
            'label'       => esc_html__('Step Number', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '1',
            'label_block' => true,
            'description' => esc_html__('Defaults to 1–5 based on step order when left empty.', 'softro-core'),
        ]);

        $step_repeater->add_control('is_active', [
            'label'        => esc_html__('Active Step', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $step_repeater->add_control('step_title', [
            'label'       => esc_html__('Step Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Brief & scope', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $step_repeater->add_control('step_description', [
            'label'   => esc_html__('Step Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'You share your requirements, reference files, and goals. We confirm scope and timeline.',
                'softro-core'
            ),
        ]);

        $this->start_controls_section('gc_va_proc_steps_section', [
            'label' => esc_html__('Process Steps', 'softro-core'),
        ]);

        $this->add_control('gc_va_proc_steps', [
            'label'       => esc_html__('Steps', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $step_repeater->get_controls(),
            'default'     => $this->get_default_steps(),
            'title_field' => '{{{ step_number }}}. {{{ step_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_va_proc_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_proc_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_va_proc_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_steps_gap', [
            'label'      => esc_html__('Steps Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-steps' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_proc_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-process',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_proc_eyebrow_heading', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-eyebrow',
        ]);

        $this->add_control('gc_va_proc_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_proc_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-title',
        ]);

        $this->add_control('gc_va_proc_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_proc_accent_heading', [
            'label'     => esc_html__('Accent', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_accent_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-accent',
        ]);

        $this->add_control('gc_va_proc_accent_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_proc_intro_heading', [
            'label'     => esc_html__('Intro', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_intro_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-intro',
        ]);

        $this->add_control('gc_va_proc_intro_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_intro_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_header_margin', [
            'label'      => esc_html__('Header Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'separator'  => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_timeline', [
            'label' => esc_html__('Timeline', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_proc_timeline_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-timeline' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_timeline_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-timeline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_step_item', [
            'label' => esc_html__('Step Item', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_proc_step_item_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-step',
        ]);

        $this->add_responsive_control('gc_va_proc_step_item_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_step_item_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_va_proc_step_item_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-step',
        ]);

        $this->add_responsive_control('gc_va_proc_step_item_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-step' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_proc_step_active_heading', [
            'label'     => esc_html__('Active Step', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_proc_step_active_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-step.is-active',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_va_proc_step_active_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-step.is-active',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_step_number', [
            'label' => esc_html__('Step Number', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_step_num_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-num',
        ]);

        $this->add_control('gc_va_proc_step_num_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-num' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_proc_step_num_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-num' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_step_num_size', [
            'label'      => esc_html__('Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-process-num' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_va_proc_step_num_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-num' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_step_num_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-num' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_va_proc_step_num_active_heading', [
            'label'     => esc_html__('Active Step Number', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_va_proc_step_num_active_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_proc_step_num_active_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_step_title', [
            'label' => esc_html__('Step Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_step_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-copy h3',
        ]);

        $this->add_control('gc_va_proc_step_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-copy h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_step_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-copy h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_proc_style_step_description', [
            'label' => esc_html__('Step Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_proc_step_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-process-copy p',
        ]);

        $this->add_control('gc_va_proc_step_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-process-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_proc_step_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-process-copy p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_va_proc_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_va_proc_theme_mode_tabs');

        $this->start_controls_tab('gc_va_proc_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_proc_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-process',
        ]);

        $this->add_control('gc_va_proc_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_item_bg', [
            'label'     => esc_html__('Step Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-step' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_active_bg', [
            'label'     => esc_html__('Active Step Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-step.is-active' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_num_color', [
            'label'     => esc_html__('Step Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-num' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_num_bg', [
            'label'     => esc_html__('Step Number Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-num' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_num_active_color', [
            'label'     => esc_html__('Active Step Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_num_active_bg', [
            'label'     => esc_html__('Active Step Number Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_title_color', [
            'label'     => esc_html__('Step Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_dark_step_desc_color', [
            'label'     => esc_html__('Step Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-process-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_va_proc_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_proc_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-process',
        ]);

        $this->add_control('gc_va_proc_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_item_bg', [
            'label'     => esc_html__('Step Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-step' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_active_bg', [
            'label'     => esc_html__('Active Step Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-step.is-active' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_num_color', [
            'label'     => esc_html__('Step Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-num' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_num_bg', [
            'label'     => esc_html__('Step Number Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-num' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_num_active_color', [
            'label'     => esc_html__('Active Step Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_num_active_bg', [
            'label'     => esc_html__('Active Step Number Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-step.is-active .gc-3d-anim-process-num' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_title_color', [
            'label'     => esc_html__('Step Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_proc_light_step_desc_color', [
            'label'     => esc_html__('Step Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-process-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_va_proc_reset_elementor_spacing'] ?? 'yes')) {
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

    private function get_step_number(array $step, $index)
    {
        $step_number = trim((string) ($step['step_number'] ?? ''));

        if ('' !== $step_number) {
            return $step_number;
        }

        return (string) ($index + 1);
    }

    private function render_process_step(array $step, $index)
    {
        $title       = trim((string) ($step['step_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($step['step_description'] ?? '');
        $step_number = $this->get_step_number($step, $index);
        $is_active   = 'yes' === ($step['is_active'] ?? '');

        if ('' === $title && '' === $description) {
            return;
        }

        $step_classes = 'gc-3d-anim-process-step fade-top';

        if ($is_active) {
            $step_classes .= ' is-active';
        }
        ?>
        <li class="<?php echo esc_attr($step_classes); ?>">
            <span class="gc-3d-anim-process-num" aria-hidden="true"><?php echo esc_html($step_number); ?></span>
            <div class="gc-3d-anim-process-copy">
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

        $eyebrow      = trim((string) ($settings['gc_va_proc_eyebrow'] ?? ''));
        $title_before = $settings['gc_va_proc_title_before'] ?? '';
        $title_accent = $settings['gc_va_proc_title_accent'] ?? '';
        $title_after  = $settings['gc_va_proc_title_after'] ?? '';
        $intro        = $this->get_paragraph_inner_content($settings['gc_va_proc_intro'] ?? '');
        $steps        = !empty($settings['gc_va_proc_steps']) ? $settings['gc_va_proc_steps'] : $this->get_default_steps();
        ?>

        <section class="gc-3d-anim-process pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-3d-anim-process-header">
                    <?php if ('' !== $eyebrow) : ?>
                        <span class="gc-3d-anim-process-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ($title_before || $title_accent || $title_after) : ?>
                        <h2 class="gc-3d-anim-process-title overflow-hidden" data-text-animation="fade-in-right"
                            data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title_before); ?><?php if ($title_accent) : ?><span
                                class="gc-3d-anim-process-accent"><?php echo esc_html($title_accent); ?></span><?php endif; ?><?php echo esc_html($title_after); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== $intro) : ?>
                        <p class="gc-3d-anim-process-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo wp_kses($intro, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($steps)) : ?>
                    <div class="gc-3d-anim-process-timeline">
                        <ol class="gc-3d-anim-process-steps">
                            <?php foreach ($steps as $index => $step) {
                                $this->render_process_step($step, $index);
                            } ?>
                        </ol>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_VA_Process_Widget());
