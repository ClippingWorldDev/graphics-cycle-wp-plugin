<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Footer_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_footer_one';
    }

    public function get_title()
    {
        return esc_html__('GC Footer One', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    /**
     * @param string $path
     * @return string
     */
    private function get_theme_img_url($path)
    {
        return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($path, '/'));
    }

    /**
     * @param array  $media
     * @param string $fallback_path
     * @return string
     */
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

    /**
     * @param array $link_settings
     * @return string
     */
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

    /**
     * @param string $content
     * @return string
     */
    private function get_multiline_text($content)
    {
        if ('' === trim((string) $content)) {
            return '';
        }

        return wp_kses($content, [
            'br' => [],
        ]);
    }

    /**
     * @param string $mode dark|light
     * @param string $selector
     * @return string
     */
    private function get_theme_mode_selector($mode, $selector)
    {
        return sprintf('[data-theme=%s] {{WRAPPER}} %s', $mode, $selector);
    }

    /**
     * @param string $mode
     * @param array  $selectors
     * @return array
     */
    private function get_theme_mode_selectors($mode, array $selectors)
    {
        $output = [];

        foreach ($selectors as $selector => $property) {
            $output[$this->get_theme_mode_selector($mode, $selector)] = $property;
        }

        return $output;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    /**
     * Content tab controls.
     */
    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_footer_one_logo_section',
            [
                'label' => esc_html__('Logo', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_logo_dark',
            [
                'label'   => esc_html__('Logo (Dark)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-2.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_footer_one_logo_light',
            [
                'label'   => esc_html__('Logo (Light)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-3.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_footer_one_logo_link',
            [
                'label'       => esc_html__('Logo Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => home_url('/'),
                ],
            ]
        );

        $this->end_controls_section();

        $social_repeater = new Repeater();

        $social_repeater->add_control(
            'social_class',
            [
                'label'       => esc_html__('CSS Class', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'facebook',
                'description' => esc_html__('Example: facebook, pinterest, twitter, instagram', 'softro-core'),
                'label_block' => true,
            ]
        );

        $social_repeater->add_control(
            'social_link',
            [
                'label'       => esc_html__('Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $social_repeater->add_control(
            'social_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $social_repeater->add_control(
            'social_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_footer_one_social_section',
            [
                'label' => esc_html__('Social Links', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_social_items',
            [
                'label'       => esc_html__('Social Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $social_repeater->get_controls(),
                'default'     => [
                    [
                        'social_class' => 'facebook',
                        'social_icon'  => ['value' => 'fab fa-facebook-f', 'library' => 'fa-brands'],
                    ],
                    [
                        'social_class' => 'pinterest',
                        'social_icon'  => ['value' => 'fab fa-pinterest', 'library' => 'fa-brands'],
                    ],
                    [
                        'social_class' => 'twitter',
                        'social_icon'  => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                    ],
                    [
                        'social_class' => 'instagram',
                        'social_icon'  => ['value' => 'fab fa-instagram', 'library' => 'fa-brands'],
                    ],
                ],
                'title_field' => '{{{ social_class }}}',
            ]
        );

        $this->end_controls_section();

        $menu_repeater = new Repeater();

        $menu_repeater->add_control(
            'menu_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('About Us', 'softro-core'),
                'label_block' => true,
            ]
        );

        $menu_repeater->add_control(
            'menu_link',
            [
                'label'       => esc_html__('Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_footer_one_company_section',
            [
                'label' => esc_html__('Company Column', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_company_title',
            [
                'label'       => esc_html__('Widget Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Company', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_company_items',
            [
                'label'       => esc_html__('Menu Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $menu_repeater->get_controls(),
                'default'     => [
                    ['menu_text' => esc_html__('About Us', 'softro-core')],
                    ['menu_text' => esc_html__('Our Gallery', 'softro-core')],
                    ['menu_text' => esc_html__('Our Services', 'softro-core')],
                    ['menu_text' => esc_html__('Our Team', 'softro-core')],
                ],
                'title_field' => '{{{ menu_text }}}',
            ]
        );

        $this->end_controls_section();

        $time_repeater = new Repeater();

        $time_repeater->add_control(
            'time_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Mon - Fri: 9:00 AM - 5:00 PM', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_footer_one_working_time_section',
            [
                'label' => esc_html__('Working Time Column', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_working_time_title',
            [
                'label'       => esc_html__('Widget Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Working Time', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_working_time_items',
            [
                'label'       => esc_html__('Time Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $time_repeater->get_controls(),
                'default'     => [
                    ['time_text' => esc_html__('Mon - Fri: 9:00 AM - 5:00 PM', 'softro-core')],
                    ['time_text' => esc_html__('Saturday: 10:00 AM - 6:00 PM', 'softro-core')],
                    ['time_text' => esc_html__('Sunday Closed', 'softro-core')],
                ],
                'title_field' => '{{{ time_text }}}',
            ]
        );

        $this->end_controls_section();

        $contact_repeater = new Repeater();

        $contact_repeater->add_control(
            'contact_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('support@agency.com', 'softro-core'),
                'rows'        => 2,
            ]
        );

        $contact_repeater->add_control(
            'contact_link',
            [
                'label'       => esc_html__('Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('mailto: or tel: or https://', 'softro-core'),
                'default'     => [
                    'url' => '',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_footer_one_contact_section',
            [
                'label' => esc_html__('Contact Column', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_contact_title',
            [
                'label'       => esc_html__('Widget Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact Us', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_contact_items',
            [
                'label'       => esc_html__('Contact Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $contact_repeater->get_controls(),
                'default'     => [
                    [
                        'contact_text' => 'support@agency.com',
                        'contact_link' => ['url' => 'mailto:support@agency.com'],
                    ],
                    [
                        'contact_text' => '+258 (549) 2158 3215',
                        'contact_link' => ['url' => 'tel:+2585492153215'],
                    ],
                    [
                        'contact_text' => "2589 Dorland Street Luke INUA <br> Berlin, Germany",
                        'contact_link' => ['url' => ''],
                    ],
                ],
                'title_field' => '{{{ contact_text }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_newsletter_section',
            [
                'label' => esc_html__('Newsletter Column', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_newsletter_title',
            [
                'label'       => esc_html__('Widget Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Subscribe newsletter', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_newsletter_placeholder',
            [
                'label'       => esc_html__('Email Placeholder', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Email address', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_newsletter_button',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Sign Up', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_newsletter_action',
            [
                'label'       => esc_html__('Form Action Value', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'mailchimpsubscribe',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_newsletter_checkbox_label',
            [
                'label'       => esc_html__('Checkbox Label', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__("I'm okay with getting emails and having that tracked to improve my experience", 'softro-core'),
                'rows'        => 3,
            ]
        );

        $this->end_controls_section();

        $copyright_repeater = new Repeater();

        $copyright_repeater->add_control(
            'copyright_link_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Security', 'softro-core'),
                'label_block' => true,
            ]
        );

        $copyright_repeater->add_control(
            'copyright_link_url',
            [
                'label'       => esc_html__('Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_footer_one_copyright_section',
            [
                'label' => esc_html__('Copyright', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_footer_one_copyright_text',
            [
                'label'       => esc_html__('Copyright Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('© 2025 GraphicsCycle. All Rights Reserved.', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_footer_one_copyright_links',
            [
                'label'       => esc_html__('Copyright Links', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $copyright_repeater->get_controls(),
                'default'     => [
                    ['copyright_link_text' => esc_html__('Security', 'softro-core')],
                    ['copyright_link_text' => esc_html__('Privacy & Cookie Policy', 'softro-core')],
                    ['copyright_link_text' => esc_html__('Terms of Services', 'softro-core')],
                ],
                'title_field' => '{{{ copyright_link_text }}}',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Style tab controls.
     */
    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_footer_one_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_footer_one_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_footer',
            [
                'label' => esc_html__('Footer', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section.footer-2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section.footer-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_footer_top',
            [
                'label' => esc_html__('Footer Top', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_top_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_top_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_logo',
            [
                'label' => esc_html__('Logo', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_logo_dark_width',
            [
                'label'      => esc_html__('Dark Logo Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 40,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .footer-logo .logo-dark' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_logo_light_width',
            [
                'label'      => esc_html__('Light Logo Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 40,
                        'max' => 300,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .footer-logo .logo-light' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_logo_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-logo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_social',
            [
                'label' => esc_html__('Social Icons', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_social_icon_size',
            [
                'label'      => esc_html__('Icon Font Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .social-list li a i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .social-list li a svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_social_image_size',
            [
                'label'      => esc_html__('Icon Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .social-list li a i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                    '{{WRAPPER}} .social-list li a img'     => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_social_list_margin',
            [
                'label'      => esc_html__('List Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .social-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_social_item_margin',
            [
                'label'      => esc_html__('Item Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .social-list li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_widget_title',
            [
                'label' => esc_html__('Widget Titles', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_widget_title_typography',
                'selector' => '{{WRAPPER}} .footer-widget .widget-title',
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_widget_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-widget .widget-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_widget_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-widget .widget-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_footer_list',
            [
                'label' => esc_html__('Footer Lists', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_footer_list_typography',
                'selector' => '{{WRAPPER}} .footer-list li, {{WRAPPER}} .footer-list li a, {{WRAPPER}} .address-list li, {{WRAPPER}} .address-list li a',
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_list_item_margin',
            [
                'label'      => esc_html__('Item Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-list li'    => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .address-list li'   => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_widget_padding',
            [
                'label'      => esc_html__('Widget Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_footer_wrap_margin',
            [
                'label'      => esc_html__('Footer Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_newsletter',
            [
                'label' => esc_html__('Newsletter Form', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_newsletter_input_typography',
                'label'    => esc_html__('Input Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .footer-form .form-control',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_newsletter_button_typography',
                'label'    => esc_html__('Button Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .footer-form .submit',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_newsletter_checkbox_typography',
                'label'    => esc_html__('Checkbox Label Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .form-check-label',
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_newsletter_form_margin',
            [
                'label'      => esc_html__('Form Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-form' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_newsletter_form_padding',
            [
                'label'      => esc_html__('Form Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_footer_one_style_copyright',
            [
                'label' => esc_html__('Copyright Area', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_copyright_text_typography',
                'label'    => esc_html__('Copyright Text Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .copyright-content p',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_footer_one_copyright_links_typography',
                'label'    => esc_html__('Copyright Links Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .copyright-list li a',
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_copyright_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .copyright-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_footer_one_copyright_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .copyright-area' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    /**
     * Dark / light mode color controls for theme switcher.
     */
    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_footer_one_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_footer_one_theme_mode_color_tabs');

        $this->start_controls_tab(
            'gc_footer_one_theme_mode_dark_tab',
            [
                'label' => esc_html__('Dark Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_footer_one_dark_footer_bg',
                'label'    => esc_html__('Footer Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .footer-section.footer-2',
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_footer_top_border',
            [
                'label'     => esc_html__('Footer Top Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-top' => 'border-bottom-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_widget_title_color',
            [
                'label'     => esc_html__('Widget Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-widget .widget-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_list_color',
            [
                'label'     => esc_html__('List Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-list li'      => 'color: {{VALUE}};',
                    '.footer-list li a'    => 'color: {{VALUE}};',
                    '.address-list li'     => 'color: {{VALUE}};',
                    '.address-list li a'   => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_list_hover_color',
            [
                'label'     => esc_html__('List Link Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-list li a:hover'  => 'color: {{VALUE}};',
                    '.address-list li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_social_icon_color',
            [
                'label'     => esc_html__('Social Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.social-list li a i'   => 'color: {{VALUE}};',
                    '.social-list li a svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_social_link_bg',
            [
                'label'     => esc_html__('Social Link Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.social-list li a' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_social_link_border',
            [
                'label'     => esc_html__('Social Link Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.social-list li a' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_social_hover_icon_color',
            [
                'label'     => esc_html__('Social Hover Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.social-list li a:hover i'   => 'color: {{VALUE}};',
                    '.social-list li a:hover svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_social_hover_bg',
            [
                'label'     => esc_html__('Social Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.social-list li a:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_input_color',
            [
                'label'     => esc_html__('Newsletter Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-form .form-control' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_input_bg',
            [
                'label'     => esc_html__('Newsletter Input Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-form .form-control' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_input_border',
            [
                'label'     => esc_html__('Newsletter Input Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-form .form-control' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_placeholder_color',
            [
                'label'     => esc_html__('Newsletter Placeholder Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '[data-theme=dark] {{WRAPPER}} .footer-form .form-control::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '[data-theme=dark] {{WRAPPER}} .footer-form .form-control::-moz-placeholder'          => 'color: {{VALUE}};',
                    '[data-theme=dark] {{WRAPPER}} .footer-form .form-control:-ms-input-placeholder'       => 'color: {{VALUE}};',
                    '[data-theme=dark] {{WRAPPER}} .footer-form .form-control::placeholder'                => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_button_color',
            [
                'label'     => esc_html__('Newsletter Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-form .submit' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_button_bg',
            [
                'label'     => esc_html__('Newsletter Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.footer-form .submit' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_newsletter_checkbox_color',
            [
                'label'     => esc_html__('Checkbox Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.form-check-label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_footer_one_dark_copyright_bg',
                'label'    => esc_html__('Copyright Area Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .copyright-area',
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_copyright_border',
            [
                'label'     => esc_html__('Copyright Top Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.copyright-area' => 'border-top-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_copyright_text_color',
            [
                'label'     => esc_html__('Copyright Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.copyright-content p' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_copyright_links_color',
            [
                'label'     => esc_html__('Copyright Links Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.copyright-list li a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_copyright_links_hover_color',
            [
                'label'     => esc_html__('Copyright Links Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.copyright-list li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_dark_copyright_separator_color',
            [
                'label'     => esc_html__('Copyright Separator Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.copyright-list li:not(:last-of-type):before' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'gc_footer_one_theme_mode_light_tab',
            [
                'label' => esc_html__('Light Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_footer_one_light_footer_bg',
                'label'    => esc_html__('Footer Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .footer-section.footer-2',
            ]
        );

        $this->add_control(
            'gc_footer_one_light_footer_top_border',
            [
                'label'     => esc_html__('Footer Top Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-top' => 'border-bottom-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_widget_title_color',
            [
                'label'     => esc_html__('Widget Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-widget .widget-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_list_color',
            [
                'label'     => esc_html__('List Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-list li'      => 'color: {{VALUE}};',
                    '.footer-list li a'    => 'color: {{VALUE}};',
                    '.address-list li'     => 'color: {{VALUE}};',
                    '.address-list li a'   => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_list_hover_color',
            [
                'label'     => esc_html__('List Link Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-list li a:hover'  => 'color: {{VALUE}};',
                    '.address-list li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_social_icon_color',
            [
                'label'     => esc_html__('Social Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.social-list li a i'   => 'color: {{VALUE}};',
                    '.social-list li a svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_social_link_bg',
            [
                'label'     => esc_html__('Social Link Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.social-list li a' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_social_link_border',
            [
                'label'     => esc_html__('Social Link Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.social-list li a' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_social_hover_icon_color',
            [
                'label'     => esc_html__('Social Hover Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.social-list li a:hover i'   => 'color: {{VALUE}};',
                    '.social-list li a:hover svg' => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_social_hover_bg',
            [
                'label'     => esc_html__('Social Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.social-list li a:hover' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_input_color',
            [
                'label'     => esc_html__('Newsletter Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-form .form-control' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_input_bg',
            [
                'label'     => esc_html__('Newsletter Input Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-form .form-control' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_input_border',
            [
                'label'     => esc_html__('Newsletter Input Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-form .form-control' => 'border-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_placeholder_color',
            [
                'label'     => esc_html__('Newsletter Placeholder Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '[data-theme=light] {{WRAPPER}} .footer-form .form-control::-webkit-input-placeholder' => 'color: {{VALUE}};',
                    '[data-theme=light] {{WRAPPER}} .footer-form .form-control::-moz-placeholder'          => 'color: {{VALUE}};',
                    '[data-theme=light] {{WRAPPER}} .footer-form .form-control:-ms-input-placeholder'       => 'color: {{VALUE}};',
                    '[data-theme=light] {{WRAPPER}} .footer-form .form-control::placeholder'                => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_button_color',
            [
                'label'     => esc_html__('Newsletter Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-form .submit' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_button_bg',
            [
                'label'     => esc_html__('Newsletter Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.footer-form .submit' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_newsletter_checkbox_color',
            [
                'label'     => esc_html__('Checkbox Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.form-check-label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_footer_one_light_copyright_bg',
                'label'    => esc_html__('Copyright Area Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .copyright-area',
            ]
        );

        $this->add_control(
            'gc_footer_one_light_copyright_border',
            [
                'label'     => esc_html__('Copyright Top Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.copyright-area' => 'border-top-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_copyright_text_color',
            [
                'label'     => esc_html__('Copyright Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.copyright-content p' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_copyright_links_color',
            [
                'label'     => esc_html__('Copyright Links Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.copyright-list li a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_copyright_links_hover_color',
            [
                'label'     => esc_html__('Copyright Links Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.copyright-list li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_footer_one_light_copyright_separator_color',
            [
                'label'     => esc_html__('Copyright Separator Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.copyright-list li:not(:last-of-type):before' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_footer_one_reset_elementor_spacing'] ?? 'yes')) {
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

    /**
     * @param array $item
     * @return void
     */
    private function render_social_icon($item)
    {
        if (!empty($item['social_icon']['value'])) {
            Icons_Manager::render_icon($item['social_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['social_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt=""></i>';
        }
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_social_item($item)
    {
        $social_class = !empty($item['social_class']) ? sanitize_html_class($item['social_class']) : '';
        $social_link  = $item['social_link'] ?? [];
        ?>
        <li<?php echo $social_class ? ' class="' . esc_attr($social_class) . '"' : ''; ?>>
            <a<?php echo $this->get_link_attributes($social_link); ?>><?php $this->render_social_icon($item); ?></a>
        </li>
        <?php
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_contact_item($item)
    {
        $text = $item['contact_text'] ?? '';
        $link = $item['contact_link'] ?? [];

        if ('' === trim((string) $text)) {
            return;
        }

        if (!empty($link['url'])) {
            ?>
            <li><a<?php echo $this->get_link_attributes($link); ?>><?php echo esc_html($text); ?></a></li>
            <?php
            return;
        }

        ?>
        <li><?php echo $this->get_multiline_text($text); ?></li>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);

        $logo_dark_url  = $this->get_media_url($settings['gc_footer_one_logo_dark'] ?? [], 'logo/logo-2.png');
        $logo_light_url = $this->get_media_url($settings['gc_footer_one_logo_light'] ?? [], 'logo/logo-3.png');
        $logo_link      = $settings['gc_footer_one_logo_link'] ?? [];

        $social_items = !empty($settings['gc_footer_one_social_items']) ? $settings['gc_footer_one_social_items'] : [];

        $company_title = $settings['gc_footer_one_company_title'] ?? '';
        $company_items = !empty($settings['gc_footer_one_company_items']) ? $settings['gc_footer_one_company_items'] : [];

        $working_time_title = $settings['gc_footer_one_working_time_title'] ?? '';
        $working_time_items = !empty($settings['gc_footer_one_working_time_items']) ? $settings['gc_footer_one_working_time_items'] : [];

        $contact_title = $settings['gc_footer_one_contact_title'] ?? '';
        $contact_items = !empty($settings['gc_footer_one_contact_items']) ? $settings['gc_footer_one_contact_items'] : [];

        $newsletter_title            = $settings['gc_footer_one_newsletter_title'] ?? '';
        $newsletter_placeholder      = $settings['gc_footer_one_newsletter_placeholder'] ?? '';
        $newsletter_button           = $settings['gc_footer_one_newsletter_button'] ?? '';
        $newsletter_action           = $settings['gc_footer_one_newsletter_action'] ?? 'mailchimpsubscribe';
        $newsletter_checkbox_label   = $settings['gc_footer_one_newsletter_checkbox_label'] ?? '';
        $checkbox_id                 = 'gc-footer-one-' . $this->get_id();

        $copyright_text  = $settings['gc_footer_one_copyright_text'] ?? '';
        $copyright_links = !empty($settings['gc_footer_one_copyright_links']) ? $settings['gc_footer_one_copyright_links'] : [];
        ?>

        <footer class="footer-section footer-2 bg-dark-1">
            <div class="container">
                <div class="footer-top">
                    <div class="footer-logo">
                        <a<?php echo $this->get_link_attributes($logo_link); ?>>
                            <?php if ($logo_dark_url) : ?>
                                <img class="logo-dark" src="<?php echo esc_url($logo_dark_url); ?>" alt="logo">
                            <?php endif; ?>
                            <?php if ($logo_light_url) : ?>
                                <img class="logo-light" src="<?php echo esc_url($logo_light_url); ?>" alt="logo">
                            <?php endif; ?>
                        </a>
                    </div>
                    <?php if (!empty($social_items)) : ?>
                        <ul class="social-list">
                            <?php foreach ($social_items as $item) {
                                $this->render_social_item($item);
                            } ?>
                        </ul>
                    <?php endif; ?>
                </div>
                <div class="row footer-wrap">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <?php if ($company_title) : ?>
                                <div class="widget-header">
                                    <h3 class="widget-title"><?php echo esc_html($company_title); ?></h3>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($company_items)) : ?>
                                <ul class="footer-list">
                                    <?php foreach ($company_items as $item) :
                                        $menu_text = $item['menu_text'] ?? '';
                                        $menu_link = $item['menu_link'] ?? [];

                                        if ('' === trim((string) $menu_text)) {
                                            continue;
                                        }
                                        ?>
                                        <li><a<?php echo $this->get_link_attributes($menu_link); ?>><?php echo esc_html($menu_text); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <?php if ($working_time_title) : ?>
                                <div class="widget-header">
                                    <h3 class="widget-title"><?php echo esc_html($working_time_title); ?></h3>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($working_time_items)) : ?>
                                <ul class="footer-list">
                                    <?php foreach ($working_time_items as $item) :
                                        $time_text = $item['time_text'] ?? '';

                                        if ('' === trim((string) $time_text)) {
                                            continue;
                                        }
                                        ?>
                                        <li><?php echo esc_html($time_text); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <?php if ($contact_title) : ?>
                                <div class="widget-header">
                                    <h3 class="widget-title"><?php echo esc_html($contact_title); ?></h3>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($contact_items)) : ?>
                                <ul class="address-list">
                                    <?php foreach ($contact_items as $item) {
                                        $this->render_contact_item($item);
                                    } ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-widget">
                            <?php if ($newsletter_title) : ?>
                                <div class="widget-header">
                                    <h3 class="widget-title"><?php echo esc_html($newsletter_title); ?></h3>
                                </div>
                            <?php endif; ?>
                            <div class="footer-form mb-20">
                                <form action="#" class="mt-subscribe-form">
                                    <input class="form-control" type="email" name="email" placeholder="<?php echo esc_attr($newsletter_placeholder); ?>">
                                    <input type="hidden" name="action" value="<?php echo esc_attr($newsletter_action); ?>">
                                    <button class="submit" type="submit"><?php echo esc_html($newsletter_button); ?></button>
                                    <div class="clearfix"></div>
                                </form>
                            </div>
                            <?php if ($newsletter_checkbox_label) : ?>
                                <div class="form-check form-item">
                                    <input class="form-check-input" type="checkbox" value="" id="<?php echo esc_attr($checkbox_id); ?>">
                                    <label class="form-check-label" for="<?php echo esc_attr($checkbox_id); ?>">
                                        <?php echo esc_html($newsletter_checkbox_label); ?>
                                    </label>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copyright-area">
                <div class="container">
                    <div class="row copyright-content">
                        <div class="col-lg-6 col-md-12">
                            <?php if ($copyright_text) : ?>
                                <p class="mb-0"><?php echo esc_html($copyright_text); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <?php if (!empty($copyright_links)) : ?>
                                <ul class="copyright-list">
                                    <?php foreach ($copyright_links as $item) :
                                        $link_text = $item['copyright_link_text'] ?? '';
                                        $link_url  = $item['copyright_link_url'] ?? [];

                                        if ('' === trim((string) $link_text)) {
                                            continue;
                                        }
                                        ?>
                                        <li><a<?php echo $this->get_link_attributes($link_url); ?>><?php echo esc_html($link_text); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Footer_One_Widget());
