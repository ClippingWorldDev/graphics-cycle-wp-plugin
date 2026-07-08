<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Why_Choose_With_Video_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_why_choose_with_video';
    }

    public function get_title()
    {
        return esc_html__('GC Why Choose With Video', 'softro-core');
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

    private function render_item_icon(array $item, array $settings)
    {
        if (!empty($item['item_icon']['value'])) {
            $this->render_icon($item['item_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['item_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_wcv_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_wcv_default_icon']['value'])) {
            $this->render_icon($settings['gc_wcv_default_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-light fa-check" aria-hidden="true"></i>';
        }
    }

    private function get_default_items()
    {
        return [
            [
                'item_icon'       => ['value' => 'fa-light fa-user-pen', 'library' => 'fa-light'],
                'item_title'      => esc_html__('Dedicated Editors', 'softro-core'),
                'item_description' => esc_html__(
                    'Every image is handled by trained professionals, not automated software.',
                    'softro-core'
                ),
            ],
            [
                'item_icon'       => ['value' => 'fa-light fa-arrows-rotate', 'library' => 'fa-light'],
                'item_title'      => esc_html__('Free Revisions', 'softro-core'),
                'item_description' => esc_html__('We refine your images until you are fully satisfied.', 'softro-core'),
            ],
            [
                'item_icon'       => ['value' => 'fa-light fa-shield-check', 'library' => 'fa-light'],
                'item_title'      => esc_html__('Secure File Handling', 'softro-core'),
                'item_description' => esc_html__(
                    'Your images and data are kept private and never shared with third parties.',
                    'softro-core'
                ),
            ],
            [
                'item_icon'       => ['value' => 'fa-light fa-badge-check', 'library' => 'fa-light'],
                'item_title'      => esc_html__('Quality Checked', 'softro-core'),
                'item_description' => esc_html__('Every image passes a final review before delivery.', 'softro-core'),
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
        $this->start_controls_section('gc_wcv_video_section', [
            'label' => esc_html__('Video', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_video_url', [
            'label'       => esc_html__('Embed URL', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'https://www.youtube.com/embed/8oON21G1Bqg?rel=0&modestbranding=1',
            'label_block' => true,
            'description' => esc_html__('Use the iframe embed URL (e.g. YouTube /embed/ URL).', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_video_title', [
            'label'       => esc_html__('Iframe Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Graphics Cycle background removal process', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Why Choose Graphics Cycle', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_wcv_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'We combine skilled editors, fast turnaround, and consistent quality to help your products look their best.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_defaults_section', [
            'label' => esc_html__('Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_default_icon', [
            'label'   => esc_html__('Default Item Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-user-pen', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_wcv_default_icon_image', [
            'label'       => esc_html__('Default Item Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $item_repeater = new Repeater();

        $item_repeater->add_control('item_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
        ]);

        $item_repeater->add_control('item_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $item_repeater->add_control('item_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Dedicated Editors', 'softro-core'),
            'label_block' => true,
        ]);

        $item_repeater->add_control('item_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Item description goes here.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_wcv_items_section', [
            'label' => esc_html__('Why Choose Items', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_items', [
            'label'       => esc_html__('Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $item_repeater->get_controls(),
            'default'     => $this->get_default_items(),
            'title_field' => '{{{ item_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_wcv_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_wcv_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_wcv_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wcv_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-choose',
        ]);

        $this->add_responsive_control('gc_wcv_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-choose' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-choose' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_section_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-choose' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_video', [
            'label' => esc_html__('Video Frame', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wcv_video_frame_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-video-frame',
        ]);

        $this->add_responsive_control('gc_wcv_video_frame_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-video-frame' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_video_frame_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-video' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_video_frame_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-video-frame' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_iframe_height', [
            'label'      => esc_html__('Iframe Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 150, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-video-frame iframe' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_iframe_min_height', [
            'label'      => esc_html__('Frame Min Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 150, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-video-frame' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_content', [
            'label' => esc_html__('Content Column', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wcv_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_heading', [
            'label' => esc_html__('Heading', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wcv_heading_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_heading_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wcv_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-heading .section-title',
        ]);

        $this->add_control('gc_wcv_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wcv_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_intro', [
            'label' => esc_html__('Intro', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wcv_intro_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-intro',
        ]);

        $this->add_control('gc_wcv_intro_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wcv_intro_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_intro_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-intro' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_list', [
            'label' => esc_html__('Items List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wcv_list_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 60]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-list' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_list_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-list' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_list_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_item', [
            'label' => esc_html__('Item Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wcv_item_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-item',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_wcv_item_border',
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-item',
        ]);

        $this->add_responsive_control('gc_wcv_item_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_item_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_item_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wcv_item_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_wcv_item_hover_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-item:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_wcv_item_line_color', [
            'label'     => esc_html__('Top Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-item::after' => 'background: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_icon', [
            'label' => esc_html__('Item Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wcv_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 16, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wcv_icon_font_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-why-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-why-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-why-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_wcv_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_wcv_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wcv_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wcv_icon_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_wcv_icon_hover_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-why-item:hover .gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-why-item:hover .gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_wcv_icon_hover_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-item:hover .gc-bg-removal-why-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_item_title', [
            'label' => esc_html__('Item Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wcv_item_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-copy h3',
        ]);

        $this->add_control('gc_wcv_item_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-copy h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wcv_item_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-copy h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wcv_style_item_desc', [
            'label' => esc_html__('Item Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wcv_item_desc_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-why-copy p',
        ]);

        $this->add_control('gc_wcv_item_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-why-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wcv_item_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-why-copy p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_wcv_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_wcv_theme_mode_tabs');

        $this->start_controls_tab('gc_wcv_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wcv_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-why-choose',
        ]);

        $this->add_control('gc_wcv_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_video_frame_bg', [
            'label'     => esc_html__('Video Frame Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-video-frame' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_video_frame_border', [
            'label'     => esc_html__('Video Frame Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-video-frame' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_item_bg', [
            'label'     => esc_html__('Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_item_hover_bg', [
            'label'     => esc_html__('Item Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-item:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_item_desc_color', [
            'label'     => esc_html__('Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wcv_dark_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-why-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_dark_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wcv_dark_icon_hover_bg', [
            'label'     => esc_html__('Icon Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon' => 'background-color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_wcv_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wcv_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-why-choose',
        ]);

        $this->add_control('gc_wcv_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_video_frame_bg', [
            'label'     => esc_html__('Video Frame Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-video-frame' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_video_frame_border', [
            'label'     => esc_html__('Video Frame Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-video-frame' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_item_bg', [
            'label'     => esc_html__('Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_item_hover_bg', [
            'label'     => esc_html__('Item Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-item:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_item_title_color', [
            'label'     => esc_html__('Item Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_item_desc_color', [
            'label'     => esc_html__('Item Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wcv_light_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-why-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wcv_light_icon_hover_color', [
            'label'     => esc_html__('Icon Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_wcv_light_icon_hover_bg', [
            'label'     => esc_html__('Icon Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-why-item:hover .gc-bg-removal-why-icon' => 'background-color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_wcv_reset_elementor_spacing'] ?? 'yes')) {
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
        $section_shape = $this->get_media_url($settings['gc_wcv_section_shape'] ?? [], '');

        if (!$section_shape) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-bg-removal-why-choose {
                background-image: url('<?php echo esc_url($section_shape); ?>');
                background-repeat: no-repeat;
            }
        </style>
        <?php
    }

    private function render_item(array $item, array $settings)
    {
        $title       = trim((string) ($item['item_title'] ?? ''));
        $description = $item['item_description'] ?? '';

        if ('' === $title && '' === trim(wp_strip_all_tags((string) $description))) {
            return;
        }
        ?>
        <div class="gc-bg-removal-why-item fade-top">
            <span class="gc-bg-removal-why-icon" aria-hidden="true"><?php $this->render_item_icon($item, $settings); ?></span>
            <div class="gc-bg-removal-why-copy">
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $title     = $settings['gc_wcv_title'] ?? '';
        $intro     = $settings['gc_wcv_intro'] ?? '';
        $items     = $settings['gc_wcv_items'] ?? [];
        $video_url = trim((string) ($settings['gc_wcv_video_url'] ?? ''));
        $video_title = $settings['gc_wcv_video_title'] ?? '';

        if (empty($items)) {
            $items = $this->get_default_items();
        }

        $this->render_elementor_spacing_fix($settings);
        $this->render_shape_backgrounds($settings);
        ?>

        <section class="gc-bg-removal-why-choose pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="row g-4 g-xl-5 align-items-center">
                    <?php if ('' !== $video_url) : ?>
                        <div class="col-lg-6">
                            <div class="gc-bg-removal-why-video fade-top">
                                <div class="gc-bg-removal-why-video-frame">
                                    <iframe src="<?php echo esc_url($video_url); ?>"
                                        title="<?php echo esc_attr($video_title); ?>"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen
                                        loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="gc-bg-removal-why-content">
                            <?php if ('' !== trim((string) $title) || '' !== trim(wp_strip_all_tags((string) $intro))) : ?>
                                <div class="section-heading gc-bg-removal-why-heading">
                                    <?php if ('' !== trim((string) $title)) : ?>
                                        <h2 class="section-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                    <?php endif; ?>
                                    <?php if ('' !== trim(wp_strip_all_tags((string) $intro))) : ?>
                                        <p class="gc-bg-removal-why-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($intro); ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <div class="gc-bg-removal-why-list">
                                <?php foreach ($items as $item) {
                                    $this->render_item($item, $settings);
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Why_Choose_With_Video_Widget());
