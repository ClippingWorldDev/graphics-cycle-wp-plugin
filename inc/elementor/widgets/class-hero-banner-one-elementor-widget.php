<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Hero_Banner_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'pdf_hero_banner';
    }

    public function get_title()
    {
        return esc_html__('GC Hero Banner One', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['pdf_widgets'];
    }

    /**
     * Default theme asset URL helper.
     *
     * @param string $path Relative path inside theme /assets/img/.
     * @return string
     */
    private function get_theme_img_url($path)
    {
        return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($path, '/'));
    }

    /**
     * Resolve media control URL with theme fallback.
     *
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
     * Build anchor attributes from Elementor URL control.
     *
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
            'gc_hero_shapes_section',
            [
                'label' => esc_html__('Background Shapes', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_shape_bg',
            [
                'label'   => esc_html__('Background Shape', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-1.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_hero_shape_one',
            [
                'label'   => esc_html__('Shape One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-22.png'),
                ],
            ]
        );

        $this->add_control(
            'gc_hero_shape_two',
            [
                'label'   => esc_html__('Shape Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-shape-3.png'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_content_section',
            [
                'label' => esc_html__('Hero Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_title_line_one',
            [
                'label'       => esc_html__('Title Line One', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Advanced Digital', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_title_line_two',
            [
                'label'       => esc_html__('Title Line Two', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Solutions Provider', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_hero_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'AI-Powered Digital Solutions: Web Development, Digital Marketing, Video Production, and Graphics Solutions may be the best choice for growing businesses. Our modern, powerful strategies help businesses increase visibility, generate leads, build brand, and so on. So, grow your business faster with our AI-driven solutions.',
                    'softro-core'
                ),
                'rows'    => 5,
            ]
        );

        $this->end_controls_section();

        $button_repeater = new Repeater();

        $button_repeater->add_control(
            'button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('View Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $button_repeater->add_control(
            'button_url',
            [
                'label'       => esc_html__('Button URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $button_repeater->add_control(
            'button_icon',
            [
                'label'   => esc_html__('Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_hero_buttons_section',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_buttons',
            [
                'label'       => esc_html__('Button List', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $button_repeater->get_controls(),
                'default'     => [
                    [
                        'button_text' => esc_html__('View Services', 'softro-core'),
                    ],
                    [
                        'button_text' => esc_html__('Make a Reservation', 'softro-core'),
                    ],
                ],
                'title_field' => '{{{ button_text }}}',
            ]
        );

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control(
            'card_image',
            [
                'label'   => esc_html__('Card Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $service_repeater->add_control(
            'card_icon',
            [
                'label'   => esc_html__('Card Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $service_repeater->add_control(
            'card_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Graphics Solutions', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'card_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('Image editing, masking, retouching, BG removal, etc.', 'softro-core'),
                'rows'    => 3,
            ]
        );

        $service_repeater->add_control(
            'card_link',
            [
                'label'       => esc_html__('Card Link', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                'default'     => [
                    'url'         => '#',
                    'is_external' => false,
                    'nofollow'    => false,
                ],
            ]
        );

        $this->start_controls_section(
            'gc_hero_services_section',
            [
                'label' => esc_html__('Service Cards', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_hero_service_cards',
            [
                'label'       => esc_html__('Service Cards', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_repeater->get_controls(),
                'default'     => [
                    [
                        'card_title'       => esc_html__('Graphics Solutions', 'softro-core'),
                        'card_description' => esc_html__('Image editing, masking, retouching, BG removal, etc.', 'softro-core'),
                        'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                        'card_link'        => ['url' => '#'],
                    ],
                    [
                        'card_title'       => esc_html__('Web & App Solutions', 'softro-core'),
                        'card_description' => esc_html__('Website, Custom Software & App Development & others.', 'softro-core'),
                        'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                        'card_link'        => ['url' => '#'],
                    ],
                    [
                        'card_title'       => esc_html__('Marketing Solutions', 'softro-core'),
                        'card_description' => esc_html__('Modern SEO (AEO/GEO/SXO), Paid Ad, Content, etc.', 'softro-core'),
                        'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                        'card_link'        => ['url' => '#'],
                    ],
                    [
                        'card_title'       => esc_html__('Video & 3D Solution', 'softro-core'),
                        'card_description' => esc_html__('Video editing, 3D modeling, animation, etc.', 'softro-core'),
                        'card_image'       => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                        'card_link'        => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ card_title }}}',
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
            'gc_hero_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_hero_reset_elementor_spacing',
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
            'gc_hero_pull_up',
            [
                'label'       => esc_html__('Pull Up (Remove Top Gap)', 'softro-core'),
                'description' => esc_html__('Increase this value if a blank gap remains above the hero background.', 'softro-core'),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => ['px', 'em', 'vh'],
                'range'       => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'     => [
                    'size' => 0,
                    'unit' => 'px',
                ],
                'condition'   => [
                    'gc_hero_reset_elementor_spacing' => 'yes',
                ],
                'selectors'   => [
                    '{{WRAPPER}} .hero-section-11' => 'margin-top: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_full_width',
            [
                'label'        => esc_html__('Full Width Breakout', 'softro-core'),
                'description'  => esc_html__('Removes side gaps from Elementor column padding.', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'selectors'    => [
                    '{{WRAPPER}} .hero-section-11' => 'width: 100vw; max-width: 100vw; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw);',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_row_padding_top',
            [
                'label'      => esc_html__('Content Top Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'    => [
                    'size' => 140,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-row-11' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_row_padding_bottom',
            [
                'label'      => esc_html__('Content Bottom Spacing', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                    ],
                ],
                'default'    => [
                    'size' => 40,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-row-11' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_hero_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .hero-section-11',
            ]
        );

        $this->add_responsive_control(
            'gc_hero_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_shapes',
            [
                'label' => esc_html__('Shape Images', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_hero_shape_bg_width',
            [
                'label'      => esc_html__('Background Shape Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 1200],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .bg-shape img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_shape_bg_opacity',
            [
                'label'     => esc_html__('Background Shape Opacity', 'softro-core'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => ['min' => 0, 'max' => 1, 'step' => 0.01],
                ],
                'selectors' => [
                    '{{WRAPPER}} .hero-section-11 .bg-shape img' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_shape_one_width',
            [
                'label'      => esc_html__('Shape One Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 800],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .shape-1 img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_shape_two_width',
            [
                'label'      => esc_html__('Shape Two Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 800],
                    '%'  => ['min' => 0, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-section-11 .shape-2 img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_title_typography',
                'selector' => '{{WRAPPER}} .hero-info .title',
            ]
        );

        $this->add_control(
            'gc_hero_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-info .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_title_second_color',
            [
                'label'     => esc_html__('Second Line Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-info .title.title-2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-info .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-info .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_description_typography',
                'selector' => '{{WRAPPER}} .hero-info > p',
            ]
        );

        $this->add_control(
            'gc_hero_description_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-info > p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_description_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-info > p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_description_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-info > p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_buttons',
            [
                'label' => esc_html__('Buttons', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_button_typography',
                'selector' => '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn',
            ]
        );

        $this->add_control(
            'gc_hero_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_button_hover_color',
            [
                'label'     => esc_html__('Hover Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_button_bg_color',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_button_hover_bg_color',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_button_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_button_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'gc_hero_button_border',
                'selector' => '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn',
            ]
        );

        $this->add_control(
            'gc_hero_button_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-btn-wrap .rr-primary-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_hero_style_service_card',
            [
                'label' => esc_html__('Service Cards', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_hero_service_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .hero-service-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_padding',
            [
                'label'      => esc_html__('Card Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_margin',
            [
                'label'      => esc_html__('Card Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .hero-service-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_service_card_title_heading',
            [
                'label'     => esc_html__('Card Title', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_service_card_title_typography',
                'selector' => '{{WRAPPER}} .service-card-info .title',
            ]
        );

        $this->add_control(
            'gc_hero_service_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-card-info .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => ['min' => 8, 'max' => 80],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-card-info .gc-card-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .service-card-info .gc-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_service_card_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-card-info .gc-card-icon'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .service-card-info .gc-card-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_service_card_desc_heading',
            [
                'label'     => esc_html__('Card Description', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_hero_service_card_desc_typography',
                'selector' => '{{WRAPPER}} .service-card-info p',
            ]
        );

        $this->add_control(
            'gc_hero_service_card_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-card-info p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_service_card_image_heading',
            [
                'label'     => esc_html__('Card Image', 'softro-core'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_image_width',
            [
                'label'      => esc_html__('Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 800],
                    '%'  => ['min' => 10, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-card-img img' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_image_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 800],
                    '%'  => ['min' => 10, 'max' => 100],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .service-card-img img' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_hero_service_card_image_object_fit',
            [
                'label'   => esc_html__('Object Fit', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''        => esc_html__('Default', 'softro-core'),
                    'cover'   => esc_html__('Cover', 'softro-core'),
                    'contain' => esc_html__('Contain', 'softro-core'),
                    'fill'    => esc_html__('Fill', 'softro-core'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .service-card-img img' => 'object-fit: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_hero_service_card_image_border_radius',
            [
                'label'      => esc_html__('Image Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .service-card-img img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Output scoped spacing fixes for Elementor wrappers (widget file only).
     *
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_hero_reset_elementor_spacing'] ?? 'yes')) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        $pull_up   = $settings['gc_hero_pull_up']['size'] ?? 0;
        $pull_unit = $settings['gc_hero_pull_up']['unit'] ?? 'px';
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

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);

        $shape_bg   = $this->get_media_url($settings['gc_hero_shape_bg'] ?? [], 'new-update/hero-shape-1.png');
        $shape_one  = $this->get_media_url($settings['gc_hero_shape_one'] ?? [], 'new-update/hero-shape-22.png');
        $shape_two  = $this->get_media_url($settings['gc_hero_shape_two'] ?? [], 'new-update/hero-shape-3.png');
        $title_one  = $settings['gc_hero_title_line_one'] ?? '';
        $title_two  = $settings['gc_hero_title_line_two'] ?? '';
        $description = $settings['gc_hero_description'] ?? '';
        $buttons    = !empty($settings['gc_hero_buttons']) ? $settings['gc_hero_buttons'] : [];
        $cards      = !empty($settings['gc_hero_service_cards']) ? $settings['gc_hero_service_cards'] : [];
    ?>

        <section class="hero-section-11">
            <div class="bg-shape"><img src="<?php echo esc_url($shape_bg); ?>" alt="shape"></div>
            <div class="shapes">
                <div class="shape-1"><img src="<?php echo esc_url($shape_one); ?>" alt="shape"></div>
                <div class="shape-2"><img src="<?php echo esc_url($shape_two); ?>" alt="shape"></div>
            </div>
            <div class="container">
                <div class="row align-items-center hero-row-11">
                    <div class="col-lg-6">
                        <div class="hero-info hero-info-3 hero-info-4">
                            <?php if ($title_one) : ?>
                                <h1 class="title anim-text"><?php echo esc_html($title_one); ?></h1>
                            <?php endif; ?>

                            <?php if ($title_two) : ?>
                                <h1 class="title title-2 anim-text"><?php echo esc_html($title_two); ?></h1>
                            <?php endif; ?>

                            <?php if ($description) : ?>
                                <?php echo wp_kses($settings['gc_hero_description'], wp_kses_allowed_html('post')); ?>
                            <?php endif; ?>

                            <?php if (!empty($buttons)) : ?>
                                <div class="hero-btn-wrap">
                                    <?php foreach ($buttons as $button) :
                                        $button_text = $button['button_text'] ?? '';
                                        $button_url  = $button['button_url'] ?? [];

                                        if (!$button_text) {
                                            continue;
                                        }
                                    ?>
                                        <a class="rr-primary-btn" <?php echo $this->get_link_attributes($button_url); ?>>
                                            <?php
                                            if (!empty($button['button_icon']['value'])) {
                                                Icons_Manager::render_icon($button['button_icon'], ['aria-hidden' => 'true']);
                                            }
                                            echo esc_html($button_text);
                                            ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="hero-services-3d-wrap">
                            <div class="hero-services-grid">
                                <?php foreach ($cards as $card) :
                                    $card_title       = $card['card_title'] ?? '';
                                    $card_description = $card['card_description'] ?? '';
                                    $card_image       = $this->get_media_url($card['card_image'] ?? [], '');
                                    $card_link        = $card['card_link'] ?? ['url' => '#'];
                                    $card_alt         = $card_title ? $card_title : esc_html__('Service', 'softro-core');
                                ?>
                                    <a href="<?php echo esc_url($card_link['url'] ?? '#'); ?>" class="hero-service-card" data-tilt<?php echo !empty($card_link['is_external']) ? ' target="_blank"' : ''; ?><?php echo !empty($card_link['nofollow']) ? ' rel="nofollow"' : ''; ?>>
                                        <div class="service-card-3d">
                                            <div class="service-card-img">
                                                <?php if ($card_image) : ?>
                                                    <img src="<?php echo esc_url($card_image); ?>" alt="<?php echo esc_attr($card_alt); ?>">
                                                <?php endif; ?>
                                                <span class="service-card-shine"></span>
                                            </div>
                                        </div>
                                        <div class="service-card-info">
                                            <?php if ($card_title) : ?>
                                                <h4 class="title">
                                                    <?php
                                                    if (!empty($card['card_icon']['value'])) {
                                                        echo '<span class="gc-card-icon">';
                                                        Icons_Manager::render_icon($card['card_icon'], ['aria-hidden' => 'true']);
                                                        echo '</span> ';
                                                    }
                                                    echo esc_html($card_title);
                                                    ?>
                                                </h4>
                                            <?php endif; ?>

                                            <?php if ($card_description) : ?>
                                                <?php echo wp_kses($card['card_description'], wp_kses_allowed_html('post')); ?>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
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

Plugin::instance()->widgets_manager->register(new Softro_Hero_Banner_One_Widget());
