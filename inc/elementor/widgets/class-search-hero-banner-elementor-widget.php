<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Search_Hero_Banner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_search_hero_banner';
    }

    public function get_title()
    {
        return esc_html__('GC Search Hero Banner', 'softro-core');
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

    private function render_feature_icon(array $item, array $settings)
    {
        if (!empty($item['feature_icon']['value'])) {
            $this->render_icon($item['feature_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['feature_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_search_hb_default_feature_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_search_hb_default_feature_icon']['value'])) {
            $this->render_icon($settings['gc_search_hb_default_feature_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-solid fa-circle-check" aria-hidden="true"></i>';
        }
    }

    private function render_button_icon(array $settings)
    {
        if (!empty($settings['gc_search_hb_button_icon']['value'])) {
            $this->render_icon($settings['gc_search_hb_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_search_hb_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function get_default_features()
    {
        return [
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('B2B Social Selling', 'softro-core'),
            ],
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('Social Branding', 'softro-core'),
            ],
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('Social Commerce', 'softro-core'),
            ],
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('Social Funnel Strategy', 'softro-core'),
            ],
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('AI Social Automation', 'softro-core'),
            ],
            [
                'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
                'feature_text' => esc_html__('CRO Optimization', 'softro-core'),
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
        $this->start_controls_section('gc_search_hb_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_search_hb_shape_bg', [
            'label'       => esc_html__('Background Shape', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_search_hb_shape_bg_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-1.png',
        ]);

        $this->add_control('gc_search_hb_shape_bg_alt', [
            'label'       => esc_html__('Background Shape Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_hb_shape_1', [
            'label'       => esc_html__('Shape 1', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_search_hb_shape_1_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-22.png',
        ]);

        $this->add_control('gc_search_hb_shape_2', [
            'label'       => esc_html__('Shape 2', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_search_hb_shape_2_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-3.png',
        ]);

        $this->add_control('gc_search_hb_shape_2_alt', [
            'label'       => esc_html__('Shape 2 Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_content_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_search_hb_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('AI-Powered SEO Services for Modern Businesses', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_search_hb_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Grow your audience, increase engagement, generate qualified leads, and drive measurable revenue with AI-powered social media strategies tailored to your business goals.',
                'softro-core'
            ),
        ]);

        $this->add_control('gc_search_hb_features_aria_label', [
            'label'       => esc_html__('Features List Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Social media marketing services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_hb_default_feature_icon', [
            'label'   => esc_html__('Default Feature Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_search_hb_default_feature_icon_image', [
            'label'       => esc_html__('Default Feature Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $feature_repeater = new Repeater();

        $feature_repeater->add_control('feature_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ]);

        $feature_repeater->add_control('feature_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $feature_repeater->add_control('feature_text', [
            'label'       => esc_html__('Feature Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('B2B Social Selling', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_search_hb_features', [
            'label'       => esc_html__('Features', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $feature_repeater->get_controls(),
            'default'     => $this->get_default_features(),
            'title_field' => '{{{ feature_text }}}',
            'separator'   => 'before',
        ]);

        $this->add_control('gc_search_hb_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Contact & Discuss', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_search_hb_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_search_hb_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_search_hb_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_image_section', [
            'label' => esc_html__('Hero Image', 'softro-core'),
        ]);

        $this->add_control('gc_search_hb_hero_image', [
            'label'       => esc_html__('Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_search_hb_hero_image_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update-3/hero-16-img-1.png',
        ]);

        $this->add_control('gc_search_hb_hero_image_alt', [
            'label'       => esc_html__('Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Social media marketing team', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_search_hb_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_search_hb_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_search_hb_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11.gc-social-hero' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11.gc-social-hero' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_hero_info_padding', [
            'label'      => esc_html__('Hero Info Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_hb_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .hero-section-11.gc-social-hero',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_shapes', [
            'label' => esc_html__('Shape Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_hb_shape_bg_width', [
            'label'      => esc_html__('Background Shape Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .bg-shape img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_control('gc_search_hb_shape_bg_opacity', [
            'label'     => esc_html__('Background Shape Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .bg-shape img' => 'opacity: {{SIZE}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_shape_1_width', [
            'label'      => esc_html__('Shape 1 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_responsive_control('gc_search_hb_shape_2_width', [
            'label'      => esc_html__('Shape 2 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_hb_title_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 .title',
        ]);

        $this->add_control('gc_search_hb_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_hb_desc_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 p',
        ]);

        $this->add_control('gc_search_hb_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_features', [
            'label' => esc_html__('Features List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_hb_features_typography',
            'selector' => '{{WRAPPER}} .hero-ai-marketing-list li',
        ]);

        $this->add_control('gc_search_hb_features_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-ai-marketing-list li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_hb_features_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .hero-ai-marketing-list li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_search_hb_features_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'selectors'  => [
                '{{WRAPPER}} .hero-ai-marketing-list li i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-ai-marketing-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-ai-marketing-list li img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_responsive_control('gc_search_hb_features_gap', [
            'label'      => esc_html__('Grid Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_features_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_features_margin', [
            'label'      => esc_html__('List Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-ai-marketing-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_hb_features_hover_color', [
            'label'     => esc_html__('Item Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-ai-marketing-list li:hover' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_search_hb_button_typography',
            'selector' => '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn',
        ]);

        $this->add_control('gc_search_hb_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_hb_button_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_hb_button_hover_color', [
            'label'     => esc_html__('Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_search_hb_button_hover_bg', [
            'label'     => esc_html__('Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_button_wrap_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_search_hb_style_thumb', [
            'label' => esc_html__('Hero Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_search_hb_thumb_max_width', [
            'label'      => esc_html__('Wrap Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-social-hero-thumb' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_thumb_padding', [
            'label'      => esc_html__('Wrap Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-social-hero-thumb' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_thumb_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-social-hero-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_thumb_img_min_height', [
            'label'      => esc_html__('Image Min Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 100, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .gc-social-hero-thumb img' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_search_hb_thumb_img_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-social-hero-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_search_hb_thumb_img_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-social-hero-thumb img' => 'border-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_search_hb_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_search_hb_theme_mode_tabs');

        $this->start_controls_tab('gc_search_hb_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_hb_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .hero-section-11.gc-social-hero',
        ]);

        $this->add_control('gc_search_hb_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_features_color', [
            'label'     => esc_html__('Features Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-ai-marketing-list li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_features_icon_color', [
            'label'     => esc_html__('Features Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '.hero-ai-marketing-list li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_search_hb_dark_features_hover_color', [
            'label'     => esc_html__('Features Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-ai-marketing-list li:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_dark_thumb_border', [
            'label'     => esc_html__('Hero Image Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-social-hero-thumb img' => 'border-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_search_hb_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_search_hb_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .hero-section-11.gc-social-hero',
        ]);

        $this->add_control('gc_search_hb_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_features_color', [
            'label'     => esc_html__('Features Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-ai-marketing-list li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_features_icon_color', [
            'label'     => esc_html__('Features Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.hero-ai-marketing-list li i'   => 'color: {{VALUE}};',
                '.hero-ai-marketing-list li svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_search_hb_light_features_hover_color', [
            'label'     => esc_html__('Features Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-ai-marketing-list li:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_search_hb_light_thumb_border', [
            'label'     => esc_html__('Hero Image Border', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-social-hero-thumb img' => 'border-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_search_hb_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_feature_item(array $item, array $settings)
    {
        $text = trim((string) ($item['feature_text'] ?? ''));

        if ('' === $text) {
            return;
        }
        ?>
        <li><?php $this->render_feature_icon($item, $settings); ?> <?php echo esc_html($text); ?></li>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $title         = $settings['gc_search_hb_title'] ?? '';
        $description   = $settings['gc_search_hb_description'] ?? '';
        $features      = $settings['gc_search_hb_features'] ?? [];
        $features_aria = $settings['gc_search_hb_features_aria_label'] ?? '';
        $button_text   = trim((string) ($settings['gc_search_hb_button_text'] ?? ''));

        if (empty($features)) {
            $features = $this->get_default_features();
        }

        $shape_bg     = $this->get_media_url($settings['gc_search_hb_shape_bg'] ?? [], $settings['gc_search_hb_shape_bg_fallback'] ?? 'new-update/hero-shape-1.png');
        $shape_bg_alt = $settings['gc_search_hb_shape_bg_alt'] ?? '';
        $shape_1      = $this->get_media_url($settings['gc_search_hb_shape_1'] ?? [], $settings['gc_search_hb_shape_1_fallback'] ?? 'new-update/hero-shape-22.png');
        $shape_2      = $this->get_media_url($settings['gc_search_hb_shape_2'] ?? [], $settings['gc_search_hb_shape_2_fallback'] ?? 'new-update/hero-shape-3.png');
        $shape_2_alt  = $settings['gc_search_hb_shape_2_alt'] ?? '';
        $hero_image   = $this->get_media_url($settings['gc_search_hb_hero_image'] ?? [], $settings['gc_search_hb_hero_image_fallback'] ?? 'new-update-3/hero-16-img-1.png');
        $hero_alt     = $settings['gc_search_hb_hero_image_alt'] ?? '';

        $this->render_elementor_spacing_fix($settings);
        ?>

        <section class="hero-section-11 gc-social-hero fade-wrapper">
            <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="<?php echo esc_attr($shape_bg_alt); ?>"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_1); ?>" alt=""></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_2); ?>" alt="<?php echo esc_attr($shape_2_alt); ?>"></div>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11">
                    <div class="col-lg-6">
                        <div class="hero-info hero-info-3 hero-info-4">
                            <?php if ('' !== trim((string) $title)) : ?>
                                <h1 class="title anim-text" data-text-animation="fade-in" data-duration="1.2"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>
                            <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                                <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($features)) : ?>
                                <ul class="hero-ai-marketing-list fade-top" aria-label="<?php echo esc_attr($features_aria); ?>">
                                    <?php foreach ($features as $feature) {
                                        $this->render_feature_item($feature, $settings);
                                    } ?>
                                </ul>
                            <?php endif; ?>
                            <?php if ('' !== $button_text) : ?>
                                <div class="hero-btn-wrap two">
                                    <a<?php echo $this->get_link_attributes($settings['gc_search_hb_button_link'] ?? []); ?> class="rr-primary-btn"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($settings); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if ($hero_image) : ?>
                        <div class="col-lg-6">
                            <div class="gc-social-hero-thumb fade-top">
                                <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_alt); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Search_Hero_Banner_Widget());
