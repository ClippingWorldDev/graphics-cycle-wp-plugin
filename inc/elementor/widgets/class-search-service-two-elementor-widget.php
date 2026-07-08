<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Search_Service_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_search_service_two';
    }

    public function get_title()
    {
        return esc_html__('GC Search Service Two', 'softro-core');
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

    private function render_card_icon(array $item, array $settings)
    {
        if (!empty($item['card_icon']['value'])) {
            $this->render_icon($item['card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['card_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_search_svc_two_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_search_svc_two_default_icon']['value'])) {
            $this->render_icon($settings['gc_search_svc_two_default_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_icon_style_class($item, $index)
    {
        if (!empty($item['icon_style']) && in_array($item['icon_style'], ['1', '2', '3', '4'], true)) {
            return $item['icon_style'];
        }

        return (string) (($index % 4) + 1);
    }

    private function get_default_cards()
    {
        return [
            [
                'icon_style'       => '1',
                'card_icon'          => ['value' => 'fa-light fa-file-lines', 'library' => 'fa-light'],
                'card_title'         => esc_html__('Technical SEO Audit', 'softro-core'),
                'card_description'   => esc_html__('We analyze site architecture, crawlability, Core Web Vitals, and indexation to build a strong technical foundation for search growth.', 'softro-core'),
                'card_shape_image'   => ['url' => $this->get_theme_img_url('shapes/service-shape-2.png')],
            ],
            [
                'icon_style'       => '2',
                'card_icon'          => ['value' => 'fa-light fa-file-lines', 'library' => 'fa-light'],
                'card_title'         => esc_html__('On-Page SEO Optimization', 'softro-core'),
                'card_description'   => esc_html__('We optimize content structure, metadata, internal linking, and keyword mapping to improve relevance and rankings across priority pages.', 'softro-core'),
                'card_shape_image'   => ['url' => $this->get_theme_img_url('shapes/service-shape-2.png')],
            ],
            [
                'icon_style'       => '3',
                'card_icon'          => ['value' => 'fa-light fa-map-location-dot', 'library' => 'fa-light'],
                'card_title'         => esc_html__('Local SEO Strategy', 'softro-core'),
                'card_description'   => esc_html__('We improve local visibility with Google Business Profile optimization, local citations, and geo-targeted content for nearby customers.', 'softro-core'),
                'card_shape_image'   => ['url' => $this->get_theme_img_url('shapes/service-shape-2.png')],
            ],
            [
                'icon_style'       => '4',
                'card_icon'          => ['value' => 'fa-light fa-link', 'library' => 'fa-light'],
                'card_title'         => esc_html__('Link Building & Authority', 'softro-core'),
                'card_description'   => esc_html__('We earn high-quality backlinks and brand mentions to strengthen domain authority and long-term organic search performance.', 'softro-core'),
                'card_shape_image'   => ['url' => $this->get_theme_img_url('shapes/service-shape-2.png')],
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
        $this->start_controls_section('gc_search_svc_two_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_two_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Popular Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_svc_two_title', [
            'label'   => esc_html__('Title', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('We Provide Amazing SEO', 'softro-core') . '<br>' . esc_html__('Solutions', 'softro-core') . '</p>',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_defaults_section', [
            'label' => esc_html__('Card Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_two_default_shape_image', [
            'label'       => esc_html__('Default Card Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('shapes/service-shape-2.png')],
        ]);

        $this->add_control('gc_search_svc_two_default_icon', [
            'label' => esc_html__('Default Card Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_search_svc_two_default_icon_image', [
            'label'       => esc_html__('Default Card Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('icon_style', [
            'label'   => esc_html__('Icon Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => '',
            'options' => [
                ''  => esc_html__('Auto (by position)', 'softro-core'),
                '1' => esc_html__('Style 1', 'softro-core'),
                '2' => esc_html__('Style 2', 'softro-core'),
                '3' => esc_html__('Style 3', 'softro-core'),
                '4' => esc_html__('Style 4', 'softro-core'),
            ],
        ]);

        $card_repeater->add_control('card_shape_image', [
            'label'       => esc_html__('Card Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $card_repeater->add_control('card_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Technical SEO Audit', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Card description goes here.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_search_svc_two_cards_section', [
            'label' => esc_html__('Service Cards', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_two_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_button_section', [
            'label' => esc_html__('Button', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_two_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact Now', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_svc_two_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $this->add_control('gc_search_svc_two_button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-sharp fa-regular fa-arrow-right',
                'library' => 'fa-sharp-regular',
            ],
        ]);

        $this->add_control('gc_search_svc_two_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_shape_section', [
            'label' => esc_html__('Section Shape', 'softro-core'),
        ]);

        $this->add_control('gc_search_svc_two_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_search_svc_two_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_two_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-seo-services',
        ]);

        $this->add_responsive_control('gc_search_svc_two_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_section_shape_size', [
            'label'      => esc_html__('Shape Background Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 50, 'max' => 1200]],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_heading_wrap', [
            'label' => esc_html__('Heading Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_two_heading_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_eyebrow_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services .section-heading .sub-heading' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_two_eyebrow_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services .section-heading .sub-heading' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_two_eyebrow_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services .section-heading .sub-heading' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_two_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-seo-services .section-heading .sub-heading',
        ]);

        $this->add_responsive_control('gc_search_svc_two_eyebrow_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services .section-heading .sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services .section-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_two_title_typography',
            'selector' => '{{WRAPPER}} .gc-seo-services .section-heading .section-title',
        ]);

        $this->add_responsive_control('gc_search_svc_two_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services .section-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_card', [
            'label' => esc_html__('Service Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_card_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_two_card_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_gap', [
            'label'      => esc_html__('Icon / Content Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card' => 'grid-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_card_shape', [
            'label' => esc_html__('Card Shape Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_shape_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 40, 'max' => 300]],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card-shape img' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_svc_two_card_shape_opacity', [
            'label'     => esc_html__('Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card-shape img' => 'opacity: {{SIZE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_card_icon', [
            'label' => esc_html__('Card Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_icon_size', [
            'label'      => esc_html__('Wrap Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 40, 'max' => 150]],
            'selectors'  => [
                '{{WRAPPER}} .gc-seo-service-card-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-seo-service-card'      => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr;',
            ],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_icon_font_size', [
            'label'      => esc_html__('Icon Font Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 12, 'max' => 80]],
            'selectors'  => [
                '{{WRAPPER}} .gc-seo-service-card-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-seo-service-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_icon_img_size', [
            'label'      => esc_html__('Icon Image Max Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card-icon img' => 'max-width: {{SIZE}}{{UNIT}}; max-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_icon_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_card_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card-content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_two_card_title_hover_color', [
            'label'     => esc_html__('Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card:hover .gc-seo-service-card-content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_two_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-seo-service-card-content .title',
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_card_desc', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_card_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-service-card-content p' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_two_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-seo-service-card-content p',
        ]);

        $this->add_responsive_control('gc_search_svc_two_card_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-service-card-content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_svc_two_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_svc_two_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_svc_two_button_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_svc_two_button_typography',
            'selector' => '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn',
        ]);

        $this->add_responsive_control('gc_search_svc_two_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_button_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_svc_two_button_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_svc_two_button_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_svc_two_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 60]],
            'selectors'  => [
                '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-seo-services__btn .rr-primary-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_search_svc_two_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_search_svc_two_theme_mode_tabs');

        $this->start_controls_tab('gc_search_svc_two_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_two_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-seo-services',
        ]);

        $this->add_control('gc_search_svc_two_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_eyebrow_bg', [
            'label'     => esc_html__('Eyebrow Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services .section-heading .sub-heading' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_eyebrow_border', [
            'label'     => esc_html__('Eyebrow Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services .section-heading .sub-heading' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_card_border', [
            'label'     => esc_html__('Card Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-service-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-service-card-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-service-card-content p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-seo-service-card-icon i'   => 'color: {{VALUE}};',
                '.gc-seo-service-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_search_svc_two_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-seo-services__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_search_svc_two_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_svc_two_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-seo-services',
        ]);

        $this->add_control('gc_search_svc_two_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_eyebrow_bg', [
            'label'     => esc_html__('Eyebrow Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services .section-heading .sub-heading' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_eyebrow_border', [
            'label'     => esc_html__('Eyebrow Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services .section-heading .sub-heading' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-service-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_card_border', [
            'label'     => esc_html__('Card Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-service-card' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-service-card-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-service-card:hover .gc-seo-service-card-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-service-card-content p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_icon_color', [
            'label'     => esc_html__('Card Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-seo-service-card-icon i'   => 'color: {{VALUE}};',
                '.gc-seo-service-card-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_search_svc_two_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_svc_two_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-seo-services__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_search_svc_two_reset_elementor_spacing']) || 'yes' !== $settings['gc_search_svc_two_reset_elementor_spacing']) {
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
        $shape_url = $this->get_media_url($settings['gc_search_svc_two_section_shape'] ?? [], '');

        if (!$shape_url) {
            return;
        }
        ?>
        <style>
            .elementor-element-<?php echo esc_attr($this->get_id()); ?> .gc-seo-services {
                background-image: url('<?php echo esc_url($shape_url); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
        <?php
    }

    private function render_button_icon(array $settings)
    {
        if (!empty($settings['gc_search_svc_two_button_icon']['value'])) {
            $this->render_icon($settings['gc_search_svc_two_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_search_svc_two_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function get_card_shape_url(array $item, array $settings)
    {
        $shape_url = $this->get_media_url($item['card_shape_image'] ?? [], '');

        if ($shape_url) {
            return $shape_url;
        }

        return $this->get_media_url($settings['gc_search_svc_two_default_shape_image'] ?? [], 'shapes/service-shape-2.png');
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_section_shape_background($settings);

        $eyebrow     = $settings['gc_search_svc_two_eyebrow'] ?? '';
        $title       = $this->get_paragraph_inner_content($settings['gc_search_svc_two_title'] ?? '');
        $cards       = !empty($settings['gc_search_svc_two_cards']) ? $settings['gc_search_svc_two_cards'] : $this->get_default_cards();
        $button_text = !empty($settings['gc_search_svc_two_button_text']) ? $settings['gc_search_svc_two_button_text'] : esc_html__('Contact Now', 'softro-core');
        $button_link = $settings['gc_search_svc_two_button_link'] ?? ['url' => '#'];

        ?>
        <section class="gc-seo-services pt-130 pb-130 overflow-hidden fade-wrapper">
            <div class="container">
                <div class="section-heading text-center">
                    <?php if ('' !== trim((string) $eyebrow)) : ?>
                        <h4 class="sub-heading fade-top" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>
                    <?php if ('' !== $title) : ?>
                        <h2 class="section-title fade-top" data-text-animation data-split="word" data-duration="1"><?php echo wp_kses($title, ['br' => []]); ?></h2>
                    <?php endif; ?>
                </div>
                <div class="row gy-4 fade-wrapper">
                    <?php foreach ($cards as $index => $item) : ?>
                        <?php
                        $icon_style  = $this->get_icon_style_class($item, $index);
                        $shape_url   = $this->get_card_shape_url($item, $settings);
                        $card_title  = $item['card_title'] ?? '';
                        $card_desc   = $this->get_paragraph_inner_content($item['card_description'] ?? '');
                        ?>
                        <div class="col-md-6">
                            <div class="gc-seo-service-card fade-top">
                                <?php if ($shape_url) : ?>
                                    <div class="gc-seo-service-card-shape" aria-hidden="true">
                                        <img src="<?php echo esc_url($shape_url); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="gc-seo-service-card-icon gc-seo-service-card-icon--<?php echo esc_attr($icon_style); ?>"><?php $this->render_card_icon($item, $settings); ?></div>
                                <div class="gc-seo-service-card-content">
                                    <?php if ('' !== $card_title) : ?>
                                        <h3 class="title"><?php echo esc_html($card_title); ?></h3>
                                    <?php endif; ?>
                                    <?php if ('' !== $card_desc) : ?>
                                        <p><?php echo wp_kses($card_desc, ['br' => []]); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="gc-seo-services__btn fade-top">
                    <a<?php echo $this->get_link_attributes($button_link); ?> class="rr-primary-btn">
                        <?php echo esc_html($button_text); ?>
                        <?php $this->render_button_icon($settings); ?>
                    </a>
                </div>
            </div>
        </section>
        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Search_Service_Two_Widget());
