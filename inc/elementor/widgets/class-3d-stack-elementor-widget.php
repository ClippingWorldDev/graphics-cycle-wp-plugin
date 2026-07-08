<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_3D_Stack_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_3d_stack';
    }

    public function get_title()
    {
        return esc_html__('GC 3D Stack', 'softro-core');
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

    private function get_logo_style_class($style)
    {
        $allowed = [
            'blender',
            'c4d',
            'max',
            'maya',
            'unreal',
            'ae',
            'pr',
            'ps',
            'ai-brand',
            'substance',
            'zbrush',
            'vray',
        ];

        return in_array($style, $allowed, true) ? $style : 'blender';
    }

    private function get_logo_style_options()
    {
        return [
            'blender'   => esc_html__('Blender', 'softro-core'),
            'c4d'       => esc_html__('Cinema 4D', 'softro-core'),
            'max'       => esc_html__('3ds Max', 'softro-core'),
            'maya'      => esc_html__('Maya', 'softro-core'),
            'unreal'    => esc_html__('Unreal Engine', 'softro-core'),
            'ae'        => esc_html__('After Effects', 'softro-core'),
            'pr'        => esc_html__('Premiere Pro', 'softro-core'),
            'ps'        => esc_html__('Photoshop', 'softro-core'),
            'ai-brand'  => esc_html__('Illustrator', 'softro-core'),
            'substance' => esc_html__('Substance Painter', 'softro-core'),
            'zbrush'    => esc_html__('ZBrush', 'softro-core'),
            'vray'      => esc_html__('V-Ray', 'softro-core'),
        ];
    }

    private function get_default_stack_cards()
    {
        return [
            ['logo_style' => 'blender', 'logo_text' => 'B', 'card_name' => esc_html__('Blender', 'softro-core')],
            ['logo_style' => 'c4d', 'logo_text' => 'C4D', 'card_name' => esc_html__('Cinema 4D', 'softro-core')],
            ['logo_style' => 'max', 'logo_text' => '3D', 'card_name' => esc_html__('3ds Max', 'softro-core')],
            ['logo_style' => 'maya', 'logo_text' => 'M', 'card_name' => esc_html__('Maya', 'softro-core')],
            ['logo_style' => 'unreal', 'logo_text' => 'UE', 'card_name' => esc_html__('Unreal Engine', 'softro-core')],
            ['logo_style' => 'ae', 'logo_text' => 'Ae', 'card_name' => esc_html__('Adobe After Effects', 'softro-core')],
            ['logo_style' => 'pr', 'logo_text' => 'Pr', 'card_name' => esc_html__('Adobe Premiere Pro', 'softro-core')],
            ['logo_style' => 'ps', 'logo_text' => 'Ps', 'card_name' => esc_html__('Adobe Photoshop', 'softro-core')],
            ['logo_style' => 'ai-brand', 'logo_text' => 'Ai', 'card_name' => esc_html__('Adobe Illustrator', 'softro-core')],
            ['logo_style' => 'substance', 'logo_text' => 'Sp', 'card_name' => esc_html__('Substance Painter', 'softro-core')],
            ['logo_style' => 'zbrush', 'logo_text' => 'Z', 'card_name' => esc_html__('ZBrush', 'softro-core')],
            ['logo_style' => 'vray', 'logo_text' => 'V', 'card_name' => esc_html__('V-Ray', 'softro-core')],
        ];
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_3d_stack_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_3d_stack_show_eyebrow', [
            'label'        => esc_html__('Show Eyebrow', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_control('gc_3d_stack_eyebrow', [
            'label'     => esc_html__('Eyebrow', 'softro-core'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Our Stack', 'softro-core'),
            'label_block' => true,
            'condition' => ['gc_3d_stack_show_eyebrow' => 'yes'],
        ]);

        $this->add_control('gc_3d_stack_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Software & tools', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3d_stack_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('we use', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_3d_stack_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We work with industry-standard software to deliver exceptional quality, reliability, and realistic 3D animation every time.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('logo_style', [
            'label'   => esc_html__('Logo Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'blender',
            'options' => $this->get_logo_style_options(),
        ]);

        $card_repeater->add_control('logo_text', [
            'label'       => esc_html__('Logo Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'B',
            'description' => esc_html__('Used when no icon or image is selected.', 'softro-core'),
        ]);

        $card_repeater->add_control('logo_icon', [
            'label'   => esc_html__('Logo Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-solid'],
        ]);

        $card_repeater->add_control('logo_icon_image', [
            'label'   => esc_html__('Logo Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $card_repeater->add_control('card_name', [
            'label'       => esc_html__('Name', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Blender', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_3d_stack_cards_section', [
            'label' => esc_html__('Stack Cards', 'softro-core'),
        ]);

        $this->add_control('gc_3d_stack_default_logo_icon', [
            'label'   => esc_html__('Default Logo Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_3d_stack_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_stack_cards(),
            'title_field' => '{{{ card_name }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_3d_stack_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_3d_stack_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_3d_stack_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_content_max_width', [
            'label'      => esc_html__('Content Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 480, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-content' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_3d_stack_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_3d_stack_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-3d-anim-stack::before',
        ]);

        $this->add_control('gc_3d_stack_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3d_stack_eyebrow_typography',
            'label'    => esc_html__('Eyebrow Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-eyebrow',
        ]);

        $this->add_control('gc_3d_stack_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_eyebrow_margin', [
            'label'      => esc_html__('Eyebrow Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_3d_stack_rule_heading', [
            'label'     => esc_html__('Decorative Rule', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_3d_stack_rule_width', [
            'label'      => esc_html__('Rule Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 56, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-rule' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_rule_height', [
            'label'      => esc_html__('Rule Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 4, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-rule' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_3d_stack_rule_bg', [
            'label'     => esc_html__('Rule Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-rule' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_rule_margin', [
            'label'      => esc_html__('Rule Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-rule' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_3d_stack_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3d_stack_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-title',
        ]);

        $this->add_control('gc_3d_stack_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3d_stack_title_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_3d_stack_intro_heading', [
            'label'     => esc_html__('Intro', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3d_stack_intro_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-intro',
        ]);

        $this->add_control('gc_3d_stack_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_intro_margin', [
            'label'      => esc_html__('Intro Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'separator'  => 'before',
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_grid', [
            'label' => esc_html__('Stack Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3d_stack_grid_gap', [
            'label'      => esc_html__('Grid Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_grid_columns', [
            'label'   => esc_html__('Columns', 'softro-core'),
            'type'    => Controls_Manager::SLIDER,
            'range'   => ['px' => ['min' => 1, 'max' => 6, 'step' => 1]],
            'default' => ['size' => 3],
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-grid' => 'grid-template-columns: repeat({{SIZE}}, minmax(0, 1fr));'],
        ]);

        $this->add_responsive_control('gc_3d_stack_grid_padding', [
            'label'      => esc_html__('Grid Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_card', [
            'label' => esc_html__('Stack Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_3d_stack_card_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3d_stack_card_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_3d_stack_card_shadow',
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-card',
        ]);

        $this->add_responsive_control('gc_3d_stack_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_card_min_height', [
            'label'      => esc_html__('Min Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 72, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_card_inner_gap', [
            'label'      => esc_html__('Logo / Name Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 12, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-card' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_3d_stack_card_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_3d_stack_card_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_3d_stack_card_hover_border_color', [
            'label'     => esc_html__('Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-card:hover' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'      => 'gc_3d_stack_card_hover_shadow',
            'label'     => esc_html__('Hover Box Shadow', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-3d-anim-stack-card:hover',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_logo', [
            'label' => esc_html__('Stack Logo', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_3d_stack_logo_width', [
            'label'      => esc_html__('Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 42, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-logo' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_logo_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 42, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-logo' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_logo_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-logo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3d_stack_logo_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-logo',
        ]);

        $this->add_control('gc_3d_stack_logo_color', [
            'label'     => esc_html__('Text / Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-3d-anim-stack-logo' => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-stack-logo i' => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-3d-anim-stack-logo svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_3d_stack_logo_icon_size', [
            'label'      => esc_html__('Icon / Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-3d-anim-stack-logo i' => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-stack-logo svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-3d-anim-stack-logo img' => 'width: {{SIZE}}{{UNIT}}; height: auto; max-height: 100%;',
            ],
        ]);

        $this->add_responsive_control('gc_3d_stack_logo_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_3d_stack_style_name', [
            'label' => esc_html__('Stack Name', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_3d_stack_name_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-stack-name',
        ]);

        $this->add_control('gc_3d_stack_name_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-stack-name' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_3d_stack_name_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-stack-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_3d_stack_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_3d_stack_theme_mode_tabs');

        $this->start_controls_tab('gc_3d_stack_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_3d_stack_dark_section_bg',
            'label'     => esc_html__('Section Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-stack',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_3d_stack_dark_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-stack::before',
        ]);

        $this->add_control('gc_3d_stack_dark_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_rule_bg', [
            'label'     => esc_html__('Rule Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-rule' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_title_accent_color', [
            'label'     => esc_html__('Title Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_card_border_color', [
            'label'     => esc_html__('Card Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_card_hover_border_color', [
            'label'     => esc_html__('Card Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-card:hover' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_dark_logo_color', [
            'label'     => esc_html__('Logo Text / Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-3d-anim-stack-logo' => 'color: {{VALUE}};',
                '.gc-3d-anim-stack-logo i' => 'color: {{VALUE}};',
                '.gc-3d-anim-stack-logo svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_3d_stack_dark_name_color', [
            'label'     => esc_html__('Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-stack-name' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_3d_stack_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_3d_stack_light_section_bg',
            'label'     => esc_html__('Section Background', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-stack',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_3d_stack_light_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-stack::before',
        ]);

        $this->add_control('gc_3d_stack_light_section_border_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_rule_bg', [
            'label'     => esc_html__('Rule Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-rule' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_title_accent_color', [
            'label'     => esc_html__('Title Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_card_border_color', [
            'label'     => esc_html__('Card Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_card_hover_border_color', [
            'label'     => esc_html__('Card Hover Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-card:hover' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_3d_stack_light_logo_color', [
            'label'     => esc_html__('Logo Text / Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-3d-anim-stack-logo' => 'color: {{VALUE}};',
                '.gc-3d-anim-stack-logo i' => 'color: {{VALUE}};',
                '.gc-3d-anim-stack-logo svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_3d_stack_light_name_color', [
            'label'     => esc_html__('Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-stack-name' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_3d_stack_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_stack_logo($card, $settings)
    {
        $style   = $this->get_logo_style_class($card['logo_style'] ?? 'blender');
        $classes = 'gc-3d-anim-stack-logo gc-3d-anim-stack-logo--' . $style;

        echo '<span class="' . esc_attr($classes) . '" aria-hidden="true">';

        if (!empty($card['logo_icon']['value'])) {
            $this->render_icon($card['logo_icon'], ['aria-hidden' => 'true']);
        } else {
            $icon_url = $this->get_media_url($card['logo_icon_image'] ?? [], '');

            if ($icon_url) {
                echo '<img src="' . esc_url($icon_url) . '" alt="">';
            } elseif (!empty($settings['gc_3d_stack_default_logo_icon']['value'])) {
                $this->render_icon($settings['gc_3d_stack_default_logo_icon'], ['aria-hidden' => 'true']);
            } else {
                echo esc_html($card['logo_text'] ?? '');
            }
        }

        echo '</span>';
    }

    private function render_stack_card($card, $settings)
    {
        $name = $card['card_name'] ?? '';

        if (!$name) {
            return;
        }
        ?>
        <article class="gc-3d-anim-stack-card fade-top">
            <?php $this->render_stack_logo($card, $settings); ?>
            <span class="gc-3d-anim-stack-name"><?php echo esc_html($name); ?></span>
        </article>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $show_eyebrow  = 'yes' === ($settings['gc_3d_stack_show_eyebrow'] ?? 'yes');
        $eyebrow       = $settings['gc_3d_stack_eyebrow'] ?? '';
        $title_before  = $settings['gc_3d_stack_title_before'] ?? '';
        $title_accent  = $settings['gc_3d_stack_title_accent'] ?? '';
        $intro         = $settings['gc_3d_stack_intro'] ?? '';
        $cards         = !empty($settings['gc_3d_stack_cards']) ? $settings['gc_3d_stack_cards'] : [];
        ?>

        <section class="gc-3d-anim-stack pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="row g-4 g-xl-5 align-items-center gc-3d-anim-stack-row">
                    <div class="col-lg-5">
                        <div class="gc-3d-anim-stack-content">
                            <?php if ($show_eyebrow && $eyebrow) : ?>
                                <span class="gc-3d-anim-stack-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                            <?php endif; ?>

                            <span class="gc-3d-anim-stack-rule fade-top" aria-hidden="true"></span>

                            <?php if ($title_before || $title_accent) : ?>
                                <h2 class="gc-3d-anim-stack-title overflow-hidden" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03">
                                    <?php echo esc_html($title_before); ?>
                                    <?php if ($title_accent) : ?>
                                        <span class="gc-3d-anim-stack-accent"><?php echo esc_html($title_accent); ?></span>
                                    <?php endif; ?>
                                </h2>
                            <?php endif; ?>

                            <?php if ($intro) : ?>
                                <p class="gc-3d-anim-stack-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($intro); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <?php if (!empty($cards)) : ?>
                            <div class="gc-3d-anim-stack-grid">
                                <?php foreach ($cards as $card) {
                                    $this->render_stack_card($card, $settings);
                                } ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_3D_Stack_Widget());
