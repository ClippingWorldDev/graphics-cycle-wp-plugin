<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_CTA_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_cta_one';
    }

    public function get_title()
    {
        return esc_html__('GC CTA One', 'softro-core');
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_cta_one_content_section',
            [
                'label' => esc_html__('Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_cta_one_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Ready to transform your images?', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_cta_one_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('From background removal to color correction, masking, and beyond — our team delivers clean, accurate edits that help your products look their best, backed by friendly support for businesses of all sizes.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_cta_one_note',
            [
                'label'   => esc_html__('Note', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Send your first 2–3 images for a free sample edit and review the quality before placing an order.', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $badge_repeater = new Repeater();

        $badge_repeater->add_control(
            'badge_text',
            [
                'label'       => esc_html__('Badge Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('24-48 hour turnaround', 'softro-core'),
                'label_block' => true,
            ]
        );

        $badge_repeater->add_control(
            'badge_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $badge_repeater->add_control(
            'badge_icon_image',
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
            'gc_cta_one_badges_section',
            [
                'label' => esc_html__('Badges', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_cta_one_badges_aria_label',
            [
                'label'       => esc_html__('Badges Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Service guarantees', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_cta_one_default_badge_icon',
            [
                'label'   => esc_html__('Default Badge Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-solid fa-gem',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_default_badge_icon_image',
            [
                'label'   => esc_html__('Default Badge Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_badges',
            [
                'label'       => esc_html__('Badge Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $badge_repeater->get_controls(),
                'default'     => [
                    ['badge_text' => esc_html__('24-48 hour turnaround', 'softro-core')],
                    ['badge_text' => esc_html__('free trial available', 'softro-core')],
                    ['badge_text' => esc_html__('100% satisfaction guarantee', 'softro-core')],
                ],
                'title_field' => '{{{ badge_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_buttons_section',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_cta_one_button_one_text',
            [
                'label'       => esc_html__('Primary Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Start Free Trial', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_cta_one_button_one_link',
            [
                'label'       => esc_html__('Primary Button Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_cta_one_button_two_text',
            [
                'label'       => esc_html__('Outline Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact us', 'softro-core'),
                'label_block' => true,
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'gc_cta_one_button_two_link',
            [
                'label'       => esc_html__('Outline Button Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'default'     => [
                    'url' => '#',
                ],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_shape_section',
            [
                'label' => esc_html__('Banner Glow Shape', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_cta_one_glow_shape_image',
            [
                'label'       => esc_html__('Glow Shape Image', 'softro-core'),
                'description' => esc_html__('Optional decorative image applied to the banner glow layer via Style tab background.', 'softro-core'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => '',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_cta_one_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_cta_one_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-cta-section',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_section_overlay_background',
                'label'    => esc_html__('Section Overlay', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-cta-section::before',
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_banner',
            [
                'label' => esc_html__('Banner', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_banner_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-cta-banner',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'gc_cta_one_banner_border',
                'selector' => '{{WRAPPER}} .gc-cta-banner',
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_banner_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-banner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'gc_cta_one_banner_shadow',
                'selector' => '{{WRAPPER}} .gc-cta-banner',
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_banner_inner_padding',
            [
                'label'      => esc_html__('Inner Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-banner-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_banner_inner_max_width',
            [
                'label'      => esc_html__('Inner Max Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-banner-inner' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_glow',
            [
                'label' => esc_html__('Banner Glow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_glow_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-cta-banner-glow',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_title_typography',
                'selector' => '{{WRAPPER}} .gc-cta-title',
            ]
        );

        $this->add_control(
            'gc_cta_one_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_desc_typography',
                'selector' => '{{WRAPPER}} .gc-cta-desc',
            ]
        );

        $this->add_control(
            'gc_cta_one_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_desc_max_width',
            [
                'label'      => esc_html__('Max Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-desc' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_badges',
            [
                'label' => esc_html__('Badges', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_badge_typography',
                'selector' => '{{WRAPPER}} .gc-cta-badges li',
            ]
        );

        $this->add_control(
            'gc_cta_one_badge_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-badges li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_badge_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-badges li' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_badge_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-badges li' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_badge_padding',
            [
                'label'      => esc_html__('Item Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-badges li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_badge_gap',
            [
                'label'      => esc_html__('Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-badges' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_badge_list_margin',
            [
                'label'      => esc_html__('List Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-badges' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_badge_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-badges li i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-cta-badges li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-cta-badges li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                    '{{WRAPPER}} .gc-cta-badges li img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_badge_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-badges li i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-cta-badges li svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_primary_button',
            [
                'label' => esc_html__('Primary Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_primary_btn_typography',
                'selector' => '{{WRAPPER}} .gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)',
            ]
        );

        $this->add_control(
            'gc_cta_one_primary_btn_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_primary_btn_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_primary_btn_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_buttons_gap',
            [
                'label'      => esc_html__('Buttons Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-btns' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_buttons_margin',
            [
                'label'      => esc_html__('Buttons Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_outline_button',
            [
                'label' => esc_html__('Outline Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_outline_btn_typography',
                'selector' => '{{WRAPPER}} .gc-cta-btn-outline',
            ]
        );

        $this->add_control(
            'gc_cta_one_outline_btn_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-btn-outline' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_outline_btn_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-btn-outline' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'gc_cta_one_outline_btn_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-btn-outline' => 'border-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_outline_btn_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-btn-outline' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_cta_one_style_note',
            [
                'label' => esc_html__('Note', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_cta_one_note_typography',
                'selector' => '{{WRAPPER}} .gc-cta-note',
            ]
        );

        $this->add_control(
            'gc_cta_one_note_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-cta-note' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_cta_one_note_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-cta-note' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_cta_one_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_cta_one_theme_mode_color_tabs');

        $this->start_controls_tab('gc_cta_one_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-cta-section',
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_banner_bg',
            [
                'label'     => esc_html__('Banner Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-banner' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_badge_color',
            [
                'label'     => esc_html__('Badge Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-badges li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_badge_bg',
            [
                'label'     => esc_html__('Badge Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-badges li' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_badge_border_color',
            [
                'label'     => esc_html__('Badge Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-badges li' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_badge_icon_color',
            [
                'label'     => esc_html__('Badge Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-badges li i' => 'color: {{VALUE}};',
                    '.gc-cta-badges li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_primary_btn_color',
            [
                'label'     => esc_html__('Primary Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_primary_btn_bg',
            [
                'label'     => esc_html__('Primary Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_outline_btn_color',
            [
                'label'     => esc_html__('Outline Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-btn-outline' => 'color: {{VALUE}} !important;',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_outline_btn_border_color',
            [
                'label'     => esc_html__('Outline Button Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-btn-outline' => 'border-color: {{VALUE}} !important;',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_dark_note_color',
            [
                'label'     => esc_html__('Note Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-cta-note' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_cta_one_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_cta_one_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-cta-section',
            ]
        );

        $this->add_control(
            'gc_cta_one_light_banner_bg',
            [
                'label'     => esc_html__('Banner Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-banner' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_badge_color',
            [
                'label'     => esc_html__('Badge Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-badges li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_badge_bg',
            [
                'label'     => esc_html__('Badge Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-badges li' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_badge_border_color',
            [
                'label'     => esc_html__('Badge Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-badges li' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_badge_icon_color',
            [
                'label'     => esc_html__('Badge Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-badges li i' => 'color: {{VALUE}};',
                    '.gc-cta-badges li svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_primary_btn_color',
            [
                'label'     => esc_html__('Primary Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_primary_btn_bg',
            [
                'label'     => esc_html__('Primary Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-btns .rr-primary-btn:not(.gc-cta-btn-outline)' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_outline_btn_color',
            [
                'label'     => esc_html__('Outline Button Text', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-btn-outline' => 'color: {{VALUE}} !important;',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_outline_btn_border_color',
            [
                'label'     => esc_html__('Outline Button Border', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-btn-outline' => 'border-color: {{VALUE}} !important;',
                ]),
            ]
        );

        $this->add_control(
            'gc_cta_one_light_note_color',
            [
                'label'     => esc_html__('Note Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-cta-note' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_cta_one_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_glow_shape_image($settings)
    {
        $shape_url = $this->get_media_url($settings['gc_cta_one_glow_shape_image'] ?? [], '');

        if (!$shape_url) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-cta-banner-glow {
                background-image: url('<?php echo esc_url($shape_url); ?>');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
        <?php
    }

    private function render_badge_icon($item, $settings)
    {
        if (!empty($item['badge_icon']['value'])) {
            $this->render_icon($item['badge_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['badge_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_cta_one_default_badge_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_cta_one_default_badge_icon']['value'])) {
            $this->render_icon($settings['gc_cta_one_default_badge_icon'], ['aria-hidden' => 'true']);
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();
        $this->render_glow_shape_image($settings);

        $title            = $settings['gc_cta_one_title'] ?? '';
        $description      = $settings['gc_cta_one_description'] ?? '';
        $note             = $settings['gc_cta_one_note'] ?? '';
        $badges           = !empty($settings['gc_cta_one_badges']) ? $settings['gc_cta_one_badges'] : [];
        $badges_aria      = $settings['gc_cta_one_badges_aria_label'] ?? esc_html__('Service guarantees', 'softro-core');
        $button_one_text  = $settings['gc_cta_one_button_one_text'] ?? '';
        $button_one_link  = $settings['gc_cta_one_button_one_link'] ?? [];
        $button_two_text  = $settings['gc_cta_one_button_two_text'] ?? '';
        $button_two_link  = $settings['gc_cta_one_button_two_link'] ?? [];
        ?>

        <section class="gc-cta-section pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-cta-banner fade-top">
                    <div class="gc-cta-banner-glow" aria-hidden="true"></div>
                    <div class="gc-cta-banner-inner text-center">
                        <?php if ($title) : ?>
                            <h2 class="gc-cta-title" data-text-animation="fade-in" data-duration="1.2"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>

                        <?php if ($description) : ?>
                            <p class="gc-cta-desc" data-text-animation="fade-in" data-duration="1.4"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>

                        <?php if (!empty($badges)) : ?>
                            <ul class="gc-cta-badges" aria-label="<?php echo esc_attr($badges_aria); ?>">
                                <?php foreach ($badges as $badge) :
                                    $badge_text = $badge['badge_text'] ?? '';

                                    if (!$badge_text) {
                                        continue;
                                    }
                                    ?>
                                    <li><?php $this->render_badge_icon($badge, $settings); ?> <?php echo esc_html($badge_text); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <?php if ($button_one_text || $button_two_text) : ?>
                            <div class="gc-cta-btns hero-btn-wrap two">
                                <?php if ($button_one_text) : ?>
                                    <a class="rr-primary-btn"<?php echo $this->get_link_attributes($button_one_link); ?>><?php echo esc_html($button_one_text); ?></a>
                                <?php endif; ?>

                                <?php if ($button_two_text) : ?>
                                    <a class="rr-primary-btn gc-cta-btn-outline"<?php echo $this->get_link_attributes($button_two_link); ?>><?php echo esc_html($button_two_text); ?></a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($note) : ?>
                            <p class="gc-cta-note"><em><?php echo $this->get_paragraph_inner_content($note); ?></em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_CTA_One_Widget());
