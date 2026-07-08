<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Service_Three_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_service_three';
    }

    public function get_title()
    {
        return esc_html__('GC Service Three', 'softro-core');
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

    private function get_default_service_cards()
    {
        return [
            [
                'card_icon'        => ['value' => 'fa-light fa-magnifying-glass', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Modern SEO', 'softro-core'),
                'card_description' => esc_html__('Boost visibility with AEO, GEO, and SXO strategies powered by AI search intent analysis.', 'softro-core'),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-bullhorn', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Paid Advertising', 'softro-core'),
                'card_description' => esc_html__('Run high-converting ad campaigns across Google, Meta, and more with AI-optimized targeting.', 'softro-core'),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-pen-nib', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Content Marketing', 'softro-core'),
                'card_description' => esc_html__('Create engaging content that builds authority, drives traffic, and converts readers into customers.', 'softro-core'),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-share-nodes', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Social Media Marketing', 'softro-core'),
                'card_description' => esc_html__('Grow your brand presence with platform-specific strategies and AI-driven audience engagement.', 'softro-core'),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-envelope-open-text', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Email Marketing', 'softro-core'),
                'card_description' => esc_html__('Nurture leads and retain customers with personalized email flows and automated campaigns.', 'softro-core'),
            ],
            [
                'card_icon'        => ['value' => 'fa-light fa-chart-line-up', 'library' => 'fa-light'],
                'card_title'       => esc_html__('Analytics & Reporting', 'softro-core'),
                'card_description' => esc_html__('Track performance with clear dashboards, actionable insights, and continuous optimization.', 'softro-core'),
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
        $this->start_controls_section('gc_service_three_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_service_three_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('AI-Powered Digital Marketing Solutions', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_service_three_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We provide advanced AI-driven marketing solutions. Our digital marketing service includes.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-magnifying-glass', 'library' => 'fa-light'],
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label'   => esc_html__('Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Modern SEO', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_link', [
            'label'       => esc_html__('Title Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Boost visibility with AEO, GEO, and SXO strategies powered by AI search intent analysis.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_service_three_cards_section', [
            'label' => esc_html__('Service Cards', 'softro-core'),
        ]);

        $this->add_control('gc_service_three_default_card_icon', [
            'label'   => esc_html__('Default Card Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-magnifying-glass', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_service_three_default_card_icon_image', [
            'label'   => esc_html__('Default Card Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_service_three_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_service_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_service_three_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_service_three_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_service_three_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section .row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_service_three_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_service_three_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-service-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_service_three_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-service-section::before',
        ]);

        $this->add_control('gc_service_three_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-section' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-section' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_service_three_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_service_three_heading_margin', [
            'label'      => esc_html__('Heading Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_service_three_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .ai-marketing-service-section .section-title',
        ]);

        $this->add_control('gc_service_three_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-section .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_service_three_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-section .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_service_three_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-service-desc',
            'separator' => 'before',
        ]);

        $this->add_control('gc_service_three_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_service_three_desc_max_width', [
            'label'      => esc_html__('Description Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 760, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-desc' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_desc_margin', [
            'label'      => esc_html__('Description Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_service_three_style_card', [
            'label' => esc_html__('Service Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_service_three_card_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_service_three_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_service_three_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_service_three_card_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_icon_heading', [
            'label'     => esc_html__('Icon Box', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_service_three_icon_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 92, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_service_three_icon_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .ai-marketing-service-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .ai-marketing-service-icon svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_service_three_icon_inner_size', [
            'label'      => esc_html__('Icon / Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .ai-marketing-service-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ai-marketing-service-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .ai-marketing-service-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_responsive_control('gc_service_three_icon_margin', [
            'label'      => esc_html__('Icon Box Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-service-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_service_three_card_title_heading', [
            'label'     => esc_html__('Card Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_service_three_card_title_typography',
            'selector' => '{{WRAPPER}} .ai-marketing-service-card .title',
        ]);

        $this->add_control('gc_service_three_card_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card .title' => 'color: {{VALUE}};', '{{WRAPPER}} .ai-marketing-service-card .title a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_card_title_hover_color', [
            'label'     => esc_html__('Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card:hover .title a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_card_desc_heading', [
            'label'     => esc_html__('Card Description', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_service_three_card_desc_typography',
            'selector' => '{{WRAPPER}} .ai-marketing-service-card p',
        ]);

        $this->add_control('gc_service_three_card_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card p' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_service_three_card_desc_hover_color', [
            'label'     => esc_html__('Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-service-card:hover p' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_service_three_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_service_three_theme_mode_tabs');

        $this->start_controls_tab('gc_service_three_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_service_three_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-service-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_service_three_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-service-section::before',
        ]);

        $this->add_control('gc_service_three_dark_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-section .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_icon_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.ai-marketing-service-icon i'   => 'color: {{VALUE}};',
                '.ai-marketing-service-icon svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_service_three_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.ai-marketing-service-card .title'   => 'color: {{VALUE}};',
                '.ai-marketing-service-card .title a' => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_service_three_dark_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-card:hover .title a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_dark_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-service-card:hover p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_service_three_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_service_three_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-service-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_service_three_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-service-section::before',
        ]);

        $this->add_control('gc_service_three_light_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-section .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_icon_bg', [
            'label'     => esc_html__('Icon Box Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.ai-marketing-service-icon i'   => 'color: {{VALUE}};',
                '.ai-marketing-service-icon svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_service_three_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.ai-marketing-service-card .title'   => 'color: {{VALUE}};',
                '.ai-marketing-service-card .title a' => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_service_three_light_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-card:hover .title a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_service_three_light_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-service-card:hover p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_service_three_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_card_icon($card, $settings)
    {
        if (!empty($card['card_icon']['value'])) {
            $this->render_icon($card['card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($card['card_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_service_three_default_card_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_service_three_default_card_icon']['value'])) {
            $this->render_icon($settings['gc_service_three_default_card_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_service_card($card, $settings)
    {
        $title       = $card['card_title'] ?? '';
        $description = $card['card_description'] ?? '';
        $link        = $card['card_link'] ?? [];

        if (!$title) {
            return;
        }
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="ai-marketing-service-card fade-top">
                <div class="ai-marketing-service-icon">
                    <?php $this->render_card_icon($card, $settings); ?>
                </div>
                <h3 class="title">
                    <a<?php echo $this->get_link_attributes($link); ?>><?php echo esc_html($title); ?></a>
                </h3>
                <?php if ($description) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
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

        $title       = $settings['gc_service_three_title'] ?? '';
        $description = $settings['gc_service_three_description'] ?? '';
        $cards       = !empty($settings['gc_service_three_cards']) ? $settings['gc_service_three_cards'] : [];
        ?>

        <section class="ai-marketing-service-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center">
                    <?php if ($title) : ?>
                        <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="ai-marketing-service-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cards)) : ?>
                    <div class="row gy-4">
                        <?php foreach ($cards as $card) {
                            $this->render_service_card($card, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Service_Three_Widget());
