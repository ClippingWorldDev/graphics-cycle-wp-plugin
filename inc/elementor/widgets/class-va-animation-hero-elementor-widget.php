<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_VA_Animation_Hero_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_va_animation_hero';
    }

    public function get_title()
    {
        return esc_html__('GC VA Animation Hero', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    private function get_media_url($media)
    {
        if (!empty($media['url'])) {
            return esc_url($media['url']);
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

    private function render_badge_icon(array $settings)
    {
        if (!empty($settings['gc_va_anim_hb_badge_icon']['value'])) {
            $this->render_icon($settings['gc_va_anim_hb_badge_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_va_anim_hb_badge_icon_image'] ?? []);

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-solid fa-star" aria-hidden="true"></i>';
    }

    private function render_primary_btn_icon(array $settings)
    {
        if (!empty($settings['gc_va_anim_hb_primary_btn_icon']['value'])) {
            $this->render_icon($settings['gc_va_anim_hb_primary_btn_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_va_anim_hb_primary_btn_icon_image'] ?? []);

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-light fa-envelope" aria-hidden="true"></i>';
    }

    private function render_outline_btn_icon(array $settings)
    {
        if (!empty($settings['gc_va_anim_hb_outline_btn_icon']['value'])) {
            $this->render_icon($settings['gc_va_anim_hb_outline_btn_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_va_anim_hb_outline_btn_icon_image'] ?? []);

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-regular fa-arrow-right" aria-hidden="true"></i>';
    }

    private function render_play_icon(array $settings)
    {
        if (!empty($settings['gc_va_anim_hb_play_icon']['value'])) {
            $this->render_icon($settings['gc_va_anim_hb_play_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_va_anim_hb_play_icon_image'] ?? []);

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-solid fa-play"></i>';
    }

    private function get_default_breadcrumbs()
    {
        return [
            [
                'breadcrumb_label' => esc_html__('Home', 'softro-core'),
                'breadcrumb_link'  => ['url' => '#'],
                'is_current'       => '',
            ],
            [
                'breadcrumb_label' => esc_html__('Services', 'softro-core'),
                'breadcrumb_link'  => ['url' => '#'],
                'is_current'       => '',
            ],
            [
                'breadcrumb_label' => esc_html__('3D Animation Services', 'softro-core'),
                'breadcrumb_link'  => ['url' => '#'],
                'is_current'       => 'yes',
            ],
        ];
    }

    private function get_default_features()
    {
        return [
            ['feature_text' => esc_html__('Fast turnaround', 'softro-core')],
            ['feature_text' => esc_html__('Revisions included', 'softro-core')],
            ['feature_text' => esc_html__('B2B ready', 'softro-core')],
        ];
    }

    private function get_default_stats()
    {
        return [
            [
                'stat_value' => '48h',
                'stat_label' => esc_html__('Avg. delivery time', 'softro-core'),
            ],
            [
                'stat_value' => '4K',
                'stat_label' => esc_html__('Output resolution', 'softro-core'),
            ],
            [
                'stat_value' => '∞',
                'stat_label' => esc_html__('Revisions included', 'softro-core'),
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
        $this->start_controls_section('gc_va_anim_hb_breadcrumb_section', [
            'label' => esc_html__('Breadcrumb', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_breadcrumb_aria_label', [
            'label'       => esc_html__('Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Breadcrumb', 'softro-core'),
            'label_block' => true,
        ]);

        $breadcrumb_repeater = new Repeater();

        $breadcrumb_repeater->add_control('breadcrumb_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Home', 'softro-core'),
            'label_block' => true,
        ]);

        $breadcrumb_repeater->add_control('breadcrumb_link', [
            'label'       => esc_html__('Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $breadcrumb_repeater->add_control('is_current', [
            'label'        => esc_html__('Current Page', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => '',
        ]);

        $this->add_control('gc_va_anim_hb_breadcrumbs', [
            'label'       => esc_html__('Breadcrumb Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $breadcrumb_repeater->get_controls(),
            'default'     => $this->get_default_breadcrumbs(),
            'title_field' => '{{{ breadcrumb_label }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_badge_section', [
            'label' => esc_html__('Badge', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_badge_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-star', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_va_anim_hb_badge_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_va_anim_hb_badge_text', [
            'label'       => esc_html__('Badge Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Professional 3D Studio', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_title_section', [
            'label' => esc_html__('Title', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Professional ', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D Animation', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__(' Services for Brands', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_subtitle', [
            'label'       => esc_html__('Subtitle', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Bring your product, idea, or brand to life with cinematic 3D animation', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_va_anim_hb_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'From product animations to architectural walkthroughs, we craft high-quality 3D visuals that capture attention, communicate value, and convert viewers into buyers.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_buttons_section', [
            'label' => esc_html__('Buttons', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_text', [
            'label'       => esc_html__('Primary Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_link', [
            'label'       => esc_html__('Primary Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_icon', [
            'label'   => esc_html__('Primary Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-envelope', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_icon_image', [
            'label'       => esc_html__('Primary Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_text', [
            'label'       => esc_html__('Outline Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('View All Services', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_link', [
            'label'       => esc_html__('Outline Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_icon', [
            'label'   => esc_html__('Outline Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_icon_image', [
            'label'       => esc_html__('Outline Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $feature_repeater = new Repeater();

        $feature_repeater->add_control('feature_text', [
            'label'       => esc_html__('Feature Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Fast turnaround', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_va_anim_hb_features_section', [
            'label' => esc_html__('Features', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_features', [
            'label'       => esc_html__('Features', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $feature_repeater->get_controls(),
            'default'     => $this->get_default_features(),
            'title_field' => '{{{ feature_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_visual_section', [
            'label' => esc_html__('Visual / Showreel', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_video_id', [
            'label'       => esc_html__('YouTube Video ID', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '8oON21G1Bqg',
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_visual_aria_label', [
            'label'       => esc_html__('Visual Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Play 3D animation showreel', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_showreel_text', [
            'label'       => esc_html__('Showreel Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('SHOWREEL / 3D SAMPLE', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_va_anim_hb_play_icon', [
            'label'   => esc_html__('Play Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-play', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_va_anim_hb_play_icon_image', [
            'label'       => esc_html__('Play Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $stats_repeater = new Repeater();

        $stats_repeater->add_control('stat_value', [
            'label'       => esc_html__('Stat Value', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '48h',
            'label_block' => true,
        ]);

        $stats_repeater->add_control('stat_label', [
            'label'       => esc_html__('Stat Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Avg. delivery time', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_va_anim_hb_stats_section', [
            'label' => esc_html__('Stats', 'softro-core'),
        ]);

        $this->add_control('gc_va_anim_hb_stats', [
            'label'       => esc_html__('Stats', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $stats_repeater->get_controls(),
            'default'     => $this->get_default_stats(),
            'title_field' => '{{{ stat_value }}} — {{{ stat_label }}}',
        ]);

        $this->end_controls_section();
    }


    private function register_style_controls()
    {
        $this->start_controls_section('gc_va_anim_hb_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_va_anim_hb_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_va_anim_hb_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_va_anim_hb_section_overlay_bg',
            'label'     => esc_html__('Background Overlay (.gc-3d-anim-hero-bg)', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-3d-anim-hero-bg',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_breadcrumb', [
            'label' => esc_html__('Breadcrumb', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_breadcrumb_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-breadcrumb',
        ]);

        $this->add_control('gc_va_anim_hb_breadcrumb_link_color', [
            'label'     => esc_html__('Link Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-breadcrumb a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_breadcrumb_current_color', [
            'label'     => esc_html__('Current Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-breadcrumb .current' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_breadcrumb_sep_color', [
            'label'     => esc_html__('Separator Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-breadcrumb .sep' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_breadcrumb_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-breadcrumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_badge', [
            'label' => esc_html__('Badge', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_badge_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-badge',
        ]);

        $this->add_control('gc_va_anim_hb_badge_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-badge' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_badge_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-badge' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_badge_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-hero-badge i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-hero-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_badge_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_badge_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_badge_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-badge' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_title', [
            'label' => esc_html__('Title / Accent', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-title',
        ]);

        $this->add_control('gc_va_anim_hb_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_va_anim_hb_accent_typography',
            'label'     => esc_html__('Accent Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-3d-anim-hero-accent',
            'separator' => 'before',
        ]);

        $this->add_control('gc_va_anim_hb_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-accent' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_subtitle', [
            'label' => esc_html__('Subtitle', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_subtitle_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-subtitle',
        ]);

        $this->add_control('gc_va_anim_hb_subtitle_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-subtitle' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_subtitle_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-subtitle' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_description', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-desc',
        ]);

        $this->add_control('gc_va_anim_hb_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_btns_wrap', [
            'label' => esc_html__('Buttons Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_anim_hb_btns_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btns' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_btns_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();


        $this->start_controls_section('gc_va_anim_hb_style_primary_btn', [
            'label' => esc_html__('Primary Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_primary_btn_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-btn--primary',
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_primary_btn_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_primary_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_primary_btn_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btn--primary' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_primary_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-hero-btn--primary i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-btn--primary svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-btn--primary img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_outline_btn', [
            'label' => esc_html__('Outline Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_outline_btn_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-btn--outline',
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_outline_btn_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_outline_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_outline_btn_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-btn--outline' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_outline_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-hero-btn--outline i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-btn--outline svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-btn--outline img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_features', [
            'label' => esc_html__('Features', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_features_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-features li',
        ]);

        $this->add_control('gc_va_anim_hb_features_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-features li' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_features_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-features' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_features_margin', [
            'label'      => esc_html__('List Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_visual_wrap', [
            'label' => esc_html__('Visual Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_anim_hb_visual_wrap_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-visual-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_visual_wrap_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-visual-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_visual_inner', [
            'label' => esc_html__('Visual Inner', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_visual_inner_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-visual-inner',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_va_anim_hb_visual_inner_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-visual-inner',
        ]);

        $this->add_responsive_control('gc_va_anim_hb_visual_inner_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-visual-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_visual_btn_min_height', [
            'label'      => esc_html__('Visual Button Min Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-visual' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_cube_stage', [
            'label' => esc_html__('Cube Stage', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_va_anim_hb_cube_stage_width', [
            'label'      => esc_html__('Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-cube-stage' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_cube_stage_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-cube-stage' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_showreel', [
            'label' => esc_html__('Showreel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_showreel_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-showreel',
        ]);

        $this->add_control('gc_va_anim_hb_showreel_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-showreel' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_va_anim_hb_showreel_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-hero-showreel-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-hero-showreel-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_va_anim_hb_showreel_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-showreel-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_showreel_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-hero-showreel-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-showreel-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-hero-showreel-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_va_anim_hb_style_stats', [
            'label' => esc_html__('Stats', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_va_anim_hb_stat_value_typography',
            'label'    => esc_html__('Value Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-3d-anim-hero-stat-value',
        ]);

        $this->add_control('gc_va_anim_hb_stat_value_color', [
            'label'     => esc_html__('Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-stat-value' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_va_anim_hb_stat_label_typography',
            'label'     => esc_html__('Label Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-3d-anim-hero-stat-label',
            'separator' => 'before',
        ]);

        $this->add_control('gc_va_anim_hb_stat_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-hero-stat-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_stats_gap', [
            'label'      => esc_html__('Stats Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-stats' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_va_anim_hb_stats_margin', [
            'label'      => esc_html__('Stats Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-hero-stats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }


    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_va_anim_hb_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_va_anim_hb_theme_mode_tabs');

        $this->start_controls_tab('gc_va_anim_hb_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-hero',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_dark_hero_bg',
            'label'    => esc_html__('Hero Background Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-hero-bg',
        ]);

        $this->add_control('gc_va_anim_hb_dark_breadcrumb_link_color', [
            'label'     => esc_html__('Breadcrumb Link Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-breadcrumb a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_breadcrumb_current_color', [
            'label'     => esc_html__('Breadcrumb Current Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-breadcrumb .current' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_breadcrumb_sep_color', [
            'label'     => esc_html__('Breadcrumb Separator Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-breadcrumb .sep' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_badge_color', [
            'label'     => esc_html__('Badge Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_badge_bg', [
            'label'     => esc_html__('Badge Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_badge_icon_color', [
            'label'     => esc_html__('Badge Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-hero-badge i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-hero-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_anim_hb_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_subtitle_color', [
            'label'     => esc_html__('Subtitle Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-subtitle' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_primary_btn_color', [
            'label'     => esc_html__('Primary Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_primary_btn_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_outline_btn_color', [
            'label'     => esc_html__('Outline Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-btn--outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-btn--outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_features_color', [
            'label'     => esc_html__('Features Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-features li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_visual_inner_bg', [
            'label'     => esc_html__('Visual Inner Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-visual-inner' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_showreel_color', [
            'label'     => esc_html__('Showreel Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-showreel' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_showreel_icon_color', [
            'label'     => esc_html__('Showreel Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-hero-showreel-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-hero-showreel-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_anim_hb_dark_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_dark_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-hero-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_va_anim_hb_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-hero',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_va_anim_hb_light_hero_bg',
            'label'    => esc_html__('Hero Background Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-hero-bg',
        ]);

        $this->add_control('gc_va_anim_hb_light_breadcrumb_link_color', [
            'label'     => esc_html__('Breadcrumb Link Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-breadcrumb a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_breadcrumb_current_color', [
            'label'     => esc_html__('Breadcrumb Current Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-breadcrumb .current' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_breadcrumb_sep_color', [
            'label'     => esc_html__('Breadcrumb Separator Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-breadcrumb .sep' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_badge_color', [
            'label'     => esc_html__('Badge Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-badge' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_badge_bg', [
            'label'     => esc_html__('Badge Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-badge' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_badge_icon_color', [
            'label'     => esc_html__('Badge Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-hero-badge i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-hero-badge svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_anim_hb_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_subtitle_color', [
            'label'     => esc_html__('Subtitle Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-subtitle' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_primary_btn_color', [
            'label'     => esc_html__('Primary Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_primary_btn_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_outline_btn_color', [
            'label'     => esc_html__('Outline Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-btn--outline' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_outline_btn_border', [
            'label'     => esc_html__('Outline Button Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-btn--outline' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_features_color', [
            'label'     => esc_html__('Features Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-features li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_visual_inner_bg', [
            'label'     => esc_html__('Visual Inner Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-visual-inner' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_showreel_color', [
            'label'     => esc_html__('Showreel Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-showreel' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_showreel_icon_color', [
            'label'     => esc_html__('Showreel Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-hero-showreel-icon i'   => 'color: {{VALUE}};',
                '.gc-3d-anim-hero-showreel-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_va_anim_hb_light_stat_value_color', [
            'label'     => esc_html__('Stat Value Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-stat-value' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_va_anim_hb_light_stat_label_color', [
            'label'     => esc_html__('Stat Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-hero-stat-label' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }


    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_va_anim_hb_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .gc-3d-anim-hero-reveal,
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

    private function render_breadcrumb_item(array $item, $is_first)
    {
        $label = trim((string) ($item['breadcrumb_label'] ?? ''));

        if ('' === $label) {
            return;
        }

        if (!$is_first) {
            echo '<span class="sep" aria-hidden="true">&gt;</span>';
        }

        if ('yes' === ($item['is_current'] ?? '')) {
            echo '<span class="current">' . esc_html($label) . '</span>';
            return;
        }

        echo '<a' . $this->get_link_attributes($item['breadcrumb_link'] ?? []) . '>' . esc_html($label) . '</a>';
    }

    private function render_breadcrumbs(array $settings)
    {
        $items = !empty($settings['gc_va_anim_hb_breadcrumbs']) ? $settings['gc_va_anim_hb_breadcrumbs'] : $this->get_default_breadcrumbs();
        $aria  = $settings['gc_va_anim_hb_breadcrumb_aria_label'] ?? esc_html__('Breadcrumb', 'softro-core');

        if (empty($items)) {
            return;
        }
        ?>
        <nav class="gc-3d-anim-hero-breadcrumb gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--1"
            aria-label="<?php echo esc_attr($aria); ?>">
            <?php
            $is_first = true;
            foreach ($items as $item) {
                $this->render_breadcrumb_item($item, $is_first);
                $is_first = false;
            }
            ?>
        </nav>
        <?php
    }

    private function render_feature_item(array $item)
    {
        $text = trim((string) ($item['feature_text'] ?? ''));

        if ('' === $text) {
            return;
        }

        echo '<li>' . esc_html($text) . '</li>';
    }

    private function render_stat_item(array $item)
    {
        $value = trim((string) ($item['stat_value'] ?? ''));
        $label = trim((string) ($item['stat_label'] ?? ''));

        if ('' === $value && '' === $label) {
            return;
        }
        ?>
        <div class="gc-3d-anim-hero-stat">
            <?php if ('' !== $value) : ?>
                <span class="gc-3d-anim-hero-stat-value"><?php echo esc_html($value); ?></span>
            <?php endif; ?>
            <?php if ('' !== $label) : ?>
                <span class="gc-3d-anim-hero-stat-label"><?php echo esc_html($label); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $badge_text       = trim((string) ($settings['gc_va_anim_hb_badge_text'] ?? ''));
        $title_before     = $settings['gc_va_anim_hb_title_before'] ?? '';
        $title_accent     = $settings['gc_va_anim_hb_title_accent'] ?? '';
        $title_after      = $settings['gc_va_anim_hb_title_after'] ?? '';
        $subtitle         = trim((string) ($settings['gc_va_anim_hb_subtitle'] ?? ''));
        $description      = $this->get_paragraph_inner_content($settings['gc_va_anim_hb_description'] ?? '');
        $primary_btn_text = trim((string) ($settings['gc_va_anim_hb_primary_btn_text'] ?? ''));
        $outline_btn_text = trim((string) ($settings['gc_va_anim_hb_outline_btn_text'] ?? ''));
        $features         = !empty($settings['gc_va_anim_hb_features']) ? $settings['gc_va_anim_hb_features'] : $this->get_default_features();
        $video_id         = trim((string) ($settings['gc_va_anim_hb_video_id'] ?? ''));
        $visual_aria      = $settings['gc_va_anim_hb_visual_aria_label'] ?? esc_html__('Play 3D animation showreel', 'softro-core');
        $showreel_text    = trim((string) ($settings['gc_va_anim_hb_showreel_text'] ?? ''));
        $stats            = !empty($settings['gc_va_anim_hb_stats']) ? $settings['gc_va_anim_hb_stats'] : $this->get_default_stats();
        ?>

        <section class="gc-3d-anim-hero pt-130 pb-110">
            <div class="gc-3d-anim-hero-bg" aria-hidden="true"></div>
            <div class="container">
                <div class="row align-items-center g-4 g-xl-5 gc-3d-anim-hero-row">
                    <div class="col-lg-6">
                        <div class="gc-3d-anim-hero-content">
                            <?php $this->render_breadcrumbs($settings); ?>
                            <?php if ('' !== $badge_text) : ?>
                                <span class="gc-3d-anim-hero-badge gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--2">
                                    <?php $this->render_badge_icon($settings); ?>
                                    <?php echo esc_html($badge_text); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ($title_before || $title_accent || $title_after) : ?>
                                <h1 class="gc-3d-anim-hero-title gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--3">
                                    <?php echo esc_html($title_before); ?><?php if ($title_accent) : ?><span class="gc-3d-anim-hero-accent"><?php echo esc_html($title_accent); ?></span><?php endif; ?><?php echo esc_html($title_after); ?>
                                </h1>
                            <?php endif; ?>
                            <?php if ('' !== $subtitle) : ?>
                                <p class="gc-3d-anim-hero-subtitle gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--4">
                                    <?php echo esc_html($subtitle); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('' !== $description) : ?>
                                <p class="gc-3d-anim-hero-desc gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--5">
                                    <?php echo wp_kses($description, ['br' => []]); ?>
                                </p>
                            <?php endif; ?>
                            <?php if ('' !== $primary_btn_text || '' !== $outline_btn_text) : ?>
                                <div class="gc-3d-anim-hero-btns gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--6">
                                    <?php if ('' !== $primary_btn_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_va_anim_hb_primary_btn_link'] ?? []); ?> class="gc-3d-anim-hero-btn gc-3d-anim-hero-btn--primary">
                                            <?php $this->render_primary_btn_icon($settings); ?> <?php echo esc_html($primary_btn_text); ?>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ('' !== $outline_btn_text) : ?>
                                        <a<?php echo $this->get_link_attributes($settings['gc_va_anim_hb_outline_btn_link'] ?? []); ?> class="gc-3d-anim-hero-btn gc-3d-anim-hero-btn--outline">
                                            <?php echo esc_html($outline_btn_text); ?> <?php $this->render_outline_btn_icon($settings); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($features)) : ?>
                                <ul class="gc-3d-anim-hero-features gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--7">
                                    <?php foreach ($features as $feature) {
                                        $this->render_feature_item($feature);
                                    } ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gc-3d-anim-hero-visual-wrap gc-3d-anim-hero-reveal gc-3d-anim-hero-reveal--4">
                            <button type="button" class="gc-3d-anim-hero-visual"
                                <?php if ($video_id) : ?>data-video="<?php echo esc_attr($video_id); ?>"<?php endif; ?>
                                aria-label="<?php echo esc_attr($visual_aria); ?>">
                                <div class="gc-3d-anim-hero-visual-inner">
                                    <div class="gc-3d-anim-cube-stage" aria-hidden="true">
                                        <div class="gc-3d-anim-cube-glow"></div>
                                        <div class="gc-3d-anim-cube-spinner">
                                            <div class="gc-3d-anim-cube">
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--front"></div>
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--back"></div>
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--right"></div>
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--left"></div>
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--top"></div>
                                                <div class="gc-3d-anim-cube-face gc-3d-anim-cube-face--bottom">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="gc-3d-anim-cube-shadow"></div>
                                    </div>
                                    <?php if ('' !== $showreel_text) : ?>
                                        <span class="gc-3d-anim-hero-showreel">
                                            <span class="gc-3d-anim-hero-showreel-icon" aria-hidden="true">
                                                <?php $this->render_play_icon($settings); ?>
                                            </span>
                                            <?php echo esc_html($showreel_text); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </button>
                            <?php if (!empty($stats)) : ?>
                                <div class="gc-3d-anim-hero-stats">
                                    <?php foreach ($stats as $stat) {
                                        $this->render_stat_item($stat);
                                    } ?>
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

Plugin::instance()->widgets_manager->register(new Softro_VA_Animation_Hero_Widget());
