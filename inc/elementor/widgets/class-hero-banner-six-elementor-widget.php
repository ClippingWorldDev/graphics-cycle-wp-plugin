<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_Six_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_hero_banner_six';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner Six', 'softro-core');
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

    private function render_icon_or_image($icon_settings, $icon_image, $args = [])
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, $args);
            return;
        }

        $icon_url = $this->get_media_url($icon_image ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function get_default_compare_tabs()
    {
        return [
            [
                'tab_label'    => esc_html__('Apparel', 'softro-core'),
                'tab_icon'     => ['value' => 'fa-light fa-shirt', 'library' => 'fa-light'],
                'before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                'after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                'before_alt'   => esc_html__('Apparel before background removal', 'softro-core'),
                'after_alt'    => esc_html__('Apparel after background removal', 'softro-core'),
            ],
            [
                'tab_label'    => esc_html__('Accessories', 'softro-core'),
                'tab_icon'     => ['value' => 'fa-light fa-bag-shopping', 'library' => 'fa-light'],
                'before_image' => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
                'after_image'  => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
                'before_alt'   => esc_html__('Accessories before background removal', 'softro-core'),
                'after_alt'    => esc_html__('Accessories after background removal', 'softro-core'),
            ],
            [
                'tab_label'    => esc_html__('Product', 'softro-core'),
                'tab_icon'     => ['value' => 'fa-light fa-box', 'library' => 'fa-light'],
                'before_image' => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                'after_image'  => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                'before_alt'   => esc_html__('Product before background removal', 'softro-core'),
                'after_alt'    => esc_html__('Product after background removal', 'softro-core'),
            ],
            [
                'tab_label'    => esc_html__('Furniture', 'softro-core'),
                'tab_icon'     => ['value' => 'fa-light fa-couch', 'library' => 'fa-light'],
                'before_image' => ['url' => $this->get_theme_img_url('new-update/project-img-3.png')],
                'after_image'  => ['url' => $this->get_theme_img_url('new-update/project-img-4.png')],
                'before_alt'   => esc_html__('Furniture before background removal', 'softro-core'),
                'after_alt'    => esc_html__('Furniture after background removal', 'softro-core'),
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
        $this->start_controls_section('gc_hero_six_shapes_section', [
            'label' => esc_html__('Background Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_shape_bg', [
            'label'   => esc_html__('Background Shape', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-1.png')],
        ]);

        $this->add_control('gc_hero_six_shape_bg_alt', [
            'label'       => esc_html__('Background Shape Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_shape_one', [
            'label'   => esc_html__('Shape One', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-22.png')],
        ]);

        $this->add_control('gc_hero_six_shape_one_alt', [
            'label'       => esc_html__('Shape One Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '',
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_shape_two', [
            'label'   => esc_html__('Shape Two', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-shape-3.png')],
        ]);

        $this->add_control('gc_hero_six_shape_two_alt', [
            'label'       => esc_html__('Shape Two Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_content_section', [
            'label' => esc_html__('Hero Content', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get Natural, Professional Photo Retouching Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('From skin retouching to color correction, every image is edited by hand for results that look real, never overdone.', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_description_2', [
            'label'   => esc_html__('Description (Second)', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Harsh lighting, uneven skin tones, or dull colors can make even great photos fall flat. At Graphics Cycle, our editors manually retouch every image with precision, so your photos look polished, natural, and ready to use.', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_trust_bar', [
            'label'   => esc_html__('Trust Bar', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<b>' . esc_html__('Trust bar (just before button): ', 'softro-core') . '</b><br>' . esc_html__('From $1.20/image · Free revisions · Hand-Edited by Experts', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get a free quote', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_hero_six_button_link', [
            'label'   => esc_html__('Button Link', 'softro-core'),
            'type'    => Controls_Manager::URL,
            'default' => ['url' => '#'],
        ]);

        $this->add_control('gc_hero_six_button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_hero_six_button_icon_image', [
            'label' => esc_html__('Button Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_compare_section', [
            'label' => esc_html__('Compare Slider', 'softro-core'),
        ]);

        $this->add_control('gc_hero_six_before_label', [
            'label'       => esc_html__('Before Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Before', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_after_label', [
            'label'       => esc_html__('After Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('After', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_compare_arrow_icon', [
            'label'   => esc_html__('Compare Arrow Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-arrow-right', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_hero_six_compare_arrow_icon_image', [
            'label' => esc_html__('Compare Arrow Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $this->add_control('gc_hero_six_tabs_aria_label', [
            'label'       => esc_html__('Tabs Aria Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Background removal examples', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $tab_repeater = new Repeater();

        $tab_repeater->add_control('tab_label', [
            'label'       => esc_html__('Tab Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Apparel', 'softro-core'),
            'label_block' => true,
        ]);

        $tab_repeater->add_control('tab_icon', [
            'label'   => esc_html__('Tab Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-shirt', 'library' => 'fa-light'],
        ]);

        $tab_repeater->add_control('tab_icon_image', [
            'label' => esc_html__('Tab Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $tab_repeater->add_control('before_image', [
            'label'   => esc_html__('Before Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
        ]);

        $tab_repeater->add_control('before_alt', [
            'label'       => esc_html__('Before Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Apparel before background removal', 'softro-core'),
            'label_block' => true,
        ]);

        $tab_repeater->add_control('after_image', [
            'label'   => esc_html__('After Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
        ]);

        $tab_repeater->add_control('after_alt', [
            'label'       => esc_html__('After Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Apparel after background removal', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_hero_six_compare_tabs', [
            'label'       => esc_html__('Compare Tabs', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $tab_repeater->get_controls(),
            'default'     => $this->get_default_compare_tabs(),
            'title_field' => '{{{ tab_label }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_hero_six_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_hero_six_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_hero_six_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .hero-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-hero-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_six_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .hero-section-11',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_shapes', [
            'label' => esc_html__('Shapes', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_hero_six_shape_bg_width', [
            'label'      => esc_html__('Background Shape Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .bg-shape img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_control('gc_hero_six_shape_bg_opacity', [
            'label'     => esc_html__('Background Shape Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .bg-shape img' => 'opacity: {{SIZE}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_shape_one_width', [
            'label'      => esc_html__('Shape One Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_responsive_control('gc_hero_six_shape_two_width', [
            'label'      => esc_html__('Shape Two Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .shapes .shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_six_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-title',
        ]);

        $this->add_control('gc_hero_six_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_description', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_six_desc_typography',
            'label'    => esc_html__('First Description Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-bg-removal-desc:not(.gc-bg-removal-desc--2)',
        ]);

        $this->add_control('gc_hero_six_desc_color', [
            'label'     => esc_html__('First Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-desc:not(.gc-bg-removal-desc--2)' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_six_desc_2_typography',
            'label'     => esc_html__('Second Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-desc--2',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_six_desc_2_color', [
            'label'     => esc_html__('Second Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-desc--2' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_six_trust_typography',
            'label'     => esc_html__('Trust Bar Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-hero-content > p:not([class])',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_six_trust_color', [
            'label'     => esc_html__('Trust Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-hero-content > p:not([class])' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_hero_six_btn_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn',
        ]);

        $this->add_control('gc_hero_six_btn_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_six_btn_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn',
        ]);

        $this->add_responsive_control('gc_hero_six_btn_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-btns .rr-primary-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_hero_six_style_compare', [
            'label' => esc_html__('Compare Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_six_compare_card_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-compare-card',
        ]);

        $this->add_responsive_control('gc_hero_six_compare_card_padding', [
            'label'      => esc_html__('Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-compare-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_hero_six_compare_label_typography',
            'label'     => esc_html__('Label Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-bg-removal-compare-label',
            'separator' => 'before',
        ]);

        $this->add_control('gc_hero_six_compare_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-compare-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_hero_six_compare_image_height', [
            'label'      => esc_html__('Compare Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'vh'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-panel img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;'],
        ]);

        $this->add_responsive_control('gc_hero_six_tab_icon_size', [
            'label'      => esc_html__('Tab Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-tab i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-tab svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-tab img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_hero_six_tab_color', [
            'label'     => esc_html__('Tab Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-tab i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-tab svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_hero_six_tab_active_color', [
            'label'     => esc_html__('Active Tab Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-tab.active i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-tab.active svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_hero_six_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_hero_six_theme_mode_tabs');

        $this->start_controls_tab('gc_hero_six_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_six_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .hero-section-11',
        ]);

        $this->add_control('gc_hero_six_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_dark_trust_color', [
            'label'     => esc_html__('Trust Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-hero-content > p:not([class])' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_dark_btn_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-btns .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_dark_compare_label_color', [
            'label'     => esc_html__('Compare Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-compare-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_dark_tab_color', [
            'label'     => esc_html__('Tab Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-tab i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-tab svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_hero_six_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_hero_six_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .hero-section-11',
        ]);

        $this->add_control('gc_hero_six_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_light_trust_color', [
            'label'     => esc_html__('Trust Bar Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-hero-content > p:not([class])' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_light_btn_color', [
            'label'     => esc_html__('Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-btns .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_light_compare_label_color', [
            'label'     => esc_html__('Compare Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-compare-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_hero_six_light_tab_color', [
            'label'     => esc_html__('Tab Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-tab i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-tab svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_hero_six_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> [data-text-animation],
            .elementor-element-<?php echo $widget_id; ?> .anim-text { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_compare_tab($tab, $index)
    {
        $label        = $tab['tab_label'] ?? '';
        $before_url   = $this->get_media_url($tab['before_image'] ?? [], 'new-update/hero-img-1.png');
        $after_url    = $this->get_media_url($tab['after_image'] ?? [], 'new-update/hero-img-2.png');
        $is_active    = 0 === $index;
        $active_class = $is_active ? ' active' : '';
        $aria_selected = $is_active ? 'true' : 'false';
        ?>
        <button type="button" class="gc-bg-removal-tab<?php echo esc_attr($active_class); ?>" role="tab"
            aria-selected="<?php echo esc_attr($aria_selected); ?>" aria-label="<?php echo esc_attr($label); ?>"
            data-label="<?php echo esc_attr($label); ?>"
            data-before="<?php echo esc_attr($before_url); ?>"
            data-after="<?php echo esc_attr($after_url); ?>">
            <?php $this->render_icon_or_image($tab['tab_icon'] ?? [], $tab['tab_icon_image'] ?? [], ['aria-hidden' => 'true']); ?>
        </button>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $shape_bg      = $this->get_media_url($settings['gc_hero_six_shape_bg'] ?? [], 'new-update/hero-shape-1.png');
        $shape_bg_alt  = $settings['gc_hero_six_shape_bg_alt'] ?? esc_html__('shape', 'softro-core');
        $shape_one     = $this->get_media_url($settings['gc_hero_six_shape_one'] ?? [], 'new-update/hero-shape-22.png');
        $shape_one_alt = $settings['gc_hero_six_shape_one_alt'] ?? '';
        $shape_two     = $this->get_media_url($settings['gc_hero_six_shape_two'] ?? [], 'new-update/hero-shape-3.png');
        $shape_two_alt = $settings['gc_hero_six_shape_two_alt'] ?? esc_html__('shape', 'softro-core');

        $title         = $settings['gc_hero_six_title'] ?? '';
        $description   = $settings['gc_hero_six_description'] ?? '';
        $description_2 = $settings['gc_hero_six_description_2'] ?? '';
        $trust_bar     = $settings['gc_hero_six_trust_bar'] ?? '';
        $button_text   = $settings['gc_hero_six_button_text'] ?? '';
        $button_link   = $settings['gc_hero_six_button_link'] ?? [];
        $button_icon   = $settings['gc_hero_six_button_icon'] ?? [];
        $button_icon_img = $settings['gc_hero_six_button_icon_image'] ?? [];

        $before_label  = $settings['gc_hero_six_before_label'] ?? esc_html__('Before', 'softro-core');
        $after_label   = $settings['gc_hero_six_after_label'] ?? esc_html__('After', 'softro-core');
        $arrow_icon    = $settings['gc_hero_six_compare_arrow_icon'] ?? [];
        $arrow_icon_img = $settings['gc_hero_six_compare_arrow_icon_image'] ?? [];
        $tabs_aria     = $settings['gc_hero_six_tabs_aria_label'] ?? esc_html__('Background removal examples', 'softro-core');
        $compare_tabs  = !empty($settings['gc_hero_six_compare_tabs']) ? $settings['gc_hero_six_compare_tabs'] : $this->get_default_compare_tabs();

        $first_tab     = $compare_tabs[0] ?? [];
        $panel_before  = $this->get_media_url($first_tab['before_image'] ?? [], 'new-update/hero-img-1.png');
        $panel_after   = $this->get_media_url($first_tab['after_image'] ?? [], 'new-update/hero-img-2.png');
        $panel_before_alt = $first_tab['before_alt'] ?? esc_html__('Apparel before background removal', 'softro-core');
        $panel_after_alt  = $first_tab['after_alt'] ?? esc_html__('Apparel after background removal', 'softro-core');
        ?>

        <section class="hero-section-11 gc-bg-removal-hero">
            <?php if ($shape_bg) : ?>
                <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="<?php echo esc_attr($shape_bg_alt); ?>"></div>
            <?php endif; ?>
            <div class="shapes">
                <?php if ($shape_one) : ?>
                    <div class="shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="<?php echo esc_attr($shape_one_alt); ?>"></div>
                <?php endif; ?>
                <?php if ($shape_two) : ?>
                    <div class="shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="<?php echo esc_attr($shape_two_alt); ?>"></div>
                <?php endif; ?>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11 gc-bg-removal-hero-row">
                    <div class="col-lg-6">
                        <div class="gc-bg-removal-hero-content fade-wrapper">
                            <?php if ($title) : ?>
                                <h1 class="title anim-text gc-bg-removal-title"><?php echo esc_html($title); ?></h1>
                            <?php endif; ?>
                            <?php if ($description) : ?>
                                <p class="gc-bg-removal-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                            <?php endif; ?>
                            <?php if ($description_2) : ?>
                                <p class="gc-bg-removal-desc gc-bg-removal-desc--2"><?php echo $this->get_paragraph_inner_content($description_2); ?></p>
                            <?php endif; ?>
                            <?php if ($trust_bar) : ?>
                                <p><?php echo $this->get_paragraph_inner_content($trust_bar); ?></p>
                            <?php endif; ?>
                            <?php if ($button_text) : ?>
                                <div class="hero-btn-wrap gc-bg-removal-btns">
                                    <a class="rr-primary-btn"<?php echo $this->get_link_attributes($button_link); ?>><?php echo esc_html($button_text); ?> <?php $this->render_icon_or_image($button_icon, $button_icon_img); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="gc-bg-removal-compare-wrap fade-top" data-bg-removal-compare>
                            <div class="gc-bg-removal-compare-card">
                                <div class="gc-bg-removal-compare-header">
                                    <span class="gc-bg-removal-compare-label"><?php echo esc_html($before_label); ?></span>
                                    <span class="gc-bg-removal-compare-arrow" aria-hidden="true"><?php $this->render_icon_or_image($arrow_icon, $arrow_icon_img); ?></span>
                                    <span class="gc-bg-removal-compare-label"><?php echo esc_html($after_label); ?></span>
                                </div>
                                <div class="gc-bg-removal-compare-panels">
                                    <div class="gc-bg-removal-panel gc-bg-removal-panel--before">
                                        <img src="<?php echo esc_url($panel_before); ?>"
                                            alt="<?php echo esc_attr($panel_before_alt); ?>" data-compare-before>
                                    </div>
                                    <div class="gc-bg-removal-panel gc-bg-removal-panel--after">
                                        <img src="<?php echo esc_url($panel_after); ?>"
                                            alt="<?php echo esc_attr($panel_after_alt); ?>" data-compare-after>
                                    </div>
                                </div>
                                <?php if (!empty($compare_tabs)) : ?>
                                    <div class="gc-bg-removal-tabs" role="tablist" aria-label="<?php echo esc_attr($tabs_aria); ?>">
                                        <?php foreach ($compare_tabs as $index => $tab) {
                                            $this->render_compare_tab($tab, $index);
                                        } ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_Six_Widget());
