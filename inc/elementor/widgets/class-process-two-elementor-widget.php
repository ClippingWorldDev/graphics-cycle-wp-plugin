<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Process_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_process_two';
    }

    public function get_title()
    {
        return esc_html__('GC Process Two', 'softro-core');
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

    private function get_default_process_steps()
    {
        return [
            [
                'step_icon'  => ['value' => 'fa-light fa-comments', 'library' => 'fa-light'],
                'step_title' => esc_html__('Discuss your project', 'softro-core'),
            ],
            [
                'step_icon'  => ['value' => 'fa-light fa-chart-pie', 'library' => 'fa-light'],
                'step_title' => esc_html__('Business Analysis', 'softro-core'),
            ],
            [
                'step_icon'  => ['value' => 'fa-light fa-list-check', 'library' => 'fa-light'],
                'step_title' => esc_html__('Custom Plan', 'softro-core'),
            ],
            [
                'step_icon'  => ['value' => 'fa-light fa-rocket', 'library' => 'fa-light'],
                'step_title' => esc_html__('Execute & Grow', 'softro-core'),
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
        $this->start_controls_section('gc_process_two_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_process_two_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('How We Work on A Project', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_two_background_section', [
            'label' => esc_html__('Section Background', 'softro-core'),
        ]);

        $this->add_control('gc_process_two_bg_image', [
            'label'   => esc_html__('Background Shape Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('bg-img/process-bg.png')],
        ]);

        $this->end_controls_section();

        $step_repeater = new Repeater();

        $step_repeater->add_control('step_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-comments', 'library' => 'fa-light'],
        ]);

        $step_repeater->add_control('step_icon_image', [
            'label'   => esc_html__('Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $step_repeater->add_control('step_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Discuss your project', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_process_two_steps_section', [
            'label' => esc_html__('Process Steps', 'softro-core'),
        ]);

        $this->add_control('gc_process_two_default_step_icon', [
            'label'   => esc_html__('Default Step Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-comments', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_process_two_default_step_icon_image', [
            'label'   => esc_html__('Default Step Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_process_two_steps', [
            'label'       => esc_html__('Steps', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $step_repeater->get_controls(),
            'default'     => $this->get_default_process_steps(),
            'title_field' => '{{{ step_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_process_two_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_process_two_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_process_two_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .process-section-3' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_two_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .process-section-3' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_two_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-section-3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_two_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .process-section-3 .row.gy-lg-0' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_two_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_two_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .process-section-3',
        ]);

        $this->add_control('gc_process_two_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-section-3' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_two_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_process_two_heading_margin', [
            'label'      => esc_html__('Heading Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-section-3 .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_process_two_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .process-section-3 .section-title',
        ]);

        $this->add_control('gc_process_two_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-section-3 .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_process_two_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-section-3 .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_two_style_item', [
            'label' => esc_html__('Process Item', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_process_two_item_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_process_two_item_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top'    => '50',
                'right'  => '40',
                'bottom' => '50',
                'left'   => '40',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .process-item-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_two_item_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-item-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_process_two_item_border',
            'selector' => '{{WRAPPER}} .process-item-2',
        ]);

        $this->add_responsive_control('gc_process_two_item_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .process-item-2' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_process_two_item_hover_overlay', [
            'label'     => esc_html__('Hover Overlay Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2:before' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_process_two_item_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .process-item-2 .title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_process_two_item_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2 .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_process_two_item_title_hover_color', [
            'label'     => esc_html__('Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2:hover .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_process_two_item_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-item-2 .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_two_style_icon', [
            'label' => esc_html__('Step Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_process_two_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 120, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .process-item-2 .icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_process_two_icon_box_margin', [
            'label'      => esc_html__('Icon Box Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .process-item-2 .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_process_two_icon_box_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2 .icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_process_two_icon_box_border',
            'selector' => '{{WRAPPER}} .process-item-2 .icon',
        ]);

        $this->add_responsive_control('gc_process_two_icon_box_radius', [
            'label'      => esc_html__('Icon Box Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'    => '50',
                'right'  => '50',
                'bottom' => '50',
                'left'   => '50',
                'unit'   => '%',
            ],
            'selectors'  => ['{{WRAPPER}} .process-item-2 .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_two_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 51, 'unit' => 'px'],
            'selectors'  => [
                '{{WRAPPER}} .process-item-2 .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .process-item-2 .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .process-item-2 .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_process_two_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .process-item-2 .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '{{WRAPPER}} .process-item-2 .icon i'   => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_process_two_icon_hover_box_bg', [
            'label'     => esc_html__('Icon Box Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2:hover .icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_process_two_icon_hover_border_color', [
            'label'     => esc_html__('Icon Box Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .process-item-2:hover .icon' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_process_two_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .process-item-2:hover .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '{{WRAPPER}} .process-item-2:hover .icon i'   => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_process_two_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_process_two_theme_mode_tabs');

        $this->start_controls_tab('gc_process_two_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_two_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .process-section-3',
        ]);

        $this->add_control('gc_process_two_dark_section_border_color', [
            'label'     => esc_html__('Section Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-section-3' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_title_color', [
            'label'     => esc_html__('Header Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-section-3 .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_item_bg', [
            'label'     => esc_html__('Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_item_hover_overlay', [
            'label'     => esc_html__('Item Hover Overlay', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2:before' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_item_title_hover_color', [
            'label'     => esc_html__('Item Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2:hover .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_icon_box_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2 .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_icon_box_border_color', [
            'label'     => esc_html__('Icon Box Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2 .icon' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.process-item-2 .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '.process-item-2 .icon i'   => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_process_two_dark_icon_hover_box_bg', [
            'label'     => esc_html__('Icon Box Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2:hover .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_icon_hover_border_color', [
            'label'     => esc_html__('Icon Box Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.process-item-2:hover .icon' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_dark_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.process-item-2:hover .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '.process-item-2:hover .icon i'   => 'color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_process_two_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_two_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .process-section-3',
        ]);

        $this->add_control('gc_process_two_light_section_border_color', [
            'label'     => esc_html__('Section Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-section-3' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_title_color', [
            'label'     => esc_html__('Header Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-section-3 .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_item_bg', [
            'label'     => esc_html__('Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_item_hover_overlay', [
            'label'     => esc_html__('Item Hover Overlay', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2:before' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_item_title_hover_color', [
            'label'     => esc_html__('Item Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2:hover .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_icon_box_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2 .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_icon_box_border_color', [
            'label'     => esc_html__('Icon Box Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2 .icon' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.process-item-2 .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '.process-item-2 .icon i'   => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_process_two_light_icon_hover_box_bg', [
            'label'     => esc_html__('Icon Box Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2:hover .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_icon_hover_border_color', [
            'label'     => esc_html__('Icon Box Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.process-item-2:hover .icon' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_two_light_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.process-item-2:hover .icon svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                '.process-item-2:hover .icon i'   => 'color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_process_two_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_step_icon($step, $settings)
    {
        if (!empty($step['step_icon']['value'])) {
            $this->render_icon($step['step_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($step['step_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_process_two_default_step_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_process_two_default_step_icon']['value'])) {
            $this->render_icon($settings['gc_process_two_default_step_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_process_step($step, $settings)
    {
        $title = $step['step_title'] ?? '';

        if (!$title) {
            return;
        }
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="process-item-wrap fade-top">
                <div class="process-item-2 text-center">
                    <div class="icon">
                        <?php $this->render_step_icon($step, $settings); ?>
                    </div>
                    <h3 class="title"><?php echo esc_html($title); ?></h3>
                </div>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $title    = $settings['gc_process_two_title'] ?? '';
        $steps    = !empty($settings['gc_process_two_steps']) ? $settings['gc_process_two_steps'] : [];
        $bg_image = $this->get_media_url($settings['gc_process_two_bg_image'] ?? [], 'bg-img/process-bg.png');
        ?>

        <section class="process-section-3 pt-130 pb-130"<?php echo $bg_image ? ' data-background="' . esc_attr($bg_image) . '"' : ''; ?>>
            <div class="container">
                <div class="section-heading heading-3 text-center">
                    <?php if ($title) : ?>
                        <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                </div>

                <?php if (!empty($steps)) : ?>
                    <div class="row gy-lg-0 gy-4 fade-wrapper">
                        <?php foreach ($steps as $step) {
                            $this->render_process_step($step, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Process_Two_Widget());
