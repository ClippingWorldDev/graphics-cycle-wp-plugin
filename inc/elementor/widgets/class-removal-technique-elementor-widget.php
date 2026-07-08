<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Removal_Technique_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_removal_technique';
    }

    public function get_title()
    {
        return esc_html__('GC Removal Technique', 'softro-core');
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

    private function render_technique_icon(array $item, array $settings)
    {
        if (!empty($item['technique_icon']['value'])) {
            $this->render_icon($item['technique_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['technique_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_rt_default_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_rt_default_icon']['value'])) {
            $this->render_icon($settings['gc_rt_default_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-light fa-check" aria-hidden="true"></i>';
        }
    }

    private function render_button_icon(array $settings)
    {
        if (!empty($settings['gc_rt_button_icon']['value'])) {
            $this->render_icon($settings['gc_rt_button_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($settings['gc_rt_button_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        echo '<i class="fa-regular fa-arrow-right" aria-hidden="true"></i>';
    }

    private function get_default_techniques()
    {
        return [
            [
                'technique_icon'      => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'technique_title'     => esc_html__('Background Removal', 'softro-core'),
                'technique_description' => esc_html__(
                    'Removes the entire background from a product image to create a clean white or transparent result. Commonly used for ecommerce stores, Amazon listings, and product catalogs.',
                    'softro-core'
                ),
            ],
            [
                'technique_icon'      => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'technique_title'     => esc_html__('Image Masking', 'softro-core'),
                'technique_description' => esc_html__(
                    'Preserves complex edges such as hair, fur, glass, smoke, and semi-transparent objects where clipping paths cannot produce natural results.',
                    'softro-core'
                ),
            ],
            [
                'technique_icon'      => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
                'technique_title'     => esc_html__('Clipping Path', 'softro-core'),
                'technique_description' => esc_html__(
                    'Uses hand-drawn vector paths to isolate products with clean, hard edges such as electronics, shoes, furniture, watches, and jewelry.',
                    'softro-core'
                ),
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
        $this->start_controls_section('gc_rt_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_rt_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => esc_html__('Background Removal vs Image Masking vs Clipping Path', 'softro-core'),
            'label_block' => true,
            'rows'        => 2,
        ]);

        $this->add_control('gc_rt_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Understand which editing technique is right for your products and image requirements.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_visual_section', [
            'label' => esc_html__('Visual Image', 'softro-core'),
        ]);

        $this->add_control('gc_rt_visual_image', [
            'label'       => esc_html__('Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_rt_visual_fallback', [
            'type'    => Controls_Manager::HIDDEN,
            'default' => 'new-update/service-img-1.png',
        ]);

        $this->add_control('gc_rt_visual_alt', [
            'label'       => esc_html__('Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Background removal, image masking, and clipping path comparison', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_defaults_section', [
            'label' => esc_html__('Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_rt_default_icon', [
            'label'   => esc_html__('Default Technique Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_rt_default_icon_image', [
            'label'       => esc_html__('Default Technique Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $technique_repeater = new Repeater();

        $technique_repeater->add_control('technique_icon', [
            'label'   => esc_html__('Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-check', 'library' => 'fa-light'],
        ]);

        $technique_repeater->add_control('technique_icon_image', [
            'label'       => esc_html__('Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $technique_repeater->add_control('technique_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Background Removal', 'softro-core'),
            'label_block' => true,
        ]);

        $technique_repeater->add_control('technique_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Technique description goes here.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_rt_techniques_section', [
            'label' => esc_html__('Technique Cards', 'softro-core'),
        ]);

        $this->add_control('gc_rt_techniques', [
            'label'       => esc_html__('Techniques', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $technique_repeater->get_controls(),
            'default'     => $this->get_default_techniques(),
            'title_field' => '{{{ technique_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_button_section', [
            'label' => esc_html__('Button', 'softro-core'),
        ]);

        $this->add_control('gc_rt_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get a Free Trial', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_rt_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->add_control('gc_rt_button_style', [
            'label'   => esc_html__('Button Style', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'primary',
            'options' => [
                'primary'   => esc_html__('Primary', 'softro-core'),
                'secondary' => esc_html__('Secondary', 'softro-core'),
            ],
        ]);

        $this->add_control('gc_rt_button_icon', [
            'label'   => esc_html__('Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_rt_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_shapes_section', [
            'label' => esc_html__('Decorative Shapes', 'softro-core'),
        ]);

        $this->add_control('gc_rt_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_rt_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_rt_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_rt_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-techniques',
        ]);

        $this->add_responsive_control('gc_rt_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_section_shape_size', [
            'label'      => esc_html__('Shape Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques' => 'background-size: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_heading', [
            'label' => esc_html__('Section Heading', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_rt_heading_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_heading_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_rt_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-techniques-heading .section-title',
        ]);

        $this->add_control('gc_rt_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-techniques-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_rt_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_rt_desc_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-techniques-desc',
        ]);

        $this->add_control('gc_rt_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-techniques-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_rt_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_desc_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_visual', [
            'label' => esc_html__('Visual Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_rt_visual_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-techniques-visual',
        ]);

        $this->add_responsive_control('gc_rt_visual_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-visual' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_visual_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-visual' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_visual_min_height', [
            'label'      => esc_html__('Min Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 100, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-visual' => 'min-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_visual_img_max_height', [
            'label'      => esc_html__('Image Max Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 100, 'max' => 900]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-visual img' => 'max-height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_visual_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-visual' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_content', [
            'label' => esc_html__('Content Column', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_rt_content_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_content_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_card', [
            'label' => esc_html__('Technique Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_rt_card_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-bg-removal-technique-card',
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_rt_card_border',
            'selector' => '{{WRAPPER}} .gc-bg-removal-technique-card',
        ]);

        $this->add_responsive_control('gc_rt_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_card_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_rt_card_hover_heading', [
            'label'     => esc_html__('Hover', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_control('gc_rt_card_hover_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-technique-card:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_icon', [
            'label' => esc_html__('Technique Icon', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_rt_icon_box_size', [
            'label'      => esc_html__('Icon Box Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 16, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_icon_font_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 8, 'max' => 40]],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-technique-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-technique-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-technique-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->add_control('gc_rt_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .gc-bg-removal-technique-icon i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .gc-bg-removal-technique-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_control('gc_rt_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-technique-icon' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_rt_icon_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_technique_title', [
            'label' => esc_html__('Technique Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_rt_technique_title_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-technique-copy h3',
        ]);

        $this->add_control('gc_rt_technique_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-technique-copy h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_rt_technique_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-copy h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_technique_desc', [
            'label' => esc_html__('Technique Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_rt_technique_desc_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-technique-copy p',
        ]);

        $this->add_control('gc_rt_technique_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-bg-removal-technique-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_rt_technique_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-technique-copy p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_buttons_wrap', [
            'label' => esc_html__('Buttons Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_rt_buttons_wrap_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-btns' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_buttons_wrap_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-btns' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_rt_style_button', [
            'label' => esc_html__('Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_rt_button_typography',
            'selector' => '{{WRAPPER}} .gc-bg-removal-techniques-btn',
        ]);

        $this->add_responsive_control('gc_rt_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_button_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-bg-removal-techniques-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_rt_button_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-bg-removal-techniques-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-techniques-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-bg-removal-techniques-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_rt_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_rt_theme_mode_tabs');

        $this->start_controls_tab('gc_rt_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_rt_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-bg-removal-techniques',
        ]);

        $this->add_control('gc_rt_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_visual_bg', [
            'label'     => esc_html__('Visual Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-visual' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_visual_border', [
            'label'     => esc_html__('Visual Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-visual' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-technique-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-technique-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_technique_title_color', [
            'label'     => esc_html__('Technique Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-technique-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_technique_desc_color', [
            'label'     => esc_html__('Technique Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-technique-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.gc-bg-removal-technique-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-technique-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_rt_dark_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-technique-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_btn_primary_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_btn_primary_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_btn_secondary_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_dark_btn_secondary_bg', [
            'label'     => esc_html__('Secondary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-bg-removal-techniques-btn--secondary' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_rt_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_rt_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-bg-removal-techniques',
        ]);

        $this->add_control('gc_rt_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_visual_bg', [
            'label'     => esc_html__('Visual Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-visual' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_visual_border', [
            'label'     => esc_html__('Visual Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-visual' => 'border-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-technique-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-technique-card:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_technique_title_color', [
            'label'     => esc_html__('Technique Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-technique-copy h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_technique_desc_color', [
            'label'     => esc_html__('Technique Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-technique-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_icon_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.gc-bg-removal-technique-icon i'   => 'color: {{VALUE}};',
                '.gc-bg-removal-technique-icon svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_rt_light_icon_bg', [
            'label'     => esc_html__('Icon Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-technique-icon' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_btn_primary_color', [
            'label'     => esc_html__('Primary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-btn--primary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_btn_primary_bg', [
            'label'     => esc_html__('Primary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-btn--primary' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_btn_secondary_color', [
            'label'     => esc_html__('Secondary Button Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-btn--secondary' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_rt_light_btn_secondary_bg', [
            'label'     => esc_html__('Secondary Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-bg-removal-techniques-btn--secondary' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_rt_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_shape_backgrounds($settings)
    {
        $section_shape = $this->get_media_url($settings['gc_rt_section_shape'] ?? [], '');

        if (!$section_shape) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .gc-bg-removal-techniques {
                background-image: url('<?php echo esc_url($section_shape); ?>');
                background-repeat: no-repeat;
            }
        </style>
        <?php
    }

    private function render_technique_card(array $item, array $settings)
    {
        $title       = trim((string) ($item['technique_title'] ?? ''));
        $description = $item['technique_description'] ?? '';

        if ('' === $title && '' === trim(wp_strip_all_tags((string) $description))) {
            return;
        }
        ?>
        <div class="gc-bg-removal-technique-card fade-top">
            <span class="gc-bg-removal-technique-icon" aria-hidden="true"><?php $this->render_technique_icon($item, $settings); ?></span>
            <div class="gc-bg-removal-technique-copy">
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                    <p><?php echo $this->get_paragraph_inner_content($description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $title       = $settings['gc_rt_title'] ?? '';
        $description = $settings['gc_rt_description'] ?? '';
        $techniques  = $settings['gc_rt_techniques'] ?? [];
        $button_text = trim((string) ($settings['gc_rt_button_text'] ?? ''));
        $button_style = $settings['gc_rt_button_style'] ?? 'primary';

        if (empty($techniques)) {
            $techniques = $this->get_default_techniques();
        }

        $visual_url = $this->get_media_url($settings['gc_rt_visual_image'] ?? [], $settings['gc_rt_visual_fallback'] ?? 'new-update/service-img-1.png');
        $visual_alt = $settings['gc_rt_visual_alt'] ?? '';

        $this->render_elementor_spacing_fix($settings);
        $this->render_shape_backgrounds($settings);
        ?>

        <section class="gc-bg-removal-techniques pt-130 pb-130 fade-wrapper">
            <div class="container">
                <?php if ('' !== trim((string) $title) || '' !== trim(wp_strip_all_tags((string) $description))) : ?>
                    <div class="section-heading text-center gc-bg-removal-techniques-heading">
                        <?php if ('' !== trim((string) $title)) : ?>
                            <h2 class="section-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ('' !== trim(wp_strip_all_tags((string) $description))) : ?>
                            <p class="gc-bg-removal-techniques-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <div class="row g-4 g-xl-5 align-items-center">
                    <?php if ($visual_url) : ?>
                        <div class="col-lg-6">
                            <div class="gc-bg-removal-techniques-visual fade-top">
                                <img src="<?php echo esc_url($visual_url); ?>" alt="<?php echo esc_attr($visual_alt); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="gc-bg-removal-techniques-content">
                            <?php foreach ($techniques as $item) {
                                $this->render_technique_card($item, $settings);
                            } ?>
                            <?php if ('' !== $button_text) : ?>
                                <div class="gc-bg-removal-techniques-btns fade-top">
                                    <a<?php echo $this->get_link_attributes($settings['gc_rt_button_link'] ?? []); ?> class="gc-bg-removal-techniques-btn gc-bg-removal-techniques-btn--<?php echo esc_attr($button_style); ?>"><?php echo esc_html($button_text); ?> <?php $this->render_button_icon($settings); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Removal_Technique_Widget());
