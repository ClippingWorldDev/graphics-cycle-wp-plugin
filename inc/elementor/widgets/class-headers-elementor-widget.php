<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Headers_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_headers';
    }

    public function get_title()
    {
        return esc_html__('EG Headers', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['softro_widgets'];
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }


    protected function register_content_controls()
    {
        // Layout Section
        $this->start_controls_section(
            'softro_header_layout_section',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_header_layout',
            [
                'label'   => esc_html__('Header Style', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'style_one'   => esc_html__('Style One', 'softro-core'),
                    'style_two'   => esc_html__('Style Two', 'softro-core'),
                    'style_three' => esc_html__('Style Three', 'softro-core'),
                    'style_four'  => esc_html__('Style Four', 'softro-core'),
                    'style_five'  => esc_html__('Style Five', 'softro-core'),
                    'style_six'   => esc_html__('Style Six', 'softro-core'),
                ],
                'default' => 'style_one',
            ]
        );

        $this->end_controls_section();

        // Logo Section
        $this->start_controls_section(
            'softro_header_one_logo_section',
            [
                'label' => esc_html__('Logo', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_header_one_light_logo',
            [
                'label'       => esc_html__('Light Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'softro_header_layout!' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_dark_logo',
            [
                'label'       => esc_html__('Dark Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_mobile_logo_light',
            [
                'label'       => esc_html__('Mobile Light Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_mobile_logo_dark',
            [
                'label'       => esc_html__('Mobile Dark Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Section
        $this->start_controls_section(
            'softro_header_one_menu_section',
            [
                'label' => esc_html__('Menu', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $available_menus = $this->get_available_menus();

        $this->add_control(
            'softro_header_one_primary_menu',
            [
                'label'       => esc_html__('Select Primary Menu', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => $available_menus,
                'default'     => !empty($available_menus) ? (string) array_key_first($available_menus) : '',
                'description' => esc_html__('Choose a menu created in Appearance -> Menus.', 'softro-core'),
            ]
        );

        if (empty($available_menus)) {
            $this->add_control(
                'softro_header_one_no_menu_notice',
                [
                    'type'            => \Elementor\Controls_Manager::RAW_HTML,
                    'raw'             => esc_html__('No menu found. Please create a menu from Appearance -> Menus.', 'softro-core'),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                ]
            );
        }

        $this->end_controls_section();

        // Buttons Section
        $this->start_controls_section(
            'softro_header_one_buttons_section',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_header_one_button_text',
            [
                'label'   => esc_html__('Button Text', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Get in Touch', 'softro-core'),
            ]
        );

        $this->add_control(
            'softro_header_one_button_url',
            [
                'label'   => esc_html__('Button URL', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->end_controls_section();

        // Email + language for h3,h5 Section
        $this->start_controls_section(
            'softro_header_one_email_lang_section',
            [
                'label'     => esc_html__('Extended content', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_header_layout' => ['style_three', 'style_five'],
                ],
            ]
        );

        $this->add_control(
            'softro_header_thee_email',
            [
                'label'       => esc_html__('Mail Address', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => 'hello@example.com',
                'label_block' => true,
                'condition'   => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_lang',
            [
                'label'       => esc_html__('Language switcher shortcode', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => '[languages-switcher]',
                'label_block' => true,
                'condition'   => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->end_controls_section();



        // Sidebar Section
        $this->start_controls_section(
            'softro_header_one_sidebar_section',
            [
                'label' => esc_html__('Sidebar', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_header_one_sidebar_logo_light',
            [
                'label'       => esc_html__('Sidebar Light Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_sidebar_logo_dark',
            [
                'label'       => esc_html__('Sidebar Dark Logo', 'softro-core'),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'media_types' => ['image', 'svg'],
                'default'     => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_sidebar_description',
            [
                'label'   => esc_html__('Sidebar Description', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('We develop innovative digital solutions that enhance Software Development, Cloud & DevOps etc.', 'softro-core'),
            ]
        );

        // Contact Info
        $contact_repeater = new \Elementor\Repeater();

        $contact_repeater->add_control(
            'softro_header_one_contact_type',
            [
                'label'   => esc_html__('Contact Type', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'email'   => esc_html__('Email', 'softro-core'),
                    'phone'   => esc_html__('Phone', 'softro-core'),
                    'address' => esc_html__('Address', 'softro-core'),
                ],
                'default' => 'email',
            ]
        );

        $contact_repeater->add_control(
            'softro_header_one_contact_title',
            [
                'label'   => esc_html__('Contact Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Contact Title', 'softro-core'),
            ]
        );
        $contact_repeater->add_control(
            'softro_header_one_contact_label',
            [
                'label'   => esc_html__('Contact Label', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Contact Label', 'softro-core'),
            ]
        );

        $contact_repeater->add_control(
            'softro_header_one_contact_value',
            [
                'label'   => esc_html__('Contact Value', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('contact@example.com', 'softro-core'),
            ]
        );

        $this->add_control(
            'softro_header_one_sidebar_contacts',
            [
                'label'   => esc_html__('Contact Info', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $contact_repeater->get_controls(),
                'default' => [
                    [
                        'softro_header_one_contact_title' => 'Send Us Mail',
                        'softro_header_one_contact_type'  => 'email',
                        'softro_header_one_contact_label' => 'Send Us Mail',
                        'softro_header_one_contact_value' => 'info@example.com',
                    ],
                    [
                        'softro_header_one_contact_title' => 'Call 24/7 Hours',
                        'softro_header_one_contact_type'  => 'phone',
                        'softro_header_one_contact_label' => 'Call Us',
                        'softro_header_one_contact_value' => '2-965-871-8617',
                    ],
                    [
                        'softro_header_one_contact_type'  => 'address',
                        'softro_header_one_contact_label' => 'Visit Office',
                        'softro_header_one_contact_value' => '1234 Innovation Drive, Suite 500 San Francisco, CA 94107 United States',
                    ],
                ],
                'title_field' => '{{{ softro_header_one_contact_label }}}',
            ]
        );

        // Social Links
        $social_repeater = new \Elementor\Repeater();

        $this->add_control(
            'softro_header_one_sidebar_social_title',
            [
                'label'   => esc_html__('Social Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Follow Us', 'softro-core'),
            ]
        );

        $social_repeater->add_control(
            'softro_header_one_social_icon',
            [
                'label'   => esc_html__('Social Icon', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fab fa-facebook-f',
                    'library' => 'fa-brands',
                ],
            ]
        );

        $social_repeater->add_control(
            'softro_header_one_social_url',
            [
                'label'   => esc_html__('Social URL', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_sidebar_socials',
            [
                'label'   => esc_html__('Social Links', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::REPEATER,
                'fields'  => $social_repeater->get_controls(),
                'default' => [
                    [
                        'softro_header_one_social_icon' => ['value' => 'fab fa-facebook-f', 'library' => 'fa-brands'],
                        'softro_header_one_social_url'  => ['url' => 'https://www.facebook.com/'],
                    ],
                    [
                        'softro_header_one_social_icon' => ['value' => 'fab fa-twitter', 'library' => 'fa-brands'],
                        'softro_header_one_social_url'  => ['url' => 'https://x.com/'],
                    ],
                    [
                        'softro_header_one_social_icon' => ['value' => 'fab fa-linkedin-in', 'library' => 'fa-brands'],
                        'softro_header_one_social_url'  => ['url' => 'https://www.linkedin.com/'],
                    ],
                    [
                        'softro_header_one_social_icon' => ['value' => 'fab fa-instagram', 'library' => 'fa-brands'],
                        'softro_header_one_social_url'  => ['url' => 'https://www.instagram.com/'],
                    ],
                ],
                'title_field' => '{{{ softro_header_one_social_icon.value }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_controls()
    {
        // ==================== STYLE ONE STYLE CONTROLS ====================
        // Header Area Wrap Style
        $this->start_controls_section(
            'softro_header_one_header_area_wrap_style',
            [
                'label'     => esc_html__('Header Area Wrap', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_header_area_wrap_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .header-area-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_one_header_area_wrap_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .header-area-wrap',
            ]
        );

        $this->add_control(
            'softro_header_one_header_area_wrap_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .header-area-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_header_area_wrap_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .header-area-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Logo Style
        $this->start_controls_section(
            'softro_header_one_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Style
        $this->start_controls_section(
            'softro_header_one_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_one_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_one_menu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li > a::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'softro_header_one_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );
        $this->add_control(
            'softro_header_one_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Submenu Style
        $this->start_controls_section(
            'softro_header_one_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_one_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_one_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu > li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_one_submenu_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_one_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'softro_header_one_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_one',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_one_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_one_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_one_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_one_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_one_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home1-header:not(.home5-header) .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Global Header Actions Style
        $this->start_controls_section(
            'softro_header_global_actions_style',
            [
                'label' => esc_html__('Header Actions', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_heading',
            [
                'label' => esc_html__('Sidebar Toggle Button', 'softro-core'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-button' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-button svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-button:hover' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_hover_icon_color',
            [
                'label'     => esc_html__('Hover Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-button:hover svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_right_sidebar_button_border',
                'selector' => '{{WRAPPER}} .right-sidebar-button',
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-button' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_right_sidebar_button_height',
            [
                'label'      => esc_html__('Height', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 30,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_button_heading',
            [
                'label'     => esc_html__('Mobile Menu Button', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_button_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mobile-menu-btn svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_mobile_button_border',
                'selector' => '{{WRAPPER}} .mobile-menu-btn',
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .mobile-menu-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_close_heading',
            [
                'label'     => esc_html__('Mobile Close Button', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_close_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-close-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_close_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-close-btn i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_close_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-close-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_mobile_close_hover_icon_color',
            [
                'label'     => esc_html__('Hover Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .menu-close-btn:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_mobile_close_border',
                'selector' => '{{WRAPPER}} .menu-close-btn',
            ]
        );

        $this->end_controls_section();

        // Sidebar Popup Style
        $this->start_controls_section(
            'softro_header_global_sidebar_popup_style',
            [
                'label' => esc_html__('Sidebar Popup', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_overlay_color',
            [
                'label'     => esc_html__('Overlay Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_bg_color',
            [
                'label'     => esc_html__('Panel Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_panel_width',
            [
                'label'      => esc_html__('Panel Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 260,
                        'max' => 900,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-menu .right-sidebar-menu-wrap' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_content_padding',
            [
                'label'      => esc_html__('Content Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_heading',
            [
                'label'     => esc_html__('Close Button', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-close-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-close-btn i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-close-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_hover_icon_color',
            [
                'label'     => esc_html__('Hover Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-close-btn:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_close_border',
                'selector' => '{{WRAPPER}} .right-sidebar-close-btn',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-close-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_close_size',
            [
                'label'      => esc_html__('Button Size', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 24,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .right-sidebar-close-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Sidebar Content Style
        $this->start_controls_section(
            'softro_header_global_sidebar_content_style',
            [
                'label' => esc_html__('Sidebar Content', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_logo_heading',
            [
                'label' => esc_html__('Logo', 'softro-core'),
                'type'  => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 40,
                        'max' => 320,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .sidebar-logo-wrap img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_description_heading',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_description_typography',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap .title-area p',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_description_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap .title-area p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_heading',
            [
                'label'     => esc_html__('Contact', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_contact_label_typography',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .contact-area > li > h6',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_label_color',
            [
                'label'     => esc_html__('Label Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .contact-area > li > h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_contact_title_typography',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .single-contact .content span, {{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap .contact-and-social-area .contact-area-wrap .contact-area li .single-contact .content span',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .single-contact .content span' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_contact_value_typography',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .single-contact .content h6 a, .right-sidebar-menu .sidebar-content-wrap .contact-and-social-area .contact-area-wrap .contact-area li .single-contact .content h6 a',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_value_color',
            [
                'label'     => esc_html__('Value Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .single-contact .content h6 a' => 'color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_value_hover_color',
            [
                'label'     => esc_html__('Value Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .single-contact .content h6 a:hover' => 'color: {{VALUE}} !important;',
                    '{{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap .contact-and-social-area .contact-area-wrap .contact-area li .single-contact .content h6 a' => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 0px 1px; background-position: right 95%;',
                    '{{WRAPPER}} .right-sidebar-menu .sidebar-content-wrap .contact-and-social-area .contact-area-wrap .contact-area li .single-contact .content h6 a:hover' => 'background-size: 100% 1px; background-position: left 95%;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_icon_bg_color',
            [
                'label'     => esc_html__('Icon Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .single-contact .icon' => 'background-color: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_contact_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .single-contact .icon svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_contact_icon_border',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .single-contact .icon',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_arrow_color',
            [
                'label'     => esc_html__('Arrow Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .contact-area-wrap .arrow svg' => 'fill: {{VALUE}} !important;',
                    '{{WRAPPER}} .right-sidebar-menu .social-area-wrap .arrow svg' => 'fill: {{VALUE}} !important;',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_heading',
            [
                'label'     => esc_html__('Social', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_social_title_typography',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .social-area h6',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .social-area h6' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_bg_color',
            [
                'label'     => esc_html__('Icon Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_global_sidebar_social_border',
                'selector' => '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a',
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_hover_color',
            [
                'label'     => esc_html__('Icon Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a:hover svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_global_sidebar_social_hover_bg_color',
            [
                'label'     => esc_html__('Icon Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .right-sidebar-menu .social-area .social-list li a:hover::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE TWO STYLE CONTROLS ====================
        // Logo Style
        $this->start_controls_section(
            'softro_header_two_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_two',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home2-header .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Style
        $this->start_controls_section(
            'softro_header_two_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_two',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_two_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home2-header .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_two_menu_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} header .header-area-wrap .main-menu > ul > li.menu-item-has-children > a::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} header .header-area-wrap .main-menu > ul > li.menu-item-has-children:hover > a::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home2-header .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Submenu Style
        $this->start_controls_section(
            'softro_header_two_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_two',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_two_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home2-header .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_two_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .sub-menu li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_two_submenu_border',
                'selector' => '{{WRAPPER}} .home2-header .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_two_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home2-header .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'softro_header_two_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_two',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_two_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home2-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_two_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home2-header .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home2-header .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_two_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home2-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_two_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home2-header .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_two_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home2-header .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE THREE STYLE CONTROLS ====================
        // Logo Style
        $this->start_controls_section(
            'softro_header_three_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home3-header .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Menu Style
        $this->start_controls_section(
            'softro_header_three_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_three_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home3-header .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_three_menu_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home3-header .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Submenu Style
        $this->start_controls_section(
            'softro_header_three_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_three_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home3-header .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_three_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .sub-menu li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_three_submenu_border',
                'selector' => '{{WRAPPER}} .home3-header .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_three_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home3-header .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Button Style
        $this->start_controls_section(
            'softro_header_three_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_three_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home3-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_three_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home3-header .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home3-header .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_three_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home3-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_three_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home3-header .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_three_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home3-header .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_three_mail_style',
            [
                'label'     => esc_html__('Mail', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_three',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_three_mail_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} header.home3-header .header-area-wrap .right-area .nav-right .mail-area a',
            ]
        );

        $this->add_control(
            'softro_header_three_mail_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} header.home3-header .header-area-wrap .right-area .nav-right .mail-area a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE FOUR STYLE CONTROLS ====================
        $this->start_controls_section(
            'softro_header_four_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_four',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_four_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_four',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_four_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_four_menu_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_four_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_four',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_four_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_four_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_four_submenu_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_four_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_four_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_four',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_four_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_four_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_four_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_four_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_four_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header:not(.inner-header) .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE FIVE STYLE CONTROLS ====================
        $this->start_controls_section(
            'softro_header_five_header_area_wrap_style',
            [
                'label'     => esc_html__('Header Area Wrap', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_header_area_wrap_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .header-area-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_five_header_area_wrap_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .header-area-wrap',
            ]
        );

        $this->add_control(
            'softro_header_five_header_area_wrap_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .header-area-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_header_area_wrap_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .header-area-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_five_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home5-header .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_five_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_five_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_five_menu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li > a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .main-menu > ul > li > a::before' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_five_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_five_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_five_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .sub-menu li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_five_submenu_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_five_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_five_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_five',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_five_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_five_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home5-header .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home5-header .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_five_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home5-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_five_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_five_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home5-header .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ==================== STYLE SIX STYLE CONTROLS ====================
        $this->start_controls_section(
            'softro_header_six_logo_style',
            [
                'label'     => esc_html__('Logo', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_six',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_logo_width',
            [
                'label'      => esc_html__('Logo Width', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min'  => 50,
                        'max'  => 300,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 150,
                ],
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .company-logo img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_six_menu_style',
            [
                'label'     => esc_html__('Menu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_six',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_six_menu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li > a',
            ]
        );

        $this->add_control(
            'softro_header_six_menu_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li:hover > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li.active > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_background_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li.active > a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li:hover > a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li.active > a' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li:hover > a' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li > a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_menu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header.inner-header .main-menu > ul > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_six_submenu_style',
            [
                'label'     => esc_html__('Submenu', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_six',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_six_submenu_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header.inner-header .sub-menu li a',
            ]
        );

        $this->add_control(
            'softro_header_six_submenu_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .sub-menu li a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header ul.sub-menu > li .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_submenu_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .sub-menu li:hover a' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header ul.sub-menu > li:hover .dropdown-icon' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_submenu_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .sub-menu' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_six_submenu_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header.inner-header .sub-menu',
            ]
        );

        $this->add_control(
            'softro_header_six_submenu_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header.inner-header .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'softro_header_six_button_style',
            [
                'label'     => esc_html__('Button', 'softro-core'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_header_layout' => 'style_six',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_header_six_button_typography',
                'label'    => esc_html__('Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header.inner-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_six_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1 span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1:hover span svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1.black-bg::after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'softro_header_six_button_border',
                'label'    => esc_html__('Border', 'softro-core'),
                'selector' => '{{WRAPPER}} .home4-header.inner-header .primary-btn1',
            ]
        );

        $this->add_control(
            'softro_header_six_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'softro_header_six_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .home4-header.inner-header .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function get_link_attributes($url_control)
    {
        $attributes = '';
        if (!empty($url_control['url'])) {
            $attributes .= ' href="' . esc_url($url_control['url']) . '"';
            if (!empty($url_control['is_external'])) {
                $attributes .= ' target="_blank"';
            }
            if (!empty($url_control['nofollow'])) {
                $attributes .= ' rel="nofollow"';
            }
        }
        return $attributes;
    }

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

    private function render_selected_menu($selected_menu_id)
    {
        $menu_args = [
            'container'   => false,
            'menu_class'  => 'menu-list',
            'fallback_cb' => false,
            'depth'       => 3,
            'after'       => '<i class="d-lg-flex d-none bi-caret-right-fill dropdown-icon"></i><i class="bi bi-plus dropdown-icon"></i>',
            'walker'      => class_exists('Egns_Menu_Walker') ? new \Egns_Menu_Walker() : null,
        ];

        if (!empty($selected_menu_id)) {
            $menu_args['menu'] = absint($selected_menu_id);
        } else {
            $menu_args['theme_location'] = 'primary-menu';
        }

        wp_nav_menu($menu_args);
    }

    private function get_contact_link($type, $value)
    {
        if ('email' === $type) {
            $email = sanitize_email($value);
            return !empty($email) ? 'mailto:' . $email : '#';
        }

        if ('phone' === $type) {
            $phone = preg_replace('/[^0-9+]/', '', (string) $value);
            return !empty($phone) ? 'tel:' . $phone : '#';
        }

        return '#';
    }



    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $contacts     = !empty($settings['softro_header_one_sidebar_contacts']) ? $settings['softro_header_one_sidebar_contacts'] : [];
        $socials      = !empty($settings['softro_header_one_sidebar_socials']) ? $settings['softro_header_one_sidebar_socials'] : [];
        $social_title = !empty($settings['softro_header_one_sidebar_social_title']) ? $settings['softro_header_one_sidebar_social_title'] : '';

        ?>
        <!-- common sidebar for all header -->
        <div class="right-sidebar-menu">
            <div class="right-sidebar-menu-wrap">
                <div class="right-sidebar-close-btn">
                    <i class="bi bi-x"></i>
                </div>
                <div class="sidebar-content-wrap">
                    <div class="sidebar-logo-wrap">
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img class="logo-dark" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_sidebar_logo_dark']['url'] ?? ''); ?>"></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img class="logo-light" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_sidebar_logo_light']['url'] ?? ''); ?>"></a>
                    </div>
                    <div class="title-area">
                        <p><?php echo esc_html($settings['softro_header_one_sidebar_description'] ?? ''); ?></p>
                    </div>
                    <div class="contact-and-social-area">
                        <div class="contact-area-wrap">
                            <div class="arrow">
                                <svg width="6" height="355" viewBox="0 0 6 355" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.38672 5L5.77347 0H-3.26633e-05L2.38672 5H3.38672ZM2.88672 355L5.77347 350H-3.26633e-05L2.88672 355ZM2.88672 4.5H2.38672V151H2.88672H3.38672V4.5H2.88672ZM2.88672 151H2.38672V350.5H2.88672H3.38672V151H2.88672Z" />
                                </svg>
                            </div>
                            <ul class="contact-area">
                                <?php foreach ($contacts as $contact): ?>
                                    <?php
                                    $contact_type  = !empty($contact['softro_header_one_contact_type']) ? $contact['softro_header_one_contact_type'] : '';
                                    $contact_title = !empty($contact['softro_header_one_contact_title']) ? $contact['softro_header_one_contact_title'] : '';
                                    $contact_label = !empty($contact['softro_header_one_contact_label']) ? $contact['softro_header_one_contact_label'] : '';
                                    $contact_value = !empty($contact['softro_header_one_contact_value']) ? $contact['softro_header_one_contact_value'] : '';
                                    if (empty($contact_value)) {
                                        continue;
                                    }
                                    $contact_url = $this->get_contact_link($contact_type, $contact_value);
                                    ?>
                                    <li>
                                        <?php if (!empty($contact_label)): ?>
                                            <h6><?php echo esc_html($contact_label); ?></h6>
                                        <?php endif; ?>
                                        <div class="single-contact">
                                            <div class="icon">
                                                <?php if ('email' === $contact_type): ?>
                                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21.7969 3.32227H2.20312C1.61902 3.32292 1.05903 3.55525 0.646007 3.96827C0.232983 4.3813 0.000657558 4.94129 0 5.52539L0 18.4742C0.000657558 19.0583 0.232983 19.6183 0.646007 20.0314C1.05903 20.4444 1.61902 20.6767 2.20312 20.6774H21.7969C22.381 20.6767 22.941 20.4444 23.354 20.0314C23.767 19.6183 23.9993 19.0583 24 18.4742V5.52539C23.9993 4.94129 23.767 4.3813 23.354 3.96827C22.941 3.55525 22.381 3.32292 21.7969 3.32227ZM15.8424 11.9998L22.7733 5.06892C22.8404 5.21173 22.8751 5.3676 22.875 5.52539V18.4742C22.8751 18.632 22.8404 18.7879 22.7733 18.9307L15.8424 11.9998ZM21.7969 4.44727H21.8038L12 14.2512L2.19623 4.44745H2.20312L21.7969 4.44727ZM1.22672 18.9307C1.15962 18.7879 1.12489 18.632 1.125 18.4742V5.52539C1.12488 5.3676 1.15961 5.21173 1.22672 5.06892L8.15761 11.9998L1.22672 18.9307ZM2.20312 19.5524C2.20083 19.5524 2.19844 19.5524 2.19619 19.5524L8.95312 12.7953L11.6023 15.4445C11.7078 15.5499 11.8509 15.6092 12 15.6092C12.1492 15.6092 12.2923 15.5499 12.3978 15.4445L15.0469 12.7953L21.8038 19.5522C21.8015 19.5522 21.7991 19.5522 21.7969 19.5522L2.20312 19.5524Z"></path>
                                                    </svg>
                                                <?php elseif ('phone' === $contact_type): ?>
                                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M9.5393 8.35927C9.68128 8.21729 9.82326 8.07058 9.96997 7.92386C10.4764 7.41747 10.7461 6.83062 10.7461 6.23431C10.7461 5.638 10.4764 5.05588 9.96997 4.54475L8.57857 3.15335C8.41766 2.99244 8.26148 2.83153 8.10531 2.67062C7.79295 2.35354 7.47586 2.02698 7.15878 1.72883C6.68078 1.25083 6.11286 1 5.51655 1C4.92023 1 4.34758 1.25083 3.85066 1.72883L2.09484 3.47991C1.46067 4.11408 1.10099 4.89024 1.02053 5.78471C0.930613 6.90161 1.13412 8.09424 1.66891 9.52823C2.48765 11.7478 3.72287 13.8065 5.55441 16.012C7.77875 18.6717 10.4574 20.7683 13.5147 22.2449C14.679 22.7986 16.236 23.447 17.9681 23.5605C18.077 23.5653 18.1811 23.57 18.2852 23.57C19.4495 23.57 20.3865 23.1677 21.1485 22.3395C21.1532 22.3348 21.1627 22.3253 21.1674 22.3158C21.4372 21.9893 21.7448 21.6959 22.0666 21.3835C22.2843 21.1705 22.5115 20.9528 22.7245 20.7257C23.723 19.6845 23.723 18.3641 22.715 17.356L19.9085 14.5496C19.4305 14.0526 18.8579 13.7876 18.2568 13.7876C17.6558 13.7876 17.0831 14.0479 16.5909 14.5401L14.9251 16.2155C14.7736 16.1255 14.6174 16.0498 14.466 15.9741C14.2814 15.8794 14.1063 15.7943 13.9501 15.6949C12.4262 14.7294 11.0443 13.4658 9.72387 11.8425C9.05657 11.0001 8.6117 10.2902 8.29935 9.56609C8.73475 9.17328 9.14649 8.75681 9.5393 8.35927ZM7.42381 8.79467C7.41434 8.80414 7.40961 8.80887 7.40014 8.81834C7.01207 9.20641 7.06886 9.58029 7.16351 9.86425C7.16824 9.87845 7.17298 9.88791 7.17771 9.90211C7.53739 10.7682 8.03905 11.5869 8.8152 12.5713C10.2208 14.2987 11.6927 15.6428 13.3207 16.6745C13.5242 16.8023 13.7372 16.9112 13.9407 17.0105C14.1252 17.1052 14.3003 17.1904 14.4565 17.2898C14.4755 17.2992 14.4896 17.3087 14.5086 17.3182C14.6648 17.3986 14.8115 17.4365 14.9629 17.4365C15.3415 17.4365 15.5829 17.1951 15.6633 17.1147L17.4097 15.3683C17.6795 15.0986 17.9729 14.9566 18.2568 14.9566C18.6118 14.9566 18.8957 15.1743 19.0803 15.3683L21.8962 18.1842C22.4547 18.7427 22.45 19.3437 21.882 19.9353C21.6833 20.1436 21.4798 20.3423 21.2573 20.5553C20.9308 20.8724 20.59 21.1989 20.2824 21.5681C19.7476 22.1455 19.1087 22.4152 18.2805 22.4152C18.2 22.4152 18.1149 22.4105 18.0344 22.4058C16.501 22.3064 15.0765 21.7101 14.0117 21.1989C11.1058 19.7933 8.55964 17.7962 6.43941 15.2689C4.6978 13.1724 3.5241 11.2178 2.75268 9.12596C2.27942 7.85287 2.09484 6.82116 2.17057 5.88409C2.22263 5.26412 2.46399 4.74353 2.90886 4.30339L4.64574 2.56651C4.9155 2.30621 5.21366 2.16896 5.50235 2.16896C5.78631 2.16896 6.07027 2.30148 6.34003 2.57124C6.65238 2.86466 6.95054 3.16755 7.2629 3.48937C7.42381 3.65028 7.58472 3.81593 7.74563 3.98157L9.13702 5.37297C9.42572 5.66166 9.57243 5.95508 9.57243 6.23904C9.57243 6.523 9.42572 6.81642 9.13702 7.10512C8.99031 7.25183 8.84833 7.39854 8.70162 7.54525C8.28042 7.98066 7.87341 8.39713 7.42381 8.79467Z"></path>
                                                        <path d="M14.297 10.2723C14.3843 10.3596 14.4971 10.4032 14.6135 10.4032C14.7299 10.4032 14.8427 10.3596 14.93 10.2723L20.7944 4.40419V7.87482C20.7944 8.12221 20.9945 8.32229 21.2419 8.32229C21.4893 8.32229 21.6894 8.12221 21.6894 7.87482V3.32735C21.6894 3.07997 21.4893 2.87988 21.2419 2.87988H16.6944C16.447 2.87988 16.2469 3.07997 16.2469 3.32735C16.2469 3.57474 16.447 3.77482 16.6944 3.77482H20.165L14.297 9.64288C14.1224 9.8175 14.1224 10.1013 14.297 10.2723Z"></path>
                                                    </svg>
                                                <?php else: ?>
                                                    <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <g>
                                                            <path d="M12.0042 22.3975C11.832 22.3975 11.6717 22.31 11.5786 22.1652L5.26323 12.3531C4.10435 10.5525 3.71588 8.40839 4.16965 6.31574C4.6233 4.22303 5.86484 2.43231 7.66545 1.27338C11.3825 -1.119 16.3528 -0.0413463 18.7453 3.67565C20.4388 6.30674 20.439 9.71264 18.7458 12.3525C18.7456 12.3527 18.7455 12.3529 18.7453 12.3531L12.4299 22.1652C12.3368 22.31 12.1764 22.3975 12.0042 22.3975ZM11.9931 1.01218C10.6953 1.01218 9.3834 1.37164 8.2133 2.12476C6.64008 3.13727 5.55538 4.70187 5.15898 6.53026C4.76258 8.35865 5.10194 10.232 6.11451 11.8052L12.0042 20.9559L17.8935 11.8059C19.3735 9.4987 19.3736 6.52243 17.8939 4.22361C16.5568 2.14609 14.2975 1.01218 11.9931 1.01218Z"></path>
                                                            <path d="M12.003 24.0002C8.37708 24.0002 4.47461 23.1869 4.47461 21.4013C4.47461 19.8335 7.50602 19.0609 10.3431 18.8605C10.5275 18.8481 10.7043 18.936 10.8044 19.0915L12.0044 20.9559L13.2042 19.0917C13.3043 18.9361 13.4811 18.8481 13.6657 18.8607C16.5014 19.0612 19.5315 19.8339 19.5315 21.4013C19.5314 23.1869 15.6289 24.0002 12.003 24.0002ZM10.1166 19.8935C7.03384 20.1554 5.48702 20.9898 5.48702 21.4013C5.48702 21.5374 5.79156 21.9815 7.18211 22.3959C8.46286 22.7776 10.1749 22.9877 12.003 22.9877C13.831 22.9877 15.5431 22.7776 16.8238 22.3959C18.2144 21.9815 18.5189 21.5375 18.5189 21.4013C18.5189 20.99 16.9727 20.1559 13.8919 19.8938L12.43 22.1652C12.3368 22.31 12.1765 22.3975 12.0043 22.3975C11.8321 22.3975 11.6718 22.31 11.5787 22.1652L10.1166 19.8935Z"></path>
                                                            <path d="M12.003 10.9966C10.2359 10.9966 8.79834 9.55898 8.79834 7.79192C8.79834 6.02486 10.236 4.58716 12.003 4.58716C13.7701 4.58716 15.2078 6.02481 15.2078 7.79192C15.2078 9.55903 13.7701 10.9966 12.003 10.9966ZM12.003 5.59957C10.7942 5.59957 9.81075 6.58308 9.81075 7.79192C9.81075 9.00076 10.7942 9.98422 12.003 9.98422C13.2119 9.98422 14.1953 9.00076 14.1953 7.79192C14.1953 6.58308 13.2118 5.59957 12.003 5.59957Z"></path>
                                                        </g>
                                                    </svg>
                                                <?php endif; ?>
                                            </div>
                                            <div class="content">
                                                <span><?php echo esc_html($contact_title); ?></span>
                                                <h6><a href="<?php echo esc_url($contact_url); ?>"><?php echo esc_html($contact_value); ?></a></h6>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="social-area-wrap">
                            <div class="arrow">
                                <svg width="6" height="66" viewBox="0 0 6 66" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M3.38672 5L5.77347 0H-3.26633e-05L2.38672 5H3.38672ZM2.88672 66L5.77347 61H-3.26633e-05L2.88672 66ZM2.88672 4.5H2.38672V28.0732H2.88672H3.38672V4.5H2.88672ZM2.88672 28.0732H2.38672V61.5H2.88672H3.38672V28.0732H2.88672Z" />
                                </svg>
                            </div>
                            <div class="social-area">
                                <?php if (!empty($social_title)): ?>
                                    <h6><?php echo esc_html($social_title); ?></h6>
                                <?php endif; ?>
                                <ul class="social-list">
                                    <?php foreach ($socials as $social): ?>
                                        <?php
                                        if (empty($social['softro_header_one_social_url']['url']) || empty($social['softro_header_one_social_icon']['value'])) {
                                            continue;
                                        }
                                        ?>
                                        <li>
                                            <a <?php echo wp_kses_post($this->get_link_attributes($social['softro_header_one_social_url'])); ?>>
                                                <?php \Elementor\Icons_Manager::render_icon($social['softro_header_one_social_icon'], ['aria-hidden' => 'true']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $layout = !empty($settings['softro_header_layout']) ? $settings['softro_header_layout'] : 'style_one';

        if ('style_two' === $layout) {
            $this->render_style_two($settings);
        } elseif ('style_three' === $layout) {
            $this->render_style_three($settings);
        } elseif ('style_four' === $layout) {
            $this->render_style_four($settings);
        } elseif ('style_five' === $layout) {
            $this->render_style_five($settings);
        } elseif ('style_six' === $layout) {
            $this->render_style_six($settings);
        } else {
            $this->render_style_one($settings);
        }
    }

    protected function render_style_one($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
        ?>

        <!-- header style one -->
        <header class="home1-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="company-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-dark"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>"></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-light"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_light_logo']['url'] ?? ''); ?>"></a>
                    </div>
                    <div class="main-menu">
                        <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                            <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                            </a>
                            <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                            </a>
                            <div class="menu-close-btn">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>

                        <?php $this->render_selected_menu($selected_menu_id); ?>

                        <?php if (!empty($button_text)): ?>
                            <div class="btn-and-contact-area d-lg-none d-block">
                                <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?><svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z"></path>
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nav-right">
                        <div class="right-sidebar-button">
                            <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                </g>
                            </svg>
                        </div>
                        <?php if (!empty($button_text)): ?>
                            <a class='primary-btn1 black-bg d-xl-flex d-none' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                <span>
                                    <?php echo esc_html($button_text); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z"></path>
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <?php echo esc_html($button_text); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                        <div class="sidebar-button mobile-menu-btn">
                            <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                    <path
                                        d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                    <path
                                        d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <?php
    }



    protected function render_style_two($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
    ?>

        <!-- header style two -->
        <header class="header-area home2-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="company-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>">
                            <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>">
                        </a>
                    </div>
                    <div class="menu-btn-wrap d-flex gap-5">
                        <div class="main-menu">
                            <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                                <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                    <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                                </a>
                                <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                    <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                                </a>
                                <div class="menu-close-btn">
                                    <i class="bi bi-x"></i>
                                </div>
                            </div>

                            <?php $this->render_selected_menu($selected_menu_id); ?>

                            <?php if (!empty($button_text)): ?>
                                <div class="btn-and-contact-area d-lg-none d-block">
                                    <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                        <span>
                                            <?php echo esc_html($button_text); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                        <span>
                                            <?php echo esc_html($button_text); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="nav-right">
                            <div class="right-sidebar-button">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                    </g>
                                </svg>
                            </div>
                            <div class="sidebar-button mobile-menu-btn">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                        <path
                                            d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                        <path
                                            d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <?php
    }

    protected function render_style_three($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
        $mail_text        = !empty($settings['softro_header_thee_email']) ? $settings['softro_header_thee_email'] : '';
    ?>

        <!-- header style three -->
        <header class="header-area home3-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="left-area">
                        <div class="company-logo">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-dark"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>"></a>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-light"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_light_logo']['url'] ?? ''); ?>"></a>
                        </div>
                        <div class="main-menu">
                            <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                                <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                    <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                                </a>
                                <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                    <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                                </a>
                                <div class="menu-close-btn">
                                    <i class="bi bi-x"></i>
                                </div>
                            </div>

                            <?php $this->render_selected_menu($selected_menu_id); ?>

                            <?php if (!empty($button_text)): ?>
                                <div class="btn-and-contact-area d-lg-none d-block">
                                    <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                        <span>
                                            <?php echo esc_html($button_text); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                        <span>
                                            <?php echo esc_html($button_text); ?>
                                            <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                <g>
                                                    <path
                                                        d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="right-area">
                        <div class="nav-right">
                            <?php if (!empty($mail_text)) : ?>
                                <div class="mail-area d-xxl-block d-none">
                                    <a href="mailto:<?php echo esc_attr($mail_text) ?>"><?php echo esc_html($mail_text) ?></a>
                                </div>
                            <?php endif; ?>
                            <div class="right-sidebar-button">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                    </g>
                                </svg>
                            </div>
                            <?php if (!empty($button_text)): ?>
                                <a class='primary-btn1 black-bg d-xl-flex d-none' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            <div class="sidebar-button mobile-menu-btn">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                        <path
                                            d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                        <path
                                            d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <?php
    }

    protected function render_style_four($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
    ?>

        <!-- header style four -->
        <header class="home4-sticky home4-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="company-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-dark"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>"></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-light"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_light_logo']['url'] ?? ''); ?>"></a>
                    </div>
                    <div class="main-menu">
                        <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                            <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                            </a>
                            <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                            </a>
                            <div class="menu-close-btn">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>

                        <?php $this->render_selected_menu($selected_menu_id); ?>

                        <?php if (!empty($button_text)): ?>
                            <div class="btn-and-contact-area d-lg-none d-block">
                                <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="right-area">
                        <div class="nav-right">
                            <div class="right-sidebar-button">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                    </g>
                                </svg>
                            </div>
                            <?php if (!empty($button_text)): ?>
                                <a class='primary-btn1 black-bg d-xl-flex d-none' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            <div class="sidebar-button mobile-menu-btn">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                        <path
                                            d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                        <path
                                            d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <?php
    }
    protected function render_style_five($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
        $lang_shortcode      = !empty($settings['softro_header_five_lang']) ? $settings['softro_header_five_lang'] : '';
    ?>

        <!-- header style five -->
        <header class="home1-header home5-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="company-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-dark"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>"></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-light"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_light_logo']['url'] ?? ''); ?>"></a>
                    </div>
                    <div class="main-menu">
                        <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                            <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                            </a>
                            <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                            </a>
                            <div class="menu-close-btn">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>

                        <?php $this->render_selected_menu($selected_menu_id); ?>

                        <?php if (!empty($button_text)): ?>
                            <div class="btn-and-contact-area d-lg-none d-block">
                                <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="nav-right">
                        <?php echo do_shortcode($lang_shortcode) ?>
                        <div class="right-sidebar-button">
                            <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                </g>
                            </svg>
                        </div>
                        <?php if (!empty($button_text)): ?>
                            <a class='primary-btn1 black-bg d-xl-flex d-none' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                <span>
                                    <?php echo esc_html($button_text); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path
                                                d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <?php echo esc_html($button_text); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path
                                                d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                        </g>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                        <div class="sidebar-button mobile-menu-btn">
                            <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                <g>
                                    <path
                                        d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                    <path
                                        d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                    <path
                                        d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <?php
    }
    protected function render_style_six($settings)
    {
        $selected_menu_id = isset($settings['softro_header_one_primary_menu']) ? absint($settings['softro_header_one_primary_menu']) : 0;
        $button_text      = !empty($settings['softro_header_one_button_text']) ? $settings['softro_header_one_button_text'] : '';
    ?>

        <!-- header style six -->
        <header class="header-area home4-header inner-header">
            <div class="header-area-wrap">
                <div class="container-fluid one d-flex flex-nowrap align-items-center justify-content-between">
                    <div class="company-logo">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-dark"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_dark_logo']['url'] ?? ''); ?>"></a>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-light"><img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_light_logo']['url'] ?? ''); ?>"></a>
                    </div>
                    <div class="main-menu">
                        <div class="mobile-logo-area d-lg-none d-flex align-items-center justify-content-between">
                            <a class='mobile-logo-wrap logo-dark' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_dark']['url'] ?? ''); ?>">
                            </a>
                            <a class='mobile-logo-wrap logo-light' href="<?php echo esc_url(home_url('/')); ?>">
                                <img alt="<?php echo esc_attr(get_bloginfo('name')); ?>" src="<?php echo esc_url($settings['softro_header_one_mobile_logo_light']['url'] ?? ''); ?>">
                            </a>
                            <div class="menu-close-btn">
                                <i class="bi bi-x"></i>
                            </div>
                        </div>

                        <?php $this->render_selected_menu($selected_menu_id); ?>

                        <?php if (!empty($button_text)): ?>
                            <div class="btn-and-contact-area d-lg-none d-block">
                                <a class='primary-btn1 black-bg' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="right-area">
                        <div class="nav-right">
                            <div class="right-sidebar-button">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M4.6142 0.138593C3.82636 -0.0461926 3.00645 -0.0461926 2.21861 0.138593C1.71263 0.257272 1.24984 0.514861 0.882348 0.882352C0.514858 1.24984 0.257268 1.71264 0.138589 2.21862C-0.0461965 3.00645 -0.0461965 3.82636 0.138589 4.6142C0.257268 5.12018 0.514858 5.58297 0.882348 5.95046C1.24984 6.31796 1.71263 6.57554 2.21861 6.69422C3.00645 6.87904 3.82636 6.87904 4.6142 6.69422C5.12017 6.57554 5.58297 6.31796 5.95046 5.95046C6.31795 5.58297 6.57554 5.12018 6.69422 4.6142C6.87904 3.82637 6.87904 3.00645 6.69422 2.21862C6.57554 1.71264 6.31795 1.24984 5.95046 0.882352C5.58297 0.514861 5.12017 0.257272 4.6142 0.138593ZM4.6142 9.30581C3.82636 9.12099 3.00645 9.12099 2.21861 9.30581C1.71263 9.42449 1.24984 9.68208 0.882348 10.0496C0.514858 10.4171 0.257268 10.8799 0.138589 11.3858C-0.0461965 12.1737 -0.0461965 12.9936 0.138589 13.7814C0.257268 14.2874 0.514858 14.7502 0.882348 15.1177C1.24984 15.4852 1.71263 15.7428 2.21861 15.8614C3.00645 16.0462 3.82635 16.0462 4.6142 15.8614C5.12017 15.7428 5.58297 15.4852 5.95046 15.1177C6.31795 14.7502 6.57554 14.2874 6.69422 13.7814C6.87904 12.9936 6.87904 12.1737 6.69422 11.3858C6.57554 10.8799 6.31795 10.4171 5.95046 10.0496C5.58297 9.68208 5.12017 9.42449 4.6142 9.30581ZM13.7814 0.138593C12.9936 -0.0461926 12.1737 -0.0461926 11.3858 0.138593C10.8798 0.257272 10.4171 0.514861 10.0496 0.882352C9.68207 1.24984 9.42448 1.71264 9.3058 2.21862C9.12099 3.00645 9.12099 3.82637 9.3058 4.6142C9.42448 5.12018 9.68207 5.58297 10.0496 5.95046C10.4171 6.31796 10.8798 6.57554 11.3858 6.69422C12.1737 6.87904 12.9936 6.87904 13.7814 6.69422C14.2874 6.57554 14.7502 6.31796 15.1177 5.95046C15.4852 5.58297 15.7428 5.12018 15.8614 4.6142C16.0462 3.82636 16.0462 3.00646 15.8614 2.21862C15.7428 1.71264 15.4852 1.24984 15.1177 0.882352C14.7502 0.514861 14.2874 0.257272 13.7814 0.138593ZM13.7814 9.30581C12.9936 9.12099 12.1737 9.12099 11.3858 9.30581C10.8798 9.42449 10.4171 9.68208 10.0496 10.0496C9.68207 10.4171 9.42448 10.8799 9.3058 11.3858C9.12099 12.1737 9.12099 12.9936 9.3058 13.7814C9.42448 14.2874 9.68207 14.7502 10.0496 15.1177C10.4171 15.4852 10.8798 15.7428 11.3858 15.8614C12.1737 16.0462 12.9936 16.0462 13.7814 15.8614C14.2874 15.7428 14.7502 15.4852 15.1177 15.1177C15.4852 14.7502 15.7428 14.2874 15.8614 13.7814C16.0462 12.9936 16.0462 12.1737 15.8614 11.3858C15.7428 10.8799 15.4852 10.4171 15.1177 10.0496C14.7502 9.68208 14.2874 9.42449 13.7814 9.30581Z" />
                                    </g>
                                </svg>
                            </div>
                            <?php if (!empty($button_text)): ?>
                                <a class='primary-btn1 black-bg d-xl-flex d-none' <?php echo wp_kses_post($this->get_link_attributes($settings['softro_header_one_button_url'])); ?>>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span>
                                        <?php echo esc_html($button_text); ?>
                                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <g>
                                                <path
                                                    d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                            </g>
                                        </svg>
                                    </span>
                                </a>
                            <?php endif; ?>
                            <div class="sidebar-button mobile-menu-btn">
                                <svg width="16" height="16" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                                    <g>
                                        <path
                                            d="M1.03556 2.52631H8.41896C8.99112 2.52631 9.45456 1.96107 9.45456 1.26316C9.45456 0.565247 8.99112 0 8.41896 0H1.03556C0.463399 0 0 0.565247 0 1.26316C0 1.96107 0.463399 2.52631 1.03556 2.52631Z" />
                                        <path
                                            d="M0.984016 9.26267H15.016C15.5597 9.26267 16 8.6974 16 7.99948C16 7.30157 15.5597 6.73633 15.016 6.73633H0.984016C0.440337 6.73633 0 7.30157 0 7.99948C0 8.6974 0.440337 9.26267 0.984016 9.26267Z" />
                                        <path
                                            d="M15.0441 13.4736H8.22859C7.70046 13.4736 7.27271 14.0389 7.27271 14.7367C7.27271 15.4347 7.70046 15.9999 8.22859 15.9999H15.0441C15.5722 15.9999 16 15.4347 16 14.7367C16 14.0389 15.5722 13.4736 15.0441 13.4736Z" />
                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Headers_Widget());
