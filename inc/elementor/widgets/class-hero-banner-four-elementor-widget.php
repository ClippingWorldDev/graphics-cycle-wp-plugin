<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_Four_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_hero_banner_four';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner Four', 'softro-core');
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

    private function get_default_features()
    {
        $items = [
            'Modern SEO',
            'Paid Advertising',
            'Content Marketing',
            'Social Media Marketing',
            'Email Marketing',
            'PPC Management',
            'Analytics & Reporting',
            'Conversion Optimization',
            'AI-Driven Campaigns',
        ];

        $output = [];

        foreach ($items as $item) {
            $output[] = [
                'feature_text' => esc_html__($item, 'softro-core'),
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
            ];
        }

        return $output;
    }

    private function get_default_service_cards()
    {
        return [
            [
                'card_title'       => esc_html__('Graphics Solutions', 'softro-core'),
                'card_description' => esc_html__('Image editing, masking, retouching, BG removal, etc.', 'softro-core'),
                'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                'card_image_alt'   => esc_html__('Graphics Solutions', 'softro-core'),
            ],
            [
                'card_title'       => esc_html__('Web & App Solutions', 'softro-core'),
                'card_description' => esc_html__('Website, Custom Software & App Development & others.', 'softro-core'),
                'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                'card_image_alt'   => esc_html__('Web & App Solutions', 'softro-core'),
            ],
            [
                'card_title'       => esc_html__('AI Marketing Solutions', 'softro-core'),
                'card_description' => esc_html__('Modern SEO (AEO/GEO/SXO), Paid Ad, Content, etc.', 'softro-core'),
                'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                'card_image_alt'   => esc_html__('AI Marketing Solutions', 'softro-core'),
            ],
            [
                'card_title'       => esc_html__('Video & 3D Solution', 'softro-core'),
                'card_description' => esc_html__('Video editing, 3D modeling, animation, etc.', 'softro-core'),
                'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                'card_image_alt'   => esc_html__('Video & 3D Solution', 'softro-core'),
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
        $this->start_controls_section('gc_hero_four_shapes_section', [
            'label' => esc_html__('Background Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_hero_four_shape_bg', [
            'label'   => esc_html__('Background Shape', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-1.png')],
        ]);

        $this->add_control('gc_hero_four_shape_bg_alt', [
            'label'       => esc_html__('Background Shape Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_four_shape_one', [
            'label'   => esc_html__('Shape One', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-22.png')],
        ]);

        $this->add_control('gc_hero_four_shape_one_alt', [
            'label'       => esc_html__('Shape One Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_four_shape_two', [
            'label'   => esc_html__('Shape Two', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-3.png')],
        ]);

        $this->add_control('gc_hero_four_shape_two_alt', [
            'label'       => esc_html__('Shape Two Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_content_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_hero_four_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('AI-Powered Digital Marketing Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_four_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('We apply a smart AI-driven digital marketing strategy to grow a business faster. We are familiar with providing AI-based marketing solutions for multiple industries.', 'softro-core'),
        ]);

        $this->add_control('gc_hero_four_features_aria_label', [
            'label'       => esc_html__('Features List Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('AI marketing services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_four_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Free Consultation', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_four_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $feature_repeater = new Repeater();

        $feature_repeater->add_control('feature_text', [
            'label'       => esc_html__('Feature Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Modern SEO', 'softro-core'),
            'label_block' => true,
        ]);

        $feature_repeater->add_control('feature_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ]);

        $feature_repeater->add_control('feature_icon_image', [
            'label'   => esc_html__('Custom Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->start_controls_section('gc_hero_four_features_section', [
            'label' => esc_html__('Features List', 'softro-core'),
        ]);

        $this->add_control('gc_hero_four_default_feature_icon', [
            'label'   => esc_html__('Default Feature Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_hero_four_default_feature_icon_image', [
            'label'   => esc_html__('Default Feature Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_hero_four_features', [
            'label'       => esc_html__('Features', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $feature_repeater->get_controls(),
            'default'     => $this->get_default_features(),
            'title_field' => '{{{ feature_text }}}',
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Graphics Solutions', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Image editing, masking, retouching, BG removal, etc.', 'softro-core'),
        ]);

        $card_repeater->add_control('card_image', [
            'label'   => esc_html__('Card Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
        ]);

        $card_repeater->add_control('card_image_alt', [
            'label'       => esc_html__('Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Graphics Solutions', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_link', [
            'label'       => esc_html__('Card Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_hero_four_cards_section', [
            'label' => esc_html__('Service Cards', 'softro-core'),
        ]);

        $this->add_control('gc_hero_four_service_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_service_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_hero_four_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_hero_four_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_hero_four_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_hero_info_padding', [
            'label'      => esc_html__('Hero Info Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_four_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .hero-section-11',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_shapes', [
            'label' => esc_html__('Shapes', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_hero_four_shape_bg_width', [
            'label'      => esc_html__('Background Shape Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .bg-shape img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_control('gc_hero_four_shape_bg_opacity', [
            'label'     => esc_html__('Background Shape Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .bg-shape img' => 'opacity: {{SIZE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_shape_one_width', [
            'label'      => esc_html__('Shape One Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_responsive_control('gc_hero_four_shape_two_width', [
            'label'      => esc_html__('Shape Two Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_four_title_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 .title',
        ]);

        $this->add_control('gc_hero_four_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_description', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_four_desc_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 p',
        ]);

        $this->add_control('gc_hero_four_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_features', [
            'label' => esc_html__('Features List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_four_features_typography',
            'selector' => '{{WRAPPER}} .hero-ai-marketing-list li',
        ]);

        $this->add_control('gc_hero_four_features_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-ai-marketing-list li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_features_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .hero-ai-marketing-list li svg' => 'fill: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_hero_four_features_icon_size', [
            'label'      => esc_html__('Icon / Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .hero-ai-marketing-list li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-ai-marketing-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-ai-marketing-list li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_responsive_control('gc_hero_four_features_gap', [
            'label'      => esc_html__('Grid Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_features_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_features_margin', [
            'label'      => esc_html__('List Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_hero_four_features_hover_color', [
            'label'     => esc_html__('Item Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-ai-marketing-list li:hover' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_four_button_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn',
        ]);

        $this->add_control('gc_hero_four_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_button_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_button_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_button_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_button_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_cards', [
            'label' => esc_html__('Service Cards', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_hero_four_card_gap', [
            'label'      => esc_html__('Grid Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .hero-services-grid' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_four_card_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .service-card-info .title',
        ]);

        $this->add_control('gc_hero_four_card_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-card-info .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_card_title_hover_color', [
            'label'     => esc_html__('Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-service-card:hover .service-card-info .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_four_card_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .service-card-info p',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_four_card_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .service-card-info p' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_hero_four_card_desc_hover_color', [
            'label'     => esc_html__('Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-service-card:hover .service-card-info p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_card_image_height', [
            'label'      => esc_html__('Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .service-card-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;'],
        ]);

        $this->add_responsive_control('gc_hero_four_card_image_width', [
            'label'      => esc_html__('Image Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .service-card-img img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_card_image_radius', [
            'label'      => esc_html__('Image Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .service-card-img'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .service-card-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_hero_four_card_info_padding', [
            'label'      => esc_html__('Card Info Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .service-card-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_four_style_orbs', [
            'label' => esc_html__('Service Orbs', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_hero_four_orb_one_heading', [
            'label' => esc_html__('Orb One', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_control('gc_hero_four_orb_one_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-services-orb-1' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_orb_one_size', [
            'label'      => esc_html__('Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .hero-services-orb-1' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('gc_hero_four_orb_two_heading', [
            'label'     => esc_html__('Orb Two', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_four_orb_two_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-services-orb-2' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_four_orb_two_size', [
            'label'      => esc_html__('Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .hero-services-orb-2' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_hero_four_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_hero_four_theme_mode_tabs');

        $this->start_controls_tab('gc_hero_four_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_four_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .hero-section-11',
        ]);

        $this->add_control('gc_hero_four_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_features_color', [
            'label'     => esc_html__('Features Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-ai-marketing-list li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_features_icon_color', [
            'label'     => esc_html__('Features Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '.hero-ai-marketing-list li svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_hero_four_dark_features_hover_color', [
            'label'     => esc_html__('Features Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-ai-marketing-list li:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_button_color', [
            'label'     => esc_html__('Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-card-info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.service-card-info p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-service-card:hover .service-card-info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-service-card:hover .service-card-info p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_dark_orb_bg', [
            'label'     => esc_html__('Orb Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-services-orb' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_hero_four_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_four_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .hero-section-11',
        ]);

        $this->add_control('gc_hero_four_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_features_color', [
            'label'     => esc_html__('Features Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-ai-marketing-list li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_features_icon_color', [
            'label'     => esc_html__('Features Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '.hero-ai-marketing-list li svg' => 'fill: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_hero_four_light_features_hover_color', [
            'label'     => esc_html__('Features Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-ai-marketing-list li:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_button_color', [
            'label'     => esc_html__('Button Text', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-card-info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.service-card-info p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-service-card:hover .service-card-info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_card_desc_hover_color', [
            'label'     => esc_html__('Card Description Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-service-card:hover .service-card-info p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_four_light_orb_bg', [
            'label'     => esc_html__('Orb Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-services-orb' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_hero_four_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> { margin-top: 0 !important; margin-bottom: 0 !important; }
            .elementor-element-<?php echo $widget_id; ?> > .elementor-widget-container { padding: 0 !important; margin: 0 !important; }
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
            .elementor-element-<?php echo $widget_id; ?> .anim-text { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_feature_icon($feature, $settings)
    {
        if (!empty($feature['feature_icon']['value'])) {
            $this->render_icon($feature['feature_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($feature['feature_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_hero_four_default_feature_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_hero_four_default_feature_icon']['value'])) {
            $this->render_icon($settings['gc_hero_four_default_feature_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_feature_item($feature, $settings)
    {
        $text = $feature['feature_text'] ?? '';

        if (!$text) {
            return;
        }
        ?>
        <li><?php $this->render_feature_icon($feature, $settings); ?> <?php echo esc_html($text); ?></li>
        <?php
    }

    private function render_service_card($card)
    {
        $title       = $card['card_title'] ?? '';
        $description = $card['card_description'] ?? '';
        $image       = $this->get_media_url($card['card_image'] ?? [], 'new-update/hero-img-1.png');
        $alt         = $card['card_image_alt'] ?? $title;
        $link        = $card['card_link'] ?? [];

        if (!$title) {
            return;
        }
        ?>
        <a class="hero-service-card" data-tilt<?php echo $this->get_link_attributes($link); ?>>
            <div class="service-card-3d">
                <div class="service-card-img">
                    <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($alt); ?>">
                    <span class="service-card-shine"></span>
                </div>
            </div>
            <div class="service-card-info">
                <h4 class="title"><?php echo esc_html($title); ?></h4>
                <?php if ($description) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
                <?php endif; ?>
            </div>
        </a>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $shape_bg      = $this->get_media_url($settings['gc_hero_four_shape_bg'] ?? [], 'new-update/hero-shape-1.png');
        $shape_bg_alt  = $settings['gc_hero_four_shape_bg_alt'] ?? esc_html__('shape', 'softro-core');
        $shape_one     = $this->get_media_url($settings['gc_hero_four_shape_one'] ?? [], 'new-update/hero-shape-22.png');
        $shape_one_alt = $settings['gc_hero_four_shape_one_alt'] ?? '';
        $shape_two     = $this->get_media_url($settings['gc_hero_four_shape_two'] ?? [], 'new-update/hero-shape-3.png');
        $shape_two_alt = $settings['gc_hero_four_shape_two_alt'] ?? esc_html__('shape', 'softro-core');
        $title         = $settings['gc_hero_four_title'] ?? '';
        $description   = $settings['gc_hero_four_description'] ?? '';
        $features_aria = $settings['gc_hero_four_features_aria_label'] ?? esc_html__('AI marketing services', 'softro-core');
        $features      = !empty($settings['gc_hero_four_features']) ? $settings['gc_hero_four_features'] : [];
        $button_text   = $settings['gc_hero_four_button_text'] ?? '';
        $button_link   = $settings['gc_hero_four_button_link'] ?? [];
        $cards         = !empty($settings['gc_hero_four_service_cards']) ? $settings['gc_hero_four_service_cards'] : [];
        ?>

        <section class="hero-section-11">
            <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="<?php echo esc_attr($shape_bg_alt); ?>"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="<?php echo esc_attr($shape_one_alt); ?>"></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="<?php echo esc_attr($shape_two_alt); ?>"></div>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11">
                    <div class="col-lg-6">
                        <div class="hero-info hero-info-3 hero-info-4">
                            <?php if ($title) : ?>
                                <h1 class="title anim-text"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>

                            <?php if ($description) : ?>
                                <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
                            <?php endif; ?>

                            <?php if (!empty($features)) : ?>
                                <ul class="hero-ai-marketing-list" aria-label="<?php echo esc_attr($features_aria); ?>">
                                    <?php foreach ($features as $feature) {
                                        $this->render_feature_item($feature, $settings);
                                    } ?>
                                </ul>
                            <?php endif; ?>

                            <?php if ($button_text) : ?>
                                <div class="hero-btn-wrap two">
                                    <a class="rr-primary-btn"<?php echo $this->get_link_attributes($button_link); ?>><?php echo esc_html($button_text); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-services-3d-wrap">
                            <?php if (!empty($cards)) : ?>
                                <div class="hero-services-grid">
                                    <?php foreach ($cards as $card) {
                                        $this->render_service_card($card);
                                    } ?>
                                </div>
                            <?php endif; ?>
                            <div class="hero-services-orb hero-services-orb-1"></div>
                            <div class="hero-services-orb hero-services-orb-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_Four_Widget());
