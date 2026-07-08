<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Service_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_service_two';
    }

    public function get_title()
    {
        return esc_html__('GC Service Two', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    /**
     * @param string $path
     * @return string
     */
    private function get_theme_img_url($path)
    {
        return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($path, '/'));
    }

    /**
     * @param array  $media
     * @param string $fallback_path
     * @return string
     */
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

    /**
     * @param array $link_settings
     * @return string
     */
    private function get_link_attributes($link_settings)
    {
        $url = !empty($link_settings['url']) ? $link_settings['url'] : '#';

        $attributes = [
            'href' => esc_url($url),
        ];

        if (!empty($link_settings['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($link_settings['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        if (!empty($link_settings['custom_attributes'])) {
            $custom_attributes = Utils::parse_custom_attributes($link_settings['custom_attributes']);

            foreach ($custom_attributes as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return $html;
    }

    /**
     * @param string $content
     * @return string
     */
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

    /**
     * @param string $mode dark|light
     * @param string $selector
     * @return string
     */
    private function get_theme_mode_selector($mode, $selector)
    {
        return sprintf('[data-theme=%s] {{WRAPPER}} %s', $mode, $selector);
    }

    /**
     * @param string $mode
     * @param array  $selectors
     * @return array
     */
    private function get_theme_mode_selectors($mode, array $selectors)
    {
        $output = [];

        foreach ($selectors as $selector => $property) {
            $output[$this->get_theme_mode_selector($mode, $selector)] = $property;
        }

        return $output;
    }

    /**
     * @param string $icon_value
     * @return string
     */
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

    /**
     * @param array $icon_settings
     * @return bool
     */
    private function should_use_elementor_icon_renderer($icon_settings)
    {
        if (empty($icon_settings['value'])) {
            return false;
        }

        $library = $icon_settings['library'] ?? '';

        if ('eicons' === $library || 'svg' === $library || false !== strpos($library, 'svg')) {
            return true;
        }

        return false;
    }

    /**
     * @param array $icon_settings
     * @param array $args
     * @return void
     */
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

    /**
     * @param array $icon_settings
     * @param array $image_settings
     * @return void
     */
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

    /**
     * Content tab controls.
     */
    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_service_two_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_service_two_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'description' => esc_html__('Leave empty to hide the eyebrow line.', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Our Graphics Solution Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('From simple touch-ups to complex restorations, our team handles it all.', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_compare_labels_section',
            [
                'label' => esc_html__('Compare Labels', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_service_two_compare_before_label',
            [
                'label'       => esc_html__('Before Tag', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Before', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_compare_after_label',
            [
                'label'       => esc_html__('After Tag', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('After', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_compare_hint_text',
            [
                'label'       => esc_html__('Compare Hint Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Move to compare', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_compare_hint_icon',
            [
                'label'   => esc_html__('Compare Hint Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-light fa-arrows-left-right',
                    'library' => 'fa-light',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_text',
            [
                'label'       => esc_html__('Details Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Show Details', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_icon',
            [
                'label'   => esc_html__('Details Button Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-regular fa-arrow-right',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control(
            'icon_style',
            [
                'label'   => esc_html__('Icon Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => esc_html__('Style 1', 'softro-core'),
                    '2' => esc_html__('Style 2', 'softro-core'),
                    '3' => esc_html__('Style 3', 'softro-core'),
                    '4' => esc_html__('Style 4', 'softro-core'),
                    '5' => esc_html__('Style 5', 'softro-core'),
                    '6' => esc_html__('Style 6', 'softro-core'),
                    '7' => esc_html__('Style 7', 'softro-core'),
                    '8' => esc_html__('Style 8', 'softro-core'),
                ],
            ]
        );

        $service_repeater->add_control(
            'service_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-light fa-eraser',
                    'library' => 'fa-light',
                ],
            ]
        );

        $service_repeater->add_control(
            'service_icon_image',
            [
                'label'       => esc_html__('Custom Icon Image', 'softro-core'),
                'description' => esc_html__('Upload an image icon when you do not want to use the icon library.', 'softro-core'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => '',
                ],
            ]
        );

        $service_repeater->add_control(
            'service_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Background Removal', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Clean, pixel-perfect cutouts for ecommerce, catalogs, and product listings.', 'softro-core'),
            ]
        );

        $service_repeater->add_control(
            'compare_before_image',
            [
                'label'   => esc_html__('Before Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $service_repeater->add_control(
            'compare_after_image',
            [
                'label'   => esc_html__('After Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $service_repeater->add_control(
            'compare_before_alt',
            [
                'label'       => esc_html__('Before Image Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'compare_after_alt',
            [
                'label'       => esc_html__('After Image Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'details_url',
            [
                'label'       => esc_html__('Details Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->start_controls_section(
            'gc_service_two_items_section',
            [
                'label' => esc_html__('Service Cards', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_service_two_items',
            [
                'label'       => esc_html__('Service Cards', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_repeater->get_controls(),
                'default'     => [
                    [
                        'icon_style'           => '1',
                        'service_icon'         => ['value' => 'fa-light fa-eraser', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Background Removal', 'softro-core'),
                        'service_description'  => esc_html__('Clean, pixel-perfect cutouts for ecommerce, catalogs, and product listings.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                    ],
                    [
                        'icon_style'           => '2',
                        'service_icon'         => ['value' => 'fa-light fa-wand-magic-sparkles', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Photo Retouching', 'softro-core'),
                        'service_description'  => esc_html__('Skin smoothing, blemish removal, and natural enhancements for polished visuals.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                    ],
                    [
                        'icon_style'           => '3',
                        'service_icon'         => ['value' => 'fa-light fa-object-ungroup', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Image Masking', 'softro-core'),
                        'service_description'  => esc_html__('Precise hair, fur, and fine-edge masking for complex product photography.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                    ],
                    [
                        'icon_style'           => '4',
                        'service_icon'         => ['value' => 'fa-light fa-bezier-curve', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Clipping Path', 'softro-core'),
                        'service_description'  => esc_html__('Hand-drawn paths for sharp edges, multi-layer selections, and bulk edits.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                    ],
                    [
                        'icon_style'           => '5',
                        'service_icon'         => ['value' => 'fa-light fa-droplet', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Color Correction', 'softro-core'),
                        'service_description'  => esc_html__('True-to-life color balance, white balance fixes, and consistent brand tones.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
                    ],
                    [
                        'icon_style'           => '6',
                        'service_icon'         => ['value' => 'fa-light fa-sun', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Shadow Creation', 'softro-core'),
                        'service_description'  => esc_html__('Natural drop, reflection, and mirror shadows that make products stand out.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/project-img-4.png')],
                    ],
                    [
                        'icon_style'           => '7',
                        'service_icon'         => ['value' => 'fa-light fa-clock-rotate-left', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Image Restoration', 'softro-core'),
                        'service_description'  => esc_html__('Repair damaged, faded, or old photos with careful detail reconstruction.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                    ],
                    [
                        'icon_style'           => '8',
                        'service_icon'         => ['value' => 'fa-light fa-vector-square', 'library' => 'fa-light'],
                        'service_title'        => esc_html__('Vector Conversion', 'softro-core'),
                        'service_description'  => esc_html__('Convert raster logos and artwork into scalable vector files for any use.', 'softro-core'),
                        'compare_before_image' => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
                        'compare_after_image'  => ['url' => $this->get_theme_img_url('new-update/project-img-3.png')],
                    ],
                ],
                'title_field' => '{{{ service_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style tab controls.
     */
    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_service_two_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_service_two_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 300],
                ],
                'default'    => [
                    'size' => 130,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 300],
                ],
                'default'    => [
                    'size' => 130,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_grid_gap',
            [
                'label'      => esc_html__('Grid Row Gap Override', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 80],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-section .row.gy-4' => 'row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_service_two_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-services-section',
            ]
        );

        $this->add_control(
            'gc_service_two_section_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-services-section' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-services-eyebrow',
            ]
        );

        $this->add_control(
            'gc_service_two_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-services-eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_title_typography',
                'selector' => '{{WRAPPER}} .gc-services-heading .section-title',
            ]
        );

        $this->add_control(
            'gc_service_two_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-services-heading .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-heading .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_desc_typography',
                'selector' => '{{WRAPPER}} .gc-services-desc',
            ]
        );

        $this->add_control(
            'gc_service_two_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-services-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_desc_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-services-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_card',
            [
                'label' => esc_html__('Service Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_service_two_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_card_hover_bg',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_card_icon',
            [
                'label' => esc_html__('Card Icon', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_icon_width',
            [
                'label'      => esc_html__('Icon Box Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 120],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-icon' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_icon_height',
            [
                'label'      => esc_html__('Icon Box Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 120],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-icon' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_icon_font_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-service-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-service-card-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_card_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card-icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-service-card-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_icon_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_card_title',
            [
                'label' => esc_html__('Card Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_card_title_typography',
                'selector' => '{{WRAPPER}} .gc-service-card-title',
            ]
        );

        $this->add_control(
            'gc_service_two_card_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_card_title_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card:hover .gc-service-card-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_card_desc',
            [
                'label' => esc_html__('Card Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_card_desc_typography',
                'selector' => '{{WRAPPER}} .gc-service-card-desc',
            ]
        );

        $this->add_control(
            'gc_service_two_card_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_card_desc_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-service-card:hover .gc-service-card-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_card_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_compare',
            [
                'label' => esc_html__('Compare Images', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_compare_height',
            [
                'label'      => esc_html__('Compare View Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => ['min' => 120, 'max' => 600],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-compare-view' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_compare_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-service-card-compare-view' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_compare_tags',
            [
                'label' => esc_html__('Compare Tags', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_compare_tag_typography',
                'selector' => '{{WRAPPER}} .gc-compare-tag',
            ]
        );

        $this->add_control(
            'gc_service_two_compare_before_tag_color',
            [
                'label'     => esc_html__('Before Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-tag--before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_before_tag_bg',
            [
                'label'     => esc_html__('Before Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-tag--before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_after_tag_color',
            [
                'label'     => esc_html__('After Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-tag--after' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_after_tag_bg',
            [
                'label'     => esc_html__('After Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-tag--after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_compare_hint',
            [
                'label' => esc_html__('Compare Hint', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_compare_hint_typography',
                'selector' => '{{WRAPPER}} .gc-compare-hint',
            ]
        );

        $this->add_control(
            'gc_service_two_compare_hint_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-hint' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_compare_hint_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 40],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-compare-hint i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-compare-hint svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_service_two_style_compare_details',
            [
                'label' => esc_html__('Details Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_service_two_compare_details_typography',
                'selector' => '{{WRAPPER}} .gc-compare-details-btn',
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-details-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-details-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-details-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_service_two_compare_details_hover_bg',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-compare-details-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_compare_details_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-compare-details-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_service_two_compare_details_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 40],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-compare-details-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-compare-details-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    /**
     * Dark / light mode color controls for theme switcher.
     */
    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_service_two_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_service_two_theme_mode_color_tabs');

        $this->start_controls_tab(
            'gc_service_two_theme_mode_dark_tab',
            [
                'label' => esc_html__('Dark Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_service_two_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-services-section',
            ]
        );

        $this->add_control(
            'gc_service_two_dark_section_border',
            [
                'label'     => esc_html__('Section Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-services-section' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-services-eyebrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-services-heading .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-services-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_heading',
            [
                'label'     => esc_html__('Service Card', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_border',
            [
                'label'     => esc_html__('Card Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_hover_bg',
            [
                'label'     => esc_html__('Card Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card-title' => 'color: {{VALUE}};',
                    '.gc-service-card:hover .gc-service-card-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_desc_color',
            [
                'label'     => esc_html__('Card Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card-desc' => 'color: {{VALUE}};',
                    '.gc-service-card:hover .gc-service-card-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_card_icon_color',
            [
                'label'     => esc_html__('Card Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-service-card-icon i'   => 'color: {{VALUE}};',
                    '.gc-service-card-icon svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_heading',
            [
                'label'     => esc_html__('Compare Overlay', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_before_tag_color',
            [
                'label'     => esc_html__('Before Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-tag--before' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_before_tag_bg',
            [
                'label'     => esc_html__('Before Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-tag--before' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_after_tag_color',
            [
                'label'     => esc_html__('After Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-tag--after' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_after_tag_bg',
            [
                'label'     => esc_html__('After Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-tag--after' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_hint_color',
            [
                'label'     => esc_html__('Compare Hint Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-hint' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_details_color',
            [
                'label'     => esc_html__('Details Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-details-btn' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_details_bg',
            [
                'label'     => esc_html__('Details Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-details-btn' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_details_hover_color',
            [
                'label'     => esc_html__('Details Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-details-btn:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_dark_compare_details_hover_bg',
            [
                'label'     => esc_html__('Details Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-compare-details-btn:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'gc_service_two_theme_mode_light_tab',
            [
                'label' => esc_html__('Light Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_service_two_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-services-section',
            ]
        );

        $this->add_control(
            'gc_service_two_light_section_border',
            [
                'label'     => esc_html__('Section Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-services-section' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-services-eyebrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-services-heading .section-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-services-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_heading',
            [
                'label'     => esc_html__('Service Card', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_border',
            [
                'label'     => esc_html__('Card Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_hover_bg',
            [
                'label'     => esc_html__('Card Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card-title' => 'color: {{VALUE}};',
                    '.gc-service-card:hover .gc-service-card-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_desc_color',
            [
                'label'     => esc_html__('Card Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card-desc' => 'color: {{VALUE}};',
                    '.gc-service-card:hover .gc-service-card-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_card_icon_color',
            [
                'label'     => esc_html__('Card Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-service-card-icon i'   => 'color: {{VALUE}};',
                    '.gc-service-card-icon svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_heading',
            [
                'label'     => esc_html__('Compare Overlay', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_before_tag_color',
            [
                'label'     => esc_html__('Before Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-tag--before' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_before_tag_bg',
            [
                'label'     => esc_html__('Before Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-tag--before' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_after_tag_color',
            [
                'label'     => esc_html__('After Tag Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-tag--after' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_after_tag_bg',
            [
                'label'     => esc_html__('After Tag Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-tag--after' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_hint_color',
            [
                'label'     => esc_html__('Compare Hint Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-hint' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_details_color',
            [
                'label'     => esc_html__('Details Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-details-btn' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_details_bg',
            [
                'label'     => esc_html__('Details Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-details-btn' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_details_hover_color',
            [
                'label'     => esc_html__('Details Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-details-btn:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_service_two_light_compare_details_hover_bg',
            [
                'label'     => esc_html__('Details Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-compare-details-btn:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_service_two_reset_elementor_spacing'] ?? 'yes')) {
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

    /**
     * @return void
     */
    private function render_editor_preview_fix()
    {
        if (!Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .fade-top,
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    /**
     * @param array $item
     * @param array $settings
     * @return void
     */
    private function render_service_card($item, $settings)
    {
        $icon_style      = !empty($item['icon_style']) ? absint($item['icon_style']) : 1;
        $icon_style      = max(1, min(8, $icon_style));
        $title           = $item['service_title'] ?? '';
        $description     = $item['service_description'] ?? '';
        $before_image    = $this->get_media_url($item['compare_before_image'] ?? [], '');
        $after_image     = $this->get_media_url($item['compare_after_image'] ?? [], '');
        $before_alt      = $item['compare_before_alt'] ?? '';
        $after_alt       = $item['compare_after_alt'] ?? '';
        $details_url     = $item['details_url'] ?? ['url' => '#'];
        $before_label    = $settings['gc_service_two_compare_before_label'] ?? esc_html__('Before', 'softro-core');
        $after_label     = $settings['gc_service_two_compare_after_label'] ?? esc_html__('After', 'softro-core');
        $hint_text       = $settings['gc_service_two_compare_hint_text'] ?? esc_html__('Move to compare', 'softro-core');
        $details_text    = $settings['gc_service_two_compare_details_text'] ?? esc_html__('Show Details', 'softro-core');

        if (!$before_alt && $before_label) {
            $before_alt = $before_label;
        }

        if (!$after_alt && $after_label) {
            $after_alt = $after_label;
        }
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="gc-service-card fade-top" data-compare>
                <div class="gc-service-card-body">
                    <div class="gc-service-card-icon gc-service-card-icon--<?php echo esc_attr($icon_style); ?>">
                        <?php $this->render_icon_or_image($item['service_icon'] ?? [], $item['service_icon_image'] ?? []); ?>
                    </div>

                    <?php if ($title) : ?>
                        <h3 class="gc-service-card-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="gc-service-card-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>
                <div class="gc-service-card-compare" aria-hidden="true">
                    <div class="gc-service-card-compare-view">
                        <?php if ($after_image) : ?>
                            <img class="gc-compare-img gc-compare-img--after" src="<?php echo esc_url($after_image); ?>" alt="<?php echo esc_attr($after_alt); ?>">
                        <?php endif; ?>

                        <?php if ($before_image) : ?>
                            <img class="gc-compare-img gc-compare-img--before" src="<?php echo esc_url($before_image); ?>" alt="<?php echo esc_attr($before_alt); ?>">
                        <?php endif; ?>

                        <div class="gc-compare-divider" aria-hidden="true"><span class="gc-compare-knob"></span></div>
                        <span class="gc-compare-tag gc-compare-tag--before"><?php echo esc_html($before_label); ?></span>
                        <span class="gc-compare-tag gc-compare-tag--after"><?php echo esc_html($after_label); ?></span>
                        <span class="gc-compare-hint"><?php $this->render_icon($settings['gc_service_two_compare_hint_icon'] ?? []); ?> <?php echo esc_html($hint_text); ?></span>
                        <a <?php echo $this->get_link_attributes($details_url); ?> class="gc-compare-details-btn"><?php echo esc_html($details_text); ?> <?php $this->render_icon($settings['gc_service_two_compare_details_icon'] ?? []); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow     = $settings['gc_service_two_eyebrow'] ?? '';
        $title       = $settings['gc_service_two_title'] ?? '';
        $description = $settings['gc_service_two_description'] ?? '';
        $items       = !empty($settings['gc_service_two_items']) ? $settings['gc_service_two_items'] : [];
        ?>

        <section class="gc-services-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center gc-services-heading">
                    <?php if ($eyebrow) : ?>
                        <h4 class="sub-heading gc-services-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="gc-services-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($items)) : ?>
                    <div class="row gy-4">
                        <?php foreach ($items as $item) :
                            $this->render_service_card($item, $settings);
                        endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Service_Two_Widget());
