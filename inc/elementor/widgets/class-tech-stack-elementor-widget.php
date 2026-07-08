<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Tech_Stack_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_tech_stack';
    }

    public function get_title()
    {
        return esc_html__('GC Tech Stack', 'softro-core');
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

    private function get_default_row_rtl_items()
    {
        $items = ['Python', 'C#', 'Laravel', 'Flutter', 'React', 'Node.js', 'PHP', 'WordPress'];
        $output = [];

        foreach ($items as $item) {
            $output[] = ['item_text' => esc_html__($item, 'softro-core')];
        }

        return $output;
    }

    private function get_default_row_ltr_items()
    {
        $items = ['C++', 'Android', 'Vue.js', 'Golang', 'Java', 'Swift', 'TypeScript', 'Moodle'];
        $output = [];

        foreach ($items as $item) {
            $output[] = ['item_text' => esc_html__($item, 'softro-core')];
        }

        return $output;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_tech_stack_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_tech_stack_aria_label', [
            'label'       => esc_html__('Section Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Technologies we work with', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_tech_stack_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Tech Stack', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_tech_stack_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Yes!', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_tech_stack_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('We cover your tech stack.', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_tech_stack_desc', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Our 850+ team has expertise in almost every programming language.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_shapes_section', [
            'label' => esc_html__('Glow Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_tech_stack_glow_one_image', [
            'label'   => esc_html__('Glow Shape One', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_tech_stack_glow_two_image', [
            'label'   => esc_html__('Glow Shape Two', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->end_controls_section();

        $item_repeater = new Repeater();

        $item_repeater->add_control('item_text', [
            'label'       => esc_html__('Item Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Python', 'softro-core'),
            'label_block' => true,
        ]);

        $item_repeater->add_control('item_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-solid'],
        ]);

        $item_repeater->add_control('item_icon_image', [
            'label'   => esc_html__('Icon / Logo Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->start_controls_section('gc_tech_stack_marquee_section', [
            'label' => esc_html__('Marquee Rows', 'softro-core'),
        ]);

        $this->add_control('gc_tech_stack_default_item_icon', [
            'label'   => esc_html__('Default Item Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_tech_stack_default_item_icon_image', [
            'label'   => esc_html__('Default Item Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_tech_stack_row_rtl_items', [
            'label'       => esc_html__('Top Row (RTL)', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $item_repeater->get_controls(),
            'default'     => $this->get_default_row_rtl_items(),
            'title_field' => '{{{ item_text }}}',
        ]);

        $this->add_control('gc_tech_stack_row_ltr_items', [
            'label'       => esc_html__('Bottom Row (LTR)', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $item_repeater->get_controls(),
            'default'     => $this->get_default_row_ltr_items(),
            'title_field' => '{{{ item_text }}}',
            'separator'   => 'before',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_tech_stack_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_tech_stack_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_tech_stack_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_tech_stack_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-web-tech-stack-section::before',
        ]);

        $this->add_control('gc_tech_stack_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-section' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-section' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_style_glow', [
            'label' => esc_html__('Glow Shapes', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_tech_stack_glow_one_heading', [
            'label' => esc_html__('Glow Shape One', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_one_width', [
            'label'      => esc_html__('Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 280, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--1' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_one_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 280, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--1' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_glow_one_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-glow--1' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_one_image_size', [
            'label'      => esc_html__('Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--1' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_glow_two_heading', [
            'label'     => esc_html__('Glow Shape Two', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_two_width', [
            'label'      => esc_html__('Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 220, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--2' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_two_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'default'    => ['size' => 220, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--2' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_glow_two_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-glow--2' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_glow_two_image_size', [
            'label'      => esc_html__('Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-glow--2' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_tech_stack_header_max_width', [
            'label'      => esc_html__('Header Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 760, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-header' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_header_margin', [
            'label'      => esc_html__('Header Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_header_padding', [
            'label'      => esc_html__('Header Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_eyebrow_heading', [
            'label'     => esc_html__('Eyebrow', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_tech_stack_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-eyebrow',
        ]);

        $this->add_control('gc_tech_stack_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_eyebrow_line_color', [
            'label'     => esc_html__('Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-web-tech-stack-eyebrow::before' => 'background: linear-gradient(90deg, transparent, {{VALUE}}, transparent);',
                '{{WRAPPER}} .gc-web-tech-stack-eyebrow::after'  => 'background: linear-gradient(90deg, transparent, {{VALUE}}, transparent);',
            ],
        ]);

        $this->add_responsive_control('gc_tech_stack_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_tech_stack_title_typography',
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-title',
        ]);

        $this->add_control('gc_tech_stack_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_title_underline_color', [
            'label'     => esc_html__('Underline Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-title::after' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_accent_heading', [
            'label'     => esc_html__('Title Accent', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_tech_stack_accent_typography',
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-accent',
        ]);

        $this->add_control('gc_tech_stack_accent_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_desc_heading', [
            'label'     => esc_html__('Description', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_tech_stack_desc_typography',
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-desc',
        ]);

        $this->add_control('gc_tech_stack_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_desc_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 560, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-desc' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_style_marquee', [
            'label' => esc_html__('Marquee Area', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_tech_stack_marquees_wrap_padding', [
            'label'      => esc_html__('Wrap Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-marquees-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_marquees_bg',
            'label'    => esc_html__('Marquees Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-marquees',
        ]);

        $this->add_control('gc_tech_stack_marquees_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-marquees' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_marquees_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-marquees' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_marquees_padding', [
            'label'      => esc_html__('Marquees Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-marquees' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_tech_stack_row_divider_color', [
            'label'     => esc_html__('Row Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-row + .gc-web-tech-stack-row' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_row_padding', [
            'label'      => esc_html__('Row Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_tech_stack_style_item', [
            'label' => esc_html__('Marquee Items', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_tech_stack_item_typography',
            'selector' => '{{WRAPPER}} .gc-web-tech-stack-item',
        ]);

        $this->add_control('gc_tech_stack_item_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-item' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_item_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_item_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-web-tech-stack-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_tech_stack_item_icon_size', [
            'label'      => esc_html__('Icon / Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-web-tech-stack-item i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-web-tech-stack-item svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-web-tech-stack-item img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_tech_stack_item_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_tech_stack_item_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-item:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_tech_stack_item_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-web-tech-stack-item:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_tech_stack_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_tech_stack_theme_mode_tabs');

        $this->start_controls_tab('gc_tech_stack_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-web-tech-stack-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-web-tech-stack-section::before',
        ]);

        $this->add_control('gc_tech_stack_dark_section_border_top_color', [
            'label'     => esc_html__('Section Top Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_section_border_bottom_color', [
            'label'     => esc_html__('Section Bottom Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_glow_one_bg', [
            'label'     => esc_html__('Glow One Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-glow--1' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_glow_two_bg', [
            'label'     => esc_html__('Glow Two Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-glow--2' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_dark_marquees_bg',
            'label'    => esc_html__('Marquees Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-web-tech-stack-marquees',
        ]);

        $this->add_control('gc_tech_stack_dark_marquees_border_top_color', [
            'label'     => esc_html__('Marquees Top Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-marquees' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_marquees_border_bottom_color', [
            'label'     => esc_html__('Marquees Bottom Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-marquees' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_row_divider_color', [
            'label'     => esc_html__('Row Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-row + .gc-web-tech-stack-row' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_item_color', [
            'label'     => esc_html__('Item Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-item' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_item_hover_color', [
            'label'     => esc_html__('Item Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-item:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_dark_item_hover_bg', [
            'label'     => esc_html__('Item Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-tech-stack-item:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_tech_stack_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-web-tech-stack-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-web-tech-stack-section::before',
        ]);

        $this->add_control('gc_tech_stack_light_section_border_top_color', [
            'label'     => esc_html__('Section Top Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_section_border_bottom_color', [
            'label'     => esc_html__('Section Bottom Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_glow_one_bg', [
            'label'     => esc_html__('Glow One Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-glow--1' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_glow_two_bg', [
            'label'     => esc_html__('Glow Two Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-glow--2' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_tech_stack_light_marquees_bg',
            'label'    => esc_html__('Marquees Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-web-tech-stack-marquees',
        ]);

        $this->add_control('gc_tech_stack_light_marquees_border_top_color', [
            'label'     => esc_html__('Marquees Top Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-marquees' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_marquees_border_bottom_color', [
            'label'     => esc_html__('Marquees Bottom Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-marquees' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_row_divider_color', [
            'label'     => esc_html__('Row Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-row + .gc-web-tech-stack-row' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_item_color', [
            'label'     => esc_html__('Item Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-item' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_item_hover_color', [
            'label'     => esc_html__('Item Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-item:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_tech_stack_light_item_hover_bg', [
            'label'     => esc_html__('Item Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-tech-stack-item:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_tech_stack_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_glow_shape_styles($settings)
    {
        $glow_one = $this->get_media_url($settings['gc_tech_stack_glow_one_image'] ?? [], '');
        $glow_two = $this->get_media_url($settings['gc_tech_stack_glow_two_image'] ?? [], '');

        if (!$glow_one && !$glow_two) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            <?php if ($glow_one) : ?>
            .elementor-element-<?php echo $widget_id; ?> .gc-web-tech-stack-glow--1 {
                background-image: url('<?php echo esc_url($glow_one); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
            <?php endif; ?>
            <?php if ($glow_two) : ?>
            .elementor-element-<?php echo $widget_id; ?> .gc-web-tech-stack-glow--2 {
                background-image: url('<?php echo esc_url($glow_two); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
            <?php endif; ?>
        </style>
        <?php
    }

    private function render_marquee_item($item, $settings)
    {
        $text = $item['item_text'] ?? '';

        if (!$text && empty($item['item_icon']['value']) && empty($item['item_icon_image']['url'])) {
            return;
        }

        echo '<span class="gc-web-tech-stack-item">';

        if (!empty($item['item_icon']['value'])) {
            $this->render_icon($item['item_icon'], ['aria-hidden' => 'true']);
            if ($text) {
                echo ' ';
            }
        } else {
            $icon_url = $this->get_media_url($item['item_icon_image'] ?? [], '');

            if (!$icon_url) {
                $icon_url = $this->get_media_url($settings['gc_tech_stack_default_item_icon_image'] ?? [], '');
            }

            if ($icon_url) {
                echo '<img src="' . esc_url($icon_url) . '" alt="">';
                if ($text) {
                    echo ' ';
                }
            } elseif (!empty($settings['gc_tech_stack_default_item_icon']['value'])) {
                $this->render_icon($settings['gc_tech_stack_default_item_icon'], ['aria-hidden' => 'true']);
                if ($text) {
                    echo ' ';
                }
            }
        }

        if ($text) {
            echo esc_html($text);
        }

        echo '</span>';
    }

    private function render_marquee_group($items, $settings, $aria_hidden = false)
    {
        $attr = $aria_hidden ? ' aria-hidden="true"' : '';

        echo '<div class="gc-web-tech-stack-group"' . $attr . '>';

        foreach ($items as $item) {
            $this->render_marquee_item($item, $settings);
        }

        echo '</div>';
    }

    private function render_marquee_row($items, $direction, $settings)
    {
        if (empty($items)) {
            return;
        }

        $row_class = 'gc-web-tech-stack-row gc-web-tech-stack-row--' . sanitize_html_class($direction);
        ?>
        <div class="<?php echo esc_attr($row_class); ?>">
            <div class="gc-web-tech-stack-track">
                <?php
                $this->render_marquee_group($items, $settings, false);
                $this->render_marquee_group($items, $settings, true);
                ?>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();
        $this->render_glow_shape_styles($settings);

        $aria_label   = $settings['gc_tech_stack_aria_label'] ?? esc_html__('Technologies we work with', 'softro-core');
        $eyebrow      = $settings['gc_tech_stack_eyebrow'] ?? '';
        $title_accent = $settings['gc_tech_stack_title_accent'] ?? '';
        $title_after  = $settings['gc_tech_stack_title_after'] ?? '';
        $desc         = $settings['gc_tech_stack_desc'] ?? '';
        $row_rtl      = !empty($settings['gc_tech_stack_row_rtl_items']) ? $settings['gc_tech_stack_row_rtl_items'] : [];
        $row_ltr      = !empty($settings['gc_tech_stack_row_ltr_items']) ? $settings['gc_tech_stack_row_ltr_items'] : [];
        ?>

        <section class="gc-web-tech-stack-section pt-130 pb-130 fade-wrapper" aria-label="<?php echo esc_attr($aria_label); ?>">
            <span class="gc-web-tech-stack-glow gc-web-tech-stack-glow--1" aria-hidden="true"></span>
            <span class="gc-web-tech-stack-glow gc-web-tech-stack-glow--2" aria-hidden="true"></span>
            <div class="container">
                <div class="gc-web-tech-stack-header text-center fade-top">
                    <?php if ($eyebrow) : ?>
                        <span class="gc-web-tech-stack-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>

                    <?php if ($title_accent || $title_after) : ?>
                        <h2 class="gc-web-tech-stack-title overflow-hidden" data-text-animation data-split="word" data-duration="1">
                            <?php if ($title_accent) : ?>
                                <span class="gc-web-tech-stack-accent"><?php echo esc_html($title_accent); ?></span>
                            <?php endif; ?>
                            <?php if ($title_after) : ?>
                                <?php echo $title_accent ? ' ' : ''; ?><?php echo esc_html($title_after); ?>
                            <?php endif; ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ($desc) : ?>
                        <p class="gc-web-tech-stack-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($desc); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="gc-web-tech-stack-marquees-wrap fade-top">
                <div class="gc-web-tech-stack-marquees" aria-hidden="true">
                    <?php
                    $this->render_marquee_row($row_rtl, 'rtl', $settings);
                    $this->render_marquee_row($row_ltr, 'ltr', $settings);
                    ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Tech_Stack_Widget());
