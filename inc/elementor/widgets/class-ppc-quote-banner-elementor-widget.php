<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_PPC_Quote_Banner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_ppc_quote_banner';
    }

    public function get_title()
    {
        return esc_html__('GC PPC Quote Banner', 'softro-core');
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

    private function render_button_icon(array $settings)
    {
        if (!empty($settings['gc_ppc_qb_button_icon']['value'])) {
            $this->render_icon($settings['gc_ppc_qb_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_ppc_qb_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_ppc_qb_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_qb_bg_shape', [
            'label'       => esc_html__('Background Shape', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_qb_bg_shape_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-1.png',
        ]);

        $this->add_control('gc_ppc_qb_shape_1', [
            'label'       => esc_html__('Shape 1', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_qb_shape_1_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-22.png',
        ]);

        $this->add_control('gc_ppc_qb_shape_2', [
            'label'       => esc_html__('Shape 2', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_ppc_qb_shape_2_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/hero-shape-3.png',
        ]);

        $this->add_control('gc_ppc_qb_shape_2_alt', [
            'label'       => esc_html__('Shape 2 Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_content_section', [
            'label' => esc_html__('Banner Content', 'softro-core'),
        ]);

        $this->add_control('gc_ppc_qb_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('All Your Digital Marketing Solutions Get on One Roof', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_ppc_qb_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Our digital marketing services focus on business growth tactics. Does it sound like you and your company need it the most? Watch us enlist and make it happen.',
                'softro-core'
            ),
        ]);

        $this->add_control('gc_ppc_qb_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get Quote', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_qb_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_ppc_qb_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_ppc_qb_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_ppc_qb_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_ppc_qb_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_qb_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-ppc-quote-banner-section',
        ]);

        $this->add_responsive_control('gc_ppc_qb_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_shapes', [
            'label' => esc_html__('Shape Images', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_qb_bg_shape_width', [
            'label'      => esc_html__('Background Shape Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-bg-shape img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_shape_1_width', [
            'label'      => esc_html__('Shape 1 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-shape-1 img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_shape_2_width', [
            'label'      => esc_html__('Shape 2 Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-shape-2 img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_content', [
            'label' => esc_html__('Content Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_ppc_qb_content_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-content' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_qb_title_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-quote-banner-title',
        ]);

        $this->add_control('gc_ppc_qb_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_title_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_qb_desc_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-quote-banner-desc',
        ]);

        $this->add_control('gc_ppc_qb_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_ppc_qb_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_ppc_qb_button_typography',
            'selector' => '{{WRAPPER}} .gc-ppc-quote-banner-btn',
        ]);

        $this->add_control('gc_ppc_qb_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_qb_button_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_button_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-ppc-quote-banner-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_ppc_qb_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-ppc-quote-banner-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-quote-banner-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-ppc-quote-banner-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_ppc_qb_button_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_ppc_qb_button_hover_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_ppc_qb_button_hover_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-ppc-quote-banner-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_ppc_qb_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_ppc_qb_theme_mode_tabs');

        $this->start_controls_tab('gc_ppc_qb_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_qb_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-ppc-quote-banner-section',
        ]);

        $this->add_control('gc_ppc_qb_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_dark_button_hover_color', [
            'label'     => esc_html__('Button Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_dark_button_hover_bg', [
            'label'     => esc_html__('Button Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-ppc-quote-banner-btn:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_ppc_qb_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_ppc_qb_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-ppc-quote-banner-section',
        ]);

        $this->add_control('gc_ppc_qb_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_light_button_hover_color', [
            'label'     => esc_html__('Button Hover Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-btn:hover' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_ppc_qb_light_button_hover_bg', [
            'label'     => esc_html__('Button Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-ppc-quote-banner-btn:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_ppc_qb_reset_elementor_spacing'] ?? 'yes')) {
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

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $title       = $settings['gc_ppc_qb_title'] ?? '';
        $description = $settings['gc_ppc_qb_description'] ?? '';
        $button_text = trim((string) ($settings['gc_ppc_qb_button_text'] ?? ''));

        $bg_shape_url = $this->get_media_url($settings['gc_ppc_qb_bg_shape'] ?? [], $settings['gc_ppc_qb_bg_shape_fallback'] ?? 'new-update/hero-shape-1.png');
        $shape_1_url  = $this->get_media_url($settings['gc_ppc_qb_shape_1'] ?? [], $settings['gc_ppc_qb_shape_1_fallback'] ?? 'new-update/hero-shape-22.png');
        $shape_2_url  = $this->get_media_url($settings['gc_ppc_qb_shape_2'] ?? [], $settings['gc_ppc_qb_shape_2_fallback'] ?? 'new-update/hero-shape-3.png');
        $shape_2_alt  = $settings['gc_ppc_qb_shape_2_alt'] ?? '';

        $this->render_elementor_spacing_fix($settings);
        ?>

        <section class="gc-ppc-quote-banner-section fade-wrapper">
            <div class="gc-ppc-quote-banner-bg-shape" aria-hidden="true">
                <img src="<?php echo esc_url($bg_shape_url); ?>" alt="">
            </div>
            <div class="gc-ppc-quote-banner-shapes" aria-hidden="true">
                <span class="gc-ppc-quote-shape gc-ppc-quote-shape-1"><img src="<?php echo esc_url($shape_1_url); ?>" alt=""></span>
                <span class="gc-ppc-quote-shape gc-ppc-quote-shape-2"><img src="<?php echo esc_url($shape_2_url); ?>" alt="<?php echo esc_attr($shape_2_alt); ?>"></span>
            </div>
            <div class="container">
                <div class="gc-ppc-quote-banner-content fade-top text-center">
                    <?php if ('' !== trim((string) $title)) : ?>
                        <h2 class="gc-ppc-quote-banner-title" data-text-animation="fade-in" data-duration="1.2"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                        <p class="gc-ppc-quote-banner-desc" data-text-animation="fade-in" data-duration="1.4"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                    <?php if ('' !== $button_text) : ?>
                        <a<?php echo $this->get_link_attributes($settings['gc_ppc_qb_button_link'] ?? []); ?> class="rr-primary-btn gc-ppc-quote-banner-btn"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($settings); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_PPC_Quote_Banner_Widget());
