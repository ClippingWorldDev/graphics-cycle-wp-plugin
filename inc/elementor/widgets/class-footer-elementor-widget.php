<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Footer_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_footer';
    }

    public function get_title()
    {
        return esc_html__('EG Footer', 'softro-core');
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
        $this->register_layout_controls();
        $this->register_brand_controls();
        $this->register_contact_controls();
        $this->register_navigation_controls();
        $this->register_newsletter_controls();
        $this->register_bottom_controls();
        $this->register_style_controls();
    }

    private function register_layout_controls()
    {
        $this->start_controls_section(
            'softro_footer_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_footer_genaral_style_selection',
            [
                'label'   => esc_html__('Select Style', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'style_one'   => esc_html__('Style One', 'softro-core'),
                    'style_two'   => esc_html__('Style Two', 'softro-core'),
                    'style_three' => esc_html__('Style Three', 'softro-core'),
                    'style_four'  => esc_html__('Style Four', 'softro-core'),
                    'style_five'  => esc_html__('Style Five', 'softro-core'),
                ],
                'default' => 'style_one',
            ]
        );

        $this->end_controls_section();
    }

    private function register_brand_controls()
    {
        $this->start_controls_section(
            'softro_footer_brand_content',
            [
                'label' => esc_html__('Brand / Heading', 'softro-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_footer_logo_dark',
            [
                'label'   => esc_html__('Logo (Dark Footer)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_asset_url('image/icon/footer-logo.svg'),
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_logo_light',
            [
                'label'   => esc_html__('Logo (Light/White)', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_asset_url('image/icon/white-logo.svg'),
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_five_vector_image',
            [
                'label'   => esc_html__('Style Five Vector Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_asset_url('image/sass/vector/footer-vector.svg'),
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_show_style_five_vector',
            [
                'label'        => esc_html__('Show Style Five Vector', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'    => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_primary_heading',
            [
                'label'       => esc_html__('Primary Heading (Style 1/2)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Driving Growth Through Smart Technology.', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_three_heading',
            [
                'label'       => esc_html__('Top Heading (Style 3)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Shape the future with us', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_three_top_btn_url',
            [
                'label'       => esc_html__('Top Circle Button URL (Style 3)', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_four_description',
            [
                'label'       => esc_html__('Description (Style 4)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('This professional company provides creative services to help businesses, brands, and individuals communicate visually and build strong identities.', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_four',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_five_top_title',
            [
                'label'       => esc_html__('Top Title (Style 5)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Get updates and product news in your inbox.', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_style_five_top_description',
            [
                'label'       => esc_html__('Top Description (Style 5)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Subscribe to receive launch updates, product notes, and practical growth tips.', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_show_download_button',
            [
                'label'        => esc_html__('Show Download Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_footer_download_button_text',
            [
                'label'       => esc_html__('Download Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Company Desk', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_button_url',
            [
                'label'       => esc_html__('Download Button URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
                'condition'   => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_contact_controls()
    {
        $this->start_controls_section(
            'softro_footer_contact_content',
            [
                'label' => esc_html__('Contact', 'softro-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_footer_contact_title',
            [
                'label'       => esc_html__('Contact Widget Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Contact', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                ],
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'contact_type',
            [
                'label'   => esc_html__('Contact Type', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'phone',
                'options' => [
                    'phone' => esc_html__('Phone', 'softro-core'),
                    'email' => esc_html__('Email', 'softro-core'),
                ],
            ]
        );

        $repeater->add_control(
            'contact_label',
            [
                'label'       => esc_html__('Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Call 24/7 Hours', 'softro-core'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'contact_value',
            [
                'label'       => esc_html__('Value', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('+99-763 684 4563', 'softro-core'),
                'label_block' => true,
                'description' => esc_html__('Phone number or email address based on selected type.', 'softro-core'),
            ]
        );

        $repeater->add_control(
            'contact_custom_url',
            [
                'label'       => esc_html__('Custom URL (Optional)', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'description' => esc_html__('If set, this URL will override auto tel:/mailto: link.', 'softro-core'),
            ]
        );

        $repeater->add_control(
            'contact_icon',
            [
                'label' => esc_html__('Icon', 'softro-core'),
                'type'  => Controls_Manager::ICONS,
            ]
        );

        $this->add_control(
            'softro_footer_contacts',
            [
                'label'       => esc_html__('Contact Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => $this->get_default_contacts(),
                'title_field' => '{{{ contact_label }}}',
            ]
        );

        $this->add_control(
            'softro_footer_show_map_button',
            [
                'label'        => esc_html__('Show Map Button', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'softro_footer_map_button_text',
            [
                'label'       => esc_html__('Map Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View Site Map', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_show_map_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_map_button_url',
            [
                'label'       => esc_html__('Map Button URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url'         => 'https://www.google.com/maps',
                    'is_external' => true,
                    'nofollow'    => true,
                ],
                'condition'   => [
                    'softro_footer_show_map_button' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_navigation_controls()
    {
        $this->start_controls_section(
            'softro_footer_navigation_content',
            [
                'label' => esc_html__('Navigation Lists', 'softro-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_footer_company_title',
            [
                'label'       => esc_html__('Company Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Company', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_company_links',
            $this->get_links_repeater_control_args(
                esc_html__('Company Links', 'softro-core'),
                $this->get_default_company_links()
            )
        );

        $this->add_control(
            'softro_footer_services_title',
            [
                'label'       => esc_html__('Services Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Services', 'softro-core'),
                'label_block' => true,
                'separator'   => 'before',
                'condition'   => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_services_links',
            array_merge(
                $this->get_links_repeater_control_args(
                    esc_html__('Services Links', 'softro-core'),
                    $this->get_default_services_links()
                ),
                [
                    'condition' => [
                        'softro_footer_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_four'],
                    ],
                ]
            )
        );

        $this->add_control(
            'softro_footer_product_title',
            [
                'label'       => esc_html__('Product Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Product', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => ['style_five'],
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_product_links',
            array_merge(
                $this->get_links_repeater_control_args(
                    esc_html__('Product Links', 'softro-core'),
                    $this->get_default_product_links()
                ),
                [
                    'condition' => [
                        'softro_footer_genaral_style_selection' => ['style_five'],
                    ],
                ]
            )
        );

        $this->add_control(
            'softro_footer_resources_title',
            [
                'label'       => esc_html__('Resources Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Resources', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => ['style_five'],
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_resources_links',
            array_merge(
                $this->get_links_repeater_control_args(
                    esc_html__('Resources Links', 'softro-core'),
                    $this->get_default_resources_links()
                ),
                [
                    'condition' => [
                        'softro_footer_genaral_style_selection' => ['style_five'],
                    ],
                ]
            )
        );

        $this->end_controls_section();
    }

    private function register_newsletter_controls()
    {
        $this->start_controls_section(
            'softro_footer_newsletter_content',
            [
                'label'     => esc_html__('Newsletter', 'softro-core'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_newsletter_title',
            [
                'label'       => esc_html__('Newsletter Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Newsletter', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_newsletter_placeholder',
            [
                'label'       => esc_html__('Input Placeholder', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Enter your email here', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_newsletter_button_text',
            [
                'label'       => esc_html__('Top Form Button Text (Style 5)', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Subscribe', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_newsletter_form_action',
            [
                'label'       => esc_html__('Form Action URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_newsletter_shortcode',
            [
                'label'       => esc_html__('Form Shortcode (Optional)', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'placeholder' => esc_html__('[contact-form-7 id="123" title="Newsletter"]', 'softro-core'),
                'description' => esc_html__('If filled, shortcode output will replace default newsletter form in widget blocks.', 'softro-core'),
            ]
        );

        $features = new Repeater();

        $features->add_control(
            'feature_text',
            [
                'label'       => esc_html__('Feature Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('No setup fee', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_style_five_features',
            [
                'label'       => esc_html__('Top Feature Items (Style 5)', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $features->get_controls(),
                'default'     => $this->get_default_style_five_features(),
                'title_field' => '{{{ feature_text }}}',
                'condition'   => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_bottom_controls()
    {
        $this->start_controls_section(
            'softro_footer_bottom_content',
            [
                'label' => esc_html__('Bottom Area', 'softro-core'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'softro_footer_copyright_text',
            [
                'label'       => esc_html__('Copyright Text', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('© Copyright 2026', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_copyright_link_text',
            [
                'label'       => esc_html__('Copyright Link Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'softro_footer_copyright_link_url',
            [
                'label'       => esc_html__('Copyright Link URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url'         => '',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->add_control(
            'softro_footer_show_social',
            [
                'label'        => esc_html__('Show Social Links', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_social_title',
            [
                'label'       => esc_html__('Social Heading', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Social media link', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'softro_footer_show_social'             => 'yes',
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );

        $social_repeater = new Repeater();

        $social_repeater->add_control(
            'social_icon_class',
            [
                'label'       => esc_html__('Icon Class', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'bx bxl-facebook',
                'label_block' => true,
            ]
        );

        $social_repeater->add_control(
            'social_url',
            [
                'label'       => esc_html__('URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_links',
            [
                'label'       => esc_html__('Social Links', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $social_repeater->get_controls(),
                'default'     => $this->get_default_social_links(),
                'title_field' => '{{{ social_icon_class }}}',
                'condition'   => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_policy_heading',
            [
                'label'     => esc_html__('Policy Links', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_policy_links',
            $this->get_links_repeater_control_args(
                esc_html__('Policy / Bottom Links', 'softro-core'),
                $this->get_default_policy_links()
            )
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->register_section_style_controls();
        $this->register_section_three_style_controls();
        $this->register_heading_style_controls();
        $this->register_widget_style_controls();
        $this->register_contact_style_controls();
        $this->register_button_style_controls();
        $this->register_bottom_style_controls();
    }

    private function register_section_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_section_style',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_footer_genaral_style_selection!' => ['style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_section_heading',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_footer_section_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .footer-section',
            ]
        );

        $this->add_control(
            'softro_footer_menu_wrap_bg_color',
            [
                'label'     => esc_html__('Menu Wrap Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-menu-wrap' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_top_area_bg_color',
            [
                'label'     => esc_html__('Style 5 Top Area Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home5-footer-section .footer-top-area-wrap .footer-top-area' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_five',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_footer_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_footer_section_margin',
            [
                'label'      => esc_html__('Section Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'softro_footer_two_line_color',
            [
                'label'     => esc_html__('Style 2 Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home2-footer-section .footer-menu-wrap' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home2-footer-section .footer-contact-wrap::before' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_two',
                ],
            ]
        );

        $this->end_controls_section();
    }
    private function register_section_three_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_three_section_style',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_footer_three_section_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap',
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'softro_footer_four_section_bg',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .footer-section.home4-footer-section .footer-menu-wrap',
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_four',
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_footer_three_section_padding',
            [
                'label'      => esc_html__('Section Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .footer-menu-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_control(
            'softro_footer_three_section_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap .footer-top-area' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .footer-menu' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .footer-contact-area' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .divider::after' => 'background: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'softro_footer_three_section_vector_image',
            [
                'label'     => esc_html__('Vector Image', 'softro-core'),
                'type'      => Controls_Manager::MEDIA,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap::after' => 'content: url({{URL}});',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_heading_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_heading_style',
            [
                'label' => esc_html__('Heading / Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_footer_heading_label',
            [
                'label'     => esc_html__('Main Heading', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_five'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_heading_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-contact-wrap .title-area h2, {{WRAPPER}} .footer-section .footer-top-area .footer-logo-area h2, {{WRAPPER}} .footer-section .footer-top-left-content h2' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_five'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_footer_heading_typography',
                'selector'  => '{{WRAPPER}} .footer-section .footer-contact-wrap .title-area h2, {{WRAPPER}} .footer-section .footer-top-area .footer-logo-area h2, {{WRAPPER}} .footer-section .footer-top-left-content h2',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two', 'style_three', 'style_five'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_description_label',
            [
                'label'     => esc_html__('Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_four', 'style_five'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_description_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-logo-area p, {{WRAPPER}} .footer-section .footer-top-left-content p' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_four', 'style_five'],
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_footer_description_typography',
                'selector'  => '{{WRAPPER}} .footer-section .footer-logo-area p, {{WRAPPER}} .footer-section .footer-top-left-content p',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_four', 'style_five'],
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_widget_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_widget_style',
            [
                'label' => esc_html__('Widget Lists', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_footer_widget_title_label',
            [
                'label' => esc_html__('Widget Title', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_footer_widget_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-widget .widget-title h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_footer_widget_title_typography',
                'selector' => '{{WRAPPER}} .footer-section .footer-widget .widget-title h3',
            ]
        );

        $this->add_control(
            'softro_footer_widget_links_label',
            [
                'label'     => esc_html__('Widget Links', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_widget_links_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-widget .widget-list li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_widget_links_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-widget .widget-list li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_footer_widget_links_typography',
                'selector' => '{{WRAPPER}} .footer-section .footer-widget .widget-list li a',
            ]
        );

        $this->add_control(
            'softro_footer_widget_badge_color',
            [
                'label'     => esc_html__('Badge Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-widget .widget-list li a span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_widget_badge_bg_color',
            [
                'label'     => esc_html__('Badge Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .footer-widget .widget-list li a span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_contact_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_contact_style',
            [
                'label' => esc_html__('Contact Items', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_footer_contact_icon_label',
            [
                'label' => esc_html__('Icon Box', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_footer_contact_icon_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_contact_icon_border',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .icon' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_contact_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .icon svg' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .icon i'   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_contact_label_style_label',
            [
                'label'     => esc_html__('Label Text', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_contact_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .content span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_footer_contact_label_typography',
                'selector' => '{{WRAPPER}} .footer-section .contact-list .single-contact .content span',
            ]
        );

        $this->add_control(
            'softro_footer_contact_value_style_label',
            [
                'label'     => esc_html__('Value Text', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_contact_value_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .content a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_contact_value_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .contact-list .single-contact .content a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_footer_contact_value_typography',
                'selector' => '{{WRAPPER}} .footer-section .contact-list .single-contact .content a',
            ]
        );

        $this->end_controls_section();
    }

    private function register_button_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_button_style',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_footer_primary_map_btn_heading',
            [
                'label'     => esc_html__('Map Button (Style 1/2)', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_footer_primary_map_btn_typography',
                'selector'  => '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_primary_map_btn_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1 svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_primary_map_btn_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1:hover'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1:hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_primary_map_btn_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_primary_map_btn_hover_bg',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1:hover::after' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'softro_footer_primary_map_btn_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .footer-section:not(.home3-footer-section):not(.home4-footer-section):not(.home5-footer-section) .footer-contact-wrap .primary-btn1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_view_map_btn_heading',
            [
                'label'     => esc_html__('Map Button (Style 3/4/5)', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_footer_view_map_btn_typography',
                'selector'  => '{{WRAPPER}} .footer-section.home3-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home4-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home5-footer-section .view-map-btn',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_view_map_btn_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home4-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home5-footer-section .view-map-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_view_map_btn_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home4-footer-section .view-map-btn, {{WRAPPER}} .footer-section.home5-footer-section .view-map-btn' => 'background: linear-gradient(to bottom, {{VALUE}} 0%, {{VALUE}} 98%); background-repeat: no-repeat; background-size: 100% 1px; background-position: left 100%;',
                    '{{WRAPPER}} .footer-section.home3-footer-section .view-map-btn:hover, {{WRAPPER}} .footer-section.home4-footer-section .view-map-btn:hover, {{WRAPPER}} .footer-section.home5-footer-section .view-map-btn:hover' => 'background-size: 0px 1px; background-position: 0% 100%;',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four', 'style_five'],
                    'softro_footer_show_map_button'         => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_heading',
            [
                'label'     => esc_html__('Download Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'softro_footer_download_btn_typography',
                'selector'  => '{{WRAPPER}} .footer-section .file-download-btn',
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_icon_bg',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_icon_hover_bg',
            [
                'label'     => esc_html__('Icon Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn:hover .icon' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_icon_hover_color',
            [
                'label'     => esc_html__('Icon Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn:hover .icon svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_download_btn_icon_border_color',
            [
                'label'     => esc_html__('Icon Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .file-download-btn .icon' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_download_button' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_heading',
            [
                'label'     => esc_html__('Style 3 Circle Button', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_border',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );
        $this->add_control(
            'softro_footer_circle_btn_border_hover',
            [
                'label'     => esc_html__('Border Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a:hover' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_hover_bg',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a:hover' => 'box-shadow: inset 0 0 0 10em {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_circle_btn_icon_hover_color',
            [
                'label'     => esc_html__('Hover Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-top-area .btn-area .icon a:hover svg' => 'fill: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => 'style_three',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_bottom_style_controls()
    {
        $this->start_controls_section(
            'softro_footer_bottom_style',
            [
                'label' => esc_html__('Bottom / Social', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'softro_footer_copyright_heading',
            [
                'label' => esc_html__('Copyright', 'softro-core'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'softro_footer_copyright_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .copyright-and-social-area p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_copyright_link_color',
            [
                'label'     => esc_html__('Link Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .copyright-and-social-area p a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_policy_bar_heading',
            [
                'label'     => esc_html__('Policy Bar', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );
        $this->add_control(
            'softro_footer_policy_bar_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .logo-and-sevice-menu-wrap' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );
        $this->add_control(
            'softro_footer_policy_bar_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .logo-and-sevice-menu-wrap' => 'border-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );
        $this->add_responsive_control(
            'softro_footer_policy_bar_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em', 'rem'],

                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                    'rem' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],

                'selectors' => [
                    '{{WRAPPER}} .footer-section .logo-and-sevice-menu-wrap' =>
                    'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'softro_footer_genaral_style_selection' => ['style_one', 'style_two'],
                ],
            ]
        );
        $this->add_control(
            'softro_footer_policy_links_heading',
            [
                'label'     => esc_html__('Policy Links', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'softro_footer_policy_links_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .copyright-and-social-area .service-list li a, {{WRAPPER}} .footer-section .logo-and-sevice-menu-wrap .service-list li a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_policy_links_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .copyright-and-social-area .service-list li a:hover, {{WRAPPER}} .footer-section .logo-and-sevice-menu-wrap .service-list li a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_title_heading',
            [
                'label'     => esc_html__('Social Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_control(
            'softro_footer_social_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap .footer-widget .social-area h4' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_footer_social_title_typography',
                'selector' => '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap .footer-widget .social-area h4',
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                    'softro_footer_genaral_style_selection' => ['style_three', 'style_four'],
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_heading_style',
            [
                'label'     => esc_html__('Social Icons', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .social-list li a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_icon_bg',
            [
                'label'     => esc_html__('Icon Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .social-list li a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap .footer-widget .social-area .social-list li a' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .footer-menu-wrap .footer-widget .social-area .social-list li a' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_icon_hover_color',
            [
                'label'     => esc_html__('Hover Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .social-list li a:hover' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'softro_footer_social_icon_hover_bg',
            [
                'label'     => esc_html__('Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .footer-section .social-list li a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home3-footer-section .footer-menu-wrap .footer-widget .social-area .social-list li a:hover' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .footer-section.home4-footer-section .footer-menu-wrap .footer-widget .social-area .social-list li a:hover' => 'background-color: {{VALUE}};',
                ],
                'condition' => [
                    'softro_footer_show_social' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_links_repeater_control_args($label, $default)
    {
        $repeater = new Repeater();

        $repeater->add_control(
            'link_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Menu Item', 'softro-core'),
                'label_block' => true,
            ]
        );

        $repeater->add_control(
            'link_url',
            [
                'label'       => esc_html__('URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'label_block' => true,
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $repeater->add_control(
            'link_badge',
            [
                'label'       => esc_html__('Badge (Optional)', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
            ]
        );

        return [
            'label'       => $label,
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $repeater->get_controls(),
            'default'     => $default,
            'title_field' => '{{{ link_text }}}',
        ];
    }

    private function get_default_contacts()
    {
        return [
            [
                'contact_type'  => 'phone',
                'contact_label' => esc_html__('Call 24/7 Hours', 'softro-core'),
                'contact_value' => '+99-763 684 4563',
            ],
            [
                'contact_type'  => 'email',
                'contact_label' => esc_html__('Send Us Mail', 'softro-core'),
                'contact_value' => 'info@example.com',
            ],
        ];
    }

    private function get_default_company_links()
    {
        return [
            ['link_text' => esc_html__('About us', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Our Team', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Our Portfolio', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Careers', 'softro-core'), 'link_url' => ['url' => '#'], 'link_badge' => esc_html__('Hiring', 'softro-core')],
            ['link_text' => esc_html__('Contact Us', 'softro-core'), 'link_url' => ['url' => '#']],
        ];
    }

    private function get_default_services_links()
    {
        return [
            ['link_text' => esc_html__('Product Development', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Design Department', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Software Development', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('E-commerce Solutions', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Cloud & DevOps', 'softro-core'), 'link_url' => ['url' => '#']],
        ];
    }

    private function get_default_product_links()
    {
        return [
            ['link_text' => esc_html__('Features', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Templates', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Pricing', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Roadmap', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('AI Builder', 'softro-core'), 'link_url' => ['url' => '#']],
        ];
    }

    private function get_default_resources_links()
    {
        return [
            ['link_text' => esc_html__('Video Tutorials', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Blog', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Help Center', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Documentation', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Community', 'softro-core'), 'link_url' => ['url' => '#']],
        ];
    }

    private function get_default_social_links()
    {
        return [
            ['social_icon_class' => 'bx bxl-facebook', 'social_url' => ['url' => 'https://www.facebook.com/']],
            ['social_icon_class' => 'bi bi-twitter-x', 'social_url' => ['url' => 'https://x.com/']],
            ['social_icon_class' => 'bx bxl-linkedin', 'social_url' => ['url' => 'https://www.linkedin.com/']],
            ['social_icon_class' => 'bx bxl-instagram-alt', 'social_url' => ['url' => 'https://www.instagram.com/']],
        ];
    }

    private function get_default_policy_links()
    {
        return [
            ['link_text' => esc_html__('Support Policy', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Terms & Conditions', 'softro-core'), 'link_url' => ['url' => '#']],
            ['link_text' => esc_html__('Privacy Policy', 'softro-core'), 'link_url' => ['url' => '#']],
        ];
    }

    private function get_default_style_five_features()
    {
        return [
            ['feature_text' => esc_html__('No setup fee', 'softro-core')],
            ['feature_text' => esc_html__('Cancel anytime', 'softro-core')],
            ['feature_text' => esc_html__('No credit card required', 'softro-core')],
        ];
    }

    private function get_asset_url($path)
    {
        return trailingslashit(get_template_directory_uri()) . 'assets/' . ltrim($path, '/');
    }

    private function get_media_url($media, $fallback = '')
    {
        if (is_array($media) && !empty($media['url'])) {
            return $media['url'];
        }

        return $fallback;
    }

    private function get_link_url($link, $fallback = '#')
    {
        if (is_array($link) && !empty($link['url'])) {
            return $link['url'];
        }

        return $fallback;
    }

    private function get_link_attrs($link, $fallback = '#')
    {
        $url = $this->get_link_url($link, $fallback);

        $attrs = 'href="' . esc_url($url) . '"';

        if (is_array($link) && !empty($link['is_external'])) {
            $attrs .= ' target="_blank"';
        }

        $rel = [];

        if (is_array($link) && !empty($link['is_external'])) {
            $rel[] = 'noopener';
        }

        if (is_array($link) && !empty($link['nofollow'])) {
            $rel[] = 'nofollow';
        }

        if (!empty($rel)) {
            $attrs .= ' rel="' . esc_attr(implode(' ', array_unique($rel))) . '"';
        }

        return $attrs;
    }

    private function sanitize_phone_for_tel($value)
    {
        $value = preg_replace('/[^0-9+]/', '', (string) $value);
        return $value ?: '';
    }

    private function get_contact_href($item)
    {
        if (!empty($item['contact_custom_url']) && !empty($item['contact_custom_url']['url'])) {
            return $item['contact_custom_url']['url'];
        }

        $type  = !empty($item['contact_type']) ? $item['contact_type'] : 'phone';
        $value = !empty($item['contact_value']) ? trim((string) $item['contact_value']) : '';

        if ($value === '') {
            return '#';
        }

        if ($type === 'email') {
            $email = sanitize_email($value);
            return $email ? 'mailto:' . $email : '#';
        }

        $phone = $this->sanitize_phone_for_tel($value);
        return $phone ? 'tel:' . $phone : '#';
    }

    private function has_icon($icon)
    {
        return is_array($icon) && !empty($icon['value']);
    }

    private function render_default_contact_icon($type)
    {
        if ($type === 'email') {
?>
            <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 5.5C3 4.67 3.67 4 4.5 4h15A1.5 1.5 0 0 1 21 5.5v13a1.5 1.5 0 0 1-1.5 1.5h-15A1.5 1.5 0 0 1 3 18.5v-13Zm1.8.3 7.2 5.18 7.2-5.18H4.8Zm14.4 12.4v-9.6l-6.73 4.84a.83.83 0 0 1-.94 0L4.8 8.6v9.6h14.4Z" />
            </svg>
        <?php
        } else {
        ?>
            <svg width="22" height="22" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M6.62 10.79a15.5 15.5 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1-.24c1.1.36 2.29.56 3.53.56a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1C10.3 21 3 13.7 3 4a1 1 0 0 1 1-1h3.31a1 1 0 0 1 1 1c0 1.24.2 2.43.56 3.53a1 1 0 0 1-.24 1l-2.01 2.26Z" />
            </svg>
        <?php
        }
    }

    private function render_arrow_icon()
    {
        ?>
        <svg width="19" height="19" viewBox="0 0 19 19" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5.69375 4.27956C5.70923 4.78621 7.37601 5.47434 7.75696 5.48321L12.0047 5.48321L4.28896 13.199C4.01573 13.4722 4.01572 13.9152 4.28896 14.1885C4.5622 14.4617 5.0052 14.4617 5.27843 14.1885L12.9942 6.47268L12.9942 10.7205C12.9943 11.1068 13.7927 12.7648 14.179 12.7648C14.5654 12.7648 14.3934 11.1068 14.3934 10.7205L14.3934 4.7836C14.3933 4.39726 14.0802 4.08409 13.6938 4.08401L7.75697 4.08401C7.35309 4.08893 5.68406 3.81613 5.69375 4.27956Z" />
        </svg>
    <?php
    }

    private function render_large_arrow_icon()
    {
    ?>
        <svg width="65" height="65" viewBox="0 0 65 65" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M20.8404 16.2108C20.8911 17.8701 26.3498 20.1237 27.5974 20.1528L41.5089 20.1528L16.2397 45.422C15.3448 46.3168 15.3448 47.7677 16.2397 48.6625C17.1345 49.5574 18.5854 49.5574 19.4802 48.6625L44.7494 23.3933L44.7494 37.3048C44.7496 38.5702 47.3644 44 48.6297 44.0001C49.895 44 49.3317 38.5701 49.3318 37.3048L49.3318 17.8616C49.3315 16.5963 48.3059 15.5707 47.0406 15.5704L27.5974 15.5704C26.2747 15.5865 20.8086 14.6931 20.8404 16.2108Z" />
        </svg>
    <?php
    }

    private function render_check_icon()
    {
    ?>
        <svg width="14" height="14" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M5.3 10.2 1.8 6.7l1-1 2.5 2.5L11.2 2.3l1 1-6.9 6.9Z" />
        </svg>
    <?php
    }

    private function render_download_icon()
    {
    ?>
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M10.6758 2.80469H13.3242V11.0102H17.0039L12 17.5609L6.99609 11.0102H10.6758V2.80469Z"></path>
            <path d="M10.6758 3.1566H13.3266L12.975 2.80504V9.99801C12.975 10.3308 12.9633 10.6636 12.975 10.9964V11.0105C12.975 11.2004 13.1367 11.3621 13.3266 11.3621H17.0062C16.9055 11.1863 16.8047 11.0105 16.7016 10.8324C16.5352 11.0504 16.3711 11.266 16.2047 11.4839C15.8062 12.0043 15.4078 12.5246 15.0117 13.0472C14.5312 13.6777 14.0484 14.3082 13.568 14.9386C13.1508 15.4847 12.7336 16.0285 12.3187 16.5746C12.1172 16.8394 11.8969 17.0972 11.7094 17.3714C11.707 17.3761 11.7023 17.3785 11.7 17.3832H12.307C12.1406 17.1652 11.9766 16.9496 11.8102 16.7316C11.4117 16.2113 11.0133 15.691 10.6172 15.1683C10.1367 14.5379 9.6539 13.9074 9.17343 13.2769C8.75625 12.7308 8.33906 12.1871 7.92422 11.641C7.72265 11.3761 7.53281 11.0949 7.31484 10.8441C7.3125 10.8394 7.30781 10.8371 7.30547 10.8324C7.20469 11.0082 7.1039 11.1839 7.00078 11.3621H10.6805C10.8703 11.3621 11.032 11.2004 11.032 11.0105V3.81754C11.032 3.48473 11.0437 3.15192 11.032 2.8191V2.80504C11.032 2.62223 10.8703 2.4441 10.6805 2.45348C10.4906 2.46285 10.3289 2.60817 10.3289 2.80504V9.99801C10.3289 10.3285 10.3102 10.666 10.3289 10.9964V11.0105L10.6805 10.6589H7.00078C6.75937 10.6589 6.52969 10.9683 6.69609 11.1886C6.8625 11.4066 7.02656 11.6222 7.19297 11.8402C7.5914 12.3605 7.98984 12.8808 8.38593 13.4035C8.8664 14.0339 9.34922 14.6644 9.82968 15.2949C10.2469 15.841 10.6641 16.3847 11.0789 16.9308C11.2828 17.1957 11.4844 17.4629 11.6883 17.7277C11.6906 17.7324 11.6953 17.7347 11.6977 17.7394C11.8687 17.9621 12.1336 17.9621 12.3047 17.7394C12.4711 17.5214 12.6352 17.3058 12.8016 17.0879C13.2 16.5675 13.5984 16.0472 13.9945 15.5246C14.475 14.8941 14.9578 14.2636 15.4383 13.6332C15.8555 13.0871 16.2727 12.5433 16.6875 11.9972C16.8891 11.7324 17.1117 11.4769 17.2969 11.2004C17.2992 11.1957 17.3039 11.1933 17.3062 11.1886C17.475 10.9683 17.2453 10.6589 17.0016 10.6589H13.3219L13.6734 11.0105V3.81754C13.6734 3.48473 13.6852 3.15192 13.6734 2.8191V2.80504C13.6734 2.6152 13.5117 2.45348 13.3219 2.45348H10.6711C10.4883 2.45348 10.3102 2.6152 10.3195 2.80504C10.3312 2.99489 10.4789 3.1566 10.6758 3.1566Z"></path>
            <path d="M20.9297 17.2117V19.9609C20.9297 20.1132 20.939 20.2726 20.9203 20.425C20.9297 20.3617 20.9367 20.3007 20.9461 20.2375C20.9344 20.3101 20.9156 20.3804 20.8875 20.4507L20.9578 20.282C20.9367 20.3289 20.9133 20.3757 20.8875 20.4203C20.843 20.4882 20.8031 20.4836 20.925 20.3781C20.9062 20.3945 20.8898 20.4156 20.8734 20.4343C20.8664 20.4414 20.8195 20.4882 20.8172 20.4859C20.8125 20.4836 20.9859 20.3734 20.8734 20.439C20.8242 20.4695 20.7773 20.4953 20.7234 20.5187L20.8922 20.4484C20.8219 20.4765 20.7539 20.4953 20.6789 20.507C20.7422 20.4976 20.8031 20.4906 20.8664 20.4812C20.6789 20.5047 20.482 20.4906 20.2945 20.4906H3.2906C3.23904 20.4906 3.18982 20.4882 3.13826 20.4812C3.20154 20.4906 3.26248 20.4976 3.32576 20.507C3.2531 20.4953 3.18279 20.4765 3.11248 20.4484L3.28123 20.5187C3.23435 20.4976 3.18748 20.4742 3.14295 20.4484C3.07498 20.4039 3.07967 20.364 3.18514 20.4859C3.16873 20.4672 3.14764 20.4507 3.12889 20.4343C3.12185 20.4273 3.07498 20.3804 3.07732 20.3781C3.07967 20.3734 3.18982 20.5468 3.1242 20.4343C3.09373 20.3851 3.06795 20.3382 3.04451 20.2843L3.11482 20.4531C3.0867 20.3828 3.06795 20.3148 3.05623 20.2398C3.0656 20.3031 3.07264 20.364 3.08201 20.4273C3.0656 20.2984 3.07264 20.1648 3.07264 20.0359V17.2164C3.07264 16.8484 2.7492 16.4968 2.36951 16.5132C1.98748 16.5297 1.66639 16.8226 1.66639 17.2164V19.9726C1.66639 20.0804 1.66404 20.1906 1.66639 20.2984C1.6781 20.9875 2.10232 21.564 2.73982 21.8078C2.96951 21.8968 3.20857 21.9015 3.44998 21.9015H20.7117C21.6047 21.8992 22.3289 21.1937 22.343 20.2984C22.357 19.3703 22.343 18.4422 22.343 17.514V17.2164C22.343 16.8484 22.0195 16.4968 21.6398 16.5132C21.2508 16.525 20.9297 16.8179 20.9297 17.2117Z"></path>
        </svg>
    <?php
    }

    private function get_repeater_items($settings, $key, $default)
    {
        if (!empty($settings[$key]) && is_array($settings[$key])) {
            return $settings[$key];
        }

        return $default;
    }

    private function render_contact_list($contacts)
    {
        if (empty($contacts) || !is_array($contacts)) {
            return;
        }

        echo '<ul class="contact-list">';

        foreach ($contacts as $item) {
            $label = !empty($item['contact_label']) ? $item['contact_label'] : '';
            $value = !empty($item['contact_value']) ? $item['contact_value'] : '';

            if ($label === '' && $value === '') {
                continue;
            }

            $type = !empty($item['contact_type']) ? $item['contact_type'] : 'phone';
            $href = $this->get_contact_href($item);
            $link = !empty($item['contact_custom_url']) && is_array($item['contact_custom_url']) && !empty($item['contact_custom_url']['url'])
                ? $item['contact_custom_url']
                :  ['url' => $href];
            $href_attrs = $this->get_link_attrs($link, '#');

            echo '<li class="single-contact">';
            echo '<div class="icon">';

            if (!empty($item['contact_icon']) && $this->has_icon($item['contact_icon'])) {
                Icons_Manager::render_icon($item['contact_icon'], ['aria-hidden' => 'true']);
            } else {
                $this->render_default_contact_icon($type);
            }

            echo '</div>';
            echo '<div class="content">';

            if ($label !== '') {
                echo '<span>' . esc_html($label) . '</span>';
            }

            if ($value !== '') {
                echo '<a ' . $href_attrs . '>' . esc_html($value) . '</a>';
            }

            echo '</div>';
            echo '</li>';
        }

        echo '</ul>';
    }

    private function render_widget_links($title, $links)
    {
        echo '<div class="footer-widget">';

        if (!empty($title)) {
            echo '<div class="widget-title"><h3>' . esc_html($title) . '</h3></div>';
        }

        echo '<ul class="widget-list">';

        if (!empty($links) && is_array($links)) {
            foreach ($links as $item) {
                $text = !empty($item['link_text']) ? $item['link_text'] : '';
                if ($text === '') {
                    continue;
                }

                $url   = !empty($item['link_url']) && is_array($item['link_url']) ? $item['link_url'] : ['url' => '#'];
                $badge = !empty($item['link_badge']) ? $item['link_badge'] : '';

                echo '<li><a ' . $this->get_link_attrs($url, '#') . '>';
                echo esc_html($text);

                if ($badge !== '') {
                    echo '<span>' . esc_html($badge) . '</span>';
                }

                echo '</a></li>';
            }
        }

        echo '</ul>';
        echo '</div>';
    }

    private function render_social_list($social_links)
    {
        if (empty($social_links) || !is_array($social_links)) {
            return;
        }

        echo '<ul class="social-list">';

        foreach ($social_links as $item) {
            $icon_class = !empty($item['social_icon_class']) ? trim((string) $item['social_icon_class']) : '';
            if ($icon_class === '') {
                continue;
            }

            $url = !empty($item['social_url']) && is_array($item['social_url']) ? $item['social_url'] : ['url' => '#'];

            echo '<li><a ' . $this->get_link_attrs($url, '#') . '><i class="' . esc_attr($icon_class) . '"></i></a></li>';
        }

        echo '</ul>';
    }

    private function render_primary_map_button($settings)
    {
        if (empty($settings['softro_footer_show_map_button']) || $settings['softro_footer_show_map_button'] !== 'yes') {
            return;
        }

        $text = !empty($settings['softro_footer_map_button_text']) ? $settings['softro_footer_map_button_text'] : esc_html__('View Site Map', 'softro-core');
        $url  = !empty($settings['softro_footer_map_button_url']) && is_array($settings['softro_footer_map_button_url'])
            ? $settings['softro_footer_map_button_url']
            :  ['url' => '#'];

        echo '<a class="primary-btn1" ' . $this->get_link_attrs($url, '#') . '>';
        echo '<span>' . esc_html($text);
        $this->render_arrow_icon();
        echo '</span>';
        echo '<span>' . esc_html($text);
        $this->render_arrow_icon();
        echo '</span>';
        echo '</a>';
    }

    private function render_map_link_button($settings)
    {
        if (empty($settings['softro_footer_show_map_button']) || $settings['softro_footer_show_map_button'] !== 'yes') {
            return;
        }

        $text = !empty($settings['softro_footer_map_button_text']) ? $settings['softro_footer_map_button_text'] : esc_html__('View Site Map', 'softro-core');
        $url  = !empty($settings['softro_footer_map_button_url']) && is_array($settings['softro_footer_map_button_url'])
            ? $settings['softro_footer_map_button_url']
            :  ['url' => '#'];

        echo '<a class="view-map-btn" ' . $this->get_link_attrs($url, '#') . '>' . esc_html($text) . '</a>';
    }

    private function render_download_button($settings)
    {
        if (empty($settings['softro_footer_show_download_button']) || $settings['softro_footer_show_download_button'] !== 'yes') {
            return;
        }

        $text = !empty($settings['softro_footer_download_button_text']) ? $settings['softro_footer_download_button_text'] : esc_html__('Company Desk', 'softro-core');
        $url  = !empty($settings['softro_footer_download_button_url']) && is_array($settings['softro_footer_download_button_url'])
            ? $settings['softro_footer_download_button_url']
            :  ['url' => '#'];

        echo '<a class="file-download-btn" ' . $this->get_link_attrs($url, '#') . ' download>';
        echo '<div class="icon">';
        $this->render_download_icon();
        echo '</div>';
        echo esc_html($text);
        echo '</a>';
    }

    private function render_newsletter_form($settings)
    {
        $shortcode = !empty($settings['softro_footer_newsletter_shortcode']) ? trim((string) $settings['softro_footer_newsletter_shortcode']) : '';
        if ($shortcode !== '') {
            echo do_shortcode($shortcode);
            return;
        }

        $placeholder = !empty($settings['softro_footer_newsletter_placeholder'])
            ? $settings['softro_footer_newsletter_placeholder']
            :  esc_html__('Enter your email here', 'softro-core');

        $action = !empty($settings['softro_footer_newsletter_form_action']) && is_array($settings['softro_footer_newsletter_form_action'])
            ? $settings['softro_footer_newsletter_form_action']
            :  ['url' => '#'];

        echo '<form class="mail-form-wrap" action="' . esc_url($this->get_link_url($action, '#')) . '" method="post">';
        echo '<div class="mail-form">';
        echo '<input type="email" name="newsletter_email" placeholder="' . esc_attr($placeholder) . '">';
        echo '<button type="submit">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" aria-hidden="true"><path d="M15.9647 0.685806C16.0011 0.594942 16.01 0.495402 15.9904 0.399526C15.9707 0.303649 15.9233 0.215653 15.8541 0.146447C15.7849 0.0772403 15.6969 0.0298668 15.601 0.0101994C15.5052 -0.0094681 15.4056 -0.000564594 15.3147 0.0358061L0.76775 5.85481L5.63875 10.3618L8.81675 15.3568L15.9647 0.685806Z"/></svg>';
        echo '</button>';
        echo '</div>';
        echo '</form>';
    }

    private function render_style_five_top_form($settings)
    {
        $placeholder = !empty($settings['softro_footer_newsletter_placeholder'])
            ? $settings['softro_footer_newsletter_placeholder']
            :  esc_html__('Enter your email here', 'softro-core');

        $button_text = !empty($settings['softro_footer_newsletter_button_text'])
            ? $settings['softro_footer_newsletter_button_text']
            :  esc_html__('Subscribe', 'softro-core');

        $action = !empty($settings['softro_footer_newsletter_form_action']) && is_array($settings['softro_footer_newsletter_form_action'])
            ? $settings['softro_footer_newsletter_form_action']
            :  ['url' => '#'];

        echo '<div class="email-area">';
        echo '<form class="form-inner" action="' . esc_url($this->get_link_url($action, '#')) . '" method="post">';
        echo '<input type="email" name="top_newsletter_email" placeholder="' . esc_attr($placeholder) . '">';
        echo '<button type="submit" class="primary-btn1"><span>' . esc_html($button_text) . '</span><span>' . esc_html($button_text) . '</span></button>';
        echo '</form>';
        echo '</div>';
    }

    private function render_copyright($settings)
    {
        $copyright = !empty($settings['softro_footer_copyright_text']) ? $settings['softro_footer_copyright_text'] : '';
        $link_text = !empty($settings['softro_footer_copyright_link_text']) ? $settings['softro_footer_copyright_link_text'] : '';
        $link_url  = !empty($settings['softro_footer_copyright_link_url']) && is_array($settings['softro_footer_copyright_link_url'])
            ? $settings['softro_footer_copyright_link_url']
            :  ['url' => '#'];

        echo '<p>';

        if ($copyright !== '') {
            echo wp_kses_post($copyright);
        }

        if ($link_text !== '') {
            echo ' <a ' . $this->get_link_attrs($link_url, '#') . '>' . esc_html($link_text) . '</a>';
        }

        echo ' | ' . esc_html__('All Right Reserved.', 'softro-core');
        echo '</p>';
    }

    private function render_style_one_two($settings, $style)
    {
        $footer_class   = $style === 'style_two' ? 'footer-section home2-footer-section' : 'footer-section';
        $logo_dark      = $this->get_media_url($settings['softro_footer_logo_dark'], $this->get_asset_url('image/icon/footer-logo.svg'));
        $heading        = !empty($settings['softro_footer_primary_heading']) ? $settings['softro_footer_primary_heading'] : '';
        $contacts       = $this->get_repeater_items($settings, 'softro_footer_contacts', $this->get_default_contacts());
        $company_links  = $this->get_repeater_items($settings, 'softro_footer_company_links', $this->get_default_company_links());
        $services_links = $this->get_repeater_items($settings, 'softro_footer_services_links', $this->get_default_services_links());
        $policy_links   = $this->get_repeater_items($settings, 'softro_footer_policy_links', $this->get_default_policy_links());
        $social_links   = $this->get_repeater_items($settings, 'softro_footer_social_links', $this->get_default_social_links());

        $company_col_class = $style === 'style_two'
            ? 'col-xl-3 col-lg-2 col-sm-6'
            :  'col-xxl-3 col-lg-3 col-md-3 col-sm-6 d-flex justify-content-xxl-start justify-content-md-center';

        $services_col_class = $style === 'style_two'
            ? 'col-xl-2 col-lg-3 col-sm-6 d-flex justify-content-lg-end'
            :  'col-xxl-2 col-lg-3 col-md-4 col-sm-6 d-flex justify-content-md-end';

    ?>
        <footer class="<?php echo esc_attr($footer_class); ?>">
            <div class="container">
                <div class="footer-menu-wrap">
                    <div class="row gy-5">
                        <div class="<?php echo esc_attr($style === 'style_two' ? 'col-lg-7' : 'col-xxl-7 col-lg-6 col-md-5'); ?>">
                            <div class="footer-contact-wrap">
                                <?php if ($heading !== ''): ?>
                                    <div class="title-area">
                                        <h2><?php echo esc_html($heading); ?></h2>
                                    </div>
                                <?php endif; ?>

                                <?php $this->render_contact_list($contacts); ?>
                                <?php $this->render_primary_map_button($settings); ?>
                            </div>
                        </div>

                        <div class="<?php echo esc_attr($company_col_class); ?>">
                            <?php $this->render_widget_links($settings['softro_footer_company_title'], $company_links); ?>
                        </div>

                        <div class="<?php echo esc_attr($services_col_class); ?>">
                            <?php $this->render_widget_links($settings['softro_footer_services_title'], $services_links); ?>
                        </div>
                    </div>
                </div>

                <?php $this->render_download_button($settings); ?>

                <div class="logo-and-sevice-menu-wrap">
                    <a class="footer-logo" href="#" aria-label="footer logo">
                        <img src="<?php echo esc_url($logo_dark); ?>" alt="<?php echo esc_attr__('Footer Logo', 'softro-core'); ?>">
                    </a>

                    <ul class="service-list">
                        <?php foreach ($policy_links as $item) :
                            $text = !empty($item['link_text']) ? $item['link_text'] : '';
                            if ($text === '') {
                                continue;
                            }
                            $url = !empty($item['link_url']) ? $item['link_url'] : ['url' => '#'];
                        ?>
                            <li><a <?php echo $this->get_link_attrs($url, '#'); ?>><?php echo esc_html($text); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="copyright-and-social-area">
                    <?php $this->render_copyright($settings); ?>

                    <?php if (!empty($settings['softro_footer_show_social']) && $settings['softro_footer_show_social'] === 'yes'): ?>
                        <?php $this->render_social_list($social_links); ?>
                    <?php endif; ?>
                </div>
            </div>
        </footer>
    <?php
    }

    private function render_style_three($settings)
    {
        $logo_light     = $this->get_media_url($settings['softro_footer_logo_light'], $this->get_asset_url('image/icon/white-logo.svg'));
        $heading        = !empty($settings['softro_footer_style_three_heading']) ? $settings['softro_footer_style_three_heading'] : '';
        $contacts       = $this->get_repeater_items($settings, 'softro_footer_contacts', $this->get_default_contacts());
        $company_links  = $this->get_repeater_items($settings, 'softro_footer_company_links', $this->get_default_company_links());
        $services_links = $this->get_repeater_items($settings, 'softro_footer_services_links', $this->get_default_services_links());
        $policy_links   = $this->get_repeater_items($settings, 'softro_footer_policy_links', $this->get_default_policy_links());
        $social_links   = $this->get_repeater_items($settings, 'softro_footer_social_links', $this->get_default_social_links());

    ?>
        <footer class="footer-section home3-footer-section">
            <div class="container-fluid one">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-menu-wrap">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="footer-top-area">
                                            <div class="footer-logo-area">
                                                <img src="<?php echo esc_url($logo_light); ?>" alt="<?php echo esc_attr__('Footer Logo', 'softro-core'); ?>">
                                                <?php if ($heading !== ''): ?>
                                                    <h2><?php echo esc_html($heading); ?></h2>
                                                <?php endif; ?>
                                            </div>

                                            <div class="btn-area">
                                                <div class="icon">
                                                    <a <?php echo $this->get_link_attrs($settings['softro_footer_style_three_top_btn_url'], '#'); ?> aria-label="<?php echo esc_attr__('Contact', 'softro-core'); ?>">
                                                        <?php $this->render_large_arrow_icon(); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row gy-5">
                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="footer-widget">
                                            <div class="widget-title">
                                                <h3><?php echo esc_html($settings['softro_footer_contact_title']); ?></h3>
                                            </div>

                                            <?php $this->render_contact_list($contacts); ?>
                                            <?php $this->render_map_link_button($settings); ?>
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-sm-6">
                                        <?php $this->render_widget_links($settings['softro_footer_company_title'], $company_links); ?>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-sm-6">
                                        <?php $this->render_widget_links($settings['softro_footer_services_title'], $services_links); ?>
                                    </div>

                                    <div class="col-xl-3 col-lg-4 col-sm-6">
                                        <div class="footer-widget">
                                            <div class="widget-title">
                                                <h3><?php echo esc_html($settings['softro_footer_newsletter_title']); ?></h3>
                                            </div>

                                            <?php $this->render_newsletter_form($settings); ?>
                                            <?php $this->render_download_button($settings); ?>

                                            <?php if (!empty($settings['softro_footer_show_social']) && $settings['softro_footer_show_social'] === 'yes'): ?>
                                                <div class="social-area">
                                                    <h4><?php echo esc_html($settings['softro_footer_social_title']); ?></h4>
                                                    <?php $this->render_social_list($social_links); ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="copyright-and-social-area-wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="copyright-and-social-area">
                                    <?php $this->render_copyright($settings); ?>

                                    <ul class="service-list">
                                        <?php foreach ($policy_links as $item) :
                                            $text = !empty($item['link_text']) ? $item['link_text'] : '';
                                            if ($text === '') {
                                                continue;
                                            }
                                            $url = !empty($item['link_url']) ? $item['link_url'] : ['url' => '#'];
                                        ?>
                                            <li><a <?php echo $this->get_link_attrs($url, '#'); ?>><?php echo esc_html($text); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <?php
    }

    private function render_style_four($settings)
    {
        $logo_light     = $this->get_media_url($settings['softro_footer_logo_light'], $this->get_asset_url('image/icon/white-logo.svg'));
        $description    = !empty($settings['softro_footer_style_four_description']) ? $settings['softro_footer_style_four_description'] : '';
        $contacts       = $this->get_repeater_items($settings, 'softro_footer_contacts', $this->get_default_contacts());
        $company_links  = $this->get_repeater_items($settings, 'softro_footer_company_links', $this->get_default_company_links());
        $services_links = $this->get_repeater_items($settings, 'softro_footer_services_links', $this->get_default_services_links());
        $policy_links   = $this->get_repeater_items($settings, 'softro_footer_policy_links', $this->get_default_policy_links());
        $social_links   = $this->get_repeater_items($settings, 'softro_footer_social_links', $this->get_default_social_links());

    ?>
        <footer class="footer-section home4-footer-section">
            <div class="container-fluid two">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="footer-menu-wrap">
                            <div class="container">
                                <div class="row gy-5">
                                    <div class="col-lg-4 col-md-7 d-flex align-items-center">
                                        <div class="footer-logo-area">
                                            <div class="footer-logo">
                                                <img src="<?php echo esc_url($logo_light); ?>" alt="<?php echo esc_attr__('Footer Logo', 'softro-core'); ?>">
                                            </div>

                                            <?php if ($description !== ''): ?>
                                                <p><?php echo esc_html($description); ?></p>
                                            <?php endif; ?>

                                            <div class="footer-widget">
                                                <div class="widget-title">
                                                    <h3><?php echo esc_html($settings['softro_footer_newsletter_title']); ?></h3>
                                                </div>

                                                <?php $this->render_newsletter_form($settings); ?>
                                                <?php $this->render_download_button($settings); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8">
                                        <div class="footer-menu">
                                            <div class="row gy-4">
                                                <div class="col-lg-6 col-sm-6 divider">
                                                    <?php $this->render_widget_links($settings['softro_footer_services_title'], $services_links); ?>
                                                </div>

                                                <div class="col-lg-6 col-sm-6 d-flex justify-content-xl-center">
                                                    <?php $this->render_widget_links($settings['softro_footer_company_title'], $company_links); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="footer-contact-area">
                                            <div class="row gy-4">
                                                <div class="col-lg-6 col-sm-6 divider">
                                                    <div class="footer-widget">
                                                        <div class="widget-title">
                                                            <h3><?php echo esc_html($settings['softro_footer_contact_title']); ?></h3>
                                                        </div>

                                                        <?php $this->render_contact_list($contacts); ?>
                                                        <?php $this->render_map_link_button($settings); ?>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-6 d-flex justify-content-xl-center">
                                                    <?php if (!empty($settings['softro_footer_show_social']) && $settings['softro_footer_show_social'] === 'yes'): ?>
                                                        <div class="footer-widget">
                                                            <div class="social-area">
                                                                <h4><?php echo esc_html($settings['softro_footer_social_title']); ?></h4>
                                                                <?php $this->render_social_list($social_links); ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-btm-area-wrap">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="copyright-and-social-area">
                                    <?php $this->render_copyright($settings); ?>

                                    <ul class="service-list">
                                        <?php foreach ($policy_links as $item) :
                                            $text = !empty($item['link_text']) ? $item['link_text'] : '';
                                            if ($text === '') {
                                                continue;
                                            }
                                            $url = !empty($item['link_url']) ? $item['link_url'] : ['url' => '#'];
                                        ?>
                                            <li><a <?php echo $this->get_link_attrs($url, '#'); ?>><?php echo esc_html($text); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <?php
    }

    private function render_style_five($settings)
    {
        $vector_image    = $this->get_media_url($settings['softro_footer_style_five_vector_image'], $this->get_asset_url('image/sass/vector/footer-vector.svg'));
        $style_five_top  = !empty($settings['softro_footer_style_five_top_title']) ? $settings['softro_footer_style_five_top_title'] : '';
        $style_five_desc = !empty($settings['softro_footer_style_five_top_description']) ? $settings['softro_footer_style_five_top_description'] : '';

        $contacts        = $this->get_repeater_items($settings, 'softro_footer_contacts', $this->get_default_contacts());
        $company_links   = $this->get_repeater_items($settings, 'softro_footer_company_links', $this->get_default_company_links());
        $product_links   = $this->get_repeater_items($settings, 'softro_footer_product_links', $this->get_default_product_links());
        $resources_links = $this->get_repeater_items($settings, 'softro_footer_resources_links', $this->get_default_resources_links());
        $policy_links    = $this->get_repeater_items($settings, 'softro_footer_policy_links', $this->get_default_policy_links());
        $social_links    = $this->get_repeater_items($settings, 'softro_footer_social_links', $this->get_default_social_links());
        $features        = $this->get_repeater_items($settings, 'softro_footer_style_five_features', $this->get_default_style_five_features());

    ?>
        <footer class="footer-section home5-footer-section">
            <div class="container">
                <div class="footer-top-area-wrap">
                    <div class="footer-top-area">
                        <div class="footer-top-left-content">
                            <?php if ($style_five_top !== ''): ?>
                                <h2><?php echo esc_html($style_five_top); ?></h2>
                            <?php endif; ?>

                            <?php if ($style_five_desc !== ''): ?>
                                <p><?php echo esc_html($style_five_desc); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="footer-top-right-content">
                            <?php $this->render_style_five_top_form($settings); ?>

                            <?php if (!empty($features)): ?>
                                <ul>
                                    <?php foreach ($features as $feature) :
                                        $text = !empty($feature['feature_text']) ? $feature['feature_text'] : '';
                                        if ($text === '') {
                                            continue;
                                        }
                                    ?>
                                        <li>
                                            <?php $this->render_check_icon(); ?>
                                            <?php echo esc_html($text); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="footer-menu-wrap">
                    <div class="row gy-5">
                        <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6">
                            <div class="footer-widget">
                                <div class="widget-title">
                                    <h3><?php echo esc_html($settings['softro_footer_contact_title']); ?></h3>
                                </div>

                                <?php $this->render_contact_list($contacts); ?>
                                <?php $this->render_map_link_button($settings); ?>
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-3 col-6 d-flex justify-content-xxl-start justify-content-md-center">
                            <?php $this->render_widget_links($settings['softro_footer_company_title'], $company_links); ?>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-6 d-flex justify-content-xl-center justify-content-lg-end">
                            <?php $this->render_widget_links($settings['softro_footer_product_title'], $product_links); ?>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 d-flex justify-content-xl-center">
                            <?php $this->render_widget_links($settings['softro_footer_resources_title'], $resources_links); ?>
                        </div>

                        <div class="col-lg-3 col-md-4 col-sm-6 d-flex justify-content-md-end">
                            <div class="footer-widget">
                                <div class="widget-title">
                                    <h3><?php echo esc_html($settings['softro_footer_newsletter_title']); ?></h3>
                                </div>

                                <?php $this->render_newsletter_form($settings); ?>
                                <?php $this->render_download_button($settings); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="copyright-and-social-area">
                    <?php $this->render_copyright($settings); ?>

                    <?php if (!empty($settings['softro_footer_show_social']) && $settings['softro_footer_show_social'] === 'yes'): ?>
                        <?php $this->render_social_list($social_links); ?>
                    <?php endif; ?>

                    <ul class="service-list">
                        <?php foreach ($policy_links as $item) :
                            $text = !empty($item['link_text']) ? $item['link_text'] : '';
                            if ($text === '') {
                                continue;
                            }
                            $url = !empty($item['link_url']) ? $item['link_url'] : ['url' => '#'];
                        ?>
                            <li><a <?php echo $this->get_link_attrs($url, '#'); ?>><?php echo esc_html($text); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <?php if (!empty($settings['softro_footer_show_style_five_vector']) && $settings['softro_footer_show_style_five_vector'] === 'yes'): ?>
                <div class="vector-icon">
                    <img src="<?php echo esc_url($vector_image); ?>" alt="<?php echo esc_attr__('Footer Vector', 'softro-core'); ?>">
                </div>
            <?php endif; ?>
        </footer>
<?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $style    = !empty($settings['softro_footer_genaral_style_selection']) ? $settings['softro_footer_genaral_style_selection'] : 'style_one';

        if ($style === 'style_two') {
            $this->render_style_one_two($settings, 'style_two');
            return;
        }

        if ($style === 'style_three') {
            $this->render_style_three($settings);
            return;
        }

        if ($style === 'style_four') {
            $this->render_style_four($settings);
            return;
        }

        if ($style === 'style_five') {
            $this->render_style_five($settings);
            return;
        }

        $this->render_style_one_two($settings, 'style_one');
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Footer_Widget());
