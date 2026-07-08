<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_PPC_Hero_Banner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_ppc_hero_banner';
    }

    public function get_title()
    {
        return esc_html__('GC PPC Hero Banner', 'softro-core');
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

    private function render_platform_icon(array $item, array $settings)
    {
        if (!empty($item['platform_icon']['value'])) {
            $this->render_icon($item['platform_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['platform_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_ppc_hb_default_platform_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_ppc_hb_default_platform_icon']['value'])) {
            $this->render_icon($settings['gc_ppc_hb_default_platform_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_default_platforms()
    {
        return [
            [
                'platform_icon' => ['value' => 'fa-brands fa-google', 'library' => 'fa-brands'],
                'platform_label' => esc_html__('Google', 'softro-core'),
            ],
            [
                'platform_icon' => ['value' => 'fa-brands fa-meta', 'library' => 'fa-brands'],
                'platform_label' => esc_html__('Meta', 'softro-core'),
            ],
            [
                'platform_icon' => ['value' => 'fa-brands fa-microsoft', 'library' => 'fa-brands'],
                'platform_label' => esc_html__('Microsoft', 'softro-core'),
            ],
            [
                'platform_icon' => ['value' => 'fa-brands fa-linkedin-in', 'library' => 'fa-brands'],
                'platform_label' => esc_html__('LinkedIn', 'softro-core'),
            ],
        ];
    }

    private function get_default_metrics()
    {
        return [
            [
                'metric_modifier' => 'roi',
                'metric_label'    => esc_html__('ROI', 'softro-core'),
                'metric_value'    => '+70%',
                'metric_bar_width' => '70',
            ],
            [
                'metric_modifier' => 'roas',
                'metric_label'    => esc_html__('ROAS', 'softro-core'),
                'metric_value'    => '4.9x',
                'metric_bar_width' => '82',
            ],
            [
                'metric_modifier' => 'cpc',
                'metric_label'    => esc_html__('CPC', 'softro-core'),
                'metric_value'    => '-32%',
                'metric_bar_width' => '32',
            ],
            [
                'metric_modifier' => 'conv',
                'metric_label'    => esc_html__('Conversion', 'softro-core'),
                'metric_value'    => '199%',
                'metric_bar_width' => '88',
            ],
        ];
    }

    private function get_default_stats()
    {
        return [
            [
                'stat_value' => '$4.2M',
                'stat_label' => esc_html__('Ad spend managed', 'softro-core'),
            ],
            [
                'stat_value' => '340+',
                'stat_label' => esc_html__('Campaigns active', 'softro-core'),
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
        $this->start_controls_section('gc_ppc_hb_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_bg_shape', [
            'label'       => esc_html__('Background Shape', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_hb_bg_shape_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-1.png',
        ]);

        $this->add_control('gc_ppc_hb_bg_shape_alt', [
            'label'       => esc_html__('Background Shape Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_shape_1', [
            'label'       => esc_html__('Shape 1', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_hb_shape_1_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-22.png',
        ]);

        $this->add_control('gc_ppc_hb_shape_2', [
            'label'       => esc_html__('Shape 2', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_hb_shape_2_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-3.png',
        ]);

        $this->add_control('gc_ppc_hb_shape_2_alt', [
            'label'       => esc_html__('Shape 2 Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_hero_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('PPC Marketing Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('PPC Marketing Services with Measurable ROI & ROAS', 'softro-core'),
            'label_block' => true,
            'rows'        => 3,
        ]);

        $this->add_control('gc_ppc_hb_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'We provide professional Pay Per Click (PPC) marketing service on multiple platforms Google, Meta, Microsoft, and LinkedIn. We drive qualified leads and profitable revenue by managing ROI and ROAS.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_platform_defaults', [
            'label' => esc_html__('Platform Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_platforms_aria_label', [
            'label'       => esc_html__('Platforms List Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Advertising platforms', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_default_platform_icon', [
            'label'   => esc_html__('Default Platform Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-brands fa-google', 'library' => 'fa-brands'],
        ]);

        $this->add_control('gc_ppc_hb_default_platform_icon_image', [
            'label'       => esc_html__('Default Platform Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $platform_repeater = new Repeater();

        $platform_repeater->add_control('platform_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-brands fa-google', 'library' => 'fa-brands'],
        ]);

        $platform_repeater->add_control('platform_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $platform_repeater->add_control('platform_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Google', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_ppc_hb_platforms_section', [
            'label' => esc_html__('Platforms', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_platforms', [
            'label'       => esc_html__('Platform Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $platform_repeater->get_controls(),
            'default'     => $this->get_default_platforms(),
            'title_field' => '{{{ platform_label }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_buttons_section', [
            'label' => esc_html__('Buttons', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_primary_button_text', [
            'label'       => esc_html__('Primary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get Free Audit', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_primary_button_link', [
            'label'       => esc_html__('Primary Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_secondary_button_text', [
            'label'       => esc_html__('Outline Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_secondary_button_link', [
            'label'       => esc_html__('Outline Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_dashboard_section', [
            'label' => esc_html__('Dashboard Card', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_hb_dashboard_aria_label', [
            'label'       => esc_html__('Dashboard Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__(
                'PPC campaign performance dashboard showing ROI +70%, ROAS 4.9x, CPC -32%, Conversion 199%',
                'softro-core'
            ),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_ppc_hb_dashboard_title', [
            'label'       => esc_html__('Dashboard Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Live campaign dashboard', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_dashboard_status', [
            'label'       => esc_html__('Status Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Active', 'softro-core'),
            'label_block' => true,
        ]);

        $metric_repeater = new Repeater();

        $metric_repeater->add_control('metric_modifier', [
            'label'   => esc_html__('Metric Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'roi',
            'options' => [
                'roi'  => esc_html__('ROI', 'softro-core'),
                'roas' => esc_html__('ROAS', 'softro-core'),
                'cpc'  => esc_html__('CPC', 'softro-core'),
                'conv' => esc_html__('Conversion', 'softro-core'),
            ],
        ]);

        $metric_repeater->add_control('metric_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('ROI', 'softro-core'),
            'label_block' => true,
        ]);

        $metric_repeater->add_control('metric_value', [
            'label'       => esc_html__('Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '+70%',
            'label_block' => true,
        ]);

        $metric_repeater->add_control('metric_bar_width', [
            'label'       => esc_html__('Bar Width (data-width)', 'softro-core'),
            'type'        => Controls_Manager::NUMBER,
            'default'     => 70,
            'min'         => 0,
            'max'         => 100,
        ]);

        $this->add_control('gc_ppc_hb_metrics', [
            'label'       => esc_html__('Metrics', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $metric_repeater->get_controls(),
            'default'     => $this->get_default_metrics(),
            'title_field' => '{{{ metric_label }}}',
            'separator'   => 'before',
        ]);

        $stat_repeater = new Repeater();

        $stat_repeater->add_control('stat_value', [
            'label'       => esc_html__('Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '$4.2M',
            'label_block' => true,
        ]);

        $stat_repeater->add_control('stat_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Ad spend managed', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_hb_stats', [
            'label'       => esc_html__('Stat Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $stat_repeater->get_controls(),
            'default'     => $this->get_default_stats(),
            'title_field' => '{{{ stat_label }}}',
            'separator'   => 'before',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_ppc_hb_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_ppc_hb_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_hb_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-roi-section',
        ]);

        $this->add_responsive_control('gc_ppc_hb_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_shapes', [
            'label' => esc_html__('Shape Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_bg_shape_width', [
            'label'      => esc_html__('Background Shape Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section .bg-shape img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_shape_1_width', [
            'label'      => esc_html__('Shape 1 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section .shape-1 img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_shape_2_width', [
            'label'      => esc_html__('Shape 2 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section .shape-2 img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_hero_content', [
            'label' => esc_html__('Hero Content', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_hero_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_hero_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-roi-section .gc-hero-eyebrow',
        ]);

        $this->add_control('gc_ppc_hb_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-roi-section .gc-hero-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_eyebrow_dot_color', [
            'label'     => esc_html__('Dot Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-roi-section .gc-hero-eyebrow::before' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-roi-section .gc-hero-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_title_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-hero-title',
        ]);

        $this->add_control('gc_ppc_hb_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_title_underline_color', [
            'label'     => esc_html__('Underline Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-title::after' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_desc_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-hero-desc',
        ]);

        $this->add_control('gc_ppc_hb_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_platforms', [
            'label' => esc_html__('Platforms List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_platforms_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-platforms' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_platforms_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-platforms' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_platform_item', [
            'label' => esc_html__('Platform Item', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_platform_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-platforms li',
        ]);

        $this->add_control('gc_ppc_hb_platform_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-platforms li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_platform_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-platforms li' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_platform_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-platforms li' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_platform_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-platforms li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_platform_icon', [
            'label' => esc_html__('Platform Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_platform_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-ppc-platforms li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-platforms li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-platforms li img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_ppc_hb_platform_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-ppc-platforms li i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-ppc-platforms li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_buttons_wrap', [
            'label' => esc_html__('Buttons Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_buttons_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-btns' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_buttons_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_primary_btn', [
            'label' => esc_html__('Primary Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_primary_btn_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)',
        ]);

        $this->add_control('gc_ppc_hb_primary_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_primary_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_primary_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_outline_btn', [
            'label' => esc_html__('Outline Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_outline_btn_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-hero-btns .gc-hero-btn-outline',
        ]);

        $this->add_control('gc_ppc_hb_outline_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-btns .gc-hero-btn-outline' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_outline_btn_border', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-btns .gc-hero-btn-outline' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_outline_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-hero-btns .gc-hero-btn-outline' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_outline_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-hero-btns .gc-hero-btn-outline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_dashboard_wrap', [
            'label' => esc_html__('Dashboard Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_hb_dashboard_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-dashboard-wrap' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_dashboard_wrap_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-dashboard-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_dashboard_card', [
            'label' => esc_html__('Dashboard Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_hb_dashboard_card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-dashboard-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_ppc_hb_dashboard_card_border',
            'selector' => '{{WRAPPER}} .gc-ppc-dashboard-card',
        ]);

        $this->add_responsive_control('gc_ppc_hb_dashboard_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-dashboard-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_hb_dashboard_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-dashboard-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_dashboard_title', [
            'label' => esc_html__('Dashboard Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_dashboard_title_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-dashboard-title',
        ]);

        $this->add_control('gc_ppc_hb_dashboard_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-dashboard-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_dashboard_live_color', [
            'label'     => esc_html__('Live Dot Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-dashboard-live' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_dashboard_status', [
            'label' => esc_html__('Dashboard Status', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_dashboard_status_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-dashboard-status',
        ]);

        $this->add_control('gc_ppc_hb_dashboard_status_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-dashboard-status' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_dashboard_status_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-dashboard-status' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_dashboard_status_border', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-dashboard-status' => 'border-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_metric', [
            'label' => esc_html__('Metrics', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_metric_label_typography',
            'label'    => esc_html__('Label Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-ppc-metric-label',
        ]);

        $this->add_control('gc_ppc_hb_metric_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-metric-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_metric_value_typography',
            'label'    => esc_html__('Value Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-ppc-metric-value',
        ]);

        $this->add_control('gc_ppc_hb_metric_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-metric-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_hb_metric_bar_bg', [
            'label'     => esc_html__('Bar Track Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-metric-bar' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_hb_style_stat', [
            'label' => esc_html__('Stat Cards', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_stat_value_typography',
            'label'    => esc_html__('Value Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-ppc-stat-value',
        ]);

        $this->add_control('gc_ppc_hb_stat_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-stat-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_hb_stat_label_typography',
            'label'    => esc_html__('Label Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-ppc-stat-label',
        ]);

        $this->add_control('gc_ppc_hb_stat_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-stat-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_hb_stat_card_background',
            'label'    => esc_html__('Card Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-stat-card',
        ]);

        $this->add_responsive_control('gc_ppc_hb_stat_card_padding', [
            'label'      => esc_html__('Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-stat-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_ppc_hb_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_ppc_hb_theme_mode_tabs');

        $this->start_controls_tab('gc_ppc_hb_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_hb_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-ppc-roi-section',
        ]);

        $this->add_control('gc_ppc_hb_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-roi-section .gc-hero-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_platform_color', [
            'label'     => esc_html__('Platform Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-platforms li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_platform_bg', [
            'label'     => esc_html__('Platform Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-platforms li' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_platform_border', [
            'label'     => esc_html__('Platform Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-platforms li' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_platform_icon_color', [
            'label'     => esc_html__('Platform Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-ppc-platforms li i'   => 'color: {{VALUE}};',
                '.gc-ppc-platforms li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_ppc_hb_dark_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_primary_btn_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_outline_btn_color', [
            'label'     => esc_html__('Outline Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-roi-section .gc-hero-btn-outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-roi-section .gc-hero-btn-outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_dashboard_card_bg', [
            'label'     => esc_html__('Dashboard Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-dashboard-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_dashboard_card_border', [
            'label'     => esc_html__('Dashboard Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-dashboard-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_dashboard_title_color', [
            'label'     => esc_html__('Dashboard Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-dashboard-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_dashboard_status_color', [
            'label'     => esc_html__('Status Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-dashboard-status' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_metric_label_color', [
            'label'     => esc_html__('Metric Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-metric-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_metric_value_color', [
            'label'     => esc_html__('Metric Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-metric-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_dark_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_ppc_hb_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_hb_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-ppc-roi-section',
        ]);

        $this->add_control('gc_ppc_hb_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-roi-section .gc-hero-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_platform_color', [
            'label'     => esc_html__('Platform Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-platforms li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_platform_bg', [
            'label'     => esc_html__('Platform Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-platforms li' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_platform_border', [
            'label'     => esc_html__('Platform Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-platforms li' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_platform_icon_color', [
            'label'     => esc_html__('Platform Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-ppc-platforms li i'   => 'color: {{VALUE}};',
                '.gc-ppc-platforms li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_ppc_hb_light_primary_btn_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_primary_btn_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_outline_btn_color', [
            'label'     => esc_html__('Outline Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-roi-section .gc-hero-btn-outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-roi-section .gc-hero-btn-outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_dashboard_card_bg', [
            'label'     => esc_html__('Dashboard Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-dashboard-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_dashboard_card_border', [
            'label'     => esc_html__('Dashboard Card Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-dashboard-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_dashboard_title_color', [
            'label'     => esc_html__('Dashboard Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-dashboard-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_dashboard_status_color', [
            'label'     => esc_html__('Status Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-dashboard-status' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_metric_label_color', [
            'label'     => esc_html__('Metric Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-metric-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_metric_value_color', [
            'label'     => esc_html__('Metric Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-metric-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_hb_light_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_ppc_hb_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_metric(array $item)
    {
        $modifier = sanitize_key($item['metric_modifier'] ?? 'roi');
        $label    = trim((string) ($item['metric_label'] ?? ''));
        $value    = trim((string) ($item['metric_value'] ?? ''));
        $bar_width = absint($item['metric_bar_width'] ?? 0);

        if ('' === $label && '' === $value) {
            return;
        }

        $allowed_modifiers = ['roi', 'roas', 'cpc', 'conv'];

        if (!in_array($modifier, $allowed_modifiers, true)) {
            $modifier = 'roi';
        }
        ?>
        <div class="gc-ppc-metric gc-ppc-metric--<?php echo esc_attr($modifier); ?>">
            <div class="gc-ppc-metric-row">
                <?php if ('' !== $label) : ?>
                    <span class="gc-ppc-metric-label"><?php echo esc_html($label); ?></span>
                <?php endif; ?>
                <?php if ('' !== $value) : ?>
                    <span class="gc-ppc-metric-value"><?php echo esc_html($value); ?></span>
                <?php endif; ?>
            </div>
            <div class="gc-ppc-metric-bar" aria-hidden="true"><span data-width="<?php echo esc_attr($bar_width); ?>"></span></div>
        </div>
        <?php
    }

    private function render_stat(array $item)
    {
        $value = trim((string) ($item['stat_value'] ?? ''));
        $label = trim((string) ($item['stat_label'] ?? ''));

        if ('' === $value && '' === $label) {
            return;
        }
        ?>
        <div class="gc-ppc-stat-card">
            <?php if ('' !== $value) : ?>
                <span class="gc-ppc-stat-value"><?php echo esc_html($value); ?></span>
            <?php endif; ?>
            <?php if ('' !== $label) : ?>
                <span class="gc-ppc-stat-label"><?php echo esc_html($label); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $eyebrow              = $settings['gc_ppc_hb_eyebrow'] ?? '';
        $title                = $settings['gc_ppc_hb_title'] ?? '';
        $description          = $settings['gc_ppc_hb_description'] ?? '';
        $platforms            = $settings['gc_ppc_hb_platforms'] ?? [];
        $metrics              = $settings['gc_ppc_hb_metrics'] ?? [];
        $stats                = $settings['gc_ppc_hb_stats'] ?? [];
        $primary_button_text  = trim((string) ($settings['gc_ppc_hb_primary_button_text'] ?? ''));
        $secondary_button_text = trim((string) ($settings['gc_ppc_hb_secondary_button_text'] ?? ''));
        $dashboard_title      = trim((string) ($settings['gc_ppc_hb_dashboard_title'] ?? ''));
        $dashboard_status     = trim((string) ($settings['gc_ppc_hb_dashboard_status'] ?? ''));
        $dashboard_aria       = $settings['gc_ppc_hb_dashboard_aria_label'] ?? '';
        $platforms_aria       = $settings['gc_ppc_hb_platforms_aria_label'] ?? '';

        if (empty($platforms)) {
            $platforms = $this->get_default_platforms();
        }

        if (empty($metrics)) {
            $metrics = $this->get_default_metrics();
        }

        if (empty($stats)) {
            $stats = $this->get_default_stats();
        }

        $bg_shape_url   = $this->get_media_url($settings['gc_ppc_hb_bg_shape'] ?? [], $settings['gc_ppc_hb_bg_shape_fallback'] ?? 'new-update/hero-shape-1.png');
        $shape_1_url    = $this->get_media_url($settings['gc_ppc_hb_shape_1'] ?? [], $settings['gc_ppc_hb_shape_1_fallback'] ?? 'new-update/hero-shape-22.png');
        $shape_2_url    = $this->get_media_url($settings['gc_ppc_hb_shape_2'] ?? [], $settings['gc_ppc_hb_shape_2_fallback'] ?? 'new-update/hero-shape-3.png');
        $bg_shape_alt   = $settings['gc_ppc_hb_bg_shape_alt'] ?? '';
        $shape_2_alt    = $settings['gc_ppc_hb_shape_2_alt'] ?? '';

        $this->render_elementor_spacing_fix($settings);
        ?>

        <section class="hero-section-11 gc-ppc-roi-section pt-130 pb-130 fade-wrapper">
            <div class="bg-shape"><img src="<?php echo esc_url($bg_shape_url); ?>" alt="<?php echo esc_attr($bg_shape_alt); ?>"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_1_url); ?>" alt=""></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_2_url); ?>" alt="<?php echo esc_attr($shape_2_alt); ?>"></div>
            </div>
            <div class="container">
                <div class="row align-items-center gc-ppc-roi-row">
                    <div class="col-lg-6">
                        <div class="gc-ppc-hero-content fade-wrapper">
                            <?php if ('' !== trim((string) $eyebrow)) : ?>
                                <span class="gc-hero-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                            <?php endif; ?>
                            <?php if ('' !== trim((string) $title)) : ?>
                                <h1 class="title gc-ppc-hero-title" data-text-animation="fade-in" data-duration="1.2"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>
                            <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                                <p class="gc-ppc-hero-desc" data-text-animation="fade-in" data-duration="1.4"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($platforms)) : ?>
                                <ul class="gc-ppc-platforms" aria-label="<?php echo esc_attr($platforms_aria); ?>">
                                    <?php foreach ($platforms as $platform) :
                                        $platform_label = trim((string) ($platform['platform_label'] ?? ''));

                                        if ('' === $platform_label) {
                                            continue;
                                        }
                                        ?>
                                        <li><?php $this->render_platform_icon($platform, $settings); ?> <?php echo esc_html($platform_label); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                            <?php if ('' !== $primary_button_text || '' !== $secondary_button_text) : ?>
                                <div class="hero-btn-wrap two gc-ppc-hero-btns">
                                    <?php if ('' !== $primary_button_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_ppc_hb_primary_button_link'] ?? []); ?> class="rr-primary-btn"><?php echo esc_html($primary_button_text); ?></a>
                                    <?php endif; ?>
                                    <?php if ('' !== $secondary_button_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_ppc_hb_secondary_button_link'] ?? []); ?> class="rr-primary-btn gc-hero-btn-outline"><?php echo esc_html($secondary_button_text); ?></a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gc-ppc-dashboard-wrap fade-top">
                            <div class="gc-ppc-dashboard-card" data-ppc-dashboard role="img" aria-label="<?php echo esc_attr($dashboard_aria); ?>">
                                <div class="gc-ppc-dashboard-header">
                                    <div class="gc-ppc-dashboard-heading">
                                        <span class="gc-ppc-dashboard-live" aria-hidden="true"></span>
                                        <?php if ('' !== $dashboard_title) : ?>
                                            <h3 class="gc-ppc-dashboard-title"><?php echo esc_html($dashboard_title); ?></h3>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ('' !== $dashboard_status) : ?>
                                        <span class="gc-ppc-dashboard-status"><?php echo esc_html($dashboard_status); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($metrics)) : ?>
                                    <div class="gc-ppc-dashboard-metrics">
                                        <?php foreach ($metrics as $metric) {
                                            $this->render_metric($metric);
                                        } ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($stats)) : ?>
                                    <div class="gc-ppc-dashboard-stats">
                                        <?php foreach ($stats as $stat) {
                                            $this->render_stat($stat);
                                        } ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_PPC_Hero_Banner_Widget());
