<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_About_Social_Media_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_about_social_media';
    }

    public function get_title()
    {
        return esc_html__('GC About Social Media', 'softro-core');
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

    private function render_inline_icon($icon_settings, $icon_image)
    {
        if (!empty($icon_settings['value'])) {
            $this->render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($icon_image, '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
        }
    }

    private function get_default_counters()
    {
        return [
            [
                'counter_number' => '98',
                'counter_suffix' => '%',
                'counter_label'  => esc_html__('Client Retention Rate', 'softro-core'),
            ],
            [
                'counter_number' => '125',
                'counter_suffix' => '+',
                'counter_label'  => esc_html__('Global Projects Delivered', 'softro-core'),
            ],
            [
                'counter_number' => '250',
                'counter_suffix' => '+',
                'counter_label'  => esc_html__('Social Campaigns Managed', 'softro-core'),
            ],
        ];
    }

    private function get_default_explore_cards()
    {
        return [
            [
                'card_layout'      => 'intro',
                'intro_title'      => '<p>' . esc_html__('Explore the', 'softro-core') . '<br>' . esc_html__('creative process', 'softro-core') . '</p>',
                'intro_button_text'=> esc_html__('Call for Joining', 'softro-core'),
                'intro_button_link'=> ['url' => '#'],
            ],
            [
                'card_layout'       => 'step',
                'step_number'       => '01',
                'step_name'         => esc_html__('Research', 'softro-core'),
                'step_description'  => esc_html__('Focussed on understanding your business requirements, users and problems', 'softro-core'),
            ],
            [
                'card_layout'       => 'step',
                'step_number'       => '02',
                'step_name'         => esc_html__('Ideation & design', 'softro-core'),
                'step_description'  => esc_html__('Focussed on understanding your business requirements, users and problems', 'softro-core'),
            ],
            [
                'card_layout'       => 'step',
                'step_number'       => '03',
                'step_name'         => esc_html__('Development', 'softro-core'),
                'step_description'  => esc_html__('Focussed on understanding your business requirements, users and problems', 'softro-core'),
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
        $this->start_controls_section('gc_about_social_images_section', [
            'label' => esc_html__('About Images', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_image_1', [
            'label'       => esc_html__('Primary Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('new-update-3/about-16-img-1.png')],
        ]);

        $this->add_control('gc_about_social_image_1_alt', [
            'label'       => esc_html__('Primary Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Social media marketing team at work', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_social_image_2', [
            'label'       => esc_html__('Secondary Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'default'     => ['url' => $this->get_theme_img_url('new-update-3/about-16-img-2.png')],
        ]);

        $this->add_control('gc_about_social_image_2_alt', [
            'label'       => esc_html__('Secondary Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Social media campaign results', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_heading_section', [
            'label' => esc_html__('About Content', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('About our Agency', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_social_title', [
            'label'   => esc_html__('Title', 'softro-core'),
            'type'    => Controls_Manager::TEXTAREA,
            'default' => esc_html__('Social marketing & advertising.', 'softro-core'),
            'rows'    => 2,
        ]);

        $this->add_control('gc_about_social_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('We provide digital experience services to start up and small businesses. We help our clients succeed by creating brand identities, digital experiences, and print materials. Install any demo, plugin or template in a matter of seconds.', 'softro-core') . '</p>',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_button_section', [
            'label' => esc_html__('Primary Button', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_button_text', [
            'label'       => esc_html__('Button Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Get in Touch', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_about_social_button_link', [
            'label'       => esc_html__('Button Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $this->add_control('gc_about_social_button_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $this->add_control('gc_about_social_button_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->end_controls_section();

        $counter_repeater = new Repeater();

        $counter_repeater->add_control('counter_number', [
            'label'       => esc_html__('Number', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '98',
            'label_block' => true,
        ]);

        $counter_repeater->add_control('counter_suffix', [
            'label'       => esc_html__('Suffix', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => '%',
            'label_block' => true,
        ]);

        $counter_repeater->add_control('counter_label', [
            'label'       => esc_html__('Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Client Retention Rate', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_about_social_counters_section', [
            'label' => esc_html__('Counters', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_counters', [
            'label'       => esc_html__('Counter Items', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $counter_repeater->get_controls(),
            'default'     => $this->get_default_counters(),
            'title_field' => '{{{ counter_label }}}',
        ]);

        $this->end_controls_section();

        $explore_repeater = new Repeater();

        $explore_repeater->add_control('card_layout', [
            'label'   => esc_html__('Card Layout', 'softro-core'),
            'type'    => Controls_Manager::SELECT,
            'default' => 'step',
            'options' => [
                'intro' => esc_html__('Intro Card', 'softro-core'),
                'step'  => esc_html__('Step Card', 'softro-core'),
            ],
        ]);

        $explore_repeater->add_control('intro_title', [
            'label'     => esc_html__('Intro Title', 'softro-core'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => '<p>' . esc_html__('Explore the creative process', 'softro-core') . '</p>',
            'condition' => ['card_layout' => 'intro'],
        ]);

        $explore_repeater->add_control('intro_button_text', [
            'label'     => esc_html__('Intro Button Text', 'softro-core'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Call for Joining', 'softro-core'),
            'condition' => ['card_layout' => 'intro'],
        ]);

        $explore_repeater->add_control('intro_button_link', [
            'label'     => esc_html__('Intro Button Link', 'softro-core'),
            'type'      => Controls_Manager::URL,
            'default'   => ['url' => '#'],
            'condition' => ['card_layout' => 'intro'],
        ]);

        $explore_repeater->add_control('intro_button_icon', [
            'label'     => esc_html__('Intro Button Icon', 'softro-core'),
            'type'      => Controls_Manager::ICONS,
            'condition' => ['card_layout' => 'intro'],
        ]);

        $explore_repeater->add_control('intro_button_icon_image', [
            'label'       => esc_html__('Intro Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'condition'   => ['card_layout' => 'intro'],
        ]);

        $explore_repeater->add_control('step_number', [
            'label'     => esc_html__('Step Number', 'softro-core'),
            'type'      => Controls_Manager::TEXT,
            'default'   => '01',
            'condition' => ['card_layout' => 'step'],
        ]);

        $explore_repeater->add_control('step_icon', [
            'label'     => esc_html__('Step Icon', 'softro-core'),
            'type'      => Controls_Manager::ICONS,
            'condition' => ['card_layout' => 'step'],
        ]);

        $explore_repeater->add_control('step_icon_image', [
            'label'       => esc_html__('Step Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'condition'   => ['card_layout' => 'step'],
        ]);

        $explore_repeater->add_control('step_name', [
            'label'     => esc_html__('Step Name', 'softro-core'),
            'type'      => Controls_Manager::TEXT,
            'default'   => esc_html__('Research', 'softro-core'),
            'condition' => ['card_layout' => 'step'],
        ]);

        $explore_repeater->add_control('step_description', [
            'label'     => esc_html__('Step Description', 'softro-core'),
            'type'      => Controls_Manager::WYSIWYG,
            'default'   => esc_html__('Focussed on understanding your business requirements, users and problems', 'softro-core'),
            'condition' => ['card_layout' => 'step'],
        ]);

        $this->start_controls_section('gc_about_social_explore_section', [
            'label' => esc_html__('Explore Cards', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_explore_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $explore_repeater->get_controls(),
            'default'     => $this->get_default_explore_cards(),
            'title_field' => '{{{ card_layout === "intro" ? intro_button_text : step_name }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_shape_section', [
            'label' => esc_html__('Section Shape', 'softro-core'),
        ]);

        $this->add_control('gc_about_social_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_about_social_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_social_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .about-section-16__area.gc-social-about',
        ]);

        $this->add_responsive_control('gc_about_social_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__area.gc-social-about' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_about_social_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__area.gc-social-about' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_image_1', [
            'label' => esc_html__('Primary Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_social_image_1_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 100, 'max' => 700]],
            'selectors'  => ['{{WRAPPER}} .about-section-16-img-box .img-1' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_about_social_image_1_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 100, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .about-section-16-img-box .img-1' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_about_social_image_1_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .about-section-16-img-box .img-1'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                '{{WRAPPER}} .about-section-16-img-box .img-1 img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_image_2', [
            'label' => esc_html__('Secondary Image', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_social_image_2_max_width', [
            'label'      => esc_html__('Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'range'      => ['px' => ['min' => 80, 'max' => 500]],
            'selectors'  => ['{{WRAPPER}} .about-section-16-img-box .img-2' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_about_social_image_2_height', [
            'label'      => esc_html__('Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 80, 'max' => 500]],
            'selectors'  => ['{{WRAPPER}} .about-section-16-img-box .img-2' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('gc_about_social_image_2_border_color', [
            'label'     => esc_html__('Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16-img-box .img-2' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_about_social_image_2_border_width', [
            'label'      => esc_html__('Border Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 30]],
            'selectors'  => ['{{WRAPPER}} .about-section-16-img-box .img-2' => 'border-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_content_wrap', [
            'label' => esc_html__('Content Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_social_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_eyebrow_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__info .sub-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_eyebrow_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__info .sub-title' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_eyebrow_typography',
            'selector' => '{{WRAPPER}} .about-section-16__info .sub-title',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__info .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_title_typography',
            'selector' => '{{WRAPPER}} .about-section-16__info .title',
        ]);

        $this->add_responsive_control('gc_about_social_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_desc', [
            'label' => esc_html__('Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__info .decs' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_desc_typography',
            'selector' => '{{WRAPPER}} .about-section-16__info .decs',
        ]);

        $this->add_responsive_control('gc_about_social_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__info .decs' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_button', [
            'label' => esc_html__('Primary Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_button_color', [
            'label'     => esc_html__('Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_button_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_button_typography',
            'selector' => '{{WRAPPER}} .about-section-16__btn .rr-primary-btn',
        ]);

        $this->add_responsive_control('gc_about_social_button_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__btn .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_about_social_button_margin', [
            'label'      => esc_html__('Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .about-section-16__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_counter', [
            'label' => esc_html__('Counters', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_counter_number_color', [
            'label'     => esc_html__('Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .about-section-16__counter-2 .counters-item .title'      => 'color: {{VALUE}};',
                '{{WRAPPER}} .about-section-16__counter-2 .counters-item .title span' => 'color: {{VALUE}};',
            ],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_counter_number_typography',
            'selector' => '{{WRAPPER}} .about-section-16__counter-2 .counters-item .title',
        ]);

        $this->add_control('gc_about_social_counter_label_color', [
            'label'     => esc_html__('Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__counter-2 .counters-item .decs' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_counter_label_typography',
            'selector' => '{{WRAPPER}} .about-section-16__counter-2 .counters-item .decs',
        ]);

        $this->add_control('gc_about_social_counter_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .about-section-16__counter-2 .counters-item' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_about_social_counter_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 40]],
            'selectors'  => ['{{WRAPPER}} .about-section-16__counter-2' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_explore_wrap', [
            'label' => esc_html__('Explore Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_about_social_explore_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .explore-16-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_explore_intro', [
            'label' => esc_html__('Explore Intro Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_explore_intro_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_explore_intro_title_typography',
            'selector' => '{{WRAPPER}} .explore-16-content .title',
        ]);

        $this->add_control('gc_about_social_explore_intro_btn_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-btn .rr-primary-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_explore_intro_btn_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-btn .rr-primary-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_about_social_style_explore_step', [
            'label' => esc_html__('Explore Step Cards', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_about_social_explore_number_color', [
            'label'     => esc_html__('Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-content .number' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_explore_name_color', [
            'label'     => esc_html__('Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-content .name' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_explore_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-content .decs' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_explore_number_typography',
            'selector' => '{{WRAPPER}} .explore-16-content .number',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_explore_name_typography',
            'selector' => '{{WRAPPER}} .explore-16-content .name',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_about_social_explore_desc_typography',
            'selector' => '{{WRAPPER}} .explore-16-content .decs',
        ]);

        $this->add_control('gc_about_social_explore_item_border_color', [
            'label'     => esc_html__('Item Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-item' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_about_social_explore_content_border_color', [
            'label'     => esc_html__('Content Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .explore-16-content' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_about_social_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_about_social_theme_mode_tabs');

        $this->start_controls_tab('gc_about_social_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_social_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .about-section-16__area.gc-social-about',
        ]);

        $this->add_control('gc_about_social_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__info .sub-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__info .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_counter_number_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.about-section-16__counter-2 .counters-item .title'      => 'color: {{VALUE}};',
                '.about-section-16__counter-2 .counters-item .title span' => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_social_dark_counter_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__counter-2 .counters-item .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_counter_card_bg', [
            'label'     => esc_html__('Counter Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.about-section-16__counter-2 .counters-item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_explore_title_color', [
            'label'     => esc_html__('Explore Intro Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.explore-16-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_explore_number_color', [
            'label'     => esc_html__('Explore Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.explore-16-content .number' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_explore_name_color', [
            'label'     => esc_html__('Explore Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.explore-16-content .name' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_dark_explore_desc_color', [
            'label'     => esc_html__('Explore Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.explore-16-content .decs' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_about_social_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_about_social_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .about-section-16__area.gc-social-about',
        ]);

        $this->add_control('gc_about_social_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__info .sub-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__info .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__info .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_button_color', [
            'label'     => esc_html__('Button Text Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__btn .rr-primary-btn' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_button_bg', [
            'label'     => esc_html__('Button Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__btn .rr-primary-btn' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_counter_number_color', [
            'label'     => esc_html__('Counter Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.about-section-16__counter-2 .counters-item .title'      => 'color: {{VALUE}};',
                '.about-section-16__counter-2 .counters-item .title span' => 'color: {{VALUE}};',
            ]),
        ]);

        $this->add_control('gc_about_social_light_counter_label_color', [
            'label'     => esc_html__('Counter Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__counter-2 .counters-item .decs' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_counter_card_bg', [
            'label'     => esc_html__('Counter Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.about-section-16__counter-2 .counters-item' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_explore_title_color', [
            'label'     => esc_html__('Explore Intro Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.explore-16-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_explore_number_color', [
            'label'     => esc_html__('Explore Number Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.explore-16-content .number' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_explore_name_color', [
            'label'     => esc_html__('Explore Name Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.explore-16-content .name' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_about_social_light_explore_desc_color', [
            'label'     => esc_html__('Explore Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.explore-16-content .decs' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_about_social_reset_elementor_spacing']) || 'yes' !== $settings['gc_about_social_reset_elementor_spacing']) {
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
        $shape_url = $this->get_media_url($settings['gc_about_social_section_shape'] ?? [], '');

        if (!$shape_url) {
            return;
        }
        ?>
        <style>
            .elementor-element-<?php echo esc_attr($this->get_id()); ?> .about-section-16__area.gc-social-about {
                background-image: url('<?php echo esc_url($shape_url); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
        <?php
    }

    private function render_explore_card(array $item)
    {
        $layout = $item['card_layout'] ?? 'step';

        if ('intro' === $layout) {
            $intro_title = $this->get_paragraph_inner_content($item['intro_title'] ?? '');
            $button_text = $item['intro_button_text'] ?? '';
            $button_link = $item['intro_button_link'] ?? ['url' => '#'];
            ?>
            <div class="col-xl-3 col-lg-6 col-md-6">
                <div class="explore-16-item fade-top">
                    <div class="explore-16-content">
                        <?php if ('' !== $intro_title) : ?>
                            <h2 class="title"><?php echo wp_kses($intro_title, ['br' => []]); ?></h2>
                        <?php endif; ?>
                        <div class="explore-16-btn">
                            <a<?php echo $this->get_link_attributes($button_link); ?> class="rr-primary-btn">
                                <?php echo esc_html($button_text); ?>
                                <?php $this->render_inline_icon($item['intro_button_icon'] ?? [], $item['intro_button_icon_image'] ?? []); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return;
        }

        $step_number      = $item['step_number'] ?? '01';
        $step_name        = $item['step_name'] ?? '';
        $step_description = $this->get_paragraph_inner_content($item['step_description'] ?? '');
        ?>
        <div class="col-xl-3 col-lg-6 col-md-6">
            <div class="explore-16-item fade-top">
                <div class="explore-16-content">
                    <h2 class="number"><?php echo esc_html($step_number); ?></h2>
                    <?php if ('' !== $step_name || !empty($item['step_icon']['value']) || !empty($item['step_icon_image']['url'])) : ?>
                        <h3 class="name">
                            <?php $this->render_inline_icon($item['step_icon'] ?? [], $item['step_icon_image'] ?? []); ?>
                            <?php echo esc_html($step_name); ?>
                        </h3>
                    <?php endif; ?>
                    <?php if ('' !== $step_description) : ?>
                        <p class="decs"><?php echo wp_kses($step_description, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_section_shape_background($settings);

        $image_1     = $this->get_media_url($settings['gc_about_social_image_1'] ?? [], 'new-update-3/about-16-img-1.png');
        $image_1_alt = $settings['gc_about_social_image_1_alt'] ?? esc_attr__('Social media marketing team at work', 'softro-core');
        $image_2     = $this->get_media_url($settings['gc_about_social_image_2'] ?? [], 'new-update-3/about-16-img-2.png');
        $image_2_alt = $settings['gc_about_social_image_2_alt'] ?? esc_attr__('Social media campaign results', 'softro-core');

        $eyebrow     = $settings['gc_about_social_eyebrow'] ?? '';
        $title       = $settings['gc_about_social_title'] ?? '';
        $description = $this->get_paragraph_inner_content($settings['gc_about_social_description'] ?? '');
        $button_text = $settings['gc_about_social_button_text'] ?? esc_html__('Get in Touch', 'softro-core');
        $button_link = $settings['gc_about_social_button_link'] ?? ['url' => '#'];

        $counters = !empty($settings['gc_about_social_counters']) ? $settings['gc_about_social_counters'] : $this->get_default_counters();
        $explore  = !empty($settings['gc_about_social_explore_cards']) ? $settings['gc_about_social_explore_cards'] : $this->get_default_explore_cards();

        ?>
        <section class="about-section-16__area gc-social-about pt-130 pb-130 fade-wrapper">
            <div class="container container-16">
                <div class="row">
                    <div class="col-xl-6 col-lg-5">
                        <div class="about-section-16-img-box">
                            <?php if ($image_1) : ?>
                                <div class="img-1 fade-top">
                                    <img src="<?php echo esc_url($image_1); ?>" alt="<?php echo esc_attr($image_1_alt); ?>">
                                </div>
                            <?php endif; ?>
                            <?php if ($image_2) : ?>
                                <div class="img-2 fade-top">
                                    <img src="<?php echo esc_url($image_2); ?>" alt="<?php echo esc_attr($image_2_alt); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-7">
                        <div class="about-section-16__item">
                            <div class="about-section-16__content">
                                <div class="about-section-16__info fade-top">
                                    <?php if ('' !== trim((string) $eyebrow)) : ?>
                                        <h4 class="sub-title " data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                                    <?php endif; ?>
                                    <?php if ('' !== trim((string) $title)) : ?>
                                        <h2 class="title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.04"><?php echo esc_html($title); ?></h2>
                                    <?php endif; ?>
                                    <?php if ('' !== $description) : ?>
                                        <p class="decs fade-top"><?php echo wp_kses($description, ['br' => []]); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="about-section-16__btn fade-top">
                                    <a<?php echo $this->get_link_attributes($button_link); ?> class="rr-primary-btn">
                                        <?php echo esc_html($button_text); ?>
                                        <?php $this->render_inline_icon($settings['gc_about_social_button_icon'] ?? [], $settings['gc_about_social_button_icon_image'] ?? []); ?>
                                    </a>

                                </div>
                                <?php if (!empty($counters)) : ?>
                                    <div class="about-section-16__counter-2 fade-top">
                                        <?php foreach ($counters as $counter) : ?>
                                            <?php
                                            $number = $counter['counter_number'] ?? '0';
                                            $suffix = $counter['counter_suffix'] ?? '';
                                            $label  = $counter['counter_label'] ?? '';
                                            ?>
                                            <div class="counters-item">

                                                <h3 class="title"><span class="odometer" data-count="<?php echo esc_attr($number); ?>">0</span><?php echo esc_html($suffix); ?></h3>
                                                <?php if ('' !== $label) : ?>
                                                    <p class="decs"><?php echo esc_html($label); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($explore)) : ?>
                        <div class="explore-16-wrapper fade-wr apper">
                            <div class="row">
                                <?php foreach ($explore as $item) : ?>
                                    <?php $this->render_explore_card($item); ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_About_Social_Media_Widget());
