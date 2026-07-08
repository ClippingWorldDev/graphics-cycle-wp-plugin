<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Video_3D_Challange_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_video_3d_challange';
    }

    public function get_title()
    {
        return esc_html__('GC Video 3D Challenge', 'softro-core');
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

    private function get_default_tool_icons()
    {
        return [
            ['tool_icon' => ['value' => 'fa-light fa-scissors', 'library' => 'fa-light']],
            ['tool_icon' => ['value' => 'fa-light fa-film', 'library' => 'fa-light']],
            ['tool_icon' => ['value' => 'fa-light fa-sliders', 'library' => 'fa-light']],
            ['tool_icon' => ['value' => 'fa-light fa-wand-magic-sparkles', 'library' => 'fa-light']],
        ];
    }

    private function get_default_challenges()
    {
        return [
            [
                'challenge_icon'        => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
                'challenge_title'       => esc_html__('Tight project deadlines', 'softro-core'),
                'challenge_description' => esc_html__('Producing professional videos and 3D content quickly can be challenging when time and resources are limited.', 'softro-core'),
            ],
            [
                'challenge_icon'        => ['value' => 'fa-light fa-image', 'library' => 'fa-light'],
                'challenge_title'       => esc_html__('Inconsistent visual quality', 'softro-core'),
                'challenge_description' => esc_html__('Poor editing, inconsistent branding, and low-quality visuals can reduce the impact of your content.', 'softro-core'),
            ],
            [
                'challenge_icon'        => ['value' => 'fa-light fa-user-group', 'library' => 'fa-light'],
                'challenge_title'       => esc_html__('Limited production resources', 'softro-core'),
                'challenge_description' => esc_html__('Building an in-house team for video editing and 3D design can be costly and difficult to scale.', 'softro-core'),
            ],
            [
                'challenge_icon'        => ['value' => 'fa-light fa-chart-line-up', 'library' => 'fa-light'],
                'challenge_title'       => esc_html__('Growing content demands', 'softro-core'),
                'challenge_description' => esc_html__('Businesses need a constant flow of marketing content, product visuals, and promotional materials to stay competitive.', 'softro-core'),
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
        $this->start_controls_section('gc_v3d_chall_visual_section', [
            'label' => esc_html__('Visual Mockup', 'softro-core'),
        ]);

        $this->add_control('gc_v3d_chall_app_name', [
            'label'       => esc_html__('App Name', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Video Editor', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_v3d_chall_play_icon', [
            'label'   => esc_html__('Play Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-play', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_v3d_chall_play_icon_image', [
            'label' => esc_html__('Play Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_v3d_chall_timecode', [
            'label'       => esc_html__('Timecode', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '00:14:23',
            'label_block' => true,
        ]);

        $tool_repeater = new Repeater();

        $tool_repeater->add_control('tool_icon', [
            'label'   => esc_html__('Tool Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-scissors', 'library' => 'fa-light'],
        ]);

        $tool_repeater->add_control('tool_icon_image', [
            'label' => esc_html__('Tool Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_v3d_chall_tool_icons', [
            'label'       => esc_html__('Toolbar Icons', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $tool_repeater->get_controls(),
            'default'     => $this->get_default_tool_icons(),
            'title_field' => esc_html__('Tool Icon', 'softro-core'),
        ]);

        $this->add_control('gc_v3d_chall_visual_caption', [
            'label'     => esc_html__('Visual Caption', 'softro-core'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => esc_html__('Professional video editing & post-production', 'softro-core'),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_v3d_chall_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('The Challenges We Solve', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_v3d_chall_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__("Creating professional visual content isn't always easy", 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $challenge_repeater = new Repeater();

        $challenge_repeater->add_control('challenge_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
        ]);

        $challenge_repeater->add_control('challenge_icon_image', [
            'label' => esc_html__('Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $challenge_repeater->add_control('challenge_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Tight project deadlines', 'softro-core'),
            'label_block' => true,
        ]);

        $challenge_repeater->add_control('challenge_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Producing professional videos and 3D content quickly can be challenging when time and resources are limited.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_v3d_chall_items_section', [
            'label' => esc_html__('Challenge Items', 'softro-core'),
        ]);

        $this->add_control('gc_v3d_chall_default_icon', [
            'label'   => esc_html__('Default Item Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-clock', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_v3d_chall_default_icon_image', [
            'label' => esc_html__('Default Item Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_v3d_chall_items', [
            'label'       => esc_html__('Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $challenge_repeater->get_controls(),
            'default'     => $this->get_default_challenges(),
            'title_field' => '{{{ challenge_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_v3d_chall_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_v3d_chall_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_v3d_chall_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 110, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_v3d_chall_section_overlay',
            'label'     => esc_html__('Section Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenges::before',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_panel', [
            'label' => esc_html__('Panel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_panel_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges-panel',
        ]);

        $this->add_responsive_control('gc_v3d_chall_panel_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default'    => [
                'top'    => '24',
                'right'  => '24',
                'bottom' => '24',
                'left'   => '24',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges-panel' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_v3d_chall_panel_border',
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges-panel',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_v3d_chall_panel_shadow',
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges-panel',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_visual', [
            'label' => esc_html__('Visual Column', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_visual_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges-visual',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_v3d_chall_visual_overlay',
            'label'     => esc_html__('Visual Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenges-visual::before',
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_v3d_chall_visual_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'default'    => [
                'top'    => '48',
                'right'  => '40',
                'bottom' => '36',
                'left'   => '40',
                'unit'   => 'px',
            ],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges-visual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_v3d_chall_caption_typography',
            'label'     => esc_html__('Caption Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenges-visual-caption',
            'separator' => 'before',
        ]);

        $this->add_control('gc_v3d_chall_caption_color', [
            'label'     => esc_html__('Caption Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenges-visual-caption' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_caption_margin', [
            'label'      => esc_html__('Caption Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges-visual-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_mockup', [
            'label' => esc_html__('Video Editor Mockup', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_v3d_chall_app_max_width', [
            'label'      => esc_html__('App Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 340, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-edit-app' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_app_bg',
            'label'    => esc_html__('App Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-edit-app',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_v3d_chall_app_border',
            'selector' => '{{WRAPPER}} .gc-video-edit-app',
        ]);

        $this->add_responsive_control('gc_v3d_chall_app_radius', [
            'label'      => esc_html__('App Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-video-edit-app' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_v3d_chall_app_name_color', [
            'label'     => esc_html__('App Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-edit-app-name' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_play_icon_size', [
            'label'      => esc_html__('Play Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-edit-play i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-edit-play svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-edit-play img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_v3d_chall_play_icon_color', [
            'label'     => esc_html__('Play Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-video-edit-play i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-video-edit-play svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_v3d_chall_timecode_color', [
            'label'     => esc_html__('Timecode Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-edit-timecode' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_tool_icon_size', [
            'label'      => esc_html__('Toolbar Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-edit-tools span i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-edit-tools span svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-edit-tools span img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_v3d_chall_tool_icon_color', [
            'label'     => esc_html__('Toolbar Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-video-edit-tools span i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-video-edit-tools span svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_v3d_chall_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_content_bg',
            'label'    => esc_html__('Content Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-challenges-content',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_v3d_chall_eyebrow_typography',
            'label'     => esc_html__('Eyebrow Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenges-eyebrow',
            'separator' => 'before',
        ]);

        $this->add_control('gc_v3d_chall_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenges-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_v3d_chall_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenges-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_v3d_chall_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenges-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenges-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_v3d_chall_style_items', [
            'label' => esc_html__('Challenge Items', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_v3d_chall_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenge-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_item_gap', [
            'label'      => esc_html__('Icon / Copy Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenge-item' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_v3d_chall_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-item' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 46, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-challenge-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_v3d_chall_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-3d-challenge-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-challenge-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-challenge-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_v3d_chall_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_v3d_chall_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_v3d_chall_icon_border_color', [
            'label'     => esc_html__('Icon Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-icon' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_v3d_chall_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_v3d_chall_icon_hover_bg', [
            'label'     => esc_html__('Icon Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_v3d_chall_item_title_typography',
            'label'     => esc_html__('Item Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-challenge-copy h3',
            'separator' => 'before',
        ]);

        $this->add_control('gc_v3d_chall_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-copy h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_v3d_chall_item_desc_typography',
            'label'    => esc_html__('Item Description Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-challenge-copy p',
        ]);

        $this->add_control('gc_v3d_chall_item_desc_color', [
            'label'     => esc_html__('Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-challenge-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_v3d_chall_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_v3d_chall_theme_mode_tabs');

        $this->start_controls_tab('gc_v3d_chall_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-challenges',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-challenges::before',
        ]);

        $this->add_control('gc_v3d_chall_dark_panel_border_color', [
            'label'     => esc_html__('Panel Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenges-panel' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_dark_visual_bg',
            'label'    => esc_html__('Visual Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-challenges-visual',
        ]);

        $this->add_control('gc_v3d_chall_dark_caption_color', [
            'label'     => esc_html__('Caption Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenges-visual-caption' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_dark_content_bg',
            'label'    => esc_html__('Content Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-challenges-content',
        ]);

        $this->add_control('gc_v3d_chall_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenges-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenges-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_dark_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenge-item' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_dark_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenge-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_dark_item_desc_color', [
            'label'     => esc_html__('Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-challenge-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '.gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_v3d_chall_dark_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '.gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_v3d_chall_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-challenges',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-challenges::before',
        ]);

        $this->add_control('gc_v3d_chall_light_panel_border_color', [
            'label'     => esc_html__('Panel Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenges-panel' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_light_visual_bg',
            'label'    => esc_html__('Visual Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-challenges-visual',
        ]);

        $this->add_control('gc_v3d_chall_light_caption_color', [
            'label'     => esc_html__('Caption Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenges-visual-caption' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_v3d_chall_light_content_bg',
            'label'    => esc_html__('Content Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-challenges-content',
        ]);

        $this->add_control('gc_v3d_chall_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenges-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenges-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_light_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenge-item' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_light_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenge-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_light_item_desc_color', [
            'label'     => esc_html__('Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-challenge-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_v3d_chall_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '.gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_v3d_chall_light_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon i'   => 'color: {{VALUE}};',
                '.gc-video-3d-challenge-item:hover .gc-video-3d-challenge-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_v3d_chall_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_icon_or_image($icon_settings, $icon_image)
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($icon_image ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function render_challenge_icon($item, $settings)
    {
        if (!empty($item['challenge_icon']['value'])) {
            $this->render_icon($item['challenge_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['challenge_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_v3d_chall_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_v3d_chall_default_icon']['value'])) {
            $this->render_icon($settings['gc_v3d_chall_default_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_tool_icons_for_render($settings)
    {
        $tools    = !empty($settings['gc_v3d_chall_tool_icons']) ? $settings['gc_v3d_chall_tool_icons'] : [];
        $defaults = $this->get_default_tool_icons();
        $output   = [];

        for ($index = 0; $index < 4; $index++) {
            $output[] = $tools[$index] ?? $defaults[$index];
        }

        return $output;
    }

    private function render_video_editor_mockup($settings)
    {
        $app_name   = $settings['gc_v3d_chall_app_name'] ?? '';
        $play_icon  = $settings['gc_v3d_chall_play_icon'] ?? [];
        $play_image = $settings['gc_v3d_chall_play_icon_image'] ?? [];
        $timecode   = $settings['gc_v3d_chall_timecode'] ?? '';
        $tools      = $this->get_tool_icons_for_render($settings);
        ?>
        <div class="gc-video-edit-studio" aria-hidden="true">
            <div class="gc-video-edit-app">
                <div class="gc-video-edit-topbar">
                    <span class="gc-video-edit-dot gc-video-edit-dot--red"></span>
                    <span class="gc-video-edit-dot gc-video-edit-dot--yellow"></span>
                    <span class="gc-video-edit-dot gc-video-edit-dot--green"></span>
                    <?php if ($app_name) : ?>
                        <span class="gc-video-edit-app-name"><?php echo esc_html($app_name); ?></span>
                    <?php endif; ?>
                </div>
                <div class="gc-video-edit-preview">
                    <div class="gc-video-edit-preview-frame">
                        <span class="gc-video-edit-play"><?php $this->render_icon_or_image($play_icon, $play_image); ?></span>
                        <span class="gc-video-edit-scrub"></span>
                    </div>
                    <?php if ($timecode) : ?>
                        <span class="gc-video-edit-timecode"><?php echo esc_html($timecode); ?></span>
                    <?php endif; ?>
                </div>
                <div class="gc-video-edit-tools">
                    <?php foreach ($tools as $tool) : ?>
                        <span><?php $this->render_icon_or_image($tool['tool_icon'] ?? [], $tool['tool_icon_image'] ?? []); ?></span>
                    <?php endforeach; ?>
                </div>
                <div class="gc-video-edit-timeline">
                    <div class="gc-video-edit-track gc-video-edit-track--video">
                        <span class="gc-video-edit-clip gc-video-edit-clip--1"></span>
                        <span class="gc-video-edit-clip gc-video-edit-clip--2"></span>
                        <span class="gc-video-edit-clip gc-video-edit-clip--3"></span>
                    </div>
                    <div class="gc-video-edit-track gc-video-edit-track--audio">
                        <span class="gc-video-edit-clip gc-video-edit-clip--a1"></span>
                        <span class="gc-video-edit-clip gc-video-edit-clip--a2"></span>
                    </div>
                    <span class="gc-video-edit-playhead"></span>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_challenge_item($item, $settings)
    {
        $title = $item['challenge_title'] ?? '';
        $desc  = $item['challenge_description'] ?? '';

        if (!$title && !$desc) {
            return;
        }
        ?>
        <li class="gc-video-3d-challenge-item fade-top">
            <span class="gc-video-3d-challenge-icon" aria-hidden="true"><?php $this->render_challenge_icon($item, $settings); ?></span>
            <div class="gc-video-3d-challenge-copy">
                <?php if ($title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ($desc) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($desc); ?></p>
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

        $visual_caption = $settings['gc_v3d_chall_visual_caption'] ?? '';
        $eyebrow        = $settings['gc_v3d_chall_eyebrow'] ?? '';
        $title          = $settings['gc_v3d_chall_title'] ?? '';
        $items          = !empty($settings['gc_v3d_chall_items']) ? $settings['gc_v3d_chall_items'] : [];
        ?>

        <section class="gc-video-3d-challenges pt-130 pb-110 fade-wrapper">
            <div class="container">
                <div class="gc-video-3d-challenges-panel fade-top">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-lg-5">
                            <div class="gc-video-3d-challenges-visual">
                                <?php $this->render_video_editor_mockup($settings); ?>
                                <?php if ($visual_caption) : ?>
                                    <p class="gc-video-3d-challenges-visual-caption"><?php echo $this->get_paragraph_inner_content($visual_caption); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="gc-video-3d-challenges-content">
                                <?php if ($eyebrow) : ?>
                                    <span class="sub-heading gc-video-3d-challenges-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <h2 class="gc-video-3d-challenges-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                                <?php if (!empty($items)) : ?>
                                    <ul class="gc-video-3d-challenges-list">
                                        <?php foreach ($items as $item) {
                                            $this->render_challenge_item($item, $settings);
                                        } ?>
                                    </ul>
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

Plugin::instance()->widgets_manager->register(new Softro_Video_3D_Challange_Widget());
