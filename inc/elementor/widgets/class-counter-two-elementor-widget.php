<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Counter_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_counter_two';
    }

    public function get_title()
    {
        return esc_html__('GC Counter Two', 'softro-core');
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

    private function render_counter_icon(array $item, array $settings)
    {
        if (!empty($item['counter_icon']['value'])) {
            $this->render_icon($item['counter_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['counter_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_counter_two_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="' . esc_attr__('icon', 'softro-core') . '">';
            return;
        }

        if (!empty($settings['gc_counter_two_default_icon']['value'])) {
            $this->render_icon($settings['gc_counter_two_default_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_default_counters()
    {
        return [
            [
                'counter_number'     => '12',
                'counter_suffix'     => '',
                'counter_label'      => esc_html__('Years of Experience', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-5.png')],
            ],
            [
                'counter_number'     => '1.5',
                'counter_suffix'     => 'K',
                'counter_label'      => esc_html__('Awesome clients', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-6.png')],
            ],
            [
                'counter_number'     => '826',
                'counter_suffix'     => '',
                'counter_label'      => esc_html__('Make Cup Of Coffee', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-7.png')],
            ],
            [
                'counter_number'     => '8',
                'counter_suffix'     => 'K',
                'counter_label'      => esc_html__('Created projects', 'softro-core'),
                'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-8.png')],
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
        $this->start_controls_section('gc_counter_two_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_counter_two_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Boost Your Ranking & AI Visibility with Us', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_counter_two_description_1', [
            'label'   => esc_html__('Description Paragraph 1', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('Lorem ipsum dolor sit amet consectetur adipiscing elit, molestie at dis mollis eros euismod himenaeos dignissim, tortor congue magnis mi nam purus.', 'softro-core') . '</p>',
        ]);

        $this->add_control('gc_counter_two_description_2', [
            'label'   => esc_html__('Description Paragraph 2', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('Consequat feugiat urna fringilla fames curae netus dis sociosqu, accumsan eu per imperdiet nisl habitasse in. Vulputate enim facilisi vel nam mollis magna curae neque, vestibulum eu odio suscipit viverra ante egestas', 'softro-core') . '</p>',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_button_section', [
            'label' => esc_html__('Button', 'softro-core'),
        ]);

        $this->add_control('gc_counter_two_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get Started Now', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_counter_two_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $this->add_control('gc_counter_two_button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-sharp fa-regular fa-arrow-right',
                'library' => 'fa-sharp-regular',
            ],
        ]);

        $this->add_control('gc_counter_two_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_defaults_section', [
            'label' => esc_html__('Counter Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_counter_two_default_icon', [
            'label' => esc_html__('Default Counter Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_counter_two_default_icon_image', [
            'label'       => esc_html__('Default Counter Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $counter_repeater = new Repeater();

        $counter_repeater->add_control('counter_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $counter_repeater->add_control('counter_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $counter_repeater->add_control('counter_number', [
            'label'       => esc_html__('Number', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '12',
            'label_block' => true,
            'description' => esc_html__('Supports decimals (e.g. 1.5). Used in odometer data-count.', 'softro-core'),
        ]);

        $counter_repeater->add_control('counter_suffix', [
            'label'       => esc_html__('Suffix', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'label_block' => true,
            'description' => esc_html__('Displayed after the odometer (e.g. K, +, %).', 'softro-core'),
        ]);

        $counter_repeater->add_control('counter_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Years of Experience', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_counter_two_counters_section', [
            'label' => esc_html__('Counter Items', 'softro-core'),
        ]);

        $this->add_control('gc_counter_two_counters', [
            'label'       => esc_html__('Counters', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $counter_repeater->get_controls(),
            'default'     => $this->get_default_counters(),
            'title_field' => '{{{ counter_label }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_media_section', [
            'label' => esc_html__('Side Image & Shape', 'softro-core'),
        ]);

        $this->add_control('gc_counter_two_side_image', [
            'label'       => esc_html__('Side Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('images/counter-img.png')],
        ]);

        $this->add_control('gc_counter_two_side_image_alt', [
            'label'       => esc_html__('Side Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('img', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_counter_two_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_counter_two_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_counter_two_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .counter-section-7',
        ]);

        $this->add_responsive_control('gc_counter_two_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-section-7' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-section-7' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_section_shape_size', [
            'label'      => esc_html__('Shape Background Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 50, 'max' => 1200]],
            'selectors'  => ['{{WRAPPER}} .counter-section-7' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_left', [
            'label' => esc_html__('Left Content', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_counter_two_left_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-content-left-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_left_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-content-left-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_left_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 200, 'max' => 900]],
            'selectors'  => ['{{WRAPPER}} .counter-content-left-2' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-content-left-2 .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_counter_two_title_typography',
            'selector' => '{{WRAPPER}} .counter-content-left-2 .section-title',
        ]);

        $this->add_responsive_control('gc_counter_two_heading_margin', [
            'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-content-left-2 .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_desc', [
            'label' => esc_html__('Descriptions', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-content-left-2 p' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_counter_two_desc_typography',
            'selector' => '{{WRAPPER}} .counter-content-left-2 p',
        ]);

        $this->add_responsive_control('gc_counter_two_desc_margin', [
            'label'      => esc_html__('Paragraph Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-content-left-2 p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-btn-box .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_counter_two_button_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-btn-box .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_counter_two_button_typography',
            'selector' => '{{WRAPPER}} .counter-btn-box .rr-primary-btn',
        ]);

        $this->add_responsive_control('gc_counter_two_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-btn-box .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_button_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-btn-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_button_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .counter-btn-box .rr-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_counter_two_button_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .counter-btn-box .rr-primary-btn i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .counter-btn-box .rr-primary-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_counter_two_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 60]],
            'selectors'  => [
                '{{WRAPPER}} .counter-btn-box .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .counter-btn-box .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .counter-btn-box .rr-primary-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_box_wrapper', [
            'label' => esc_html__('Counter Box Wrapper', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_box_wrapper_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_counter_two_box_wrapper_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_box_wrapper_padding', [
            'label'      => esc_html__('Inner Wrap Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_box_wrapper_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_box_wrapper_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 200, 'max' => 1000]],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_counter_box', [
            'label' => esc_html__('Counter Box', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_box_divider_color', [
            'label'     => esc_html__('Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box:not(:last-of-type)' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_box_spacing', [
            'label'      => esc_html__('Item Bottom Spacing', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box:not(:last-of-type)' => 'margin-bottom: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_box_gap', [
            'label'      => esc_html__('Icon / Content Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box' => 'grid-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_counter_icon', [
            'label' => esc_html__('Counter Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_icon_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_counter_two_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_counter_two_icon_size', [
            'label'      => esc_html__('Wrap Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 40, 'max' => 150]],
            'selectors'  => [
                '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box'     => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr;',
            ],
        ]);

        $this->add_responsive_control('gc_counter_two_icon_img_size', [
            'label'      => esc_html__('Image Max Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon img' => 'max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_icon_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_counter_number', [
            'label' => esc_html__('Counter Number', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_number_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_counter_two_number_typography',
            'selector' => '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .content .title',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_counter_label', [
            'label' => esc_html__('Counter Label', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_counter_two_label_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .content p' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_counter_two_label_typography',
            'selector' => '{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .content p',
        ]);

        $this->add_responsive_control('gc_counter_two_label_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .counter-box-wrapper .counter-box-wrap .counter-box .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_counter_two_style_side_image', [
            'label' => esc_html__('Side Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_counter_two_side_img_height', [
            'label'      => esc_html__('Wrap Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 100, 'max' => 700]],
            'selectors'  => ['{{WRAPPER}} .content-box-img' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_side_img_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .content-box-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_counter_two_side_img_object_position', [
            'label'     => esc_html__('Object Position', 'softro-core'),
            'type'      => Controls_Manager::SELECT,
            'default'   => 'center center',
            'options'   => [
                'center center' => esc_html__('Center Center', 'softro-core'),
                'center top'    => esc_html__('Center Top', 'softro-core'),
                'center bottom' => esc_html__('Center Bottom', 'softro-core'),
                'left center'   => esc_html__('Left Center', 'softro-core'),
                'right center'  => esc_html__('Right Center', 'softro-core'),
            ],
            'selectors' => ['{{WRAPPER}} .content-box-img img' => 'object-position: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_counter_two_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_counter_two_theme_mode_tabs');

        $this->start_controls_tab('gc_counter_two_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_counter_two_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .counter-section-7',
        ]);

        $this->add_control('gc_counter_two_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-content-left-2 .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-content-left-2 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-btn-box .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-btn-box .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_box_wrapper_bg', [
            'label'     => esc_html__('Counter Wrapper Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-box-wrapper' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_box_divider_color', [
            'label'     => esc_html__('Counter Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-box-wrapper .counter-box-wrap .counter-box:not(:last-of-type)' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_icon_bg', [
            'label'     => esc_html__('Counter Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-box-wrapper .counter-box-wrap .counter-box .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_icon_color', [
            'label'     => esc_html__('Counter Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.counter-box-wrapper .counter-box-wrap .counter-box .icon i'   => 'color: {{VALUE}};',
                '.counter-box-wrapper .counter-box-wrap .counter-box .icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_counter_two_dark_number_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-box-wrapper .counter-box-wrap .counter-box .content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_dark_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.counter-box-wrapper .counter-box-wrap .counter-box .content p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_counter_two_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_counter_two_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .counter-section-7',
        ]);

        $this->add_control('gc_counter_two_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-content-left-2 .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-content-left-2 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-btn-box .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-btn-box .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_box_wrapper_bg', [
            'label'     => esc_html__('Counter Wrapper Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_box_wrapper_border', [
            'label'     => esc_html__('Counter Wrapper Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_box_divider_color', [
            'label'     => esc_html__('Counter Divider Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper .counter-box-wrap .counter-box:not(:last-of-type)' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_icon_bg', [
            'label'     => esc_html__('Counter Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper .counter-box-wrap .counter-box .icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_icon_color', [
            'label'     => esc_html__('Counter Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.counter-box-wrapper .counter-box-wrap .counter-box .icon i'   => 'color: {{VALUE}};',
                '.counter-box-wrapper .counter-box-wrap .counter-box .icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_counter_two_light_number_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper .counter-box-wrap .counter-box .content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_counter_two_light_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.counter-box-wrapper .counter-box-wrap .counter-box .content p' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_counter_two_reset_elementor_spacing']) || 'yes' !== $settings['gc_counter_two_reset_elementor_spacing']) {
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

    private function render_section_shape_background($settings)
    {
        $shape_url = $this->get_media_url($settings['gc_counter_two_section_shape'] ?? [], '');

        if (!$shape_url) {
            return;
        }
        ?>
        <style>
            .elementor-element-<?php echo esc_attr($this->get_id()); ?> .counter-section-7 {
                background-image: url('<?php echo esc_url($shape_url); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
        <?php
    }

    private function render_button_icon(array $settings)
    {
        if (!empty($settings['gc_counter_two_button_icon']['value'])) {
            $this->render_icon($settings['gc_counter_two_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_counter_two_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_section_shape_background($settings);

        $title         = $settings['gc_counter_two_title'] ?? '';
        $description_1 = $this->get_paragraph_inner_content($settings['gc_counter_two_description_1'] ?? '');
        $description_2 = $this->get_paragraph_inner_content($settings['gc_counter_two_description_2'] ?? '');
        $button_text   = !empty($settings['gc_counter_two_button_text']) ? $settings['gc_counter_two_button_text'] : esc_html__('Get Started Now', 'softro-core');
        $button_link   = $settings['gc_counter_two_button_link'] ?? ['url' => '#'];
        $counters      = !empty($settings['gc_counter_two_counters']) ? $settings['gc_counter_two_counters'] : $this->get_default_counters();
        $side_image    = $this->get_media_url($settings['gc_counter_two_side_image'] ?? [], 'images/counter-img.png');
        $side_alt      = !empty($settings['gc_counter_two_side_image_alt']) ? $settings['gc_counter_two_side_image_alt'] : esc_attr__('img', 'softro-core');

        ?>
        <section class="counter-section-7 pt-130 pb-130">
            <div class="container">
                <div class="row align-items-center fade-wrapper">
                    <div class="col-xl-6 col-lg-12">
                        <div class="counter-content-left-2">
                            <?php if ('' !== trim((string) $title)) : ?>
                                <div class="section-heading mb-40">
                                    <h2 class="section-title" data-text-animation data-split="word" data-duration="1">
                                        <?php echo esc_html($title); ?>
                                    </h2>
                                </div>
                            <?php endif; ?>
                            <?php if ('' !== $description_1) : ?>
                                <p class="fade-top"><?php echo wp_kses($description_1, ['br' => []]); ?></p>
                            <?php endif; ?>
                            <?php if ('' !== $description_2) : ?>
                                <p class="fade-top"><?php echo wp_kses($description_2, ['br' => []]); ?></p>
                            <?php endif; ?>
                            <div class="counter-btn-box mt-40 fade-top">
                                <a<?php echo $this->get_link_attributes($button_link); ?> class="rr-primary-btn">
                                    <?php echo esc_html($button_text); ?>
                                    <?php $this->render_button_icon($settings); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12">
                        <div class="counter-box-wrapper fade-top">
                            <div class="counter-box-wrap">
                                <?php foreach ($counters as $item) : ?>
                                    <?php
                                    $number = isset($item['counter_number']) ? $item['counter_number'] : '0';
                                    $suffix = $item['counter_suffix'] ?? '';
                                    $label  = $item['counter_label'] ?? '';
                                    ?>
                                    <div class="counter-box">
                                        <div class="icon"><?php $this->render_counter_icon($item, $settings); ?></div>
                                        <div class="content">
                                            <h3 class="title">
                                                <span class="odometer" data-count="<?php echo esc_attr($number); ?>">0</span><?php echo esc_html($suffix); ?>
                                            </h3>
                                            <?php if ('' !== $label) : ?>
                                                <p><?php echo esc_html($label); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <?php if ($side_image) : ?>
                                <div class="content-box-img">
                                    <img src="<?php echo esc_url($side_image); ?>" alt="<?php echo esc_attr($side_alt); ?>">
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

Plugin::instance()->widgets_manager->register(new Softro_Counter_Two_Widget());
