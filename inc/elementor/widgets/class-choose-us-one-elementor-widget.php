<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Choose_Us_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_choose_us_one';
    }

    public function get_title()
    {
        return esc_html__('GC Choose Us One', 'softro-core');
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

    private function render_icon_or_image($icon_settings, $image_settings = [])
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($image_settings, '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_choose_us_one_shape_section',
            [
                'label' => esc_html__('Background Shape', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_shape_image',
            [
                'label'   => esc_html__('Shape Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('shapes/choose-bg-shape.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_shape_alt',
            [
                'label'       => esc_html__('Shape Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('bg-shape', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_image_section',
            [
                'label' => esc_html__('Feature Image', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_main_image',
            [
                'label'   => esc_html__('Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('images/choose-us-img.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_main_image_alt',
            [
                'label'       => esc_html__('Image Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('img', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_content_section',
            [
                'label' => esc_html__('Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Why Choose Graphics Cycle', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_choose_us_one_default_icon',
            [
                'label'   => esc_html__('Default List Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-light fa-check',
                    'library' => 'fa-light',
                ],
            ]
        );

        $this->end_controls_section();

        $list_repeater = new Repeater();

        $list_repeater->add_control(
            'item_text',
            [
                'label'       => esc_html__('List Text', 'softro-core'),
                'type'        => Controls_Manager::WYSIWYG,
                'default'     => esc_html__('Dedicated Editors - Same editor learns your brand style over time, so quality stays consistent.', 'softro-core'),
            ]
        );

        $list_repeater->add_control(
            'item_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-light',
                ],
            ]
        );

        $list_repeater->add_control(
            'item_icon_image',
            [
                'label'       => esc_html__('Custom Icon Image', 'softro-core'),
                'description' => esc_html__('Upload an image icon when you do not want to use the icon library.', 'softro-core'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => '',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_choose_us_one_list_section',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_list_items',
            [
                'label'       => esc_html__('List Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $list_repeater->get_controls(),
                'default'     => [
                    ['item_text' => esc_html__('Dedicated Editors - Same editor learns your brand style over time, so quality stays consistent.', 'softro-core')],
                    ['item_text' => esc_html__('Flexible for Any Volume - Five images or five thousand, our workflow scales without delay.', 'softro-core')],
                    ['item_text' => esc_html__('Free Unlimited Revisions - We get it right the first time, and fix it free if you need changes.', 'softro-core')],
                    ['item_text' => esc_html__('Secure File Handling - Your images and brand assets stay protected with secure transfer and storage.', 'softro-core')],
                    ['item_text' => esc_html__('No Outsourcing Layers - You work directly with our team, not a chain of middlemen.', 'softro-core')],
                    ['item_text' => esc_html__('Quality Checked - Every image is reviewed for accuracy before it reaches you.', 'softro-core')],
                ],
                'title_field' => '{{{ item_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_choose_us_one_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_choose_us_one_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_choose_us_one_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .choose-us',
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_shape',
            [
                'label' => esc_html__('Background Shape', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_shape_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us .shape img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_shape_opacity',
            [
                'label'     => esc_html__('Opacity', 'softro-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .choose-us .shape img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_main_image',
            [
                'label' => esc_html__('Feature Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_main_image_width',
            [
                'label'      => esc_html__('Max Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-img' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_main_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-img, {{WRAPPER}} .choose-us-img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_main_image_object_fit',
            [
                'label'   => esc_html__('Object Fit', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''        => esc_html__('Default', 'softro-core'),
                    'cover'   => esc_html__('Cover', 'softro-core'),
                    'contain' => esc_html__('Contain', 'softro-core'),
                    'fill'    => esc_html__('Fill', 'softro-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .choose-us-img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_main_image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_main_image_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_choose_us_one_title_typography',
                'selector' => '{{WRAPPER}} .choose-us-content .section-title',
            ]
        );

        $this->add_control(
            'gc_choose_us_one_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .choose-us-content .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_list',
            [
                'label' => esc_html__('Feature List', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_choose_us_one_list_typography',
                'selector' => '{{WRAPPER}} .choose-us-content .choose-list li',
            ]
        );

        $this->add_control(
            'gc_choose_us_one_list_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .choose-us-content .choose-list li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_gap',
            [
                'label'      => esc_html__('Items Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_column_gap',
            [
                'label'      => esc_html__('Icon / Text Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_choose_us_one_style_list_icon',
            [
                'label' => esc_html__('List Icon', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_icon_width',
            [
                'label'      => esc_html__('Icon Box Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_icon_height',
            [
                'label'      => esc_html__('Icon Box Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_icon_font_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_list_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_choose_us_one_list_icon_bg',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_choose_us_one_list_icon_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .choose-us-content .choose-list li .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_choose_us_one_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_choose_us_one_theme_mode_color_tabs');

        $this->start_controls_tab('gc_choose_us_one_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_choose_us_one_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .choose-us',
            ]
        );

        $this->add_control(
            'gc_choose_us_one_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.choose-us-content .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_dark_list_color',
            [
                'label'     => esc_html__('List Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.choose-us-content .choose-list li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_dark_list_icon_color',
            [
                'label'     => esc_html__('List Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.choose-us-content .choose-list li .icon'     => 'color: {{VALUE}};',
                    '.choose-us-content .choose-list li .icon i'   => 'color: {{VALUE}};',
                    '.choose-us-content .choose-list li .icon svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_dark_list_icon_bg',
            [
                'label'     => esc_html__('List Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.choose-us-content .choose-list li .icon' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_choose_us_one_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_choose_us_one_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .choose-us',
            ]
        );

        $this->add_control(
            'gc_choose_us_one_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.choose-us-content .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_light_list_color',
            [
                'label'     => esc_html__('List Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.choose-us-content .choose-list li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_light_list_icon_color',
            [
                'label'     => esc_html__('List Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.choose-us-content .choose-list li .icon'     => 'color: {{VALUE}};',
                    '.choose-us-content .choose-list li .icon i'   => 'color: {{VALUE}};',
                    '.choose-us-content .choose-list li .icon svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_choose_us_one_light_list_icon_bg',
            [
                'label'     => esc_html__('List Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.choose-us-content .choose-list li .icon' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_choose_us_one_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-top,
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top,
            .elementor-element-<?php echo $widget_id; ?> .choose-us-img.reveal,
            .elementor-element-<?php echo $widget_id; ?> .choose-us-img.reveal img {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    private function render_list_item($item, $default_icon)
    {
        $item_text = $item['item_text'] ?? '';

        if (!$item_text) {
            return;
        }

        $icon_settings = !empty($item['item_icon']['value']) ? ($item['item_icon'] ?? []) : $default_icon;
        ?>
        <li class="fade-top">
            <div class="icon"><?php $this->render_icon_or_image($icon_settings, $item['item_icon_image'] ?? []); ?></div>
            <?php echo $this->get_paragraph_inner_content($item_text); ?>
        </li>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $shape_image = $this->get_media_url($settings['gc_choose_us_one_shape_image'] ?? [], 'shapes/choose-bg-shape.png');
        $shape_alt   = $settings['gc_choose_us_one_shape_alt'] ?? esc_html__('bg-shape', 'softro-core');
        $main_image  = $this->get_media_url($settings['gc_choose_us_one_main_image'] ?? [], 'images/choose-us-img.png');
        $main_alt    = $settings['gc_choose_us_one_main_image_alt'] ?? esc_html__('img', 'softro-core');
        $title       = $settings['gc_choose_us_one_title'] ?? '';
        $list_items  = !empty($settings['gc_choose_us_one_list_items']) ? $settings['gc_choose_us_one_list_items'] : [];
        $default_icon = $settings['gc_choose_us_one_default_icon'] ?? ['value' => 'fa-light fa-check', 'library' => 'fa-light'];
        ?>

        <section class="choose-us pt-130 pb-130 overflow-hidden">
            <div class="shape"><img src="<?php echo esc_url($shape_image); ?>" alt="<?php echo esc_attr($shape_alt); ?>"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="choose-us-img reveal">
                            <?php if ($main_image) : ?>
                                <img src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr($main_alt); ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="choose-us-content fade-wrapper">
                            <?php if ($title) : ?>
                                <div class="section-heading mb-40">
                                    <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title); ?></h2>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($list_items)) : ?>
                                <ul class="choose-list">
                                    <?php foreach ($list_items as $item) :
                                        $this->render_list_item($item, $default_icon);
                                    endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Choose_Us_One_Widget());
