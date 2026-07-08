<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_Three_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_hero_banner_three';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner Three', 'softro-core');
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

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_hero_three_shapes_section',
            [
                'label' => esc_html__('Background Shapes', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_bg',
            [
                'label'   => esc_html__('Background Shape', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-1.png')],
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_bg_alt',
            [
                'label'       => esc_html__('Background Shape Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('shape', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_one',
            [
                'label'   => esc_html__('Shape One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-22.png')],
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_one_alt',
            [
                'label'       => esc_html__('Shape One Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_two',
            [
                'label'   => esc_html__('Shape Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-3.png')],
            ]
        );

        $this->add_control(
            'gc_hero_three_shape_two_alt',
            [
                'label'       => esc_html__('Shape Two Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('shape', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_three_content_section',
            [
                'label' => esc_html__('Hero Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_three_title_before',
            [
                'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Professional Web Solution', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_title_accent',
            [
                'label'       => esc_html__('Title Accent', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_title_after',
            [
                'label'       => esc_html__('Title (After Accent)', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('for Modern Businesses', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('We build starts with your business goals, not a template. We combine UX, technical SEO, and conversion-focused design to create websites that help turn visitors into customers. We provide high-quality apps and custom software development services tailored to your requirements.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_three_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact US', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_three_button_link',
            [
                'label'       => esc_html__('Button Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'default'     => ['url' => '#'],
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control(
            'card_title',
            [
                'label'       => esc_html__('Card Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Corporate Website', 'softro-core'),
                'label_block' => true,
            ]
        );

        $card_repeater->add_control(
            'card_image',
            [
                'label'   => esc_html__('Card Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
            ]
        );

        $card_repeater->add_control(
            'card_image_alt',
            [
                'label'       => esc_html__('Image Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Website Development', 'softro-core'),
                'label_block' => true,
            ]
        );

        $card_repeater->add_control(
            'card_link',
            [
                'label'       => esc_html__('Card Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'default'     => ['url' => '#'],
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_hero_three_cards_section',
            [
                'label' => esc_html__('Service Cards', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_three_service_cards',
            [
                'label'       => esc_html__('Cards', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $card_repeater->get_controls(),
                'default'     => [
                    ['card_title' => esc_html__('Corporate Website', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')], 'card_image_alt' => esc_html__('Website Development', 'softro-core')],
                    ['card_title' => esc_html__('E-Commerce Website', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')], 'card_image_alt' => esc_html__('App Development', 'softro-core')],
                    ['card_title' => esc_html__('School / College', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')], 'card_image_alt' => esc_html__('Custom Software', 'softro-core')],
                    ['card_title' => esc_html__('Consultancy Website', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')], 'card_image_alt' => esc_html__('UI UX Design', 'softro-core')],
                    ['card_title' => esc_html__('WordPress Website', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')], 'card_image_alt' => esc_html__('E-Commerce Solutions', 'softro-core')],
                    ['card_title' => esc_html__('Web Services', 'softro-core'), 'card_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')], 'card_image_alt' => esc_html__('CMS and Maintenance', 'softro-core')],
                ],
                'title_field' => '{{{ card_title }}}',
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_hero_three_style_layout', ['label' => esc_html__('Layout', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('gc_hero_three_reset_elementor_spacing', ['label' => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes']);
        $this->add_responsive_control('gc_hero_three_section_padding', ['label' => esc_html__('Section Padding', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .hero-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_section', ['label' => esc_html__('Section', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_hero_three_section_bg', 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} .hero-section-11']);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_shapes', ['label' => esc_html__('Shapes', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('gc_hero_three_shape_bg_width', ['label' => esc_html__('Background Shape Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .bg-shape img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_hero_three_shape_one_width', ['label' => esc_html__('Shape One Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .shapes .shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_hero_three_shape_two_width', ['label' => esc_html__('Shape Two Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .shapes .shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_title', ['label' => esc_html__('Title', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_hero_three_title_typography', 'selector' => '{{WRAPPER}} .hero-info-4 .title']);
        $this->add_control('gc_hero_three_title_color', ['label' => esc_html__('Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .hero-info-4 .title' => 'color: {{VALUE}};']]);
        $this->add_control('gc_hero_three_title_accent_color', ['label' => esc_html__('Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-video-3d-hero-accent' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_hero_three_title_margin', ['label' => esc_html__('Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .hero-info-4 .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_description', ['label' => esc_html__('Description', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_hero_three_desc_typography', 'selector' => '{{WRAPPER}} .hero-info-4 p']);
        $this->add_control('gc_hero_three_desc_color', ['label' => esc_html__('Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .hero-info-4 p' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_hero_three_desc_margin', ['label' => esc_html__('Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .hero-info-4 p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_button', ['label' => esc_html__('Button', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_hero_three_button_typography', 'selector' => '{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn']);
        $this->add_control('gc_hero_three_button_color', ['label' => esc_html__('Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};']]);
        $this->add_control('gc_hero_three_button_bg', ['label' => esc_html__('Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('gc_hero_three_button_padding', ['label' => esc_html__('Padding', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_hero_three_style_cards', ['label' => esc_html__('Service Cards', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_hero_three_card_title_typography', 'selector' => '{{WRAPPER}} .service-card-info .title']);
        $this->add_control('gc_hero_three_card_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .service-card-info .title' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_hero_three_card_image_height', ['label' => esc_html__('Card Image Height', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}} .service-card-img img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;']]);
        $this->add_responsive_control('gc_hero_three_card_image_radius', ['label' => esc_html__('Card Image Radius', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .service-card-img, {{WRAPPER}} .service-card-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('gc_hero_three_card_gap', ['label' => esc_html__('Grid Gap', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}} .hero-services-grid' => 'gap: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_hero_three_style_theme_mode', ['label' => esc_html__('Dark / Light Mode Colors', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->start_controls_tabs('gc_hero_three_theme_mode_tabs');

        $this->start_controls_tab('gc_hero_three_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_hero_three_dark_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=dark] {{WRAPPER}} .hero-section-11']);
        $this->add_control('gc_hero_three_dark_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_dark_title_accent_color', ['label' => esc_html__('Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-hero-accent' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_dark_desc_color', ['label' => esc_html__('Description Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 p' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_dark_button_color', ['label' => esc_html__('Button Text', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_dark_button_bg', ['label' => esc_html__('Button Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_dark_card_title_color', ['label' => esc_html__('Card Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.service-card-info .title' => 'color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->start_controls_tab('gc_hero_three_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_hero_three_light_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=light] {{WRAPPER}} .hero-section-11']);
        $this->add_control('gc_hero_three_light_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_light_title_accent_color', ['label' => esc_html__('Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-hero-accent' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_light_desc_color', ['label' => esc_html__('Description Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 p' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_light_button_color', ['label' => esc_html__('Button Text', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_light_button_bg', ['label' => esc_html__('Button Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.hero-info-4 .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};'])]);
        $this->add_control('gc_hero_three_light_card_title_color', ['label' => esc_html__('Card Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.service-card-info .title' => 'color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_hero_three_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_service_card($card)
    {
        $title = $card['card_title'] ?? '';
        $image = $this->get_media_url($card['card_image'] ?? [], 'new-update/hero-img-2.png');
        $alt   = $card['card_image_alt'] ?? $title;
        $link  = $card['card_link'] ?? [];

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
            </div>
        </a>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $shape_bg      = $this->get_media_url($settings['gc_hero_three_shape_bg'] ?? [], 'new-update/hero-shape-1.png');
        $shape_bg_alt  = $settings['gc_hero_three_shape_bg_alt'] ?? esc_html__('shape', 'softro-core');
        $shape_one     = $this->get_media_url($settings['gc_hero_three_shape_one'] ?? [], 'new-update/hero-shape-22.png');
        $shape_one_alt = $settings['gc_hero_three_shape_one_alt'] ?? '';
        $shape_two     = $this->get_media_url($settings['gc_hero_three_shape_two'] ?? [], 'new-update/hero-shape-3.png');
        $shape_two_alt = $settings['gc_hero_three_shape_two_alt'] ?? esc_html__('shape', 'softro-core');
        $title_before  = $settings['gc_hero_three_title_before'] ?? '';
        $title_accent  = $settings['gc_hero_three_title_accent'] ?? '';
        $title_after   = $settings['gc_hero_three_title_after'] ?? '';
        $description   = $settings['gc_hero_three_description'] ?? '';
        $button_text   = $settings['gc_hero_three_button_text'] ?? '';
        $button_link   = $settings['gc_hero_three_button_link'] ?? [];
        $cards         = !empty($settings['gc_hero_three_service_cards']) ? $settings['gc_hero_three_service_cards'] : [];
        ?>

        <section class="hero-section-11 gc-web-solutions-hero">
            <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="<?php echo esc_attr($shape_bg_alt); ?>"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="<?php echo esc_attr($shape_one_alt); ?>"></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="<?php echo esc_attr($shape_two_alt); ?>"></div>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11">
                    <div class="col-lg-6">
                        <div class="hero-info hero-info-3 hero-info-4">
                            <?php if ($title_before || $title_accent || $title_after) : ?>
                                <h1 class="title anim-text">
                                    <?php echo esc_html($title_before); ?>
                                    <?php if ($title_accent) : ?>
                                        <span class="gc-video-3d-hero-accent gc-video-3d-hero-accent--primary"><?php echo esc_html($title_accent); ?></span>
                                    <?php endif; ?>
                                    <?php echo esc_html($title_after); ?>
                                </h1>
                            <?php endif; ?>

                            <?php if ($description) : ?>
                                <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
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
                                <div class="hero-services-grid gc-web-solutions-hero-grid">
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

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_Three_Widget());
