<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_hero_banner_two';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner Two', 'softro-core');
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
     * Strip outer <p> tags from WYSIWYG output for use inside existing paragraph markup.
     *
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
     * Convert Font Awesome 5 shorthand classes to Font Awesome 6 Pro classes used by the theme.
     *
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
     * Render icon using theme-compatible Font Awesome classes or Elementor SVG/eicons.
     *
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
            'gc_hero_two_shapes_section',
            [
                'label' => esc_html__('Background Shapes', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_bg',
            [
                'label'   => esc_html__('Background Shape', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-1.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_bg_alt',
            [
                'label'       => esc_html__('Background Shape Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('shape', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_one',
            [
                'label'   => esc_html__('Shape One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-22.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_one_alt',
            [
                'label'       => esc_html__('Shape One Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_two',
            [
                'label'   => esc_html__('Shape Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-3.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_two_alt',
            [
                'label'       => esc_html__('Shape Two Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('shape', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_content_section',
            [
                'label' => esc_html__('Hero Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Professional Image Editing Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_two_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Image Editing That Makes Your Products Sell', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_two_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('From background removal to vector conversion, we deliver clean, accurate edits for ecommerce, fashion, and marketing teams worldwide.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_features_aria_label',
            [
                'label'       => esc_html__('Features List Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Service highlights', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $feature_repeater = new Repeater();

        $feature_repeater->add_control(
            'feature_text',
            [
                'label'       => esc_html__('Feature Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('24-48 Hour Turnaround', 'softro-core'),
                'label_block' => true,
            ]
        );

        $feature_repeater->add_control(
            'feature_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-solid fa-gem',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $feature_repeater->add_control(
            'feature_icon_image',
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
            'gc_hero_two_features_section',
            [
                'label' => esc_html__('Features List', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_features',
            [
                'label'       => esc_html__('Features', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $feature_repeater->get_controls(),
                'default'     => [
                    [
                        'feature_text' => esc_html__('24-48 Hour Turnaround', 'softro-core'),
                    ],
                    [
                        'feature_text' => esc_html__('Free Trial Available', 'softro-core'),
                    ],
                    [
                        'feature_text' => esc_html__('100% Satisfaction Guarantee', 'softro-core'),
                    ],
                ],
                'title_field' => '{{{ feature_text }}}',
            ]
        );

        $this->end_controls_section();

        $button_repeater = new Repeater();

        $button_repeater->add_control(
            'button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Start Free Trial', 'softro-core'),
                'label_block' => true,
            ]
        );

        $button_repeater->add_control(
            'button_url',
            [
                'label'       => esc_html__('Button Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $button_repeater->add_control(
            'button_style',
            [
                'label'   => esc_html__('Button Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => [
                    'primary' => esc_html__('Primary', 'softro-core'),
                    'outline' => esc_html__('Outline', 'softro-core'),
                ],
            ]
        );

        $button_repeater->add_control(
            'button_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_hero_two_buttons_section',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_buttons',
            [
                'label'       => esc_html__('Buttons', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $button_repeater->get_controls(),
                'default'     => [
                    [
                        'button_text'  => esc_html__('Start Free Trial', 'softro-core'),
                        'button_url'   => ['url' => '#'],
                        'button_style' => 'primary',
                    ],
                    [
                        'button_text'  => esc_html__('View Pricing', 'softro-core'),
                        'button_url'   => ['url' => '#'],
                        'button_style' => 'outline',
                    ],
                ],
                'title_field' => '{{{ button_text }}}',
            ]
        );

        $this->end_controls_section();

        $slide_repeater = new Repeater();

        $slide_repeater->add_control(
            'slide_image',
            [
                'label'   => esc_html__('Slide Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $slide_repeater->add_control(
            'slide_title',
            [
                'label'       => esc_html__('Slide Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Background Removal', 'softro-core'),
                'label_block' => true,
            ]
        );

        $slide_repeater->add_control(
            'slide_alt',
            [
                'label'       => esc_html__('Image Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_hero_two_slider_section',
            [
                'label' => esc_html__('Service Slider', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_two_slides',
            [
                'label'       => esc_html__('Slides', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $slide_repeater->get_controls(),
                'default'     => [
                    [
                        'slide_title' => esc_html__('Background Removal', 'softro-core'),
                        'slide_alt'   => esc_html__('Background Removal', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                    ],
                    [
                        'slide_title' => esc_html__('Photo Retouching', 'softro-core'),
                        'slide_alt'   => esc_html__('Photo Retouching', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                    ],
                    [
                        'slide_title' => esc_html__('Image Masking', 'softro-core'),
                        'slide_alt'   => esc_html__('Image Masking', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                    ],
                    [
                        'slide_title' => esc_html__('Clipping Path', 'softro-core'),
                        'slide_alt'   => esc_html__('Clipping Path', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                    ],
                    [
                        'slide_title' => esc_html__('Color Correction', 'softro-core'),
                        'slide_alt'   => esc_html__('Color Correction', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                    ],
                    [
                        'slide_title' => esc_html__('Shadow Creation', 'softro-core'),
                        'slide_alt'   => esc_html__('Shadow Creation', 'softro-core'),
                        'slide_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                    ],
                ],
                'title_field' => '{{{ slide_title }}}',
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_prev_icon',
            [
                'label'   => esc_html__('Previous Arrow Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-regular fa-arrow-left',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_next_icon',
            [
                'label'   => esc_html__('Next Arrow Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-regular fa-arrow-right',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_prev_label',
            [
                'label'       => esc_html__('Previous Button Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Previous slide', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_next_label',
            [
                'label'       => esc_html__('Next Button Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Next slide', 'softro-core'),
                'label_block' => true,
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
            'gc_hero_two_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_hero_two_reset_elementor_spacing',
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
            'gc_hero_two_pull_up',
            [
                'label'       => esc_html__('Pull Up (Remove Top Gap)', 'softro-core'),
                'description' => esc_html__('Increase this value if a blank gap remains above the hero background.', 'softro-core'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', 'em', 'vh'],
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'     => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'condition'   => [
                    'gc_hero_two_reset_elementor_spacing' => 'yes',
                ],
                'selectors'   => [
                    '{{WRAPPER}} .hero-section-11' => 'margin-top: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_full_width',
            [
                'label'        => esc_html__('Full Width Breakout', 'softro-core'),
                'description'  => esc_html__('Removes side gaps from Elementor column padding.', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .hero-section-11' => 'width: 100vw; max-width: 100vw; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_row_padding_top',
            [
                'label'      => esc_html__('Content Top Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'    => [
                    'size' => 140,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-row-11' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_row_padding_bottom',
            [
                'label'      => esc_html__('Content Bottom Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-row-11' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_content_padding',
            [
                'label'      => esc_html__('Content Column Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slider_wrap_padding',
            [
                'label'      => esc_html__('Slider Column Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slider-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_hero_two_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .hero-section-11',
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_shapes',
            [
                'label' => esc_html__('Shape Images', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_shape_bg_width',
            [
                'label'      => esc_html__('Background Shape Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 1200],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .bg-shape img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_shape_bg_opacity',
            [
                'label'     => esc_html__('Background Shape Opacity', 'softro-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-section-11 .bg-shape img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_shape_one_width',
            [
                'label'      => esc_html__('Shape One Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 800],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .shape-1 img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_shape_two_width',
            [
                'label'      => esc_html__('Shape Two Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 800],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .shape-2 img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-hero-eyebrow',
            ]
        );

        $this->add_control(
            'gc_hero_two_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_eyebrow_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-eyebrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_title_typography',
                'selector' => '{{WRAPPER}} .gc-hero-title',
            ]
        );

        $this->add_control(
            'gc_hero_two_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_description_typography',
                'selector' => '{{WRAPPER}} .gc-hero-desc',
            ]
        );

        $this->add_control(
            'gc_hero_two_description_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_description_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_description_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_features',
            [
                'label' => esc_html__('Features List', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_features_typography',
                'selector' => '{{WRAPPER}} .gc-hero-features li',
            ]
        );

        $this->add_control(
            'gc_hero_two_features_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-features li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_features_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-features li i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-hero-features li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_features_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-features li i'    => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-hero-features li svg'  => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-hero-features li img'  => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_features_gap',
            [
                'label'      => esc_html__('Items Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-features' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_features_margin',
            [
                'label'      => esc_html__('List Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-features' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_features_padding',
            [
                'label'      => esc_html__('List Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-features' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_buttons',
            [
                'label' => esc_html__('Primary Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_button_typography',
                'selector' => '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)',
            ]
        );

        $this->add_control(
            'gc_hero_two_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_button_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_buttons_wrap_margin',
            [
                'label'      => esc_html__('Buttons Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_buttons_wrap_gap',
            [
                'label'      => esc_html__('Buttons Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-btns' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_outline_button',
            [
                'label' => esc_html__('Outline Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_outline_button_typography',
                'selector' => '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline',
            ]
        );

        $this->add_control(
            'gc_hero_two_outline_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_outline_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_outline_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_outline_button_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_outline_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-btns .rr-primary-btn.gc-hero-btn-outline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_slide_image',
            [
                'label' => esc_html__('Slide Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_image_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 1200],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_image_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 800],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slide_image_object_fit',
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
                    '{{WRAPPER}} .gc-hero-slide-img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_image_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_card_padding',
            [
                'label'      => esc_html__('Slide Card Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_slide_title',
            [
                'label' => esc_html__('Slide Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_two_slide_title_typography',
                'selector' => '{{WRAPPER}} .gc-hero-slide-title',
            ]
        );

        $this->add_control(
            'gc_hero_two_slide_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-slide-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slide_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slide-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_two_style_slider_controls',
            [
                'label' => esc_html__('Slider Controls', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slider_nav_size',
            [
                'label'      => esc_html__('Arrow Button Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 24, 'max' => 120],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slider-prev, {{WRAPPER}} .gc-hero-slider-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_nav_color',
            [
                'label'     => esc_html__('Arrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-slider-prev, {{WRAPPER}} .gc-hero-slider-next' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-hero-slider-prev i, {{WRAPPER}} .gc-hero-slider-next i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-hero-slider-prev svg, {{WRAPPER}} .gc-hero-slider-next svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_nav_bg_color',
            [
                'label'     => esc_html__('Arrow Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-slider-prev, {{WRAPPER}} .gc-hero-slider-next' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slider_nav_icon_size',
            [
                'label'      => esc_html__('Arrow Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 60],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slider-prev i, {{WRAPPER}} .gc-hero-slider-next i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-hero-slider-prev svg, {{WRAPPER}} .gc-hero-slider-next svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_pagination_color',
            [
                'label'     => esc_html__('Pagination Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-slider-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_two_slider_pagination_active_color',
            [
                'label'     => esc_html__('Pagination Active Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-hero-slider-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_two_slider_controls_margin',
            [
                'label'      => esc_html__('Controls Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-hero-slider-controls' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
            'gc_hero_two_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_hero_two_theme_mode_color_tabs');

        $this->start_controls_tab(
            'gc_hero_two_theme_mode_dark_tab',
            [
                'label' => esc_html__('Dark Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_hero_two_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .hero-section-11',
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-eyebrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_features_heading',
            [
                'label'     => esc_html__('Features', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_features_text_color',
            [
                'label'     => esc_html__('Features Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-features li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_features_bg_color',
            [
                'label'     => esc_html__('Features Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-features li' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_features_border_color',
            [
                'label'     => esc_html__('Features Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-features li' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_features_icon_color',
            [
                'label'     => esc_html__('Features Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-features li i'   => 'color: {{VALUE}};',
                    '.gc-hero-features li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_primary_button_heading',
            [
                'label'     => esc_html__('Primary Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_primary_button_color',
            [
                'label'     => esc_html__('Primary Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_primary_button_bg',
            [
                'label'     => esc_html__('Primary Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_primary_button_hover_color',
            [
                'label'     => esc_html__('Primary Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_primary_button_hover_bg',
            [
                'label'     => esc_html__('Primary Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_heading',
            [
                'label'     => esc_html__('Outline Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_color',
            [
                'label'     => esc_html__('Outline Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btn-outline' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_border',
            [
                'label'     => esc_html__('Outline Button Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btn-outline' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_bg',
            [
                'label'     => esc_html__('Outline Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btn-outline' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_hover_color',
            [
                'label'     => esc_html__('Outline Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btn-outline:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_outline_button_hover_bg',
            [
                'label'     => esc_html__('Outline Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-btn-outline:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_heading',
            [
                'label'     => esc_html__('Slider', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slide_card_bg',
            [
                'label'     => esc_html__('Slide Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slide-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slide_title_color',
            [
                'label'     => esc_html__('Slide Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slide-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_stack_card_bg',
            [
                'label'     => esc_html__('Stack Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-stack-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_nav_color',
            [
                'label'     => esc_html__('Slider Arrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next'     => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev i, .gc-hero-slider-next i' => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev svg, .gc-hero-slider-next svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_nav_bg',
            [
                'label'     => esc_html__('Slider Arrow Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_nav_border',
            [
                'label'     => esc_html__('Slider Arrow Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_nav_hover_color',
            [
                'label'     => esc_html__('Slider Arrow Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-prev:hover, .gc-hero-slider-next:hover'     => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev:hover i, .gc-hero-slider-next:hover i' => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev:hover svg, .gc-hero-slider-next:hover svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_nav_hover_bg',
            [
                'label'     => esc_html__('Slider Arrow Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-prev:hover, .gc-hero-slider-next:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_pagination_color',
            [
                'label'     => esc_html__('Pagination Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_dark_slider_pagination_active_color',
            [
                'label'     => esc_html__('Pagination Active Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-hero-slider-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'gc_hero_two_theme_mode_light_tab',
            [
                'label' => esc_html__('Light Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_hero_two_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .hero-section-11',
            ]
        );

        $this->add_control(
            'gc_hero_two_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-eyebrow' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_features_heading',
            [
                'label'     => esc_html__('Features', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_light_features_text_color',
            [
                'label'     => esc_html__('Features Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-features li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_features_bg_color',
            [
                'label'     => esc_html__('Features Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-features li' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_features_border_color',
            [
                'label'     => esc_html__('Features Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-features li' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_features_icon_color',
            [
                'label'     => esc_html__('Features Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-features li i'   => 'color: {{VALUE}};',
                    '.gc-hero-features li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_primary_button_heading',
            [
                'label'     => esc_html__('Primary Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_light_primary_button_color',
            [
                'label'     => esc_html__('Primary Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_primary_button_bg',
            [
                'label'     => esc_html__('Primary Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_primary_button_hover_color',
            [
                'label'     => esc_html__('Primary Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_primary_button_hover_bg',
            [
                'label'     => esc_html__('Primary Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btns .rr-primary-btn:not(.gc-hero-btn-outline):hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_heading',
            [
                'label'     => esc_html__('Outline Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_color',
            [
                'label'     => esc_html__('Outline Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btn-outline' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_border',
            [
                'label'     => esc_html__('Outline Button Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btn-outline' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_bg',
            [
                'label'     => esc_html__('Outline Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btn-outline' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_hover_color',
            [
                'label'     => esc_html__('Outline Button Hover Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btn-outline:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_outline_button_hover_bg',
            [
                'label'     => esc_html__('Outline Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-btn-outline:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_heading',
            [
                'label'     => esc_html__('Slider', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slide_card_bg',
            [
                'label'     => esc_html__('Slide Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slide-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slide_title_color',
            [
                'label'     => esc_html__('Slide Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slide-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_stack_card_bg',
            [
                'label'     => esc_html__('Stack Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-stack-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_nav_color',
            [
                'label'     => esc_html__('Slider Arrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next'     => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev i, .gc-hero-slider-next i' => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev svg, .gc-hero-slider-next svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_nav_bg',
            [
                'label'     => esc_html__('Slider Arrow Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_nav_border',
            [
                'label'     => esc_html__('Slider Arrow Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-prev, .gc-hero-slider-next' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_nav_hover_color',
            [
                'label'     => esc_html__('Slider Arrow Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-prev:hover, .gc-hero-slider-next:hover'     => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev:hover i, .gc-hero-slider-next:hover i' => 'color: {{VALUE}};',
                    '.gc-hero-slider-prev:hover svg, .gc-hero-slider-next:hover svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_nav_hover_bg',
            [
                'label'     => esc_html__('Slider Arrow Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-prev:hover, .gc-hero-slider-next:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_pagination_color',
            [
                'label'     => esc_html__('Pagination Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-pagination .swiper-pagination-bullet' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_hero_two_light_slider_pagination_active_color',
            [
                'label'     => esc_html__('Pagination Active Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-hero-slider-pagination .swiper-pagination-bullet-active' => 'background-color: {{VALUE}};',
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
        if ('yes' !== ($settings['gc_hero_two_reset_elementor_spacing'] ?? 'yes')) {
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
     * @param array $icon_settings
     * @param array $image_settings
     * @return void
     */
    private function render_feature_icon($icon_settings, $image_settings = [])
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

    /**
     * @return void
     */
    private function render_icon_compatibility_styles()
    {
        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-features li i,
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-features li svg,
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-features li img {
                flex-shrink: 0;
                line-height: 1;
            }

            .elementor-element-<?php echo $widget_id; ?> .gc-hero-features li svg {
                display: inline-block;
            }

            .elementor-element-<?php echo $widget_id; ?> .gc-hero-slider-prev i,
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-slider-next i,
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-slider-prev svg,
            .elementor-element-<?php echo $widget_id; ?> .gc-hero-slider-next svg {
                display: inline-flex;
                line-height: 1;
            }
        </style>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();
        $this->render_icon_compatibility_styles();

        $shape_bg      = $this->get_media_url($settings['gc_hero_two_shape_bg'] ?? [], 'new-update/hero-shape-1.png');
        $shape_one     = $this->get_media_url($settings['gc_hero_two_shape_one'] ?? [], 'new-update/hero-shape-22.png');
        $shape_two     = $this->get_media_url($settings['gc_hero_two_shape_two'] ?? [], 'new-update/hero-shape-3.png');
        $shape_bg_alt  = $settings['gc_hero_two_shape_bg_alt'] ?? esc_html__('shape', 'softro-core');
        $shape_one_alt = $settings['gc_hero_two_shape_one_alt'] ?? '';
        $shape_two_alt = $settings['gc_hero_two_shape_two_alt'] ?? esc_html__('shape', 'softro-core');
        $eyebrow       = $settings['gc_hero_two_eyebrow'] ?? '';
        $title         = $settings['gc_hero_two_title'] ?? '';
        $description   = $settings['gc_hero_two_description'] ?? '';
        $features      = !empty($settings['gc_hero_two_features']) ? $settings['gc_hero_two_features'] : [];
        $buttons       = !empty($settings['gc_hero_two_buttons']) ? $settings['gc_hero_two_buttons'] : [];
        $slides        = !empty($settings['gc_hero_two_slides']) ? $settings['gc_hero_two_slides'] : [];
        $features_aria = $settings['gc_hero_two_features_aria_label'] ?? esc_html__('Service highlights', 'softro-core');
        $prev_label    = $settings['gc_hero_two_slider_prev_label'] ?? esc_html__('Previous slide', 'softro-core');
        $next_label    = $settings['gc_hero_two_slider_next_label'] ?? esc_html__('Next slide', 'softro-core');
        ?>

        <section class="hero-section-11 gc-hero-section graphics-solutions-hero">
            <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="<?php echo esc_attr($shape_bg_alt); ?>"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="<?php echo esc_attr($shape_one_alt); ?>"></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="<?php echo esc_attr($shape_two_alt); ?>"></div>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11 gc-hero-row">
                    <div class="col-lg-6">
                        <div class="gc-hero-content fade-wrapper">
                            <?php if ($eyebrow) : ?>
                                <span class="gc-hero-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                            <?php endif; ?>

                            <?php if ($title) : ?>
                                <h1 class="title anim-text gc-hero-title"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>

                            <?php if ($description) : ?>
                                <p class="gc-hero-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($features)) : ?>
                                <ul class="gc-hero-features" aria-label="<?php echo esc_attr($features_aria); ?>">
                                    <?php foreach ($features as $feature) :
                                        $feature_text = $feature['feature_text'] ?? '';

                                        if (!$feature_text) {
                                            continue;
                                        }
                                        ?>
                                        <li>
                                            <?php $this->render_feature_icon($feature['feature_icon'] ?? [], $feature['feature_icon_image'] ?? []); ?>
                                            <?php echo esc_html($feature_text); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <?php if (!empty($buttons)) : ?>
                                <div class="hero-btn-wrap two gc-hero-btns">
                                    <?php foreach ($buttons as $button) :
                                        $button_text = $button['button_text'] ?? '';
                                        $button_url  = $button['button_url'] ?? [];
                                        $button_style = $button['button_style'] ?? 'primary';

                                        if (!$button_text) {
                                            continue;
                                        }

                                        $button_class = 'rr-primary-btn';

                                        if ('outline' === $button_style) {
                                            $button_class .= ' gc-hero-btn-outline';
                                        }
                                        ?>
                                        <a class="<?php echo esc_attr($button_class); ?>" <?php echo $this->get_link_attributes($button_url); ?>>
                                            <?php
                                            $this->render_icon($button['button_icon'] ?? [], ['aria-hidden' => 'true']);
                                            echo esc_html($button_text);
                                            ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gc-hero-slider-wrap fade-top">
                            <div class="gc-hero-slider-stack" aria-hidden="true">
                                <span class="gc-hero-stack-card gc-hero-stack-card-1"></span>
                                <span class="gc-hero-stack-card gc-hero-stack-card-2"></span>
                            </div>
                            <div class="swiper gc-hero-service-slider">
                                <div class="swiper-wrapper">
                                    <?php foreach ($slides as $slide) :
                                        $slide_title = $slide['slide_title'] ?? '';
                                        $slide_alt   = $slide['slide_alt'] ?? '';
                                        $slide_image = $this->get_media_url($slide['slide_image'] ?? [], '');

                                        if (!$slide_image && !$slide_title) {
                                            continue;
                                        }

                                        if (!$slide_alt && $slide_title) {
                                            $slide_alt = $slide_title;
                                        }
                                        ?>
                                        <div class="swiper-slide">
                                            <div class="gc-hero-slide-card">
                                                <?php if ($slide_image) : ?>
                                                    <div class="gc-hero-slide-img">
                                                        <img src="<?php echo esc_url($slide_image); ?>" alt="<?php echo esc_attr($slide_alt); ?>">
                                                    </div>
                                                <?php endif; ?>

                                                <?php if ($slide_title) : ?>
                                                    <h3 class="gc-hero-slide-title"><?php echo esc_html($slide_title); ?></h3>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="gc-hero-slider-controls">
                                    <button type="button" class="gc-hero-slider-prev" aria-label="<?php echo esc_attr($prev_label); ?>"><?php $this->render_icon($settings['gc_hero_two_slider_prev_icon'] ?? [], ['aria-hidden' => 'true']); ?></button>
                                    <div class="swiper-pagination gc-hero-slider-pagination"></div>
                                    <button type="button" class="gc-hero-slider-next" aria-label="<?php echo esc_attr($next_label); ?>"><?php $this->render_icon($settings['gc_hero_two_slider_next_icon'] ?? [], ['aria-hidden' => 'true']); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_Two_Widget());
