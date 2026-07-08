<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_About_Photo_Retouching_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_about_photo_retouching';
    }

    public function get_title()
    {
        return esc_html__('GC About Photo Retouching', 'softro-core');
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

    private function get_media_url($media, $fallback_path = '')
    {
        if (!empty($media['url'])) {
            return esc_url($media['url']);
        }

        if ($fallback_path) {
            return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($fallback_path, '/'));
        }

        return '';
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

    private function render_benefit_icon($item, $settings)
    {
        echo '<span class="gc-bg-removal-benefit-icon" aria-hidden="true">';

        if (!empty($item['benefit_icon']['value'])) {
            $this->render_icon($item['benefit_icon'], ['aria-hidden' => 'true']);
            echo '</span>';
            return;
        }

        $icon_url = $this->get_media_url($item['benefit_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_about_pr_default_benefit_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            echo '</span>';
            return;
        }

        if (!empty($settings['gc_about_pr_default_benefit_icon']['value'])) {
            $this->render_icon($settings['gc_about_pr_default_benefit_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-light fa-check" aria-hidden="true"></i>';
        }

        echo '</span>';
    }

    private function get_default_benefits()
    {
        return [
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Creates polished, natural looking photos', 'softro-core'),
            ],
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Improves consistency across an entire shoot', 'softro-core'),
            ],
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Correction lighting, color, and exposure', 'softro-core'),
            ],
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Smooths skin tones without losing texture', 'softro-core'),
            ],
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Increases viewer trust and visual appeal', 'softro-core'),
            ],
            [
                'benefit_icon' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'benefit_text' => esc_html__('Supports better engagement across platforms', 'softro-core'),
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
        $this->start_controls_section('gc_about_pr_content_section', [
            'label' => esc_html__('Content', 'softro-core'),
        ]);

        $this->add_control('gc_about_pr_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('What Is Photo Retouching?', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_about_pr_callout', [
            'label'   => esc_html__('Callout Text', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Photo retouching is a photo editing technique that enhances and refines images by correcting lighting, color and small imperfections.',
                'softro-core'
            ),
        ]);

        $this->add_control('gc_about_pr_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'It creates a polished, natural looking image that helps photos stand out across portraits, fashion shoots, product catalogs, and marketing materials. Professional retouching keeps result realistic and makes it easy to maintain a consistent style across an entire shoot.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $benefit_repeater = new Repeater();

        $benefit_repeater->add_control('benefit_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-light fa-check',
                'library' => 'fa-light',
            ],
        ]);

        $benefit_repeater->add_control('benefit_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $benefit_repeater->add_control('benefit_text', [
            'label'       => esc_html__('Text', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Creates polished, natural looking photos', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->start_controls_section('gc_about_pr_benefits_section', [
            'label' => esc_html__('Benefit Cards', 'softro-core'),
        ]);

        $this->add_control('gc_about_pr_default_benefit_icon', [
            'label'   => esc_html__('Default Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-light fa-check',
                'library' => 'fa-light',
            ],
        ]);

        $this->add_control('gc_about_pr_default_benefit_icon_image', [
            'label'       => esc_html__('Default Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Used when a card has no icon selected.', 'softro-core'),
        ]);

        $this->add_control('gc_about_pr_benefits', [
            'label'       => esc_html__('Benefits', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $benefit_repeater->get_controls(),
            'default'     => $this->get_default_benefits(),
            'title_field' => '{{{ benefit_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_about_pr_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->add_control('gc_about_pr_panel_shape', [
            'label'       => esc_html__('Panel Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the panel.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_about_pr_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_pr_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .delivery-speed-section-11',
        ]);

        $this->add_responsive_control('gc_about_pr_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_section_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 50, 'max' => 1200],
                '%'  => ['min' => 10, 'max' => 100],
            ],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-section-11' => 'background-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_panel', [
            'label' => esc_html__('Panel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_panel_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .delivery-speed-panel',
        ]);

        $this->add_responsive_control('gc_about_pr_panel_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_panel_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_panel_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => [
                'px' => ['min' => 50, 'max' => 1200],
                '%'  => ['min' => 10, 'max' => 100],
            ],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-panel' => 'background-size: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_content_column', [
            'label' => esc_html__('Content Column', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_pr_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-about-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-about-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_pr_title_typography',
            'selector' => '{{WRAPPER}} .delivery-speed-title',
        ]);

        $this->add_control('gc_about_pr_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .delivery-speed-title' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_callout_box', [
            'label' => esc_html__('Callout Box', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_callout_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-callout',
        ]);

        $this->add_control('gc_about_pr_callout_border_color', [
            'label'     => esc_html__('Left Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-callout' => 'border-left-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_callout_border_width', [
            'label'      => esc_html__('Left Border Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 20]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-callout' => 'border-left-width: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_callout_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-callout' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_callout_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-callout' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_callout_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-callout' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_callout_text', [
            'label' => esc_html__('Callout Text', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_pr_callout_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-callout p',
        ]);

        $this->add_control('gc_about_pr_callout_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-callout p' => 'color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_description', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_pr_desc_typography',
            'selector' => '{{WRAPPER}} .delivery-speed-desc',
        ]);

        $this->add_control('gc_about_pr_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .delivery-speed-desc' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .delivery-speed-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_benefits_grid', [
            'label' => esc_html__('Benefits Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_pr_benefits_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefits-grid' => 'gap: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefits_grid_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefits-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefits_grid_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefits-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_benefit_card', [
            'label' => esc_html__('Benefit Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_benefit_card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-benefit-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_about_pr_benefit_card_border',
            'selector' => '{{WRAPPER}} .gc-bg-removal-benefit-card',
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_card_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_about_pr_benefit_card_hover_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_card_hover_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover' => 'border-color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_card_line_color', [
            'label'     => esc_html__('Top Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card::after' => 'background: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_benefit_icon', [
            'label' => esc_html__('Benefit Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_icon_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 16, 'max' => 80]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_icon_font_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-benefit-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-benefit-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_icon_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_about_pr_benefit_icon_hover_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_icon_hover_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_pr_style_benefit_text', [
            'label' => esc_html__('Benefit Text', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_pr_benefit_text_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-benefit-card p',
        ]);

        $this->add_control('gc_about_pr_benefit_text_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card p' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_about_pr_benefit_text_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card:hover p' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_text_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_about_pr_benefit_text_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-benefit-card p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_about_pr_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_about_pr_theme_mode_tabs');

        $this->start_controls_tab('gc_about_pr_dark_tab', [
            'label' => esc_html__('Dark Mode', 'softro-core'),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .delivery-speed-section-11',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_dark_panel_bg',
            'label'    => esc_html__('Panel Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .delivery-speed-panel',
        ]);

        $this->add_control('gc_about_pr_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.delivery-speed-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_dark_callout_bg',
            'label'    => esc_html__('Callout Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-callout',
        ]);

        $this->add_control('gc_about_pr_dark_callout_border_color', [
            'label'     => esc_html__('Callout Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-callout' => 'border-left-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_callout_color', [
            'label'     => esc_html__('Callout Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-callout p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.delivery-speed-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_card_bg', [
            'label'     => esc_html__('Benefit Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_card_border', [
            'label'     => esc_html__('Benefit Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_card_hover_bg', [
            'label'     => esc_html__('Benefit Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_card_hover_border', [
            'label'     => esc_html__('Benefit Card Hover Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card:hover' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_text_color', [
            'label'     => esc_html__('Benefit Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_text_hover_color', [
            'label'     => esc_html__('Benefit Text Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-card:hover p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_icon_color', [
            'label'     => esc_html__('Benefit Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_icon_bg', [
            'label'     => esc_html__('Benefit Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_icon_hover_color', [
            'label'     => esc_html__('Benefit Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_pr_dark_benefit_icon_hover_bg', [
            'label'     => esc_html__('Benefit Icon Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_about_pr_light_tab', [
            'label' => esc_html__('Light Mode', 'softro-core'),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .delivery-speed-section-11',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_light_panel_bg',
            'label'    => esc_html__('Panel Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .delivery-speed-panel',
        ]);

        $this->add_control('gc_about_pr_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.delivery-speed-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_pr_light_callout_bg',
            'label'    => esc_html__('Callout Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-callout',
        ]);

        $this->add_control('gc_about_pr_light_callout_border_color', [
            'label'     => esc_html__('Callout Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-callout' => 'border-left-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_callout_color', [
            'label'     => esc_html__('Callout Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-callout p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.delivery-speed-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_card_bg', [
            'label'     => esc_html__('Benefit Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_card_border', [
            'label'     => esc_html__('Benefit Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_card_hover_bg', [
            'label'     => esc_html__('Benefit Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_card_hover_border', [
            'label'     => esc_html__('Benefit Card Hover Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card:hover' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_text_color', [
            'label'     => esc_html__('Benefit Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_text_hover_color', [
            'label'     => esc_html__('Benefit Text Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-card:hover p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_icon_color', [
            'label'     => esc_html__('Benefit Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_pr_light_benefit_icon_bg', [
            'label'     => esc_html__('Benefit Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_pr_light_benefit_icon_hover_color', [
            'label'     => esc_html__('Benefit Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_pr_light_benefit_icon_hover_bg', [
            'label'     => esc_html__('Benefit Icon Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-benefit-card:hover .gc-bg-removal-benefit-icon' => 'background-color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_about_pr_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_shape_backgrounds($settings)
    {
        $section_shape = $this->get_media_url($settings['gc_about_pr_section_shape'] ?? [], '');
        $panel_shape   = $this->get_media_url($settings['gc_about_pr_panel_shape'] ?? [], '');

        if (!$section_shape && !$panel_shape) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            <?php if ($section_shape) : ?>
            .elementor-element-<?php echo $widget_id; ?> .delivery-speed-section-11 {
                background-image: url('<?php echo esc_url($section_shape); ?>');
                background-repeat: no-repeat;
            }
            <?php endif; ?>
            <?php if ($panel_shape) : ?>
            .elementor-element-<?php echo $widget_id; ?> .delivery-speed-panel {
                background-image: url('<?php echo esc_url($panel_shape); ?>');
                background-repeat: no-repeat;
            }
            <?php endif; ?>
        </style>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $title       = $settings['gc_about_pr_title'] ?? '';
        $callout     = $settings['gc_about_pr_callout'] ?? '';
        $description = $settings['gc_about_pr_description'] ?? '';
        $benefits    = $settings['gc_about_pr_benefits'] ?? [];

        if (empty($benefits)) {
            $benefits = $this->get_default_benefits();
        }

        $this->render_elementor_spacing_fix($settings);
        $this->render_shape_backgrounds($settings);
        ?>

        <section class="delivery-speed-section-11 gc-bg-removal-about pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="delivery-speed-panel gc-bg-removal-about-panel">
                    <div class="row g-4 g-xl-5 align-items-start">
                        <div class="col-lg-5">
                            <div class="delivery-speed-content gc-bg-removal-about-content">
                                <?php if ('' !== trim((string) $title)) : ?>
                                    <h2 class="delivery-speed-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                                <?php if ('' !== trim(wp_strip_all_tags((string) $callout))) : ?>
                                    <div class="gc-bg-removal-callout fade-top">
                                        <p><?php echo $this->get_paragraph_inner_content($callout); ?></p>
                                    </div>
                                <?php endif; ?>
                                <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                                    <p class="delivery-speed-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="gc-bg-removal-benefits-grid">
                                <?php foreach ($benefits as $benefit) : ?>
                                    <?php
                                    $benefit_text = trim((string) ($benefit['benefit_text'] ?? ''));

                                    if ('' === $benefit_text) {
                                        continue;
                                    }
                                    ?>
                                    <div class="gc-bg-removal-benefit-card fade-top">
                                        <?php $this->render_benefit_icon($benefit, $settings); ?>
                                        <p><?php echo esc_html($benefit_text); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_About_Photo_Retouching_Widget());
