<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_3D_Hero_Banner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_3d_hero_banner';
    }

    public function get_title()
    {
        return esc_html__('GC 3D Hero Banner', 'softro-core');
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        // ── Breadcrumb ──
        $this->start_controls_section('gc_3dhb_breadcrumb_section', [
            'label' => esc_html__('Breadcrumb', 'softro-core'),
        ]);

        $breadcrumb_repeater = new Repeater();

        $breadcrumb_repeater->add_control('bc_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Home', 'softro-core'),
            'label_block' => true,
        ]);

        $breadcrumb_repeater->add_control('bc_link', [
            'label'       => esc_html__('Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $breadcrumb_repeater->add_control('bc_is_current', [
            'label'        => esc_html__('Current Page', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('gc_3dhb_breadcrumbs', [
            'label'       => esc_html__('Breadcrumb Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $breadcrumb_repeater->get_controls(),
            'default'     => [
                ['bc_label' => esc_html__('Home', 'softro-core'), 'bc_link' => ['url' => '#'], 'bc_is_current' => ''],
                ['bc_label' => esc_html__('Video & 3D Solution', 'softro-core'), 'bc_link' => ['url' => '#'], 'bc_is_current' => ''],
                ['bc_label' => esc_html__('3D Rendering', 'softro-core'), 'bc_link' => ['url' => ''], 'bc_is_current' => 'yes'],
            ],
            'title_field' => '{{{ bc_label }}}',
        ]);

        $this->add_control('gc_3dhb_breadcrumb_separator', [
            'label'   => esc_html__('Separator', 'softro-core'),
            'type'    => Controls_Manager::TEXT,
            'default' => '>',
        ]);

        $this->end_controls_section();

        // ── Badge ──
        $this->start_controls_section('gc_3dhb_badge_section', [
            'label' => esc_html__('Badge', 'softro-core'),
        ]);

        $this->add_control('gc_3dhb_badge_icon', [
            'label'   => esc_html__('Badge Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-cube', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_3dhb_badge_text', [
            'label'       => esc_html__('Badge Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D Visualization Studio', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        // ── Hero Content ──
        $this->start_controls_section('gc_3dhb_hero_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_3dhb_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Professional', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_title_accent', [
            'label'       => esc_html__('Title Accent Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D Rendering', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('for Products & Brands', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_subtitle', [
            'label'   => esc_html__('Subtitle', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Turn concepts into photoreal visuals that sell before production starts.', 'softro-core'),
        ]);

        $this->add_control('gc_3dhb_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('From ecommerce product shots and packaging mockups to architectural previews and marketing campaigns — we deliver studio-quality 3D renders with accurate lighting, materials, and brand-ready output.', 'softro-core'),
        ]);

        $this->end_controls_section();

        // ── Buttons ──
        $this->start_controls_section('gc_3dhb_buttons_section', [
            'label' => esc_html__('Buttons', 'softro-core'),
        ]);

        $this->add_control('gc_3dhb_primary_btn_icon', [
            'label'   => esc_html__('Primary Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-envelope', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_3dhb_primary_btn_text', [
            'label'       => esc_html__('Primary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_primary_btn_link', [
            'label'       => esc_html__('Primary Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_outline_btn_text', [
            'label'       => esc_html__('Outline Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('View All Services', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_3dhb_outline_btn_icon', [
            'label'   => esc_html__('Outline Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_3dhb_outline_btn_link', [
            'label'       => esc_html__('Outline Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->end_controls_section();

        // ── Features ──
        $this->start_controls_section('gc_3dhb_features_section', [
            'label' => esc_html__('Features List', 'softro-core'),
        ]);

        $feature_repeater = new Repeater();

        $feature_repeater->add_control('feature_text', [
            'label'       => esc_html__('Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Feature item', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_features', [
            'label'       => esc_html__('Feature Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $feature_repeater->get_controls(),
            'default'     => [
                ['feature_text' => esc_html__('4K output', 'softro-core')],
                ['feature_text' => esc_html__('Fast turnaround', 'softro-core')],
                ['feature_text' => esc_html__('All formats', 'softro-core')],
            ],
            'title_field' => '{{{ feature_text }}}',
        ]);

        $this->end_controls_section();

        // ── Comparison Panel ──
        $this->start_controls_section('gc_3dhb_comparison_section', [
            'label' => esc_html__('Comparison Panel', 'softro-core'),
        ]);

        $this->add_control('gc_3dhb_panel_aria_label', [
            'label'       => esc_html__('Panel Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Render quality comparison showing basic 3D model versus photorealistic product render', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_3dhb_basic_heading', [
            'label'     => esc_html__('Basic Card', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_3dhb_basic_image', [
            'label'       => esc_html__('Basic Card Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_3dhb_basic_image_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-img-1.png',
        ]);

        $this->add_control('gc_3dhb_basic_image_alt', [
            'label'       => esc_html__('Basic Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Photorealistic 3D product render sample', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_basic_badge_text', [
            'label'       => esc_html__('Basic Badge Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Basic Model', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_pro_heading', [
            'label'     => esc_html__('Pro Card', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_3dhb_pro_image', [
            'label'       => esc_html__('Pro Card Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_3dhb_pro_image_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-img-1.png',
        ]);

        $this->add_control('gc_3dhb_pro_image_alt', [
            'label'       => esc_html__('Pro Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Photorealistic 3D product render sample', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_pro_badge_text', [
            'label'       => esc_html__('Pro Badge Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Photorealistic Render', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_caption_heading', [
            'label'     => esc_html__('Caption', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_3dhb_caption_icon', [
            'label'   => esc_html__('Caption Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-image', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_3dhb_caption_title', [
            'label'       => esc_html__('Caption Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Render Quality Comparison', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_caption_note', [
            'label'       => esc_html__('Caption Note', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Replace with actual render samples', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        // ── Stats ──
        $this->start_controls_section('gc_3dhb_stats_section', [
            'label' => esc_html__('Stats', 'softro-core'),
        ]);

        $stat_repeater = new Repeater();

        $stat_repeater->add_control('stat_value', [
            'label'       => esc_html__('Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '4K',
            'label_block' => true,
        ]);

        $stat_repeater->add_control('stat_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Output resolution', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3dhb_stats', [
            'label'       => esc_html__('Stat Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $stat_repeater->get_controls(),
            'default'     => [
                ['stat_value' => '4K', 'stat_label' => esc_html__('Output resolution', 'softro-core')],
                ['stat_value' => '48h', 'stat_label' => esc_html__('Avg. delivery time', 'softro-core')],
                ['stat_value' => '100%', 'stat_label' => esc_html__('Brand accuracy', 'softro-core')],
            ],
            'title_field' => '{{{ stat_value }}} — {{{ stat_label }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $s = '.gc-title-anim-hero';
        $c = '.gc-3d-render';

        // ── Layout ──
        $this->start_controls_section('gc_3dhb_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_3dhb_reset_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        // ── Section ──
        $this->start_controls_section('gc_3dhb_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $s . '.gc-3d-render-hero',
        ]);

        $this->add_responsive_control('gc_3dhb_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '.gc-3d-render-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '.gc-3d-render-hero' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Background Shape ──
        $this->start_controls_section('gc_3dhb_style_bg_shape', [
            'label' => esc_html__('Background Overlay', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_3dhb_bg_color', [
            'label'     => esc_html__('Overlay Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-bg' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_bg_opacity', [
            'label'     => esc_html__('Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.05]],
            'selectors' => ['{{WRAPPER}} ' . $s . '-bg' => 'opacity: {{SIZE}};'],
        ]);

        $this->end_controls_section();

        // ── Breadcrumb ──
        $this->start_controls_section('gc_3dhb_style_breadcrumb', [
            'label' => esc_html__('Breadcrumb', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_bc_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-breadcrumb, {{WRAPPER}} ' . $s . '-breadcrumb a',
        ]);

        $this->add_control('gc_3dhb_bc_link_color', [
            'label'     => esc_html__('Link Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-breadcrumb a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_bc_link_hover', [
            'label'     => esc_html__('Link Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-breadcrumb a:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_bc_sep_color', [
            'label'     => esc_html__('Separator Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-breadcrumb .sep' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_bc_current_color', [
            'label'     => esc_html__('Current Page Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-breadcrumb .current' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_bc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_bc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-breadcrumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Badge ──
        $this->start_controls_section('gc_3dhb_style_badge', [
            'label' => esc_html__('Badge', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_badge_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-badge',
        ]);

        $this->add_control('gc_3dhb_badge_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-badge' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_badge_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-badge' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_badge_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $s . '-badge i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} ' . $s . '-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_3dhb_badge_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} ' . $s . '-badge i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $s . '-badge svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_3dhb_badge_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_badge_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_badge_border_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Title ──
        $this->start_controls_section('gc_3dhb_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_title_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-title',
        ]);

        $this->add_control('gc_3dhb_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_title_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_title_accent_typography',
            'label'    => esc_html__('Accent Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} ' . $s . '-accent',
        ]);

        $this->add_responsive_control('gc_3dhb_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Subtitle ──
        $this->start_controls_section('gc_3dhb_style_subtitle', [
            'label' => esc_html__('Subtitle', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_subtitle_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-subtitle',
        ]);

        $this->add_control('gc_3dhb_subtitle_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-subtitle' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_subtitle_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_subtitle_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-subtitle' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Description ──
        $this->start_controls_section('gc_3dhb_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_desc_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-desc',
        ]);

        $this->add_control('gc_3dhb_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Buttons Wrap ──
        $this->start_controls_section('gc_3dhb_style_btns_wrap', [
            'label' => esc_html__('Buttons Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3dhb_btns_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btns' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_btns_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Primary Button ──
        $this->start_controls_section('gc_3dhb_style_primary_btn', [
            'label' => esc_html__('Primary Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_primary_btn_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-btn--primary',
        ]);

        $this->add_control('gc_3dhb_primary_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_primary_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--primary' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_primary_btn_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--primary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_primary_btn_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--primary:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_primary_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} ' . $s . '-btn--primary i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $s . '-btn--primary svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_3dhb_primary_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btn--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_primary_btn_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btn--primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Outline Button ──
        $this->start_controls_section('gc_3dhb_style_outline_btn', [
            'label' => esc_html__('Outline Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_outline_btn_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-btn--outline',
        ]);

        $this->add_control('gc_3dhb_outline_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--outline' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_outline_btn_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--outline' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_outline_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--outline' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_outline_btn_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--outline:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_outline_btn_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-btn--outline:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_outline_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} ' . $s . '-btn--outline i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $s . '-btn--outline svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_3dhb_outline_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btn--outline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_outline_btn_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-btn--outline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Features List ──
        $this->start_controls_section('gc_3dhb_style_features', [
            'label' => esc_html__('Features List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_features_typography',
            'selector' => '{{WRAPPER}} ' . $s . '-features li',
        ]);

        $this->add_control('gc_3dhb_features_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-features li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_features_dot_color', [
            'label'     => esc_html__('Dot / Separator Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-features li::before' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_features_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-features' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_features_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_features_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Comparison Panel ──
        $this->start_controls_section('gc_3dhb_style_comparison_panel', [
            'label' => esc_html__('Comparison Panel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_panel_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $c . '-comparison-panel',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_3dhb_panel_border',
            'selector' => '{{WRAPPER}} ' . $c . '-comparison-panel',
        ]);

        $this->add_responsive_control('gc_3dhb_panel_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_panel_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_panel_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Comparison Cards ──
        $this->start_controls_section('gc_3dhb_style_compare_cards', [
            'label' => esc_html__('Comparison Cards', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3dhb_grid_gap', [
            'label'      => esc_html__('Grid Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_card_bg',
            'label'    => esc_html__('Card Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $c . '-compare-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_3dhb_card_border',
            'selector' => '{{WRAPPER}} ' . $c . '-compare-card',
        ]);

        $this->add_responsive_control('gc_3dhb_card_radius', [
            'label'      => esc_html__('Card Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_card_padding', [
            'label'      => esc_html__('Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Compare Images ──
        $this->start_controls_section('gc_3dhb_style_compare_images', [
            'label' => esc_html__('Comparison Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3dhb_compare_img_width', [
            'label'      => esc_html__('Image Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 50, 'max' => 600], '%' => ['min' => 10, 'max' => 100]],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-visual img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_compare_img_height', [
            'label'      => esc_html__('Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 50, 'max' => 600]],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-visual img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;'],
        ]);

        $this->add_responsive_control('gc_3dhb_compare_img_radius', [
            'label'      => esc_html__('Image Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-visual img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Compare Badges ──
        $this->start_controls_section('gc_3dhb_style_compare_badges', [
            'label' => esc_html__('Comparison Badges', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_compare_badge_typography',
            'selector' => '{{WRAPPER}} ' . $c . '-compare-badge',
        ]);

        $this->add_control('gc_3dhb_compare_badge_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-compare-badge' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_compare_badge_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-compare-badge' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3dhb_compare_badge_pro_color', [
            'label'     => esc_html__('Pro Badge Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-compare-card--pro ' . $c . '-compare-badge' => 'color: {{VALUE}};'],
            'separator' => 'before',
        ]);

        $this->add_control('gc_3dhb_compare_badge_pro_bg', [
            'label'     => esc_html__('Pro Badge Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-compare-card--pro ' . $c . '-compare-badge' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_compare_badge_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_compare_badge_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-compare-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Caption ──
        $this->start_controls_section('gc_3dhb_style_caption', [
            'label' => esc_html__('Comparison Caption', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_caption_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $c . '-comparison-caption',
        ]);

        $this->add_control('gc_3dhb_caption_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} ' . $c . '-comparison-caption-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} ' . $c . '-comparison-caption-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_3dhb_caption_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} ' . $c . '-comparison-caption-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} ' . $c . '-comparison-caption-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_caption_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} ' . $c . '-comparison-caption-title',
        ]);

        $this->add_control('gc_3dhb_caption_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-comparison-caption-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_caption_note_typography',
            'label'    => esc_html__('Note Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} ' . $c . '-comparison-caption-note',
        ]);

        $this->add_control('gc_3dhb_caption_note_color', [
            'label'     => esc_html__('Note Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $c . '-comparison-caption-note' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_caption_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_caption_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Stats ──
        $this->start_controls_section('gc_3dhb_style_stats', [
            'label' => esc_html__('Stats', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_stat_value_typography',
            'label'    => esc_html__('Value Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} ' . $s . '-stat-value',
        ]);

        $this->add_control('gc_3dhb_stat_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-stat-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3dhb_stat_label_typography',
            'label'    => esc_html__('Label Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} ' . $s . '-stat-label',
        ]);

        $this->add_control('gc_3dhb_stat_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} ' . $s . '-stat-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_stats_bg',
            'label'    => esc_html__('Stats Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} ' . $s . '-stats' . $c . '-hero-stats',
        ]);

        $this->add_responsive_control('gc_3dhb_stats_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-stats' . $c . '-hero-stats' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_stats_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-stats' . $c . '-hero-stats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_stats_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-stats' . $c . '-hero-stats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_stats_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-stats' . $c . '-hero-stats' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        // ── Content & Visual Wrap ──
        $this->start_controls_section('gc_3dhb_style_wraps', [
            'label' => esc_html__('Content & Visual Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3dhb_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_content_margin', [
            'label'      => esc_html__('Content Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $s . '-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3dhb_comparison_wrap_padding', [
            'label'      => esc_html__('Comparison Wrap Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'separator'  => 'before',
        ]);

        $this->add_responsive_control('gc_3dhb_comparison_wrap_margin', [
            'label'      => esc_html__('Comparison Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} ' . $c . '-comparison-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $s = '.gc-title-anim-hero';
        $c = '.gc-3d-render';

        $this->start_controls_section('gc_3dhb_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_3dhb_theme_mode_tabs');

        // ── Dark ──
        $this->start_controls_tab('gc_3dhb_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} ' . $s . '.gc-3d-render-hero',
        ]);

        $this->add_control('gc_3dhb_dark_bg_overlay', [
            'label'     => esc_html__('BG Overlay', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-bg' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_bc_link', [
            'label'     => esc_html__('Breadcrumb Link', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-breadcrumb a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_bc_sep', [
            'label'     => esc_html__('Breadcrumb Separator', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-breadcrumb .sep' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_bc_current', [
            'label'     => esc_html__('Breadcrumb Current', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-breadcrumb .current' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_badge_color', [
            'label'     => esc_html__('Badge Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_badge_bg', [
            'label'     => esc_html__('Badge BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_badge_icon', [
            'label'     => esc_html__('Badge Icon', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                $s . '-badge i'   => 'color: {{VALUE}};',
                $s . '-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_3dhb_dark_title', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_accent', [
            'label'     => esc_html__('Accent', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_subtitle', [
            'label'     => esc_html__('Subtitle', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-subtitle' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_desc', [
            'label'     => esc_html__('Description', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_primary_btn', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_primary_btn_bg', [
            'label'     => esc_html__('Primary Button BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_outline_btn', [
            'label'     => esc_html__('Outline Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-btn--outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-btn--outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_features', [
            'label'     => esc_html__('Features Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-features li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_panel_bg', [
            'label'     => esc_html__('Panel BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-comparison-panel' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_panel_border', [
            'label'     => esc_html__('Panel Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-comparison-panel' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_compare_badge', [
            'label'     => esc_html__('Compare Badge Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-compare-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_compare_badge_bg', [
            'label'     => esc_html__('Compare Badge BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-compare-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_caption_title', [
            'label'     => esc_html__('Caption Title', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-comparison-caption-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_caption_note', [
            'label'     => esc_html__('Caption Note', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$c . '-comparison-caption-note' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_stat_value', [
            'label'     => esc_html__('Stat Value', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_dark_stat_label', [
            'label'     => esc_html__('Stat Label', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [$s . '-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        // ── Light ──
        $this->start_controls_tab('gc_3dhb_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3dhb_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} ' . $s . '.gc-3d-render-hero',
        ]);

        $this->add_control('gc_3dhb_light_bg_overlay', [
            'label'     => esc_html__('BG Overlay', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-bg' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_bc_link', [
            'label'     => esc_html__('Breadcrumb Link', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-breadcrumb a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_bc_sep', [
            'label'     => esc_html__('Breadcrumb Separator', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-breadcrumb .sep' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_bc_current', [
            'label'     => esc_html__('Breadcrumb Current', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-breadcrumb .current' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_badge_color', [
            'label'     => esc_html__('Badge Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_badge_bg', [
            'label'     => esc_html__('Badge BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_badge_icon', [
            'label'     => esc_html__('Badge Icon', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                $s . '-badge i'   => 'color: {{VALUE}};',
                $s . '-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_3dhb_light_title', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_accent', [
            'label'     => esc_html__('Accent', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_subtitle', [
            'label'     => esc_html__('Subtitle', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-subtitle' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_desc', [
            'label'     => esc_html__('Description', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_primary_btn', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_primary_btn_bg', [
            'label'     => esc_html__('Primary Button BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_outline_btn', [
            'label'     => esc_html__('Outline Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-btn--outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-btn--outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_features', [
            'label'     => esc_html__('Features Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-features li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_panel_bg', [
            'label'     => esc_html__('Panel BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-comparison-panel' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_panel_border', [
            'label'     => esc_html__('Panel Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-comparison-panel' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_compare_badge', [
            'label'     => esc_html__('Compare Badge Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-compare-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_compare_badge_bg', [
            'label'     => esc_html__('Compare Badge BG', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-compare-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_caption_title', [
            'label'     => esc_html__('Caption Title', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-comparison-caption-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_caption_note', [
            'label'     => esc_html__('Caption Note', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$c . '-comparison-caption-note' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_stat_value', [
            'label'     => esc_html__('Stat Value', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3dhb_light_stat_label', [
            'label'     => esc_html__('Stat Label', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [$s . '-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_3dhb_reset_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            .elementor-element-<?php echo $widget_id; ?>>.elementor-widget-container {
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
    ?>
        <style>
            .elementor-editor-active {{WRAPPER}} .gc-title-anim-hero-reveal {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
<?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $breadcrumbs      = $settings['gc_3dhb_breadcrumbs'] ?? [];
        $bc_separator     = $settings['gc_3dhb_breadcrumb_separator'] ?? '>';
        $badge_text       = trim((string) ($settings['gc_3dhb_badge_text'] ?? ''));
        $title_before     = trim((string) ($settings['gc_3dhb_title_before'] ?? ''));
        $title_accent     = trim((string) ($settings['gc_3dhb_title_accent'] ?? ''));
        $title_after      = trim((string) ($settings['gc_3dhb_title_after'] ?? ''));
        $subtitle         = $this->get_paragraph_inner_content($settings['gc_3dhb_subtitle'] ?? '');
        $description      = $this->get_paragraph_inner_content($settings['gc_3dhb_description'] ?? '');
        $primary_btn_text = trim((string) ($settings['gc_3dhb_primary_btn_text'] ?? ''));
        $outline_btn_text = trim((string) ($settings['gc_3dhb_outline_btn_text'] ?? ''));
        $features         = $settings['gc_3dhb_features'] ?? [];
        $panel_aria       = $settings['gc_3dhb_panel_aria_label'] ?? '';
        $basic_image_url  = $this->get_media_url($settings['gc_3dhb_basic_image'] ?? [], $settings['gc_3dhb_basic_image_fallback'] ?? 'new-update/hero-img-1.png');
        $basic_image_alt  = $settings['gc_3dhb_basic_image_alt'] ?? '';
        $basic_badge_text = trim((string) ($settings['gc_3dhb_basic_badge_text'] ?? ''));
        $pro_image_url    = $this->get_media_url($settings['gc_3dhb_pro_image'] ?? [], $settings['gc_3dhb_pro_image_fallback'] ?? 'new-update/hero-img-1.png');
        $pro_image_alt    = $settings['gc_3dhb_pro_image_alt'] ?? '';
        $pro_badge_text   = trim((string) ($settings['gc_3dhb_pro_badge_text'] ?? ''));
        $caption_title    = trim((string) ($settings['gc_3dhb_caption_title'] ?? ''));
        $caption_note     = trim((string) ($settings['gc_3dhb_caption_note'] ?? ''));
        $stats            = $settings['gc_3dhb_stats'] ?? [];

        $has_title = '' !== $title_before || '' !== $title_accent || '' !== $title_after;

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();
?>

        <section class="gc-title-anim-hero gc-3d-render-hero pt-130 pb-110">
            <div class="gc-title-anim-hero-bg" aria-hidden="true"></div>
            <div class="container">
                <div class="row align-items-center g-4 g-xl-5 gc-title-anim-hero-row">
                    <div class="col-lg-6">
                        <div class="gc-title-anim-hero-content">
                            <?php if (!empty($breadcrumbs)) : ?>
                                <nav class="gc-title-anim-hero-breadcrumb gc-title-anim-hero-reveal gc-title-anim-hero-reveal--1"
                                    aria-label="Breadcrumb">
                                    <?php foreach ($breadcrumbs as $i => $bc) :
                                        $bc_label = trim((string) ($bc['bc_label'] ?? ''));
                                        if ('' === $bc_label) {
                                            continue;
                                        }
                                        $is_current = 'yes' === ($bc['bc_is_current'] ?? '');
                                        if ($i > 0) : ?>
                                            <span class="sep" aria-hidden="true"><?php echo esc_html($bc_separator); ?></span>
                                        <?php endif;
                                        if ($is_current) : ?>
                                            <span class="current"><?php echo esc_html($bc_label); ?></span>
                                        <?php else : ?>
                                            <a<?php echo $this->get_link_attributes($bc['bc_link'] ?? []); ?>><?php echo esc_html($bc_label); ?></a>
                                        <?php endif;
                                    endforeach; ?>
                                </nav>
                            <?php endif; ?>
                            <?php if ('' !== $badge_text || !empty($settings['gc_3dhb_badge_icon']['value'])) : ?>
                                <span
                                    class="gc-title-anim-hero-badge gc-title-anim-hero-reveal gc-title-anim-hero-reveal--2">
                                    <?php $this->render_icon($settings['gc_3dhb_badge_icon'] ?? [], ['aria-hidden' => 'true']); ?>
                                    <?php echo esc_html($badge_text); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($has_title) : ?>
                                <h1
                                    class="gc-title-anim-hero-title gc-title-anim-hero-reveal gc-title-anim-hero-reveal--3">
                                    <?php echo esc_html($title_before); ?>
                                    <?php if ('' !== $title_accent) : ?>
                                        <span class="gc-title-anim-hero-accent"><?php echo esc_html($title_accent); ?></span>
                                    <?php endif; ?>
                                    <?php echo esc_html($title_after); ?>
                                </h1>
                            <?php endif; ?>
                            <?php if ('' !== $subtitle) : ?>
                                <p
                                    class="gc-title-anim-hero-subtitle gc-title-anim-hero-reveal gc-title-anim-hero-reveal--4">
                                    <?php echo wp_kses($subtitle, ['br' => []]); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('' !== $description) : ?>
                                <p
                                    class="gc-title-anim-hero-desc gc-title-anim-hero-reveal gc-title-anim-hero-reveal--5">
                                    <?php echo wp_kses($description, ['br' => []]); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('' !== $primary_btn_text || '' !== $outline_btn_text) : ?>
                                <div
                                    class="gc-title-anim-hero-btns gc-title-anim-hero-reveal gc-title-anim-hero-reveal--6">
                                    <?php if ('' !== $primary_btn_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_3dhb_primary_btn_link'] ?? []); ?>
                                            class="gc-title-anim-hero-btn gc-title-anim-hero-btn--primary">
                                            <?php $this->render_icon($settings['gc_3dhb_primary_btn_icon'] ?? [], ['aria-hidden' => 'true']); ?> <?php echo esc_html($primary_btn_text); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ('' !== $outline_btn_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_3dhb_outline_btn_link'] ?? []); ?>
                                            class="gc-title-anim-hero-btn gc-title-anim-hero-btn--outline">
                                            <?php echo esc_html($outline_btn_text); ?> <?php $this->render_icon($settings['gc_3dhb_outline_btn_icon'] ?? [], ['aria-hidden' => 'true']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($features)) : ?>
                                <ul
                                    class="gc-title-anim-hero-features gc-title-anim-hero-reveal gc-title-anim-hero-reveal--7">
                                    <?php foreach ($features as $feature) :
                                        $feature_text = trim((string) ($feature['feature_text'] ?? ''));
                                        if ('' === $feature_text) {
                                            continue;
                                        }
                                    ?>
                                        <li><?php echo esc_html($feature_text); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div
                            class="gc-3d-render-comparison-wrap gc-title-anim-hero-reveal gc-title-anim-hero-reveal--4">
                            <div class="gc-3d-render-comparison-panel" role="img"
                                aria-label="<?php echo esc_attr($panel_aria); ?>">
                                <div class="gc-3d-render-comparison-grid">
                                    <div class="gc-3d-render-compare-card gc-3d-render-compare-card--basic">
                                        <div class="gc-3d-render-compare-visual" aria-hidden="true">
                                            <?php if ($basic_image_url) : ?>
                                                <img src="<?php echo esc_url($basic_image_url); ?>"
                                                    alt="<?php echo esc_attr($basic_image_alt); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <?php if ('' !== $basic_badge_text) : ?>
                                            <span class="gc-3d-render-compare-badge"><?php echo esc_html($basic_badge_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="gc-3d-render-compare-card gc-3d-render-compare-card--pro">
                                        <div class="gc-3d-render-compare-visual">
                                            <?php if ($pro_image_url) : ?>
                                                <img src="<?php echo esc_url($pro_image_url); ?>"
                                                    alt="<?php echo esc_attr($pro_image_alt); ?>">
                                            <?php endif; ?>
                                        </div>
                                        <?php if ('' !== $pro_badge_text) : ?>
                                            <span class="gc-3d-render-compare-badge"><?php echo esc_html($pro_badge_text); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="gc-3d-render-comparison-caption">
                                    <span class="gc-3d-render-comparison-caption-icon" aria-hidden="true"><?php $this->render_icon($settings['gc_3dhb_caption_icon'] ?? [], ['aria-hidden' => 'true']); ?></span>
                                    <?php if ('' !== $caption_title) : ?>
                                        <span class="gc-3d-render-comparison-caption-title"><?php echo esc_html($caption_title); ?></span>
                                    <?php endif; ?>
                                    <?php if ('' !== $caption_note) : ?>
                                        <span class="gc-3d-render-comparison-caption-note"><?php echo esc_html($caption_note); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!empty($stats)) : ?>
                                <div class="gc-title-anim-hero-stats gc-3d-render-hero-stats">
                                    <?php foreach ($stats as $stat) :
                                        $stat_value = trim((string) ($stat['stat_value'] ?? ''));
                                        $stat_label = trim((string) ($stat['stat_label'] ?? ''));
                                        if ('' === $stat_value && '' === $stat_label) {
                                            continue;
                                        }
                                    ?>
                                        <div class="gc-title-anim-hero-stat">
                                            <?php if ('' !== $stat_value) : ?>
                                                <span class="gc-title-anim-hero-stat-value"><?php echo esc_html($stat_value); ?></span>
                                            <?php endif; ?>
                                            <?php if ('' !== $stat_label) : ?>
                                                <span class="gc-title-anim-hero-stat-label"><?php echo esc_html($stat_label); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
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

Plugin::instance()->widgets_manager->register(new Softro_3D_Hero_Banner_Widget());
