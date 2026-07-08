<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_About_Web_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_about_web';
    }

    public function get_title()
    {
        return esc_html__('GC About Web', 'softro-core');
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

    private function render_list_icon($item, $settings)
    {
        if (!empty($item['list_icon']['value'])) {
            $this->render_icon($item['list_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['list_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_about_web_default_list_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_about_web_default_list_icon']['value'])) {
            $this->render_icon($settings['gc_about_web_default_list_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_title_with_breaks($title)
    {
        $title = (string) $title;

        if ('' === trim($title)) {
            return;
        }

        echo wp_kses(
            str_replace(["\r\n", "\r", "\n"], '<br>', esc_html($title)),
            ['br' => []]
        );
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_about_web_heading_section', ['label' => esc_html__('Section Heading', 'softro-core')]);

        $this->add_control('gc_about_web_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('About Our Company', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'description' => esc_html__('Use a new line for line breaks.', 'softro-core'),
            'type' => Controls_Manager::TEXTAREA,
            'default' => "Connecting People And\nBuild Technology",
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_experience_section', ['label' => esc_html__('Experience Box', 'softro-core')]);

        $this->add_control('gc_about_web_exp_year', [
            'label' => esc_html__('Years Number', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => '25',
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_exp_label', [
            'label' => esc_html__('Experience Label', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Years Experience', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_exp_description', [
            'label' => esc_html__('Experience Description', 'softro-core'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Lorem ipsum dolor sit amet consectur adipiscing elit eiusmod ex tempor incididunt labore dolore magna aliquaenim ad minim veniam quis nostrud exercitation laboris.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $list_repeater = new Repeater();

        $list_repeater->add_control('list_text', [
            'label' => esc_html__('List Text', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Custom Solutions', 'softro-core'),
            'label_block' => true,
        ]);

        $list_repeater->add_control('list_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-regular'],
        ]);

        $list_repeater->add_control('list_icon_image', [
            'label' => esc_html__('Custom Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->start_controls_section('gc_about_web_list_section', ['label' => esc_html__('Why Choose List', 'softro-core')]);

        $this->add_control('gc_about_web_list_label', [
            'label' => esc_html__('List Heading', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Why Choose Us', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_default_list_icon', [
            'label' => esc_html__('Default List Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-circle-check', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_about_web_default_list_icon_image', [
            'label' => esc_html__('Default List Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_about_web_list_items', [
            'label' => esc_html__('List Items', 'softro-core'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $list_repeater->get_controls(),
            'default' => [
                ['list_text' => esc_html__('Custom Solutions', 'softro-core')],
                ['list_text' => esc_html__('SEO-Ready Architecture', 'softro-core')],
                ['list_text' => esc_html__('Lightning-Fast Performance', 'softro-core')],
                ['list_text' => esc_html__('Mobile-First Development', 'softro-core')],
                ['list_text' => esc_html__('Secure & Scalable Technology', 'softro-core')],
                ['list_text' => esc_html__('Dedicated Experts & Support', 'softro-core')],
            ],
            'title_field' => '{{{ list_text }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_button_section', ['label' => esc_html__('Button', 'softro-core')]);

        $this->add_control('gc_about_web_button_text', [
            'label' => esc_html__('Button Text', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Get Started Now', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_button_link', [
            'label' => esc_html__('Button Link', 'softro-core'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-sharp fa-regular fa-arrow-right', 'library' => 'fa-sharp fa-regular'],
        ]);

        $this->add_control('gc_about_web_button_icon_image', [
            'label' => esc_html__('Button Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_images_section', ['label' => esc_html__('Images & Shapes', 'softro-core')]);

        $this->add_control('gc_about_web_shape_one', [
            'label' => esc_html__('Shape One', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('shapes/about-shape-4.png')],
        ]);

        $this->add_control('gc_about_web_shape_one_alt', [
            'label' => esc_html__('Shape One Alt', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_shape_two', [
            'label' => esc_html__('Shape Two', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('shapes/about-shape-5.png')],
        ]);

        $this->add_control('gc_about_web_shape_two_alt', [
            'label' => esc_html__('Shape Two Alt', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('shape', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_image_one', [
            'label' => esc_html__('Main Image One', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('images/about-img-8.png')],
        ]);

        $this->add_control('gc_about_web_image_one_alt', [
            'label' => esc_html__('Main Image One Alt', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('img', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_web_image_two', [
            'label' => esc_html__('Main Image Two', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('images/about-img-9.png')],
        ]);

        $this->add_control('gc_about_web_image_two_alt', [
            'label' => esc_html__('Main Image Two Alt', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('img', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_about_web_style_layout', ['label' => esc_html__('Layout', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('gc_about_web_reset_elementor_spacing', ['label' => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes']);
        $this->add_responsive_control('gc_about_web_section_padding_bottom', ['label' => esc_html__('Section Bottom Padding', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'default' => ['size' => 130, 'unit' => 'px'], 'selectors' => ['{{WRAPPER}} .about-section-10' => 'padding-bottom: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_section', ['label' => esc_html__('Section', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_about_web_section_bg', 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} .about-section-10']);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_eyebrow', ['label' => esc_html__('Eyebrow', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_eyebrow_typography', 'selector' => '{{WRAPPER}} .section-heading .sub-heading']);
        $this->add_control('gc_about_web_eyebrow_color', ['label' => esc_html__('Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .section-heading .sub-heading' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_about_web_heading_margin', ['label' => esc_html__('Heading Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_title', ['label' => esc_html__('Title', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_title_typography', 'selector' => '{{WRAPPER}} .section-heading .section-title']);
        $this->add_control('gc_about_web_title_color', ['label' => esc_html__('Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .section-heading .section-title' => 'color: {{VALUE}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_exp', ['label' => esc_html__('Experience Box', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_exp_year_typography', 'label' => esc_html__('Year Typography', 'softro-core'), 'selector' => '{{WRAPPER}} .about-exp-box .year']);
        $this->add_control('gc_about_web_exp_year_color', ['label' => esc_html__('Year Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-exp-box .year' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_exp_text_typography', 'label' => esc_html__('Text Typography', 'softro-core'), 'selector' => '{{WRAPPER}} .about-exp-box p']);
        $this->add_control('gc_about_web_exp_text_color', ['label' => esc_html__('Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-exp-box p' => 'color: {{VALUE}};']]);
        $this->add_control('gc_about_web_exp_label_color', ['label' => esc_html__('Label Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-exp-box p span' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_about_web_exp_box_margin', ['label' => esc_html__('Box Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .about-exp-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_list', ['label' => esc_html__('Why Choose List', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_list_label_typography', 'label' => esc_html__('Heading Typography', 'softro-core'), 'selector' => '{{WRAPPER}} .about-list-wrap > span']);
        $this->add_control('gc_about_web_list_label_color', ['label' => esc_html__('Heading Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-list-wrap > span' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_list_typography', 'selector' => '{{WRAPPER}} .about-list li']);
        $this->add_control('gc_about_web_list_color', ['label' => esc_html__('List Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-list li' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_about_web_list_icon_size', ['label' => esc_html__('Icon Size', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .about-list li i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .about-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .about-list li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_control('gc_about_web_list_icon_color', ['label' => esc_html__('Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-list li i' => 'color: {{VALUE}};', '{{WRAPPER}} .about-list li svg' => 'fill: {{VALUE}};']]);
        $this->add_responsive_control('gc_about_web_list_wrap_margin', ['label' => esc_html__('List Wrap Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .about-list-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_button', ['label' => esc_html__('Button', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_about_web_button_typography', 'selector' => '{{WRAPPER}} .about-btn .rr-primary-btn']);
        $this->add_control('gc_about_web_button_color', ['label' => esc_html__('Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-btn .rr-primary-btn' => 'color: {{VALUE}};']]);
        $this->add_control('gc_about_web_button_bg', ['label' => esc_html__('Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .about-btn .rr-primary-btn' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('gc_about_web_button_padding', ['label' => esc_html__('Padding', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .about-btn .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('gc_about_web_button_icon_size', ['label' => esc_html__('Icon Size', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .about-btn .rr-primary-btn i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .about-btn .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_about_web_style_images', ['label' => esc_html__('Images & Shapes', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('gc_about_web_shape_one_width', ['label' => esc_html__('Shape One Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .about-img-wrap-5 .shape-1 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_about_web_shape_two_width', ['label' => esc_html__('Shape Two Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .about-img-wrap-5 .shape-2 img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_about_web_image_one_width', ['label' => esc_html__('Image One Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .about-img .img-1' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_about_web_image_two_width', ['label' => esc_html__('Image Two Width', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .about-img-2 .img-2' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_about_web_style_theme_mode', ['label' => esc_html__('Dark / Light Mode Colors', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->start_controls_tabs('gc_about_web_theme_mode_tabs');

        $this->start_controls_tab('gc_about_web_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_about_web_dark_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=dark] {{WRAPPER}} .about-section-10']);
        $this->add_control('gc_about_web_dark_eyebrow_color', ['label' => esc_html__('Eyebrow Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.section-heading .sub-heading' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.section-heading .section-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_exp_year_color', ['label' => esc_html__('Experience Year Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-exp-box .year' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_exp_text_color', ['label' => esc_html__('Experience Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-exp-box p' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_list_color', ['label' => esc_html__('List Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-list li' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_list_icon_color', ['label' => esc_html__('List Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-list li i' => 'color: {{VALUE}};', '.about-list li svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_button_color', ['label' => esc_html__('Button Text', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-btn .rr-primary-btn' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_dark_button_bg', ['label' => esc_html__('Button Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.about-btn .rr-primary-btn' => 'background-color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->start_controls_tab('gc_about_web_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_about_web_light_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=light] {{WRAPPER}} .about-section-10']);
        $this->add_control('gc_about_web_light_eyebrow_color', ['label' => esc_html__('Eyebrow Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.section-heading .sub-heading' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.section-heading .section-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_exp_year_color', ['label' => esc_html__('Experience Year Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-exp-box .year' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_exp_text_color', ['label' => esc_html__('Experience Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-exp-box p' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_list_color', ['label' => esc_html__('List Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-list li' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_list_icon_color', ['label' => esc_html__('List Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-list li i' => 'color: {{VALUE}};', '.about-list li svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_button_color', ['label' => esc_html__('Button Text', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-btn .rr-primary-btn' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_about_web_light_button_bg', ['label' => esc_html__('Button Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.about-btn .rr-primary-btn' => 'background-color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_about_web_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .reveal { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_button_icon($settings)
    {
        if (!empty($settings['gc_about_web_button_icon']['value'])) {
            $this->render_icon($settings['gc_about_web_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_about_web_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow     = $settings['gc_about_web_eyebrow'] ?? '';
        $title       = $settings['gc_about_web_title'] ?? '';
        $exp_year    = $settings['gc_about_web_exp_year'] ?? '';
        $exp_label   = $settings['gc_about_web_exp_label'] ?? '';
        $exp_desc    = $settings['gc_about_web_exp_description'] ?? '';
        $list_label  = $settings['gc_about_web_list_label'] ?? '';
        $list_items  = !empty($settings['gc_about_web_list_items']) ? $settings['gc_about_web_list_items'] : [];
        $button_text = $settings['gc_about_web_button_text'] ?? '';
        $button_link = $settings['gc_about_web_button_link'] ?? [];
        $shape_one   = $this->get_media_url($settings['gc_about_web_shape_one'] ?? [], 'shapes/about-shape-4.png');
        $shape_two   = $this->get_media_url($settings['gc_about_web_shape_two'] ?? [], 'shapes/about-shape-5.png');
        $image_one   = $this->get_media_url($settings['gc_about_web_image_one'] ?? [], 'images/about-img-8.png');
        $image_two   = $this->get_media_url($settings['gc_about_web_image_two'] ?? [], 'images/about-img-9.png');
        ?>

        <section class="about-section-10 gc-web-about-section pb-130 overflow-hidden">
            <div class="container">
                <div class="row gy-lg-0 gy-4">
                    <div class="col-lg-6">
                        <div class="about-content-10 about-content-7 fade-wrapper">
                            <?php if ($eyebrow || $title) : ?>
                                <div class="section-heading mb-40">
                                    <?php if ($eyebrow) : ?>
                                        <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($title) : ?>
                                        <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php $this->render_title_with_breaks($title); ?></h2>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($exp_year || $exp_label || $exp_desc) : ?>
                                <div class="about-exp-box fade-top">
                                    <?php if ($exp_year) : ?>
                                        <h3 class="year"><?php echo esc_html($exp_year); ?></h3>
                                    <?php endif; ?>
                                    <?php if ($exp_label || $exp_desc) : ?>
                                        <p>
                                            <?php if ($exp_label) : ?>
                                                <span><?php echo esc_html($exp_label); ?></span>
                                            <?php endif; ?>
                                            <?php echo $this->get_paragraph_inner_content($exp_desc); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($list_label || !empty($list_items)) : ?>
                                <div class="about-list-wrap gc-web-about-list-wrap fade-top">
                                    <?php if ($list_label) : ?>
                                        <span><?php echo esc_html($list_label); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($list_items)) : ?>
                                        <ul class="about-list">
                                            <?php foreach ($list_items as $item) :
                                                $list_text = $item['list_text'] ?? '';
                                                if (!$list_text) {
                                                    continue;
                                                }
                                                ?>
                                                <li><?php $this->render_list_icon($item, $settings); ?><?php echo esc_html($list_text); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($button_text) : ?>
                                <div class="about-btn fade-top">
                                    <a class="rr-primary-btn"<?php echo $this->get_link_attributes($button_link); ?>>
                                        <?php echo esc_html($button_text); ?>
                                        <?php $this->render_button_icon($settings); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-img-wrap-5">
                            <div class="shapes">
                                <div class="shape shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="<?php echo esc_attr($settings['gc_about_web_shape_one_alt'] ?? esc_html__('shape', 'softro-core')); ?>"></div>
                                <div class="shape shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="<?php echo esc_attr($settings['gc_about_web_shape_two_alt'] ?? esc_html__('shape', 'softro-core')); ?>"></div>
                            </div>
                            <div class="about-img reveal">
                                <img class="img-1" src="<?php echo esc_url($image_one); ?>" alt="<?php echo esc_attr($settings['gc_about_web_image_one_alt'] ?? esc_html__('img', 'softro-core')); ?>">
                            </div>
                            <div class="about-img-2 reveal">
                                <img class="img-2" src="<?php echo esc_url($image_two); ?>" alt="<?php echo esc_attr($settings['gc_about_web_image_two_alt'] ?? esc_html__('img', 'softro-core')); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_About_Web_Widget());
