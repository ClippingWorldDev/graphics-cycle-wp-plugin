<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Process_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_process_one';
    }

    public function get_title()
    {
        return esc_html__('GC Process One', 'softro-core');
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_process_one_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_process_one_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Our Process', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_process_one_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('How We Work', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_process_one_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Getting started is simple. Send us your images, and our team will take care of the rest.', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_arrow_section',
            [
                'label' => esc_html__('Step Arrow', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_process_one_arrow_icon',
            [
                'label'   => esc_html__('Arrow Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-light fa-arrow-right',
                    'library' => 'fa-light',
                ],
            ]
        );

        $this->end_controls_section();

        $step_repeater = new Repeater();

        $step_repeater->add_control(
            'card_style',
            [
                'label'   => esc_html__('Card Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'softro-core'),
                    '2' => esc_html__('Style 2', 'softro-core'),
                    '3' => esc_html__('Style 3', 'softro-core'),
                    '4' => esc_html__('Style 4', 'softro-core'),
                ],
            ]
        );

        $step_repeater->add_control(
            'step_number',
            [
                'label'       => esc_html__('Step Number', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '01',
                'label_block' => true,
            ]
        );

        $step_repeater->add_control(
            'step_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Upload Your Images', 'softro-core'),
                'label_block' => true,
            ]
        );

        $step_repeater->add_control(
            'step_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Share your images and project requirements through our quote form or email.', 'softro-core'),
            ]
        );

        $step_repeater->add_control(
            'show_arrow',
            [
                'label'        => esc_html__('Show Arrow After Step', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->start_controls_section(
            'gc_process_one_steps_section',
            [
                'label' => esc_html__('Process Steps', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_process_one_steps',
            [
                'label'       => esc_html__('Steps', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $step_repeater->get_controls(),
                'default'     => [
                    [
                        'card_style'        => '1',
                        'step_number'       => '01',
                        'step_title'        => esc_html__('Upload Your Images', 'softro-core'),
                        'step_description'  => esc_html__('Share your images and project requirements through our quote form or email.', 'softro-core'),
                        'show_arrow'        => 'yes',
                    ],
                    [
                        'card_style'        => '2',
                        'step_number'       => '02',
                        'step_title'        => esc_html__('Get a Custom Quote', 'softro-core'),
                        'step_description'  => esc_html__('We review your files and provide pricing, turnaround time, and project details.', 'softro-core'),
                        'show_arrow'        => 'yes',
                    ],
                    [
                        'card_style'        => '3',
                        'step_number'       => '03',
                        'step_title'        => esc_html__('We Edit Your Images', 'softro-core'),
                        'step_description'  => esc_html__('Our editors work on your images based on your instructions and quality requirements.', 'softro-core'),
                        'show_arrow'        => 'yes',
                    ],
                    [
                        'card_style'        => '4',
                        'step_number'       => '04',
                        'step_title'        => esc_html__('Review & Delivery', 'softro-core'),
                        'step_description'  => esc_html__('Once editing is complete, we deliver final files, with unlimited free revisions if needed.', 'softro-core'),
                        'show_arrow'        => '',
                    ],
                ],
                'title_field' => '{{{ step_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_process_one_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_process_one_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_heading_margin_bottom',
            [
                'label'      => esc_html__('Heading Bottom Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-heading' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_process_one_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-process-section',
            ]
        );

        $this->add_control(
            'gc_process_one_section_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-section' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-process-eyebrow',
            ]
        );

        $this->add_control(
            'gc_process_one_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => [
                    '{{WRAPPER}} .gc-process-heading .sub-heading.gc-process-eyebrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-process-eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_title_typography',
                'selector' => '{{WRAPPER}} .gc-process-heading .section-title',
            ]
        );

        $this->add_control(
            'gc_process_one_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-heading .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_desc_typography',
                'selector' => '{{WRAPPER}} .gc-process-desc',
            ]
        );

        $this->add_control(
            'gc_process_one_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_card',
            [
                'label' => esc_html__('Process Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_process_one_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_process_one_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_card_number',
            [
                'label' => esc_html__('Step Number', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_card_num_typography',
                'selector' => '{{WRAPPER}} .gc-process-card-num',
            ]
        );

        $this->add_control(
            'gc_process_one_card_num_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card-num' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_card_head_padding',
            [
                'label'      => esc_html__('Head Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-card-head' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_card_title',
            [
                'label' => esc_html__('Card Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_card_title_typography',
                'selector' => '{{WRAPPER}} .gc-process-card-title',
            ]
        );

        $this->add_control(
            'gc_process_one_card_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_process_one_card_title_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card:hover .gc-process-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_card_body_padding',
            [
                'label'      => esc_html__('Body Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-card-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_card_desc',
            [
                'label' => esc_html__('Card Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_process_one_card_desc_typography',
                'selector' => '{{WRAPPER}} .gc-process-card-desc',
            ]
        );

        $this->add_control(
            'gc_process_one_card_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_process_one_card_desc_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-card:hover .gc-process-card-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_process_one_style_arrow',
            [
                'label' => esc_html__('Step Arrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_process_one_arrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-process-arrow'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-process-arrow i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-process-arrow svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_process_one_arrow_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-process-arrow i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-process-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_process_one_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_process_one_theme_mode_color_tabs');

        $this->start_controls_tab('gc_process_one_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_process_one_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-process-section',
            ]
        );

        $this->add_control(
            'gc_process_one_dark_section_border',
            [
                'label'     => esc_html__('Section Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-section' => 'border-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-eyebrow' => 'color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-heading .section-title' => 'color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-desc' => 'color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-card' => 'background-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_card_border',
            [
                'label'     => esc_html__('Card Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-process-card' => 'border-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-process-card-title' => 'color: {{VALUE}};',
                    '.gc-process-card:hover .gc-process-card-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_card_desc_color',
            [
                'label'     => esc_html__('Card Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-process-card-desc' => 'color: {{VALUE}};',
                    '.gc-process-card:hover .gc-process-card-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_process_one_dark_arrow_color',
            [
                'label'     => esc_html__('Arrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-process-arrow'     => 'color: {{VALUE}};',
                    '.gc-process-arrow i'   => 'color: {{VALUE}};',
                    '.gc-process-arrow svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_process_one_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_process_one_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-process-section',
            ]
        );

        $this->add_control(
            'gc_process_one_light_section_border',
            [
                'label'     => esc_html__('Section Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', ['.gc-process-section' => 'border-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-process-heading .sub-heading.gc-process-eyebrow' => 'color: {{VALUE}};',
                    '.gc-process-eyebrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_process_one_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', ['.gc-process-heading .section-title' => 'color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', ['.gc-process-desc' => 'color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_light_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', ['.gc-process-card' => 'background-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_light_card_border',
            [
                'label'     => esc_html__('Card Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', ['.gc-process-card' => 'border-color: {{VALUE}};']),
            ]
        );

        $this->add_control(
            'gc_process_one_light_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-process-card-title' => 'color: {{VALUE}};',
                    '.gc-process-card:hover .gc-process-card-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_process_one_light_card_desc_color',
            [
                'label'     => esc_html__('Card Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-process-card-desc' => 'color: {{VALUE}};',
                    '.gc-process-card:hover .gc-process-card-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_process_one_light_arrow_color',
            [
                'label'     => esc_html__('Arrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-process-arrow'     => 'color: {{VALUE}};',
                    '.gc-process-arrow i'   => 'color: {{VALUE}};',
                    '.gc-process-arrow svg' => 'fill: {{VALUE}};',
                    '.gc-process-col:hover .gc-process-arrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_process_one_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_process_step($step, $settings)
    {
        $card_style  = !empty($step['card_style']) ? absint($step['card_style']) : 1;
        $card_style  = max(1, min(4, $card_style));
        $step_number = $step['step_number'] ?? '';
        $step_title  = $step['step_title'] ?? '';
        $step_desc   = $step['step_description'] ?? '';
        $show_arrow  = ('yes' === ($step['show_arrow'] ?? ''));
        ?>
        <div class="col-lg-3 col-md-6 gc-process-col">
            <div class="gc-process-card gc-process-card--<?php echo esc_attr($card_style); ?> fade-top">
                <div class="gc-process-card-head">
                    <?php if ($step_number) : ?>
                        <span class="gc-process-card-num"><?php echo esc_html($step_number); ?></span>
                    <?php endif; ?>
                </div>
                <div class="gc-process-card-body">
                    <?php if ($step_title) : ?>
                        <h3 class="gc-process-card-title"><?php echo esc_html($step_title); ?></h3>
                    <?php endif; ?>

                    <?php if ($step_desc) : ?>
                        <p class="gc-process-card-desc"><?php echo $this->get_paragraph_inner_content($step_desc); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($show_arrow) : ?>
                <span class="gc-process-arrow" aria-hidden="true"><?php $this->render_icon($settings['gc_process_one_arrow_icon'] ?? []); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow     = $settings['gc_process_one_eyebrow'] ?? '';
        $title       = $settings['gc_process_one_title'] ?? '';
        $description = $settings['gc_process_one_description'] ?? '';
        $steps       = !empty($settings['gc_process_one_steps']) ? $settings['gc_process_one_steps'] : [];
        ?>

        <section class="gc-process-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center gc-process-heading">
                    <?php if ($eyebrow) : ?>
                        <h4 class="sub-heading gc-process-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="gc-process-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($steps)) : ?>
                    <div class="row gy-4 gc-process-row">
                        <?php foreach ($steps as $step) :
                            $this->render_process_step($step, $settings);
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Process_One_Widget());
