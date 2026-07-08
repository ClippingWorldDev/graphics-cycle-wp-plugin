<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Marketing_Service_One_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_marketing_service_one';
    }

    public function get_title()
    {
        return esc_html__('GC Marketing Service One', 'softro-core');
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

    private function get_default_service_cards()
    {
        $cards = [
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-15.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('AI Email Marketing Strategy', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-16.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('AI Email Automation', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-17.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Email Copywriting', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-14.jpg')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Audience Segmentation', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-15.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Lead Nurturing Campaigns', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-16.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Abandoned Cart Email Automation', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-17.png')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Ecommerce Email Marketings', 'softro-core'),
            ],
            [
                'card_image'     => ['url' => $this->get_theme_img_url('blog/post-14.jpg')],
                'card_image_alt' => esc_html__('post', 'softro-core'),
                'card_title'     => esc_html__('Analytics & Campaign Optimization', 'softro-core'),
            ],
        ];

        return $cards;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_marketing_svc_one_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_marketing_svc_one_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('AI-Powered Email Marketing Services We Offer', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_marketing_svc_one_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Graphics Cycle provides very advanced AI-based email marketing services that engage customers, increase conversions, and grow revenue.',
                'softro-core'
            ),
        ]);

        $this->end_controls_section();

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_image', [
            'label'   => esc_html__('Card Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('blog/post-15.png')],
        ]);

        $card_repeater->add_control('card_image_alt', [
            'label'       => esc_html__('Image Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('post', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('AI Email Marketing Strategy', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_marketing_svc_one_cards_section', [
            'label' => esc_html__('Service Cards', 'softro-core'),
        ]);

        $this->add_control('gc_marketing_svc_one_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_service_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_marketing_svc_one_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_marketing_svc_one_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section .row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_marketing_svc_one_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_marketing_svc_one_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .ai-marketing-blog-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_marketing_svc_one_section_overlay',
            'label'     => esc_html__('Section Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .ai-marketing-blog-section::before',
        ]);

        $this->add_control('gc_marketing_svc_one_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-blog-section' => 'border-top-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_marketing_svc_one_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-blog-section' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_marketing_svc_one_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_heading_margin', [
            'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section .section-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_marketing_svc_one_title_typography',
            'label'    => esc_html__('Title Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .ai-marketing-blog-section .section-title',
        ]);

        $this->add_control('gc_marketing_svc_one_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-blog-section .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-section .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_marketing_svc_one_desc_typography',
            'label'     => esc_html__('Description Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .ai-marketing-blog-desc',
            'separator' => 'before',
        ]);

        $this->add_control('gc_marketing_svc_one_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .ai-marketing-blog-desc' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_desc_max_width', [
            'label'      => esc_html__('Description Max Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'default'    => ['size' => 820, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-desc' => 'max-width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_desc_margin', [
            'label'      => esc_html__('Description Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .ai-marketing-blog-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_marketing_svc_one_style_card', [
            'label' => esc_html__('Service Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_marketing_svc_one_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .post-card.card-6.card-8' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_card_padding', [
            'label'      => esc_html__('Card Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_card_margin', [
            'label'      => esc_html__('Card Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .post-card-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_card_radius', [
            'label'      => esc_html__('Card Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_marketing_svc_one_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .post-card.card-6.card-8:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_marketing_svc_one_thumb_heading', [
            'label'     => esc_html__('Card Image', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_thumb_height', [
            'label'      => esc_html__('Image Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .post-card.card-6.card-8 .post-thumb'     => 'height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .post-card.card-6.card-8 .post-thumb img' => 'height: 100%; object-fit: cover;',
            ],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_thumb_width', [
            'label'      => esc_html__('Image Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8 .post-thumb img' => 'width: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_thumb_radius', [
            'label'      => esc_html__('Image Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => [
                '{{WRAPPER}} .post-card.card-6.card-8 .post-thumb'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                '{{WRAPPER}} .post-card.card-6.card-8 .post-thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_thumb_margin', [
            'label'      => esc_html__('Thumb Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8 .post-thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_marketing_svc_one_card_title_heading', [
            'label'     => esc_html__('Card Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_marketing_svc_one_card_title_typography',
            'selector' => '{{WRAPPER}} .post-card.card-6.card-8 .post-content .title',
        ]);

        $this->add_control('gc_marketing_svc_one_card_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .post-card.card-6.card-8 .post-content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_marketing_svc_one_card_title_hover_color', [
            'label'     => esc_html__('Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .post-card.card-6.card-8:hover .post-content .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_card_title_margin', [
            'label'      => esc_html__('Title Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8 .post-content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_marketing_svc_one_card_content_padding', [
            'label'      => esc_html__('Content Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .post-card.card-6.card-8 .post-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_marketing_svc_one_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_marketing_svc_one_theme_mode_tabs');

        $this->start_controls_tab('gc_marketing_svc_one_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_marketing_svc_one_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-blog-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_marketing_svc_one_dark_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .ai-marketing-blog-section::before',
        ]);

        $this->add_control('gc_marketing_svc_one_dark_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-blog-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-blog-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-blog-section .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.ai-marketing-blog-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.post-card.card-6.card-8' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.post-card.card-6.card-8:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.post-card.card-6.card-8 .post-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_dark_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.post-card.card-6.card-8:hover .post-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_marketing_svc_one_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_marketing_svc_one_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-blog-section',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_marketing_svc_one_light_section_overlay',
            'label'    => esc_html__('Section Overlay', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .ai-marketing-blog-section::before',
        ]);

        $this->add_control('gc_marketing_svc_one_light_section_border_top_color', [
            'label'     => esc_html__('Top Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-blog-section' => 'border-top-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-blog-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-blog-section .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_desc_color', [
            'label'     => esc_html__('Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.ai-marketing-blog-desc' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.post-card.card-6.card-8' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_card_hover_bg', [
            'label'     => esc_html__('Card Hover Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.post-card.card-6.card-8:hover' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.post-card.card-6.card-8 .post-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_marketing_svc_one_light_card_title_hover_color', [
            'label'     => esc_html__('Card Title Hover Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.post-card.card-6.card-8:hover .post-content .title' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_marketing_svc_one_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .overflow-hidden { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_service_card($card)
    {
        $title = $card['card_title'] ?? '';
        $image = $this->get_media_url($card['card_image'] ?? [], 'blog/post-15.png');
        $alt   = $card['card_image_alt'] ?? esc_html__('post', 'softro-core');

        if (!$title || !$image) {
            return;
        }
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="post-card-wrap fade-top">
                <div class="post-card card-6 card-8">
                    <div class="post-thumb">
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($alt); ?>">
                    </div>
                    <div class="post-content-wrap">
                        <div class="post-content">
                            <h3 class="title"><?php echo esc_html($title); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $title       = $settings['gc_marketing_svc_one_title'] ?? '';
        $description = $this->get_paragraph_inner_content($settings['gc_marketing_svc_one_description'] ?? '');
        $cards       = !empty($settings['gc_marketing_svc_one_cards']) ? $settings['gc_marketing_svc_one_cards'] : [];
        ?>

        <section class="blog-section-8 pt-130 pb-130 fade-wrapper ai-marketing-blog-section">
            <div class="container">
                <div class="section-heading text-center">
                    <?php if ($title) : ?>
                        <h2 class="section-title" data-text-animation="fade-in-right" data-split="char" data-duration="0.6" data-stagger="0.03"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ('' !== $description) : ?>
                        <p class="ai-marketing-blog-desc"><?php echo wp_kses($description, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cards)) : ?>
                    <div class="row gy-4">
                        <?php foreach ($cards as $card) {
                            $this->render_service_card($card);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Marketing_Service_One_Widget());
