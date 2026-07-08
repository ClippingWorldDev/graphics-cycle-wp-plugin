<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Search_Service_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_search_service';
    }

    public function get_title()
    {
        return esc_html__('GC Search Service', 'softro-core');
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

    private function render_inline_icon($icon_settings, $icon_image, $default_icon_settings = [], $default_icon_image = [])
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($icon_image, '');

        if (!$icon_url && !empty($default_icon_image)) {
            $icon_url = $this->get_media_url($default_icon_image, '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($default_icon_settings['value'])) {
            $this->render_icon($default_icon_settings, ['aria-hidden' => 'true']);
        }
    }

    private function get_default_services()
    {
        return [
            [
                'service_number'      => '01',
                'service_title'       => esc_html__('Content & Creative', 'softro-core'),
                'service_link'        => ['url' => '#'],
                'service_description' => esc_html__('We immerse ourselves in your issues and we put our knowledge and expertise at your service', 'softro-core'),
                'more_text'           => esc_html__('Learn More', 'softro-core'),
                'more_link'           => ['url' => '#'],
                'service_image'       => ['url' => $this->get_theme_img_url('new-update-3/service-16-img-2.png')],
            ],
            [
                'service_number'      => '02',
                'service_title'       => esc_html__('Social Media Management', 'softro-core'),
                'service_link'        => ['url' => '#'],
                'service_description' => esc_html__('We immerse ourselves in your issues and we put our knowledge and expertise at your service', 'softro-core'),
                'more_text'           => esc_html__('Learn More', 'softro-core'),
                'more_link'           => ['url' => '#'],
                'service_image'       => ['url' => $this->get_theme_img_url('new-update-3/service-16-img-1.png')],
            ],
            [
                'service_number'      => '03',
                'service_title'       => esc_html__('Social Media Advertising', 'softro-core'),
                'service_link'        => ['url' => '#'],
                'service_description' => esc_html__('We immerse ourselves in your issues and we put our knowledge and expertise at your service', 'softro-core'),
                'more_text'           => esc_html__('Learn More', 'softro-core'),
                'more_link'           => ['url' => '#'],
                'service_image'       => ['url' => $this->get_theme_img_url('new-update-3/service-16-img-3.png')],
            ],
            [
                'service_number'      => '04',
                'service_title'       => esc_html__('Track Growth & Analytics', 'softro-core'),
                'service_link'        => ['url' => '#'],
                'service_description' => esc_html__('We immerse ourselves in your issues and we put our knowledge and expertise at your service', 'softro-core'),
                'more_text'           => esc_html__('Learn More', 'softro-core'),
                'more_link'           => ['url' => '#'],
                'service_image'       => ['url' => $this->get_theme_img_url('new-update-3/service-16-img-4.png')],
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
        $this->start_controls_section('gc_search_svc_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $shape_defaults = [
            '1' => 'new-update-3/service-16-shape-1.png',
            '2' => 'new-update-3/service-16-shape-2.png',
            '3' => 'new-update-3/service-16-shape-3.png',
            '4' => 'new-update-3/service-16-shape-4.png',
        ];

        foreach ($shape_defaults as $index => $fallback) {
            $this->add_control('gc_search_svc_shape_' . $index, [
                'label'       => sprintf(esc_html__('Shape %s Image', 'softro-core'), $index),
                'type'        => Controls_Manager::MEDIA,
                'media_types' => ['image'],
                'default'     => ['url' => $this->get_theme_img_url($fallback)],
            ]);
        }

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_info_section', [
            'label' => esc_html__('Left Panel', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_title', [
            'label'   => esc_html__('Title', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('We Provide the Best', 'softro-core') . '<br>' . esc_html__('Service.', 'softro-core') . '</p>',
        ]);

        $this->add_control('gc_search_svc_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('Great projects where every project starts with a dream and ends with a space that feels like natural heaven!', 'softro-core') . '</p>',
        ]);

        $this->add_control('gc_search_svc_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_svc_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $this->add_control('gc_search_svc_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_search_svc_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_search_svc_side_label', [
            'label'       => esc_html__('Side Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Service', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_defaults_section', [
            'label' => esc_html__('Link Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_default_more_icon', [
            'label' => esc_html__('Default Learn More Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_search_svc_default_more_icon_image', [
            'label'       => esc_html__('Default Learn More Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control('service_number', [
            'label'       => esc_html__('Number', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '01',
            'label_block' => true,
        ]);

        $service_repeater->add_control('service_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Content & Creative', 'softro-core'),
            'label_block' => true,
        ]);

        $service_repeater->add_control('service_link', [
            'label'       => esc_html__('Title Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $service_repeater->add_control('service_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We immerse ourselves in your issues and we put our knowledge and expertise at your service', 'softro-core'),
        ]);

        $service_repeater->add_control('more_text', [
            'label'       => esc_html__('Learn More Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Learn More', 'softro-core'),
            'label_block' => true,
        ]);

        $service_repeater->add_control('more_link', [
            'label'       => esc_html__('Learn More Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $service_repeater->add_control('more_icon', [
            'label' => esc_html__('Learn More Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $service_repeater->add_control('more_icon_image', [
            'label'       => esc_html__('Learn More Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $service_repeater->add_control('service_image', [
            'label'       => esc_html__('Thumbnail Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $service_repeater->add_control('service_image_alt', [
            'label'       => esc_html__('Thumbnail Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_search_svc_services_section', [
            'label' => esc_html__('Service Items', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_services', [
            'label'       => esc_html__('Services', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $service_repeater->get_controls(),
            'default'     => $this->get_default_services(),
            'title_field' => '{{{ service_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_search_svc_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_section', [
            'label' => esc_html__('Section Wrapper', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_wrapper_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .service-section-16__wrapper',
        ]);

        $this->add_responsive_control('gc_search_svc_wrapper_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_wrapper_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_shapes', [
            'label' => esc_html__('Shape Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_shapes_opacity', [
            'label'     => esc_html__('Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .service-section-16__wrapper .shapes img' => 'opacity: {{SIZE}};'],
        ]);

        foreach (['1', '2', '3', '4'] as $shape_index) {
            $this->add_responsive_control('gc_search_svc_shape_' . $shape_index . '_width', [
                'label'      => sprintf(esc_html__('Shape %s Width', 'softro-core'), $shape_index),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => ['px' => ['min' => 20, 'max' => 600]],
                'selectors'  => ['{{WRAPPER}} .service-section-16__wrapper .shapes .shape-' . $shape_index => 'width: {{SIZE}}{{UNIT}};'],
            ]);
        }

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_info', [
            'label' => esc_html__('Left Panel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_info_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_info_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__info .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_title_typography',
            'selector' => '{{WRAPPER}} .service-section-16__info .title',
        ]);

        $this->add_responsive_control('gc_search_svc_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_info_desc', [
            'label' => esc_html__('Left Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_info_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__info .decs' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_info_desc_typography',
            'selector' => '{{WRAPPER}} .service-section-16__info .decs',
        ]);

        $this->add_responsive_control('gc_search_svc_info_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__info .decs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_side_label', [
            'label' => esc_html__('Side Label', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_side_label_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__info .sercice h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_side_label_typography',
            'selector' => '{{WRAPPER}} .service-section-16__info .sercice h3',
        ]);

        $this->add_responsive_control('gc_search_svc_side_label_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__info .sercice' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_button', [
            'label' => esc_html__('Primary Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_button_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_button_typography',
            'selector' => '{{WRAPPER}} .service-section-16__btn .rr-primary-btn',
        ]);

        $this->add_responsive_control('gc_search_svc_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__btn .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_button_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_button_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__btn .rr-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_svc_button_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .service-section-16__btn .rr-primary-btn i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .service-section-16__btn .rr-primary-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_svc_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 60]],
            'selectors'  => [
                '{{WRAPPER}} .service-section-16__btn .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .service-section-16__btn .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .service-section-16__btn .rr-primary-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_card', [
            'label' => esc_html__('Service Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_card_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__item' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_card_spacing', [
            'label'      => esc_html__('Item Bottom Spacing', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .service-section-16__item.mb-30' => 'margin-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_card_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_number', [
            'label' => esc_html__('Service Number', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_number_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__content .number span' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_number_typography',
            'selector' => '{{WRAPPER}} .service-section-16__content .number span',
        ]);

        $this->add_responsive_control('gc_search_svc_number_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__content .number' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_service_title', [
            'label' => esc_html__('Service Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_service_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__content .name a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_service_title_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__content .name a:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_service_title_typography',
            'selector' => '{{WRAPPER}} .service-section-16__content .name',
        ]);

        $this->add_responsive_control('gc_search_svc_service_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__content .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_service_desc', [
            'label' => esc_html__('Service Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_service_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__content .decs' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_service_desc_typography',
            'selector' => '{{WRAPPER}} .service-section-16__content .decs',
        ]);

        $this->add_responsive_control('gc_search_svc_service_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__content .decs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_more_link', [
            'label' => esc_html__('Learn More Link', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_more_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__more a' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_more_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__more a:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_more_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-section-16__more a' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_more_typography',
            'selector' => '{{WRAPPER}} .service-section-16__more a',
        ]);

        $this->add_control('gc_search_svc_more_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .service-section-16__more a i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .service-section-16__more a svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_svc_more_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .service-section-16__more a i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .service-section-16__more a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .service-section-16__more a img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_style_thumb', [
            'label' => esc_html__('Service Thumbnail', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_thumb_max_width', [
            'label'      => esc_html__('Wrap Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 80, 'max' => 500]],
            'selectors'  => ['{{WRAPPER}} .service-section-16__thumb' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_thumb_img_height', [
            'label'      => esc_html__('Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 80, 'max' => 500]],
            'selectors'  => ['{{WRAPPER}} .service-section-16__thumb img' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_thumb_img_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_thumb_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-section-16__thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_search_svc_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_search_svc_theme_mode_tabs');

        $this->start_controls_tab('gc_search_svc_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_dark_wrapper_bg',
            'label'    => esc_html__('Wrapper Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .service-section-16__wrapper',
        ]);

        $this->add_control('gc_search_svc_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_info_desc_color', [
            'label'     => esc_html__('Left Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__info .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_side_label_color', [
            'label'     => esc_html__('Side Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__info .sercice h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_number_color', [
            'label'     => esc_html__('Service Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__content .number span' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_service_title_color', [
            'label'     => esc_html__('Service Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__content .name a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_service_title_hover_color', [
            'label'     => esc_html__('Service Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__content .name a:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_service_desc_color', [
            'label'     => esc_html__('Service Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__content .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_more_color', [
            'label'     => esc_html__('Learn More Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__more a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_more_hover_color', [
            'label'     => esc_html__('Learn More Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__more a:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_more_border_color', [
            'label'     => esc_html__('Learn More Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__more a' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_dark_shapes_opacity', [
            'label'     => esc_html__('Shape Images Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-section-16__wrapper .shapes img' => 'opacity: {{SIZE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_search_svc_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_light_wrapper_bg',
            'label'    => esc_html__('Wrapper Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .service-section-16__wrapper',
        ]);

        $this->add_control('gc_search_svc_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_info_desc_color', [
            'label'     => esc_html__('Left Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__info .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_side_label_color', [
            'label'     => esc_html__('Side Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__info .sercice h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_card_border_color', [
            'label'     => esc_html__('Card Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__item' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_number_color', [
            'label'     => esc_html__('Service Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__content .number span' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_service_title_color', [
            'label'     => esc_html__('Service Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__content .name a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_service_title_hover_color', [
            'label'     => esc_html__('Service Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__content .name a:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_service_desc_color', [
            'label'     => esc_html__('Service Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__content .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_more_color', [
            'label'     => esc_html__('Learn More Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__more a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_more_hover_color', [
            'label'     => esc_html__('Learn More Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__more a:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_more_border_color', [
            'label'     => esc_html__('Learn More Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__more a' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_light_shapes_opacity', [
            'label'     => esc_html__('Shape Images Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-section-16__wrapper .shapes img' => 'opacity: {{SIZE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_search_svc_reset_elementor_spacing']) || 'yes' !== $settings['gc_search_svc_reset_elementor_spacing']) {
            return;
        }

        echo '<style>.elementor-element-' . esc_attr($this->get_id()) . '{--padding-top:0;--padding-right:0;--padding-bottom:0;--padding-left:0;--margin-top:0;--margin-right:0;--margin-bottom:0;--margin-left:0;padding:0;margin:0;}</style>';
    }

    private function get_shape_url($settings, $index, $fallback)
    {
        return $this->get_media_url($settings['gc_search_svc_shape_' . $index] ?? [], $fallback);
    }

    private function get_service_image_url($item, $index)
    {
        $fallbacks = [
            0 => 'new-update-3/service-16-img-2.png',
            1 => 'new-update-3/service-16-img-1.png',
            2 => 'new-update-3/service-16-img-3.png',
            3 => 'new-update-3/service-16-img-4.png',
        ];

        $fallback = $fallbacks[$index] ?? '';

        return $this->get_media_url($item['service_image'] ?? [], $fallback);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);

        $shape_fallbacks = [
            '1' => 'new-update-3/service-16-shape-1.png',
            '2' => 'new-update-3/service-16-shape-2.png',
            '3' => 'new-update-3/service-16-shape-3.png',
            '4' => 'new-update-3/service-16-shape-4.png',
        ];

        $services = !empty($settings['gc_search_svc_services']) ? $settings['gc_search_svc_services'] : $this->get_default_services();
        $services_count = count($services);

        $title_content = $this->get_paragraph_inner_content($settings['gc_search_svc_title'] ?? '');
        $info_desc     = $this->get_paragraph_inner_content($settings['gc_search_svc_description'] ?? '');
        $side_label    = !empty($settings['gc_search_svc_side_label']) ? $settings['gc_search_svc_side_label'] : esc_html__('Service', 'softro-core');
        $button_text   = !empty($settings['gc_search_svc_button_text']) ? $settings['gc_search_svc_button_text'] : esc_html__('Contact Us', 'softro-core');
        $button_link   = $settings['gc_search_svc_button_link'] ?? ['url' => '#'];

        ?>
        <section class="service-section-16__area rr-panel-pin-area gc-social-services">
            <div class="service-section-16__wrapper">
                <div class="shapes">
                    <?php foreach ($shape_fallbacks as $shape_key => $shape_fallback) : ?>
                        <?php $shape_url = $this->get_shape_url($settings, $shape_key, $shape_fallback); ?>
                        <?php if ($shape_url) : ?>
                            <img class="shape-<?php echo esc_attr($shape_key); ?>" src="<?php echo esc_url($shape_url); ?>" alt="<?php echo esc_attr__('img not found', 'softro-core'); ?>">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="container container-16">
                    <div class="row">
                        <div class="col-xl-6 col-lg-5">
                            <div class="service-section-16__info rr-panel-pin">
                                <?php if ('' !== $title_content) : ?>
                                    <h2 class="title"><?php echo wp_kses($title_content, ['br' => []]); ?></h2>
                                <?php endif; ?>
                                <?php if ('' !== $info_desc) : ?>
                                    <p class="decs"><?php echo wp_kses($info_desc, ['br' => []]); ?></p>
                                <?php endif; ?>
                                <div class="service-section-16__btn">
                                    <a<?php echo $this->get_link_attributes($button_link); ?> class="rr-primary-btn">
                                        <?php
                                        $this->render_inline_icon(
                                            $settings['gc_search_svc_button_icon'] ?? [],
                                            $settings['gc_search_svc_button_icon_image'] ?? []
                                        );
                                        echo esc_html($button_text);
                                        ?>
                                    </a>
                                </div>
                                <div class="sercice">
                                    <h3><?php echo esc_html($side_label); ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-7">
                            <div class="service-section-16__wrap">
                                <?php foreach ($services as $index => $item) : ?>
                                    <?php
                                    $item_classes = 'service-section-16__item rr-panel-pin';

                                    if ($index < ($services_count - 1)) {
                                        $item_classes .= ' mb-30';
                                    }

                                    $service_number = !empty($item['service_number']) ? $item['service_number'] : sprintf('%02d', $index + 1);
                                    $service_title  = !empty($item['service_title']) ? $item['service_title'] : '';
                                    $service_link   = $item['service_link'] ?? ['url' => '#'];
                                    $service_desc   = $this->get_paragraph_inner_content($item['service_description'] ?? '');
                                    $more_text      = !empty($item['more_text']) ? $item['more_text'] : esc_html__('Learn More', 'softro-core');
                                    $more_link      = $item['more_link'] ?? ['url' => '#'];
                                    $thumb_url      = $this->get_service_image_url($item, $index);
                                    $thumb_alt      = !empty($item['service_image_alt']) ? $item['service_image_alt'] : esc_attr__('img not found', 'softro-core');
                                    ?>
                                    <div class="<?php echo esc_attr($item_classes); ?>">
                                        <div class="service-section-16__content">
                                            <div class="number">
                                                <span><?php echo esc_html($service_number); ?></span>
                                            </div>
                                            <?php if ('' !== $service_title) : ?>
                                                <h3 class="name"><a<?php echo $this->get_link_attributes($service_link); ?>><?php echo esc_html($service_title); ?></a></h3>
                                            <?php endif; ?>
                                            <?php if ('' !== $service_desc) : ?>
                                                <p class="decs"><?php echo wp_kses($service_desc, ['br' => []]); ?></p>
                                            <?php endif; ?>
                                            <div class="service-section-16__more">
                                                <a<?php echo $this->get_link_attributes($more_link); ?>>
                                                    <?php
                                                    $this->render_inline_icon(
                                                        $item['more_icon'] ?? [],
                                                        $item['more_icon_image'] ?? [],
                                                        $settings['gc_search_svc_default_more_icon'] ?? [],
                                                        $settings['gc_search_svc_default_more_icon_image'] ?? []
                                                    );
                                                    echo esc_html($more_text);
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                        <?php if ($thumb_url) : ?>
                                            <div class="service-section-16__thumb">
                                                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($thumb_alt); ?>">
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Search_Service_Widget());
