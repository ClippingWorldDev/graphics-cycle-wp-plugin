<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Portfolio_Gallary_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_portfolio_gallary';
    }

    public function get_title()
    {
        return esc_html__('GC Portfolio Gallery', 'softro-core');
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

    /**
     * @return array
     */
    private function get_default_portfolio_items()
    {
        $items = [
            ['background-removal', 'Clipping Path', 'new-update/hero-img-2.png', 'service/service-1.jpg'],
            ['image-masking', 'Image Masking', 'new-update/hero-img-3.png', 'service/service-3.jpg'],
            ['photo-retouching', 'Photo Retouching', 'service/service-1.jpg', 'service/service-2.jpg'],
            ['color-correction', 'Color Correction', 'new-update/service-img-1.png', 'new-update/hero-img-1.png'],
            ['ghost-effects', 'Ghost Mannequin', 'new-update/project-img-1.png', 'new-update/project-img-3.png'],
            ['shadow-effects', 'Drop Shadow', 'new-update/project-img-2.png', 'new-update/project-img-4.png'],
            ['restoration', 'Photo Restoration', 'service/service-3.jpg', 'service/service-8.jpg'],
            ['vectorization', 'Vectorization', 'service/service-4.jpg', 'service/service-10.jpg'],
            ['background-removal', 'Background Remove', 'new-update/hero-img-4.png', 'new-update/hero-img-2.png'],
            ['photo-retouching', 'Skin Retouch', 'service/service-2.jpg', 'service/service-12.jpg'],
            ['color-correction', 'Color Grading', 'new-update/hero-img-1.png', 'new-update/service-img-1.png'],
            ['image-masking', 'Hair Masking', 'service/service-5.jpg', 'new-update/hero-img-3.png'],
        ];

        $defaults = [];

        foreach ($items as $item) {
            $title = $item[1];
            $defaults[] = [
                'category_slug' => $item[0],
                'card_title'    => $title,
                'card_link'     => ['url' => '#'],
                'card_images'   => [
                    [
                        'image'    => ['url' => $this->get_theme_img_url($item[2])],
                        'image_alt' => $title . ' sample 1',
                    ],
                    [
                        'image'    => ['url' => $this->get_theme_img_url($item[3])],
                        'image_alt' => $title . ' sample 2',
                    ],
                ],
            ];
        }

        return $defaults;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_portfolio_gallery_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Our Work', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Portfolio Gallery', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_section_aria_label',
            [
                'label'       => esc_html__('Section Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Portfolio gallery', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $tab_repeater = new Repeater();

        $tab_repeater->add_control(
            'tab_label',
            [
                'label'       => esc_html__('Tab Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('All', 'softro-core'),
                'label_block' => true,
            ]
        );

        $tab_repeater->add_control(
            'tab_filter',
            [
                'label'       => esc_html__('Filter Slug', 'softro-core'),
                'description' => esc_html__('Use "all" for the show-everything tab.', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'all',
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_portfolio_gallery_tabs_section',
            [
                'label' => esc_html__('Filter Tabs', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tabs_aria_label',
            [
                'label'       => esc_html__('Tabs Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Filter portfolio by service', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tabs',
            [
                'label'       => esc_html__('Tabs', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $tab_repeater->get_controls(),
                'default'     => [
                    ['tab_label' => esc_html__('All', 'softro-core'), 'tab_filter' => 'all'],
                    ['tab_label' => esc_html__('Background Removing', 'softro-core'), 'tab_filter' => 'background-removal'],
                    ['tab_label' => esc_html__('Photo Retouching', 'softro-core'), 'tab_filter' => 'photo-retouching'],
                    ['tab_label' => esc_html__('Color Correction', 'softro-core'), 'tab_filter' => 'color-correction'],
                    ['tab_label' => esc_html__('Ghost Effects', 'softro-core'), 'tab_filter' => 'ghost-effects'],
                    ['tab_label' => esc_html__('Shadow Effects', 'softro-core'), 'tab_filter' => 'shadow-effects'],
                    ['tab_label' => esc_html__('Restoration', 'softro-core'), 'tab_filter' => 'restoration'],
                    ['tab_label' => esc_html__('Vectorization', 'softro-core'), 'tab_filter' => 'vectorization'],
                    ['tab_label' => esc_html__('Image Masking', 'softro-core'), 'tab_filter' => 'image-masking'],
                ],
                'title_field' => '{{{ tab_label }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_link_section',
            [
                'label' => esc_html__('Card Link', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_link_text',
            [
                'label'       => esc_html__('Link Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View Details', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_link_icon',
            [
                'label'   => esc_html__('Link Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-regular fa-arrow-right-long',
                    'library' => 'fa-regular',
                ],
            ]
        );

        $this->end_controls_section();

        $image_repeater = new Repeater();

        $image_repeater->add_control(
            'image',
            [
                'label'   => esc_html__('Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $image_repeater->add_control(
            'image_alt',
            [
                'label'       => esc_html__('Alt Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $image_repeater->add_control(
            'image_link',
            [
                'label'       => esc_html__('Image Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $card_repeater = new Repeater();

        $card_repeater->add_control(
            'category_slug',
            [
                'label'       => esc_html__('Category Slug', 'softro-core'),
                'description' => esc_html__('Must match a tab filter slug.', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'background-removal',
                'label_block' => true,
            ]
        );

        $card_repeater->add_control(
            'card_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Clipping Path', 'softro-core'),
                'label_block' => true,
            ]
        );

        $card_repeater->add_control(
            'card_link',
            [
                'label'       => esc_html__('Card Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $card_repeater->add_control(
            'card_images',
            [
                'label'       => esc_html__('Gallery Images', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $image_repeater->get_controls(),
                'default'     => [
                    [
                        'image'     => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                        'image_alt' => esc_html__('Sample 1', 'softro-core'),
                    ],
                    [
                        'image'     => ['url' => $this->get_theme_img_url('service/service-1.jpg')],
                        'image_alt' => esc_html__('Sample 2', 'softro-core'),
                    ],
                ],
                'title_field' => '{{{ image_alt }}}',
            ]
        );

        $this->start_controls_section(
            'gc_portfolio_gallery_items_section',
            [
                'label' => esc_html__('Portfolio Items', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_items',
            [
                'label'       => esc_html__('Portfolio Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $card_repeater->get_controls(),
                'default'     => $this->get_default_portfolio_items(),
                'title_field' => '{{{ card_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_portfolio_gallery_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-gallery-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-gallery-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-portfolio-gallery-section',
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-gallery-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-gallery-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-portfolio-heading .sub-heading.gc-process-eyebrow, {{WRAPPER}} .gc-portfolio-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-heading .sub-heading.gc-process-eyebrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-portfolio-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_title_typography',
                'selector' => '{{WRAPPER}} .gc-portfolio-title',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_heading_margin',
            [
                'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_tabs',
            [
                'label' => esc_html__('Filter Tabs', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_tab_typography',
                'selector' => '{{WRAPPER}} .gc-portfolio-tab',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tab_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-tab' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tab_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-tab' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tab_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-tab' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tab_active_color',
            [
                'label'     => esc_html__('Active Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-tab.active' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_tab_active_bg',
            [
                'label'     => esc_html__('Active Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-tab.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_tab_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-tab' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_tabs_wrap_margin',
            [
                'label'      => esc_html__('Tabs Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-tabs-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_card',
            [
                'label' => esc_html__('Portfolio Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_card_image',
            [
                'label' => esc_html__('Card Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_card_image_height',
            [
                'label'      => esc_html__('Image Area Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-card-media' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_image_object_fit',
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
                    '{{WRAPPER}} .gc-portfolio-card-img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_grid_gap',
            [
                'label'      => esc_html__('Grid Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_card_title',
            [
                'label' => esc_html__('Card Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_card_title_typography',
                'selector' => '{{WRAPPER}} .gc-portfolio-card-title a',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_title_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card-title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_card_footer_padding',
            [
                'label'      => esc_html__('Footer Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-card-footer' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_portfolio_gallery_style_card_link',
            [
                'label' => esc_html__('Card Link', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_card_link_typography',
                'selector' => '{{WRAPPER}} .gc-portfolio-card-link',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_link_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card-link' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_card_link_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-portfolio-card-link:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_portfolio_gallery_card_link_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-portfolio-card-link i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-portfolio-card-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_portfolio_gallery_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_portfolio_gallery_theme_mode_color_tabs');

        $this->start_controls_tab('gc_portfolio_gallery_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-portfolio-gallery-section',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-heading .sub-heading.gc-process-eyebrow' => 'color: {{VALUE}};',
                    '.gc-portfolio-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_tab_color',
            [
                'label'     => esc_html__('Tab Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-tab' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_tab_active_color',
            [
                'label'     => esc_html__('Active Tab Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-tab.active' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-card-title a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_dark_card_link_color',
            [
                'label'     => esc_html__('Card Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-portfolio-card-link' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_portfolio_gallery_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_portfolio_gallery_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-portfolio-gallery-section',
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-heading .sub-heading.gc-process-eyebrow' => 'color: {{VALUE}};',
                    '.gc-portfolio-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_tab_color',
            [
                'label'     => esc_html__('Tab Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-tab' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_tab_active_color',
            [
                'label'     => esc_html__('Active Tab Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-tab.active' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_card_title_color',
            [
                'label'     => esc_html__('Card Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-card-title a' => 'color: {{VALUE}};',
                    '.gc-portfolio-card-title a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_portfolio_gallery_light_card_link_color',
            [
                'label'     => esc_html__('Card Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-portfolio-card-link' => 'color: {{VALUE}};',
                    '.gc-portfolio-card-link:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_portfolio_gallery_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    private function render_portfolio_tab($tab, $is_active)
    {
        $label  = $tab['tab_label'] ?? '';
        $filter = sanitize_title($tab['tab_filter'] ?? '');

        if (!$label) {
            return;
        }

        if (!$filter) {
            $filter = 'all';
        }
        ?>
        <li role="presentation">
            <button type="button"
                class="gc-portfolio-tab<?php echo $is_active ? ' active' : ''; ?>"
                data-filter="<?php echo esc_attr($filter); ?>"
                role="tab"
                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"><?php echo esc_html($label); ?></button>
        </li>
        <?php
    }

    private function render_portfolio_card($item, $settings)
    {
        $category   = sanitize_title($item['category_slug'] ?? '');
        $title      = $item['card_title'] ?? '';
        $card_link  = $item['card_link'] ?? ['url' => '#'];
        $images     = !empty($item['card_images']) ? $item['card_images'] : [];
        $link_text  = $settings['gc_portfolio_gallery_link_text'] ?? esc_html__('View Details', 'softro-core');

        if (!$category && !$title) {
            return;
        }
        ?>
        <div class="gc-portfolio-grid-item" data-category="<?php echo esc_attr($category); ?>">
            <article class="gc-portfolio-card">
                <div class="gc-portfolio-card-media">
                    <?php foreach ($images as $index => $image_item) :
                        $image_url  = $this->get_media_url($image_item['image'] ?? [], '');
                        $image_alt  = $image_item['image_alt'] ?? '';
                        $image_link = !empty($image_item['image_link']['url']) ? $image_item['image_link'] : $card_link;

                        if (!$image_url) {
                            continue;
                        }

                        if (!$image_alt && $title) {
                            $image_alt = $title;
                        }
                        ?>
                        <a <?php echo $this->get_link_attributes($image_link); ?> class="gc-portfolio-card-img<?php echo 0 === (int) $index ? ' is-active' : ''; ?>">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                        </a>
                    <?php endforeach; ?>

                    <span class="gc-portfolio-card-shine" aria-hidden="true"></span>

                    <?php if (!empty($images)) : ?>
                        <div class="gc-portfolio-card-dots" aria-hidden="true">
                            <?php foreach ($images as $index => $image_item) :
                                if (!$this->get_media_url($image_item['image'] ?? [], '')) {
                                    continue;
                                }
                                ?>
                                <span<?php echo 0 === (int) $index ? ' class="is-active"' : ''; ?>></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="gc-portfolio-card-footer">
                    <?php if ($title) : ?>
                        <h3 class="gc-portfolio-card-title"><a <?php echo $this->get_link_attributes($card_link); ?>><?php echo esc_html($title); ?></a></h3>
                    <?php endif; ?>

                    <a <?php echo $this->get_link_attributes($card_link); ?> class="gc-portfolio-card-link"><?php echo esc_html($link_text); ?> <?php $this->render_icon($settings['gc_portfolio_gallery_link_icon'] ?? []); ?></a>
                </div>
            </article>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow          = $settings['gc_portfolio_gallery_eyebrow'] ?? '';
        $title            = $settings['gc_portfolio_gallery_title'] ?? '';
        $section_aria     = $settings['gc_portfolio_gallery_section_aria_label'] ?? esc_html__('Portfolio gallery', 'softro-core');
        $tabs_aria        = $settings['gc_portfolio_gallery_tabs_aria_label'] ?? esc_html__('Filter portfolio by service', 'softro-core');
        $tabs             = !empty($settings['gc_portfolio_gallery_tabs']) ? $settings['gc_portfolio_gallery_tabs'] : [];
        $items            = !empty($settings['gc_portfolio_gallery_items']) ? $settings['gc_portfolio_gallery_items'] : [];
        ?>

        <section class="gc-portfolio-gallery-section pt-130 pb-130 fade-wrapper" aria-label="<?php echo esc_attr($section_aria); ?>">
            <div class="container">
                <div class="section-heading text-center gc-portfolio-heading">
                    <?php if ($eyebrow) : ?>
                        <h4 class="sub-heading gc-process-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="section-title gc-portfolio-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                </div>

                <?php if (!empty($tabs)) : ?>
                    <div class="gc-portfolio-tabs-wrap fade-top">
                        <ul class="gc-portfolio-tabs" role="tablist" aria-label="<?php echo esc_attr($tabs_aria); ?>">
                            <?php foreach ($tabs as $index => $tab) :
                                $this->render_portfolio_tab($tab, 0 === (int) $index);
                            endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($items)) : ?>
                    <div class="gc-portfolio-grid-wrap fade-top">
                        <div class="gc-portfolio-grid">
                            <?php foreach ($items as $item) :
                                $this->render_portfolio_card($item, $settings);
                            endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Portfolio_Gallary_Widget());
