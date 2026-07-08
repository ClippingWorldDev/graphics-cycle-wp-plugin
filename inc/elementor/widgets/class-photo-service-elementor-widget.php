<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Photo_Service_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_photo_service';
    }

    public function get_title()
    {
        return esc_html__('GC Photo Service', 'softro-core');
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

    private function render_button_icon(array $item, array $settings)
    {
        if (!empty($item['button_icon']['value'])) {
            $this->render_icon($item['button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['button_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_photo_svc_default_button_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_photo_svc_default_button_icon']['value'])) {
            $this->render_icon($settings['gc_photo_svc_default_button_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-regular fa-arrow-right" aria-hidden="true"></i>';
        }
    }

    private function get_default_services()
    {
        return [
            [
                'service_before_fallback' => 'new-update/hero-img-1.png',
                'service_after_fallback'  => 'new-update/hero-img-2.png',
                'compare_aria_label'      => esc_html__('Model background removal comparison', 'softro-core'),
                'before_image_alt'        => esc_html__('Model photo before background removal', 'softro-core'),
                'after_image_alt'         => esc_html__('Model photo after background removal', 'softro-core'),
                'service_title'           => esc_html__('Model Background Removal', 'softro-core'),
                'price_amount'            => '$0.85',
                'service_description'     => esc_html__(
                    'Clean cutouts for fashion, editorial, and lifestyle photography. We isolate subjects with precise paths and deliver natural edges ready for any backdrop or marketplace.',
                    'softro-core'
                ),
                'button_text'             => esc_html__('Get a Quote', 'softro-core'),
                'button_link'             => ['url' => '#'],
                'button_icon'             => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
            ],
            [
                'service_before_fallback' => 'new-update/project-img-1.png',
                'service_after_fallback'  => 'new-update/project-img-2.png',
                'compare_aria_label'      => esc_html__('Beauty background removal comparison', 'softro-core'),
                'before_image_alt'        => esc_html__('Beauty product before background removal', 'softro-core'),
                'after_image_alt'         => esc_html__('Beauty product after background removal', 'softro-core'),
                'service_title'           => esc_html__('Beauty Background Removal', 'softro-core'),
                'price_amount'            => '$1.00',
                'service_description'     => esc_html__(
                    'Flawless isolation for cosmetics, skincare, and beauty campaigns. We preserve fine details like bottles, caps, and soft shadows while removing distracting backgrounds.',
                    'softro-core'
                ),
                'button_text'             => esc_html__('Get a Quote', 'softro-core'),
                'button_link'             => ['url' => '#'],
                'button_icon'             => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
            ],
            [
                'service_before_fallback' => 'new-update/hero-img-3.png',
                'service_after_fallback'  => 'new-update/hero-img-4.png',
                'compare_aria_label'      => esc_html__('Product background removal comparison', 'softro-core'),
                'before_image_alt'        => esc_html__('Product photo before background removal', 'softro-core'),
                'after_image_alt'         => esc_html__('Product photo after background removal', 'softro-core'),
                'service_title'           => esc_html__('Product Background Removal', 'softro-core'),
                'price_amount'            => '$0.39',
                'service_description'     => esc_html__(
                    'Sharp, accurate cutouts for ecommerce stores and catalogs. We remove backgrounds cleanly so every listing looks consistent, professional, and ready to convert.',
                    'softro-core'
                ),
                'button_text'             => esc_html__('Get a Quote', 'softro-core'),
                'button_link'             => ['url' => '#'],
                'button_icon'             => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
            ],
            [
                'service_before_fallback' => 'new-update/project-img-3.png',
                'service_after_fallback'  => 'new-update/project-img-4.png',
                'compare_aria_label'      => esc_html__('Jewelry background removal comparison', 'softro-core'),
                'before_image_alt'        => esc_html__('Jewelry photo before background removal', 'softro-core'),
                'after_image_alt'         => esc_html__('Jewelry photo after background removal', 'softro-core'),
                'service_title'           => esc_html__('Jewelry Background Removal', 'softro-core'),
                'price_amount'            => '$1.20',
                'service_description'     => esc_html__(
                    'Specialized cutouts for gold, diamond, platinum, and gemstone jewelry. We handle reflections, fine chains, and intricate edges for high-end product listings.',
                    'softro-core'
                ),
                'button_text'             => esc_html__('Get a Quote', 'softro-core'),
                'button_link'             => ['url' => '#'],
                'button_icon'             => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
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
        $this->start_controls_section('gc_photo_svc_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_photo_svc_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Our Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_photo_svc_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Our Photo Retouching Services', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_photo_svc_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Professional background removal for every category, starting at transparent, competitive prices.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_defaults_section', [
            'label' => esc_html__('Card Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_photo_svc_default_before_tag', [
            'label'       => esc_html__('Before Tag', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Before', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_photo_svc_default_after_tag', [
            'label'       => esc_html__('After Tag', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('After', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_photo_svc_default_price_label', [
            'label'       => esc_html__('Price Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Starting Price', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_photo_svc_default_button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-regular fa-arrow-right',
                'library' => 'fa-regular',
            ],
        ]);

        $this->add_control('gc_photo_svc_default_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control('service_before_image', [
            'label'       => esc_html__('Before Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $service_repeater->add_control('service_after_image', [
            'label'       => esc_html__('After Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $service_repeater->add_control('service_before_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => '',
        ]);

        $service_repeater->add_control('service_after_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => '',
        ]);

        $service_repeater->add_control('compare_aria_label', [
            'label'       => esc_html__('Compare Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Background removal comparison', 'softro-core'),
            'label_block' => true,
        ]);

        $service_repeater->add_control('before_image_alt', [
            'label'       => esc_html__('Before Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $service_repeater->add_control('after_image_alt', [
            'label'       => esc_html__('After Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $service_repeater->add_control('before_tag', [
            'label'       => esc_html__('Before Tag Override', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $service_repeater->add_control('after_tag', [
            'label'       => esc_html__('After Tag Override', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $service_repeater->add_control('service_title', [
            'label'       => esc_html__('Service Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Model Background Removal', 'softro-core'),
            'label_block' => true,
        ]);

        $service_repeater->add_control('price_amount', [
            'label'       => esc_html__('Price Amount', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '$0.85',
            'label_block' => true,
        ]);

        $service_repeater->add_control('price_label', [
            'label'       => esc_html__('Price Label Override', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
        ]);

        $service_repeater->add_control('service_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Service description goes here.', 'softro-core'),
        ]);

        $service_repeater->add_control('button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get a Quote', 'softro-core'),
            'label_block' => true,
        ]);

        $service_repeater->add_control('button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $service_repeater->add_control('button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-regular fa-arrow-right',
                'library' => 'fa-regular',
            ],
        ]);

        $service_repeater->add_control('button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->start_controls_section('gc_photo_svc_services_section', [
            'label' => esc_html__('Service Cards', 'softro-core'),
        ]);

        $this->add_control('gc_photo_svc_services', [
            'label'       => esc_html__('Services', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $service_repeater->get_controls(),
            'default'     => $this->get_default_services(),
            'title_field' => '{{{ service_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_photo_svc_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_photo_svc_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_photo_svc_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-services',
        ]);

        $this->add_responsive_control('gc_photo_svc_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_section_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_heading_wrap', [
            'label' => esc_html__('Section Heading', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_photo_svc_heading_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_heading_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-services-eyebrow',
        ]);

        $this->add_control('gc_photo_svc_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-services-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_eyebrow_line_color', [
            'label'     => esc_html__('Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-services-eyebrow::before' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_eyebrow_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-eyebrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-services-heading .section-title',
        ]);

        $this->add_control('gc_photo_svc_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-services-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_heading_desc', [
            'label' => esc_html__('Heading Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_heading_desc_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-services-desc',
        ]);

        $this->add_control('gc_photo_svc_heading_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-services-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_heading_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_grid', [
            'label' => esc_html__('Services Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_photo_svc_grid_gap_y', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-grid' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_grid_gap_x', [
            'label'      => esc_html__('Column Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-grid' => 'column-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_grid_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-services-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_card', [
            'label' => esc_html__('Service Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_photo_svc_card_border',
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-card',
        ]);

        $this->add_responsive_control('gc_photo_svc_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_photo_svc_card_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_card_line_color', [
            'label'     => esc_html__('Top Line Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-card::after' => 'background: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_compare', [
            'label' => esc_html__('Compare Area', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_photo_svc_compare_height', [
            'label'      => esc_html__('Compare Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'range'      => ['px' => ['min' => 180, 'max' => 700]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-compare-view' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_compare_image_height', [
            'label'      => esc_html__('Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-compare-img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_compare_tags', [
            'label' => esc_html__('Compare Tags', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_compare_tag_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-compare-tag',
        ]);

        $this->add_control('gc_photo_svc_before_tag_color', [
            'label'     => esc_html__('Before Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-compare-tag--before' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_before_tag_bg', [
            'label'     => esc_html__('Before Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-compare-tag--before' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_after_tag_color', [
            'label'     => esc_html__('After Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-compare-tag--after' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_after_tag_bg', [
            'label'     => esc_html__('After Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-compare-tag--after' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_compare_tag_padding', [
            'label'      => esc_html__('Tag Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-compare-tag' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_body', [
            'label' => esc_html__('Service Body', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_body_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-body',
        ]);

        $this->add_responsive_control('gc_photo_svc_body_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_service_title', [
            'label' => esc_html__('Service Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_service_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-title',
        ]);

        $this->add_control('gc_photo_svc_service_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-title' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_price', [
            'label' => esc_html__('Price', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_price_amount_typography',
            'label'    => esc_html__('Amount Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-price .amount',
        ]);

        $this->add_control('gc_photo_svc_price_amount_color', [
            'label'     => esc_html__('Amount Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-price .amount' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_photo_svc_price_label_typography',
            'label'     => esc_html__('Label Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-service-price .label',
            'separator' => 'before',
        ]);

        $this->add_control('gc_photo_svc_price_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-price .label' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_service_desc', [
            'label' => esc_html__('Service Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_service_desc_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-desc',
        ]);

        $this->add_control('gc_photo_svc_service_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_photo_svc_service_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_photo_svc_button_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-btn',
        ]);

        $this->add_control('gc_photo_svc_button_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_button_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-service-btn',
        ]);

        $this->add_responsive_control('gc_photo_svc_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-service-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_photo_svc_button_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_photo_svc_button_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-service-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_photo_svc_style_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_photo_svc_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-service-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-service-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-service-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_photo_svc_button_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-service-btn i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-service-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_photo_svc_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_photo_svc_theme_mode_tabs');

        $this->start_controls_tab('gc_photo_svc_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-services',
        ]);

        $this->add_control('gc_photo_svc_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-services-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-services-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_heading_desc_color', [
            'label'     => esc_html__('Heading Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-services-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_body_bg', [
            'label'     => esc_html__('Body Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-body' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_service_title_color', [
            'label'     => esc_html__('Service Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_price_amount_color', [
            'label'     => esc_html__('Price Amount Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-price .amount' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_price_label_color', [
            'label'     => esc_html__('Price Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-price .label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_service_desc_color', [
            'label'     => esc_html__('Service Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_button_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_button_hover_color', [
            'label'     => esc_html__('Button Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-service-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_before_tag_color', [
            'label'     => esc_html__('Before Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-compare-tag--before' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_before_tag_bg', [
            'label'     => esc_html__('Before Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-compare-tag--before' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_after_tag_color', [
            'label'     => esc_html__('After Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-compare-tag--after' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_dark_after_tag_bg', [
            'label'     => esc_html__('After Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-compare-tag--after' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_photo_svc_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_photo_svc_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-services',
        ]);

        $this->add_control('gc_photo_svc_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-services-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-services-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_heading_desc_color', [
            'label'     => esc_html__('Heading Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-services-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_body_bg', [
            'label'     => esc_html__('Body Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-body' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_service_title_color', [
            'label'     => esc_html__('Service Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_price_amount_color', [
            'label'     => esc_html__('Price Amount Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-price .amount' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_price_label_color', [
            'label'     => esc_html__('Price Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-price .label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_service_desc_color', [
            'label'     => esc_html__('Service Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_button_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_button_hover_color', [
            'label'     => esc_html__('Button Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-service-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_before_tag_color', [
            'label'     => esc_html__('Before Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-compare-tag--before' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_before_tag_bg', [
            'label'     => esc_html__('Before Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-compare-tag--before' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_after_tag_color', [
            'label'     => esc_html__('After Tag Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-compare-tag--after' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_photo_svc_light_after_tag_bg', [
            'label'     => esc_html__('After Tag Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-compare-tag--after' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_photo_svc_reset_elementor_spacing'] ?? 'yes')) {
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
        $section_shape = $this->get_media_url($settings['gc_photo_svc_section_shape'] ?? [], '');

        if (!$section_shape) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-bg-removal-services {
                background-image: url('<?php echo esc_url($section_shape); ?>');
                background-repeat: no-repeat;
            }
        </style>
        <?php
    }

    private function render_service_card(array $item, array $settings)
    {
        $before_url = $this->get_media_url($item['service_before_image'] ?? [], $item['service_before_fallback'] ?? '');
        $after_url  = $this->get_media_url($item['service_after_image'] ?? [], $item['service_after_fallback'] ?? '');

        if (!$before_url || !$after_url) {
            return;
        }

        $before_tag = trim((string) ($item['before_tag'] ?? ''));
        $after_tag  = trim((string) ($item['after_tag'] ?? ''));

        if ('' === $before_tag) {
            $before_tag = $settings['gc_photo_svc_default_before_tag'] ?? esc_html__('Before', 'softro-core');
        }

        if ('' === $after_tag) {
            $after_tag = $settings['gc_photo_svc_default_after_tag'] ?? esc_html__('After', 'softro-core');
        }

        $price_label = trim((string) ($item['price_label'] ?? ''));

        if ('' === $price_label) {
            $price_label = $settings['gc_photo_svc_default_price_label'] ?? esc_html__('Starting Price', 'softro-core');
        }

        $compare_label = trim((string) ($item['compare_aria_label'] ?? ''));

        if ('' === $compare_label) {
            $compare_label = esc_html__('Background removal comparison', 'softro-core');
        }

        $service_title = trim((string) ($item['service_title'] ?? ''));
        $price_amount  = trim((string) ($item['price_amount'] ?? ''));
        $button_text   = trim((string) ($item['button_text'] ?? ''));
        $description   = $item['service_description'] ?? '';

        if ('' === $service_title) {
            return;
        }
        ?>
        <div class="col-lg-6">
            <article class="gc-bg-removal-service-card fade-top" data-bg-removal-service-compare>
                <div class="gc-bg-removal-service-compare" aria-label="<?php echo esc_attr($compare_label); ?>">
                    <div class="gc-bg-removal-service-compare-view">
                        <img class="gc-bg-removal-compare-img gc-bg-removal-compare-img--after"
                            src="<?php echo esc_url($after_url); ?>"
                            alt="<?php echo esc_attr($item['after_image_alt'] ?? ''); ?>">
                        <img class="gc-bg-removal-compare-img gc-bg-removal-compare-img--before"
                            src="<?php echo esc_url($before_url); ?>"
                            alt="<?php echo esc_attr($item['before_image_alt'] ?? ''); ?>">
                        <div class="gc-bg-removal-compare-divider" aria-hidden="true"><span
                                class="gc-bg-removal-compare-knob"></span></div>
                        <span
                            class="gc-bg-removal-compare-tag gc-bg-removal-compare-tag--before"><?php echo esc_html($before_tag); ?></span>
                        <span
                            class="gc-bg-removal-compare-tag gc-bg-removal-compare-tag--after"><?php echo esc_html($after_tag); ?></span>
                    </div>
                </div>
                <div class="gc-bg-removal-service-body">
                    <div class="gc-bg-removal-service-top">
                        <h3 class="gc-bg-removal-service-title"><?php echo esc_html($service_title); ?></h3>
                        <?php if ('' !== $price_amount) : ?>
                            <div class="gc-bg-removal-service-price">
                                <span class="amount"><?php echo esc_html($price_amount); ?></span>
                                <span class="label"><?php echo esc_html($price_label); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                        <p class="gc-bg-removal-service-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                    <?php if ('' !== $button_text) : ?>
                        <a <?php echo $this->get_link_attributes($item['button_link'] ?? []); ?> class="gc-bg-removal-service-btn"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($item, $settings); ?></a>
                    <?php endif; ?>
                </div>
            </article>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $eyebrow     = $settings['gc_photo_svc_eyebrow'] ?? '';
        $title       = $settings['gc_photo_svc_title'] ?? '';
        $description = $settings['gc_photo_svc_description'] ?? '';
        $services    = $settings['gc_photo_svc_services'] ?? [];

        if (empty($services)) {
            $services = $this->get_default_services();
        }

        $this->render_elementor_spacing_fix($settings);
        $this->render_shape_backgrounds($settings);
        ?>

        <section class="gc-bg-removal-services pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center gc-bg-removal-services-heading">
                    <?php if ('' !== trim((string) $eyebrow)) : ?>
                        <span class="sub-heading gc-bg-removal-services-eyebrow" data-text-animation="fade-in"
                            data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ('' !== trim((string) $title)) : ?>
                        <h2 class="section-title overflow-hidden" data-text-animation data-split="word"
                            data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                        <p class="gc-bg-removal-services-desc" data-text-animation="fade-in" data-duration="1.5">
                            <?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>
                <div class="row gy-4 gx-lg-4 gc-bg-removal-services-grid">
                    <?php foreach ($services as $service) : ?>
                        <?php $this->render_service_card($service, $settings); ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Photo_Service_Widget());
