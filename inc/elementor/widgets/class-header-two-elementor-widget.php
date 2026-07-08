<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Header_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_header_two';
    }

    public function get_title()
    {
        return esc_html__('GC Header Two', 'softro-core');
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
     * @param string $mode
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
            'gc_header_two_logo_section',
            [
                'label' => esc_html__('Logo', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_logo_dark',
            [
                'label'   => esc_html__('Logo (Dark)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-2.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_logo_light',
            [
                'label'   => esc_html__('Logo (Light)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-3.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_logo_link',
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

        $available_menus = $this->get_available_menus();

        $this->start_controls_section(
            'gc_header_two_menu_section',
            [
                'label' => esc_html__('Menu', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_primary_menu',
            [
                'label'       => esc_html__('Select Primary Menu', 'softro-core'),
                'type'        => Controls_Manager::SELECT,
                'options'     => $available_menus,
                'default'     => !empty($available_menus) ? (string) array_key_first($available_menus) : '',
                'description' => esc_html__('Choose a menu created in Appearance -> Menus. Mega menu items are supported via menu item settings.', 'softro-core'),
            ]
        );

        if (empty($available_menus)) {
            $this->add_control(
                'gc_header_two_no_menu_notice',
                [
                    'type'            => Controls_Manager::RAW_HTML,
                    'raw'             => esc_html__('No menu found. Please create a menu from Appearance -> Menus.', 'softro-core'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_button_section',
            [
                'label' => esc_html__('Quote Button', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_show_quote_button',
            [
                'label'        => esc_html__('Show Quote Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_header_two_quote_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get Quote', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_quote_link',
            [
                'label'       => esc_html__('Button Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
                'condition'   => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_quote_icon',
            [
                'label'     => esc_html__('Button Icon', 'softro-core'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fa-light fa-comment-dots',
                    'library' => 'fa-light',
                ],
                'condition' => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_quote_arrow_icon',
            [
                'label'     => esc_html__('Button Arrow Icon', 'softro-core'),
                'type'      => Controls_Manager::ICONS,
                'default'   => [
                    'value'   => 'fa-regular fa-arrow-right',
                    'library' => 'fa-regular',
                ],
                'condition' => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_quote_aria_label',
            [
                'label'       => esc_html__('Button Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Get Quote', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_sidebar_section',
            [
                'label' => esc_html__('Sidebar Toggle', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_show_sidebar_trigger',
            [
                'label'        => esc_html__('Show Sidebar Trigger', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_aria_label',
            [
                'label'       => esc_html__('Trigger Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Open menu', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'gc_header_two_show_sidebar_trigger' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_options_section',
            [
                'label' => esc_html__('Header Options', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_enable_sticky',
            [
                'label'        => esc_html__('Enable Sticky Class', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => esc_html__('Adds the sticky-active class used by the theme header script.', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_search_section',
            [
                'label' => esc_html__('Search Popup', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_search_placeholder',
            [
                'label'       => esc_html__('Search Placeholder', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Type keywords here...', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_sidebar_panel_section',
            [
                'label' => esc_html__('Sidebar Panel', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_logo_dark',
            [
                'label'   => esc_html__('Sidebar Logo (Dark)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-2.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_logo_light',
            [
                'label'   => esc_html__('Sidebar Logo (Light)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-3.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_about_title',
            [
                'label'       => esc_html__('About Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('About Us', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_about_text',
            [
                'label'   => esc_html__('About Text', 'softro-core'),
                'type'    => Controls_Manager::TEXTAREA,
                'default' => esc_html__(
                    'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud nisi ut aliquip ex ea commodo consequat.',
                    'softro-core'
                ),
                'rows'    => 4,
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_about_button_text',
            [
                'label'       => esc_html__('About Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact Us', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_about_button_link',
            [
                'label'   => esc_html__('About Button Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_contact_title',
            [
                'label'       => esc_html__('Contact Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact Us', 'softro-core'),
                'label_block' => true,
            ]
        );

        $sidebar_contact_repeater = new Repeater();

        $sidebar_contact_repeater->add_control(
            'sidebar_contact_type',
            [
                'label'   => esc_html__('Type', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'address' => esc_html__('Address', 'softro-core'),
                    'phone'   => esc_html__('Phone', 'softro-core'),
                    'email'   => esc_html__('Email', 'softro-core'),
                ],
                'default' => 'address',
            ]
        );

        $sidebar_contact_repeater->add_control(
            'sidebar_contact_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Valentin, Street Road 24, New York,', 'softro-core'),
                'label_block' => true,
            ]
        );

        $sidebar_contact_repeater->add_control(
            'sidebar_contact_link',
            [
                'label' => esc_html__('Link', 'softro-core'),
                'type'  => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_contacts',
            [
                'label'       => esc_html__('Contact Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $sidebar_contact_repeater->get_controls(),
                'default'     => [
                    [
                        'sidebar_contact_type' => 'address',
                        'sidebar_contact_text' => esc_html__('Valentin, Street Road 24, New York,', 'softro-core'),
                    ],
                    [
                        'sidebar_contact_type' => 'phone',
                        'sidebar_contact_text' => '+000 123 (456) 789',
                        'sidebar_contact_link' => ['url' => 'tel:+000123456789'],
                    ],
                    [
                        'sidebar_contact_type' => 'email',
                        'sidebar_contact_text' => 'runokcontact@gmail.com',
                        'sidebar_contact_link' => ['url' => 'mailto:runokcontact@gmail.com'],
                    ],
                ],
                'title_field' => '{{{ sidebar_contact_text }}}',
            ]
        );

        $sidebar_social_repeater = new Repeater();

        $sidebar_social_repeater->add_control(
            'sidebar_social_class',
            [
                'label'       => esc_html__('CSS Class', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'facebook',
                'label_block' => true,
            ]
        );

        $sidebar_social_repeater->add_control(
            'sidebar_social_link',
            [
                'label'   => esc_html__('Link', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $sidebar_social_repeater->add_control(
            'sidebar_social_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_sidebar_socials',
            [
                'label'       => esc_html__('Social Links', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $sidebar_social_repeater->get_controls(),
                'default'     => [
                    [
                        'sidebar_social_class' => 'facebook',
                        'sidebar_social_icon'  => ['value' => 'fab fa-facebook-f', 'library' => 'fa-brands'],
                    ],
                    [
                        'sidebar_social_class' => 'instagram',
                        'sidebar_social_icon'  => ['value' => 'fab fa-instagram', 'library' => 'fa-brands'],
                    ],
                    [
                        'sidebar_social_class' => 'twitter',
                        'sidebar_social_icon'  => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                    ],
                    [
                        'sidebar_social_class' => 'g-plus',
                        'sidebar_social_icon'  => ['value' => 'fab fa-google-plus', 'library' => 'fa-brands'],
                    ],
                ],
                'title_field' => '{{{ sidebar_social_class }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_mobile_panel_section',
            [
                'label' => esc_html__('Mobile Side Panel', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_header_two_mobile_logo',
            [
                'label'   => esc_html__('Mobile Panel Logo', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('logo/logo-2.png'),
                ],
            ]
        );

        $mobile_contact_repeater = new Repeater();

        $mobile_contact_repeater->add_control(
            'mobile_contact_type',
            [
                'label'   => esc_html__('Type', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'address' => esc_html__('Address', 'softro-core'),
                    'phone'   => esc_html__('Phone', 'softro-core'),
                    'email'   => esc_html__('Email', 'softro-core'),
                ],
                'default' => 'address',
            ]
        );

        $mobile_contact_repeater->add_control(
            'mobile_contact_label',
            [
                'label'       => esc_html__('Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Address :', 'softro-core'),
                'label_block' => true,
            ]
        );

        $mobile_contact_repeater->add_control(
            'mobile_contact_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Amsterdam, 109-74', 'softro-core'),
                'label_block' => true,
            ]
        );

        $mobile_contact_repeater->add_control(
            'mobile_contact_link',
            [
                'label' => esc_html__('Link', 'softro-core'),
                'type'  => Controls_Manager::URL,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $this->add_control(
            'gc_header_two_mobile_contacts',
            [
                'label'       => esc_html__('Contact Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $mobile_contact_repeater->get_controls(),
                'default'     => [
                    [
                        'mobile_contact_type'  => 'address',
                        'mobile_contact_label' => esc_html__('Address :', 'softro-core'),
                        'mobile_contact_text'  => esc_html__('Amsterdam, 109-74', 'softro-core'),
                    ],
                    [
                        'mobile_contact_type'  => 'phone',
                        'mobile_contact_label' => esc_html__('Phone :', 'softro-core'),
                        'mobile_contact_text'  => '+01 569 896 654',
                        'mobile_contact_link'  => ['url' => 'tel:+01569896654'],
                    ],
                    [
                        'mobile_contact_type'  => 'email',
                        'mobile_contact_label' => esc_html__('Email :', 'softro-core'),
                        'mobile_contact_text'  => 'info@example.com',
                        'mobile_contact_link'  => ['url' => 'mailto:info@example.com'],
                    ],
                ],
                'title_field' => '{{{ mobile_contact_label }}} {{{ mobile_contact_text }}}',
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
            'gc_header_two_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_header_two_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_header_padding',
            [
                'label'      => esc_html__('Header Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header.header-11 .primary-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_style_logo',
            [
                'label' => esc_html__('Logo', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_logo_dark_width',
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
                    '{{WRAPPER}} .header-logo .logo-dark' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_logo_light_width',
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
                    '{{WRAPPER}} .header-logo .logo-light' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_style_menu',
            [
                'label' => esc_html__('Menu', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_header_two_menu_typography',
                'selector' => '{{WRAPPER}} .header-menu-wrap ul li a',
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_menu_item_padding',
            [
                'label'      => esc_html__('Menu Item Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-menu-wrap ul li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_style_quote_button',
            [
                'label'     => esc_html__('Quote Button', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gc_header_two_show_quote_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_header_two_quote_typography',
                'selector' => '{{WRAPPER}} .gc-header-quote-btn-text',
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_quote_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-header-quote-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_quote_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 40,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-header-quote-btn-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-header-quote-btn-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-header-quote-btn-arrow i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-header-quote-btn-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_header_two_style_sidebar_trigger',
            [
                'label'     => esc_html__('Sidebar Trigger', 'softro-core'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gc_header_two_show_sidebar_trigger' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_header_two_sidebar_trigger_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 16,
                        'max' => 48,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-trigger svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    /**
     * Dark / light mode color controls.
     */
    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_header_two_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_header_two_theme_mode_color_tabs');

        $this->start_controls_tab(
            'gc_header_two_theme_mode_dark_tab',
            [
                'label' => esc_html__('Dark Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_header_two_dark_header_bg',
                'label'    => esc_html__('Header Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .header.header-11',
            ]
        );

        $this->add_control(
            'gc_header_two_dark_menu_link_color',
            [
                'label'     => esc_html__('Menu Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.header-menu-wrap ul li a'        => 'color: {{VALUE}};',
                    '.header-menu-wrap ul li.active a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_menu_link_hover_color',
            [
                'label'     => esc_html__('Menu Link Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.header-menu-wrap ul li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_submenu_link_color',
            [
                'label'     => esc_html__('Submenu Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.header-menu-wrap ul li ul li a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_quote_text_color',
            [
                'label'     => esc_html__('Quote Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-header-quote-btn'              => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-icon i'       => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-icon svg'     => 'fill: {{VALUE}};',
                    '.gc-header-quote-btn-arrow i'      => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-arrow svg'    => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_quote_bg_color',
            [
                'label'     => esc_html__('Quote Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-header-quote-btn' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_sidebar_trigger_color',
            [
                'label'     => esc_html__('Sidebar Trigger Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.sidebar-trigger'     => 'color: {{VALUE}};',
                    '.sidebar-trigger svg' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_dark_sidebar_trigger_hover_color',
            [
                'label'     => esc_html__('Sidebar Trigger Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.sidebar-trigger:hover'     => 'color: {{VALUE}};',
                    '.sidebar-trigger:hover svg' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'gc_header_two_theme_mode_light_tab',
            [
                'label' => esc_html__('Light Mode', 'softro-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_header_two_light_header_bg',
                'label'    => esc_html__('Header Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .header.header-11',
            ]
        );

        $this->add_control(
            'gc_header_two_light_menu_link_color',
            [
                'label'     => esc_html__('Menu Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.header-menu-wrap ul li a'        => 'color: {{VALUE}};',
                    '.header-menu-wrap ul li.active a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_menu_link_hover_color',
            [
                'label'     => esc_html__('Menu Link Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.header-menu-wrap ul li a:hover' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_submenu_link_color',
            [
                'label'     => esc_html__('Submenu Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.header-menu-wrap ul li ul li a' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_quote_text_color',
            [
                'label'     => esc_html__('Quote Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-header-quote-btn'              => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-icon i'       => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-icon svg'     => 'fill: {{VALUE}};',
                    '.gc-header-quote-btn-arrow i'      => 'color: {{VALUE}};',
                    '.gc-header-quote-btn-arrow svg'    => 'fill: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_quote_bg_color',
            [
                'label'     => esc_html__('Quote Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-header-quote-btn' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_sidebar_trigger_color',
            [
                'label'     => esc_html__('Sidebar Trigger Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.sidebar-trigger'     => 'color: {{VALUE}};',
                    '.sidebar-trigger svg' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_header_two_light_sidebar_trigger_hover_color',
            [
                'label'     => esc_html__('Sidebar Trigger Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.sidebar-trigger:hover'     => 'color: {{VALUE}};',
                    '.sidebar-trigger:hover svg' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * @return array
     */
    private function get_available_menus()
    {
        $menus   = wp_get_nav_menus();
        $options = [];

        if (!empty($menus) && !is_wp_error($menus)) {
            foreach ($menus as $menu) {
                $options[(string) $menu->term_id] = $menu->name;
            }
        }

        return $options;
    }

    /**
     * @param int $selected_menu_id
     * @return void
     */
    private function render_selected_menu($selected_menu_id)
    {
        $menu_args = [
            'container'   => false,
            'menu_class'  => '',
            'items_wrap'  => '<ul>%3$s</ul>',
            'fallback_cb' => false,
            'depth'       => 4,
            'walker'      => class_exists('Egns_Menu_Walker') ? new \Egns_Menu_Walker() : null,
        ];

        if (!empty($selected_menu_id) && wp_get_nav_menu_object($selected_menu_id)) {
            $menu_args['menu'] = absint($selected_menu_id);
        } elseif (has_nav_menu('primary-menu')) {
            $menu_args['theme_location'] = 'primary-menu';
        } else {
            $this->render_menu_fallback();
            return;
        }

        wp_nav_menu($menu_args);
    }

    /**
     * @return void
     */
    private function render_menu_fallback()
    {
        if (!current_user_can('edit_theme_options')) {
            return;
        }

        echo '<ul><li><a href="' . esc_url(admin_url('nav-menus.php')) . '">' . esc_html__('Set Menu Here...', 'softro-core') . '</a></li></ul>';
    }

    /**
     * @param string $type
     * @param string $context sidebar|mobile
     * @return void
     */
    private function render_contact_icon($type, $context = 'sidebar')
    {
        if ('mobile' === $context) {
            if ('address' === $type) {
                echo '<i class="fa-light fa-location-dot"></i>';
                return;
            }

            if ('phone' === $type) {
                echo '<i class="fa-light fa-phone"></i>';
                return;
            }

            echo '<i class="fa-light fa-envelope"></i>';
            return;
        }

        if ('address' === $type) {
            echo '<i class="fas fa-map-marker-alt"></i>';
            return;
        }

        if ('phone' === $type) {
            echo '<i class="fas fa-phone"></i>';
            return;
        }

        echo '<i class="fas fa-envelope-open-text"></i>';
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_sidebar_contact_item($item)
    {
        $type = $item['sidebar_contact_type'] ?? 'address';
        $text = $item['sidebar_contact_text'] ?? '';
        $link = $item['sidebar_contact_link'] ?? [];

        if ('' === trim((string) $text)) {
            return;
        }

        if ('address' === $type || empty($link['url'])) {
?>
            <li>
                <?php $this->render_contact_icon($type, 'sidebar'); ?>
                <p><?php echo esc_html($text); ?></p>
            </li>
        <?php
            return;
        }

        ?>
        <li>
            <?php $this->render_contact_icon($type, 'sidebar'); ?>
            <a<?php echo $this->get_link_attributes($link); ?>><?php echo esc_html($text); ?></a>
        </li>
    <?php
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_mobile_contact_item($item)
    {
        $type  = $item['mobile_contact_type'] ?? 'address';
        $label = $item['mobile_contact_label'] ?? '';
        $text  = $item['mobile_contact_text'] ?? '';
        $link  = $item['mobile_contact_link'] ?? [];

        if ('' === trim((string) $text)) {
            return;
        }

    ?>
        <li>
            <?php $this->render_contact_icon($type, 'mobile'); ?>
            <?php echo esc_html($label); ?>
            <?php if (!empty($link['url'])) : ?>
                <a<?php echo $this->get_link_attributes($link); ?>><?php echo esc_html($text); ?></a>
                <?php else : ?>
                    <span><?php echo esc_html($text); ?></span>
                <?php endif; ?>
        </li>
    <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_menu_active_styles()
    {
        $widget_id = esc_attr($this->get_id());
    ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.current-menu-ancestor>a,
            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.current-menu-parent>a,
            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.active>a,
            .elementor-element-<?php echo $widget_id; ?>.side-menu-wrap ul>li.current-menu-ancestor>a,
            .elementor-element-<?php echo $widget_id; ?>.side-menu-wrap ul>li.current-menu-parent>a,
            .elementor-element-<?php echo $widget_id; ?>.side-menu-wrap ul>li.active>a,
            .elementor-element-<?php echo $widget_id; ?>.mean-nav ul>li.current-menu-ancestor>a,
            .elementor-element-<?php echo $widget_id; ?>.mean-nav ul>li.current-menu-parent>a,
            .elementor-element-<?php echo $widget_id; ?>.mean-nav ul>li.active>a {
                color: var(--rr-color-theme-primary);
            }

            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.current-menu-ancestor.menu-item-has-children:after,
            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.current-menu-parent.menu-item-has-children:after,
            .elementor-element-<?php echo $widget_id; ?>.header-menu-wrap ul>li.active.menu-item-has-children:after {
                color: var(--rr-color-theme-primary);
            }
        </style>
    <?php
    }

    /**
     * Sync header menu into sidebar/mobile side-menu-wrap on desktop.
     *
     * @return void
     */
    private function render_menu_sync_script()
    {
        $widget_id = esc_js($this->get_id());
    ?>
        <script>
            (function($) {
                function gcHeaderTwoSyncSideMenus(scope) {
                    var $widget = scope && scope.length ? scope : $('.elementor-element-<?php echo $widget_id; ?>');
                    if (!$widget.length) {
                        return;
                    }

                    var $sourceMenu = $widget.find('.mobile-menu-items > ul').first();
                    if (!$sourceMenu.length) {
                        return;
                    }

                    if (window.innerWidth > 992) {
                        $widget.find('.side-menu-wrap').each(function() {
                            var $wrap = $(this);
                            if ($wrap.hasClass('mean-container') || $wrap.find('.mean-bar').length) {
                                return;
                            }
                            $wrap.html($sourceMenu.clone().removeAttr('id'));
                        });
                    }
                }

                $(document).ready(function() {
                    gcHeaderTwoSyncSideMenus();
                });

                $(window).on('resize', function() {
                    gcHeaderTwoSyncSideMenus();
                });

                if (window.elementorFrontend) {
                    elementorFrontend.hooks.addAction('frontend/element_ready/gc_header_two.default', function($scope) {
                        gcHeaderTwoSyncSideMenus($scope);
                    });
                }
            })(jQuery);
        </script>
    <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_sidebar_panel($settings)
    {
        $sidebar_logo_dark  = $this->get_media_url($settings['gc_header_two_sidebar_logo_dark'] ?? [], 'logo/logo-2.png');
        $sidebar_logo_light = $this->get_media_url($settings['gc_header_two_sidebar_logo_light'] ?? [], 'logo/logo-3.png');
        $logo_link          = $settings['gc_header_two_logo_link'] ?? [];

        $about_title        = $settings['gc_header_two_sidebar_about_title'] ?? '';
        $about_text         = $settings['gc_header_two_sidebar_about_text'] ?? '';
        $about_button_text  = $settings['gc_header_two_sidebar_about_button_text'] ?? '';
        $about_button_link  = $settings['gc_header_two_sidebar_about_button_link'] ?? [];
        $contact_title      = $settings['gc_header_two_sidebar_contact_title'] ?? '';
        $contacts           = !empty($settings['gc_header_two_sidebar_contacts']) ? $settings['gc_header_two_sidebar_contacts'] : [];
        $socials            = !empty($settings['gc_header_two_sidebar_socials']) ? $settings['gc_header_two_sidebar_socials'] : [];
    ?>

        <div id="preloader">
            <div class="loading" data-loading-text="Graphics Cycle"></div>
        </div>

        <div id="sidebar-area" class="sidebar-area">
            <button class="sidebar-trigger close" type="button" aria-label="<?php echo esc_attr__('Close menu', 'softro-core'); ?>">
                <svg class="sidebar-close" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px" y="0px" width="16px" height="12.7px" viewBox="0 0 16 12.7"
                    style="enable-background: new 0 0 16 12.7" xml:space="preserve">
                    <g>
                        <rect x="0" y="5.4" transform="matrix(0.7071 -0.7071 0.7071 0.7071 -2.1569 7.5208)" width="16"
                            height="2"></rect>
                        <rect x="0" y="5.4" transform="matrix(0.7071 0.7071 -0.7071 0.7071 6.8431 -3.7929)" width="16"
                            height="2"></rect>
                    </g>
                </svg>
            </button>
            <div class="side-menu-content">
                <div class="side-menu-logo">
                    <?php if ($sidebar_logo_dark) : ?>
                        <a class="dark-img" <?php echo $this->get_link_attributes($logo_link); ?>><img src="<?php echo esc_url($sidebar_logo_dark); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                    <?php endif; ?>
                    <?php if ($sidebar_logo_light) : ?>
                        <a class="light-img" <?php echo $this->get_link_attributes($logo_link); ?>><img src="<?php echo esc_url($sidebar_logo_light); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                    <?php endif; ?>
                </div>
                <div class="side-menu-wrap"></div>
                <?php if ($about_title || $about_text || $about_button_text) : ?>
                    <div class="side-menu-about">
                        <?php if ($about_title) : ?>
                            <div class="side-menu-header">
                                <h3><?php echo esc_html($about_title); ?></h3>
                            </div>
                        <?php endif; ?>
                        <?php if ($about_text) : ?>
                            <p><?php echo esc_html($about_text); ?></p>
                        <?php endif; ?>
                        <?php if ($about_button_text) : ?>
                            <a<?php echo $this->get_link_attributes($about_button_link); ?> class="rr-primary-btn"><?php echo esc_html($about_button_text); ?></a>
                            <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($contact_title || !empty($contacts)) : ?>
                    <div class="side-menu-contact">
                        <?php if ($contact_title) : ?>
                            <div class="side-menu-header">
                                <h3><?php echo esc_html($contact_title); ?></h3>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($contacts)) : ?>
                            <ul class="side-menu-list">
                                <?php foreach ($contacts as $item) {
                                    $this->render_sidebar_contact_item($item);
                                } ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($socials)) : ?>
                    <ul class="side-menu-social">
                        <?php foreach ($socials as $item) :
                            $social_class = !empty($item['sidebar_social_class']) ? sanitize_html_class($item['sidebar_social_class']) : '';
                            $social_link  = $item['sidebar_social_link'] ?? [];
                        ?>
                            <li<?php echo $social_class ? ' class="' . esc_attr($social_class) . '"' : ''; ?>>
                                <a<?php echo $this->get_link_attributes($social_link); ?>>
                                    <?php $this->render_icon($item['sidebar_social_icon'] ?? []); ?>
                                    </a>
                                    </li>
                                <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div id="sidebar-overlay"></div>
    <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_mobile_panel($settings)
    {
        $mobile_logo = $this->get_media_url($settings['gc_header_two_mobile_logo'] ?? [], 'logo/logo-2.png');
        $logo_link   = $settings['gc_header_two_logo_link'] ?? [];
        $contacts    = !empty($settings['gc_header_two_mobile_contacts']) ? $settings['gc_header_two_mobile_contacts'] : [];
    ?>
        <div class="mobile-side-menu">
            <div class="side-menu-content">
                <div class="side-menu-head">
                    <?php if ($mobile_logo) : ?>
                        <a<?php echo $this->get_link_attributes($logo_link); ?>><img src="<?php echo esc_url($mobile_logo); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>"></a>
                        <?php endif; ?>
                        <button class="mobile-side-menu-close" type="button" aria-label="<?php echo esc_attr__('Close menu', 'softro-core'); ?>"><i class="fa-regular fa-xmark"></i></button>
                </div>
                <div class="side-menu-wrap"></div>
                <?php if (!empty($contacts)) : ?>
                    <ul class="side-menu-list">
                        <?php foreach ($contacts as $item) {
                            $this->render_mobile_contact_item($item);
                        } ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="mobile-side-menu-overlay"></div>
    <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_header_two_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
    ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> {
                margin-top: 0 !important;
                margin-bottom: 0 !important;
            }

            .elementor-element-<?php echo $widget_id; ?>>.elementor-widget-container {
                padding: 0 !important;
                margin: 0 !important;
            }
        </style>
    <?php
    }

    /**
     * @param array $icon_settings
     * @return void
     */
    private function render_icon($icon_settings)
    {
        if (!empty($icon_settings['value'])) {
            Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
        }
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_sidebar_trigger($settings)
    {
        $aria_label = !empty($settings['gc_header_two_sidebar_aria_label'])
            ? $settings['gc_header_two_sidebar_aria_label']
            : esc_html__('Open menu', 'softro-core');
    ?>
        <div class="sidebar-icon">
            <button class="sidebar-trigger open" type="button" aria-label="<?php echo esc_attr($aria_label); ?>">
                <svg width="24" height="23" viewBox="0 0 24 23" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M0.300781 0H5.30078V5H0.300781V0Z" fill="currentColor" />
                    <path d="M0.300781 9H5.30078V14H0.300781V9Z" fill="currentColor" />
                    <path d="M0.300781 18H5.30078V23H0.300781V18Z" fill="currentColor" />
                    <path d="M9.30078 0H14.3008V5H9.30078V0Z" fill="currentColor" />
                    <path d="M9.30078 9H14.3008V14H9.30078V9Z" fill="currentColor" />
                    <path d="M9.30078 18H14.3008V23H9.30078V18Z" fill="currentColor" />
                    <path d="M18.3008 0H23.3008V5H18.3008V0Z" fill="currentColor" />
                    <path d="M18.3008 9H23.3008V14H18.3008V9Z" fill="currentColor" />
                    <path d="M18.3008 18H23.3008V23H18.3008V18Z" fill="currentColor" />
                </svg>
            </button>
        </div>
    <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_menu_active_styles();

        $logo_dark_url   = $this->get_media_url($settings['gc_header_two_logo_dark'] ?? [], 'logo/logo-2.png');
        $logo_light_url  = $this->get_media_url($settings['gc_header_two_logo_light'] ?? [], 'logo/logo-3.png');
        $logo_link       = $settings['gc_header_two_logo_link'] ?? [];
        $selected_menu   = isset($settings['gc_header_two_primary_menu']) ? absint($settings['gc_header_two_primary_menu']) : 0;

        $show_quote      = 'yes' === ($settings['gc_header_two_show_quote_button'] ?? 'yes');
        $quote_text      = $settings['gc_header_two_quote_text'] ?? '';
        $quote_link      = $settings['gc_header_two_quote_link'] ?? [];
        $quote_aria      = !empty($settings['gc_header_two_quote_aria_label']) ? $settings['gc_header_two_quote_aria_label'] : $quote_text;

        $show_sidebar    = 'yes' === ($settings['gc_header_two_show_sidebar_trigger'] ?? 'yes');
        $sticky_class    = 'yes' === ($settings['gc_header_two_enable_sticky'] ?? 'yes') ? ' sticky-active' : '';
        $search_placeholder = $settings['gc_header_two_search_placeholder'] ?? esc_html__('Type keywords here...', 'softro-core');

        $header_classes  = trim('header header-5 header-11' . $sticky_class);
    ?>

        <header class="<?php echo esc_attr($header_classes); ?>">
            <div class="primary-header">
                <div class="container header-container">
                    <div class="primary-header-inner">
                        <div class="header-logo">
                            <a<?php echo $this->get_link_attributes($logo_link); ?>>
                                <?php if ($logo_dark_url) : ?>
                                    <img class="logo-dark" src="<?php echo esc_url($logo_dark_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                                <?php endif; ?>
                                <?php if ($logo_light_url) : ?>
                                    <img class="logo-light" src="<?php echo esc_url($logo_light_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
                                <?php endif; ?>
                                </a>
                        </div>
                        <div class="header-menu-wrap">
                            <div class="mobile-menu-items">
                                <?php $this->render_selected_menu($selected_menu); ?>
                            </div>
                        </div>
                        <div class="header-right">
                            <div class="gc-header-actions">
                                <?php if ($show_quote && $quote_text) : ?>
                                    <a<?php echo $this->get_link_attributes($quote_link); ?> class="gc-header-quote-btn" aria-label="<?php echo esc_attr($quote_aria); ?>">
                                        <span class="gc-header-quote-btn-icon" aria-hidden="true">
                                            <?php $this->render_icon($settings['gc_header_two_quote_icon'] ?? []); ?>
                                        </span>
                                        <span class="gc-header-quote-btn-text"><?php echo esc_html($quote_text); ?></span>
                                        <span class="gc-header-quote-btn-arrow" aria-hidden="true">
                                            <?php $this->render_icon($settings['gc_header_two_quote_arrow_icon'] ?? []); ?>
                                        </span>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($show_sidebar) {
                                        $this->render_sidebar_trigger($settings);
                                    } ?>
                            </div>
                        </div>
                        <!-- /.header-right -->
                    </div>
                    <!--.primary-header-inner -->
                </div>
            </div>
        </header>

        <div id="popup-search-box">
            <div class="box-inner-wrap d-flex align-items-center">
                <form id="form" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
                    <input id="popup-search" type="text" name="s" placeholder="<?php echo esc_attr($search_placeholder); ?>">
                </form>
                <div class="search-close"><i class="fa-sharp fa-regular fa-xmark"></i></div>
            </div>
        </div>
        <!--#popup-search-box -->

<?php
        $this->render_sidebar_panel($settings);
        $this->render_mobile_panel($settings);
        $this->render_menu_sync_script();
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Header_Two_Widget());
