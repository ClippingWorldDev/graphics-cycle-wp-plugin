<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Search_Experience_Section_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_search_experience_section';
    }

    public function get_title()
    {
        return esc_html__('GC Search Experience Section', 'softro-core');
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

    private function render_skill_icon(array $item, array $settings)
    {
        if (!empty($item['skill_icon']['value'])) {
            $this->render_icon($item['skill_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['skill_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_search_exp_default_skill_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_search_exp_default_skill_icon']['value'])) {
            $this->render_icon($settings['gc_search_exp_default_skill_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_default_skills()
    {
        return [
            [
                'skill_title'   => esc_html__('UI/UX Design', 'softro-core'),
                'skill_percent' => 70,
                'skill_delay'   => '0ms',
            ],
            [
                'skill_title'   => esc_html__('Branding Research', 'softro-core'),
                'skill_percent' => 60,
                'skill_delay'   => '200ms',
            ],
            [
                'skill_title'   => esc_html__('Product Design', 'softro-core'),
                'skill_percent' => 90,
                'skill_delay'   => '200ms',
            ],
        ];
    }

    private function get_skill_delay($item, $index)
    {
        if (!empty($item['skill_delay'])) {
            return $item['skill_delay'];
        }

        return 0 === $index ? '0ms' : '200ms';
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_search_exp_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_search_exp_shape_1', [
            'label'       => esc_html__('Shape 1 Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('shapes/exp-shape-1.png')],
        ]);

        $this->add_control('gc_search_exp_shape_2', [
            'label'       => esc_html__('Shape 2 Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('shapes/exp-shape-2.png')],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_search_exp_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Our Working Growth', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_exp_title', [
            'label'   => esc_html__('Title', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('Fast Digital Agency in a', 'softro-core') . '<br>' . esc_html__('Simple way', 'softro-core') . '</p>',
        ]);

        $this->add_control('gc_search_exp_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('We don\'t just work with concrete and steel. We work with people We are Approachable, with even our highest work', 'softro-core') . '</p>',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_skill_defaults_section', [
            'label' => esc_html__('Skill Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_search_exp_default_skill_icon', [
            'label' => esc_html__('Default Skill Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_search_exp_default_skill_icon_image', [
            'label'       => esc_html__('Default Skill Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $skill_repeater = new Repeater();

        $skill_repeater->add_control('skill_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $skill_repeater->add_control('skill_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $skill_repeater->add_control('skill_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('UI/UX Design', 'softro-core'),
            'label_block' => true,
        ]);

        $skill_repeater->add_control('skill_percent', [
            'label'   => esc_html__('Progress (%)', 'softro-core'),
            'type'    => Controls_Manager::NUMBER,
            'default' => 70,
            'min'     => 0,
            'max'     => 100,
        ]);

        $skill_repeater->add_control('skill_delay', [
            'label'       => esc_html__('Animation Delay', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'label_block' => true,
            'description' => esc_html__('Example: 0ms or 200ms. Leave empty for automatic delay.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_search_exp_skills_section', [
            'label' => esc_html__('Skills / Progress Bars', 'softro-core'),
        ]);

        $this->add_control('gc_search_exp_skills', [
            'label'       => esc_html__('Skills', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $skill_repeater->get_controls(),
            'default'     => $this->get_default_skills(),
            'title_field' => '{{{ skill_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_gallery_section', [
            'label' => esc_html__('Gallery Images', 'softro-core'),
        ]);

        $image_defaults = [
            '1' => 'images/exp-img-1.png',
            '2' => 'images/exp-img-2.png',
            '3' => 'images/exp-img-3.png',
            '4' => 'images/exp-img-4.png',
        ];

        foreach ($image_defaults as $index => $fallback) {
            $this->add_control('gc_search_exp_image_' . $index, [
                'label'       => sprintf(esc_html__('Image %s', 'softro-core'), $index),
                'type'        => Controls_Manager::MEDIA,
                'media_types' => ['image'],
                'default'     => ['url' => $this->get_theme_img_url($fallback)],
            ]);

            $this->add_control('gc_search_exp_image_' . $index . '_alt', [
                'label'       => sprintf(esc_html__('Image %s Alt Text', 'softro-core'), $index),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('img', 'softro-core'),
                'label_block' => true,
            ]);
        }

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_search_exp_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_exp_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_exp_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .exp-section-2',
        ]);

        $this->add_responsive_control('gc_search_exp_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-section-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-section-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_shapes', [
            'label' => esc_html__('Shape Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_exp_shape_1_width', [
            'label'      => esc_html__('Shape 1 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 20, 'max' => 600]],
            'selectors'  => ['{{WRAPPER}} .exp-section-2 .shapes .shape.shape-1 img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_shape_2_opacity', [
            'label'     => esc_html__('Shape 2 Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .exp-section-2 .shapes .shape.shape-2 img' => 'opacity: {{SIZE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_left', [
            'label' => esc_html__('Left Content', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_exp_left_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_left_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_exp_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .section-heading .sub-heading' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_exp_eyebrow_typography',
            'selector' => '{{WRAPPER}} .exp-content-2 .section-heading .sub-heading',
        ]);

        $this->add_responsive_control('gc_search_exp_heading_margin', [
            'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_exp_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .section-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_exp_title_typography',
            'selector' => '{{WRAPPER}} .exp-content-2 .section-heading .section-title',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_exp_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 > p.fade-top' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_exp_desc_typography',
            'selector' => '{{WRAPPER}} .exp-content-2 > p.fade-top',
        ]);

        $this->add_responsive_control('gc_search_exp_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 > p.fade-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_skills_wrap', [
            'label' => esc_html__('Skills List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_exp_skills_margin', [
            'label'      => esc_html__('List Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .skills-items' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_skill_item_spacing', [
            'label'      => esc_html__('Item Bottom Spacing', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_skill_title', [
            'label' => esc_html__('Skill Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_exp_skill_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_exp_skill_title_typography',
            'selector' => '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title',
        ]);

        $this->add_responsive_control('gc_search_exp_skill_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_exp_skill_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_exp_skill_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .title img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_progress', [
            'label' => esc_html__('Progress Bar', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_exp_progress_height', [
            'label'      => esc_html__('Track Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 2, 'max' => 30]],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_exp_progress_bg', [
            'label'     => esc_html__('Track Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_exp_progress_bar_bg', [
            'label'     => esc_html__('Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress .progress-bar' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_progress_bar_radius', [
            'label'      => esc_html__('Bar Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress .progress-bar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_exp_progress_label_color', [
            'label'     => esc_html__('Percent Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress .progress-bar span' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_exp_progress_label_typography',
            'selector' => '{{WRAPPER}} .exp-content-2 .skills-items .skills-item .progress .progress-bar span',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_exp_style_gallery_wrap', [
            'label' => esc_html__('Gallery Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_exp_gallery_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 200, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .exp-img-wrap' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_gallery_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 200, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .exp-img-wrap' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_exp_gallery_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .exp-img-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        foreach (['1', '2', '3', '4'] as $img_index) {
            $this->start_controls_section('gc_search_exp_style_image_' . $img_index, [
                'label' => sprintf(esc_html__('Gallery Image %s', 'softro-core'), $img_index),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]);

            $this->add_responsive_control('gc_search_exp_image_' . $img_index . '_width', [
                'label'      => esc_html__('Max Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 80, 'max' => 400]],
                'selectors'  => ['{{WRAPPER}} .exp-img-wrap .exp-img-' . $img_index => 'max-width: {{SIZE}}{{UNIT}};'],
            ]);

            $this->add_responsive_control('gc_search_exp_image_' . $img_index . '_height', [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => ['px' => ['min' => 80, 'max' => 400]],
                'selectors'  => ['{{WRAPPER}} .exp-img-wrap .exp-img-' . $img_index => 'height: {{SIZE}}{{UNIT}};'],
            ]);

            $this->add_responsive_control('gc_search_exp_image_' . $img_index . '_radius', [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => ['{{WRAPPER}} .exp-img-wrap .exp-img-' . $img_index => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]);

            $this->end_controls_section();
        }

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_search_exp_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_search_exp_theme_mode_tabs');

        $this->start_controls_tab('gc_search_exp_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_exp_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .exp-section-2',
        ]);

        $this->add_control('gc_search_exp_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 > p.fade-top' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_dark_skill_title_color', [
            'label'     => esc_html__('Skill Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 .skills-items .skills-item .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_dark_progress_bar_bg', [
            'label'     => esc_html__('Progress Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 .skills-items .skills-item .progress .progress-bar' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_dark_progress_label_color', [
            'label'     => esc_html__('Progress Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.exp-content-2 .skills-items .skills-item .progress .progress-bar span' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_search_exp_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_exp_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .exp-section-2',
        ]);

        $this->add_control('gc_search_exp_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 > p.fade-top' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_light_skill_title_color', [
            'label'     => esc_html__('Skill Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 .skills-items .skills-item .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_light_progress_bar_bg', [
            'label'     => esc_html__('Progress Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 .skills-items .skills-item .progress .progress-bar' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_exp_light_progress_label_color', [
            'label'     => esc_html__('Progress Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.exp-content-2 .skills-items .skills-item .progress .progress-bar span' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_search_exp_reset_elementor_spacing']) || 'yes' !== $settings['gc_search_exp_reset_elementor_spacing']) {
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

    private function get_progress_bar_style($percent, $delay)
    {
        return sprintf(
            'width: %s%%; visibility: visible; animation-duration: 2000ms; animation-delay: %s; animation-name: slideInLeft;',
            esc_attr($percent),
            esc_attr($delay)
        );
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);

        $eyebrow     = $settings['gc_search_exp_eyebrow'] ?? '';
        $title       = $this->get_paragraph_inner_content($settings['gc_search_exp_title'] ?? '');
        $description = $this->get_paragraph_inner_content($settings['gc_search_exp_description'] ?? '');
        $skills      = !empty($settings['gc_search_exp_skills']) ? $settings['gc_search_exp_skills'] : $this->get_default_skills();

        $shape_1 = $this->get_media_url($settings['gc_search_exp_shape_1'] ?? [], 'shapes/exp-shape-1.png');
        $shape_2 = $this->get_media_url($settings['gc_search_exp_shape_2'] ?? [], 'shapes/exp-shape-2.png');

        $image_fallbacks = [
            '1' => 'images/exp-img-1.png',
            '2' => 'images/exp-img-2.png',
            '3' => 'images/exp-img-3.png',
            '4' => 'images/exp-img-4.png',
        ];

        ?>
        <section class="exp-section-2 pt-130 pb-130 overflow-hidden">
            <div class="shapes">
                <?php if ($shape_1) : ?>
                    <div class="shape shape-1"><img src="<?php echo esc_url($shape_1); ?>" alt="<?php echo esc_attr__('shape', 'softro-core'); ?>"></div>
                <?php endif; ?>
                <?php if ($shape_2) : ?>
                    <div class="shape shape-2"><img src="<?php echo esc_url($shape_2); ?>" alt="<?php echo esc_attr__('shape', 'softro-core'); ?>"></div>
                <?php endif; ?>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="exp-content exp-content-2 fade-wrapper">
                            <div class="section-heading mb-30">
                                <?php if ('' !== trim((string) $eyebrow)) : ?>
                                    <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                                <?php endif; ?>
                                <?php if ('' !== $title) : ?>
                                    <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo wp_kses($title, ['br' => []]); ?></h2>
                                <?php endif; ?>
                            </div>
                            <?php if ('' !== $description) : ?>
                                <p class="fade-top"><?php echo wp_kses($description, ['br' => []]); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($skills)) : ?>
                                <ul class="skills-items">
                                    <?php foreach ($skills as $index => $item) : ?>
                                        <?php
                                        $skill_title   = $item['skill_title'] ?? '';
                                        $skill_percent = isset($item['skill_percent']) ? max(0, min(100, (int) $item['skill_percent'])) : 0;
                                        $skill_delay   = $this->get_skill_delay($item, $index);
                                        $has_icon      = !empty($item['skill_icon']['value']) || !empty($item['skill_icon_image']['url']) || !empty($settings['gc_search_exp_default_skill_icon']['value']) || !empty($settings['gc_search_exp_default_skill_icon_image']['url']);
                                        ?>
                                        <li class="skills-item fade-top">
                                            <?php if ('' !== $skill_title || $has_icon) : ?>
                                                <h5 class="title">
                                                    <?php $this->render_skill_icon($item, $settings); ?>
                                                    <?php echo esc_html($skill_title); ?>
                                                </h5>
                                            <?php endif; ?>
                                            <div class="progress">
                                                <div class="progress-bar wow slideInLeft" data-wow-delay="<?php echo esc_attr($skill_delay); ?>" data-wow-duration="2000ms" role="progressbar" style="<?php echo esc_attr($this->get_progress_bar_style($skill_percent, $skill_delay)); ?>">
                                                    <span><?php echo esc_html($skill_percent); ?>%</span>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="exp-img-wrap">
                            <?php foreach ($image_fallbacks as $index => $fallback) : ?>
                                <?php
                                $image_url = $this->get_media_url($settings['gc_search_exp_image_' . $index] ?? [], $fallback);
                                $image_alt = !empty($settings['gc_search_exp_image_' . $index . '_alt']) ? $settings['gc_search_exp_image_' . $index . '_alt'] : esc_attr__('img', 'softro-core');
                                ?>
                                <?php if ($image_url) : ?>
                                    <div class="exp-img-<?php echo esc_attr($index); ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Search_Experience_Section_Widget());
