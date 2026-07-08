<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_What_We_Animate_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_what_we_animate';
    }

    public function get_title()
    {
        return esc_html__('GC What We Animate', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
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

    private function get_default_type_cards()
    {
        return [
            [
                'card_label'       => esc_html__('Most Popular', 'softro-core'),
                'card_title'       => esc_html__('3D product animation', 'softro-core'),
                'card_description' => esc_html__(
                    'Photorealistic 360° product spins, exploded views, and feature highlight animations designed for ecommerce and advertising.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('Ecommerce', 'softro-core')],
                    ['tag_text' => esc_html__('Amazon', 'softro-core')],
                    ['tag_text' => esc_html__('Ads', 'softro-core')],
                ],
            ],
            [
                'card_label'       => esc_html__('Architecture', 'softro-core'),
                'card_title'       => esc_html__('Architectural walkthrough', 'softro-core'),
                'card_description' => esc_html__(
                    'Cinematic fly-through animations of buildings, interiors, and urban developments — before a single brick is laid.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('Real estate', 'softro-core')],
                    ['tag_text' => esc_html__('Developers', 'softro-core')],
                    ['tag_text' => esc_html__('Pitch decks', 'softro-core')],
                ],
            ],
            [
                'card_label'       => esc_html__('Corporate', 'softro-core'),
                'card_title'       => esc_html__('3D explainer video', 'softro-core'),
                'card_description' => esc_html__(
                    'Break down complex ideas, products, or processes into clear, engaging 3D animated explainer videos.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('SaaS', 'softro-core')],
                    ['tag_text' => esc_html__('Healthcare', 'softro-core')],
                    ['tag_text' => esc_html__('Finance', 'softro-core')],
                ],
            ],
            [
                'card_label'       => esc_html__('Branding', 'softro-core'),
                'card_title'       => esc_html__('Logo & title animation', 'softro-core'),
                'card_description' => esc_html__(
                    'Bring your logo or title sequence to life in 3D for intros, brand videos, presentations, and social media.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('YouTube', 'softro-core')],
                    ['tag_text' => esc_html__('Brand film', 'softro-core')],
                    ['tag_text' => esc_html__('Events', 'softro-core')],
                ],
            ],
            [
                'card_label'       => esc_html__('Marketing', 'softro-core'),
                'card_title'       => esc_html__('3D ad animation', 'softro-core'),
                'card_description' => esc_html__(
                    'Short-form 3D animations optimized for social media ads — scroll-stopping visuals that drive clicks and conversions.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('Meta Ads', 'softro-core')],
                    ['tag_text' => esc_html__('TikTok', 'softro-core')],
                    ['tag_text' => esc_html__('YouTube Ads', 'softro-core')],
                ],
            ],
            [
                'card_label'       => esc_html__('Industrial', 'softro-core'),
                'card_title'       => esc_html__('Mechanical & technical animation', 'softro-core'),
                'card_description' => esc_html__(
                    'Precise 3D animations of machinery, mechanisms, and technical components for manuals, training, and sales.',
                    'softro-core'
                ),
                'tags'             => [
                    ['tag_text' => esc_html__('Manufacturing', 'softro-core')],
                    ['tag_text' => esc_html__('Engineering', 'softro-core')],
                    ['tag_text' => esc_html__('Training', 'softro-core')],
                ],
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
        $this->start_controls_section('gc_wwa_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_wwa_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('What We Animate', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_wwa_title_before', [
            'label'       => esc_html__('Title (Before Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Types of ', 'softro-core'),
            'label_block' => true,
            'separator'   => 'before',
        ]);

        $this->add_control('gc_wwa_title_accent', [
            'label'       => esc_html__('Title Accent', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D animation', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_wwa_title_after', [
            'label'       => esc_html__('Title (After Accent)', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__(' we offer', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_wwa_intro', [
            'label'   => esc_html__('Intro', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Whether you need a 10-second product spin or a full 3D brand film, we have the tools and team to deliver it.',
                'softro-core'
            ),
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $tag_repeater = new Repeater();

        $tag_repeater->add_control('tag_text', [
            'label'       => esc_html__('Tag Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Tag', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_label', [
            'label'       => esc_html__('Card Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Most Popular', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_title', [
            'label'       => esc_html__('Card Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('3D product animation', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label'   => esc_html__('Card Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__(
                'Photorealistic 360° product spins, exploded views, and feature highlight animations designed for ecommerce and advertising.',
                'softro-core'
            ),
        ]);

        $card_repeater->add_control('tags', [
            'label'       => esc_html__('Tags', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $tag_repeater->get_controls(),
            'default'     => [
                ['tag_text' => esc_html__('Ecommerce', 'softro-core')],
                ['tag_text' => esc_html__('Amazon', 'softro-core')],
                ['tag_text' => esc_html__('Ads', 'softro-core')],
            ],
            'title_field' => '{{{ tag_text }}}',
            'separator'   => 'before',
        ]);

        $this->start_controls_section('gc_wwa_cards_section', [
            'label' => esc_html__('Type Cards', 'softro-core'),
        ]);

        $this->add_control('gc_wwa_type_cards', [
            'label'       => esc_html__('Cards', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $card_repeater->get_controls(),
            'default'     => $this->get_default_type_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_wwa_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_wwa_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_wwa_section_padding', [
            'label'      => esc_html__('Section Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-grid' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_column_gap', [
            'label'      => esc_html__('Column Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-grid' => 'column-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwa_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-types',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_wwa_eyebrow_heading', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type'  => Controls_Manager::HEADING,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_eyebrow_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-types-eyebrow',
        ]);

        $this->add_control('gc_wwa_eyebrow_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-types-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_eyebrow_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wwa_title_heading', [
            'label'     => esc_html__('Title', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-types-title',
        ]);

        $this->add_control('gc_wwa_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-types-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('gc_wwa_accent_heading', [
            'label'     => esc_html__('Accent', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_accent_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-types-accent',
        ]);

        $this->add_control('gc_wwa_accent_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-types-accent' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_wwa_intro_heading', [
            'label'     => esc_html__('Intro', 'softro-core'),
            'type'      => Controls_Manager::HEADING,
            'separator' => 'before',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_intro_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-types-intro',
        ]);

        $this->add_control('gc_wwa_intro_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-types-intro' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_intro_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_header_margin', [
            'label'      => esc_html__('Header Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            'separator'  => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_grid', [
            'label' => esc_html__('Grid', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wwa_grid_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-types-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_type_card', [
            'label' => esc_html__('Type Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwa_type_card_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-card',
        ]);

        $this->add_responsive_control('gc_wwa_type_card_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_type_card_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_wwa_type_card_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-card',
        ]);

        $this->add_group_control(Group_Control_Box_Shadow::get_type(), [
            'name'     => 'gc_wwa_type_card_shadow',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-card',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_card_label', [
            'label' => esc_html__('Card Label', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_card_label_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-label',
        ]);

        $this->add_control('gc_wwa_card_label_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-type-label' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_card_label_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_card_title_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-card h3',
        ]);

        $this->add_control('gc_wwa_card_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-type-card h3' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_card_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-card h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_card_description', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_card_desc_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-card p',
        ]);

        $this->add_control('gc_wwa_card_desc_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-type-card p' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_card_desc_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-card p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_tags_list', [
            'label' => esc_html__('Tags List', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_wwa_tags_list_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-tags' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_tags_list_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-tags' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_tags_list_gap', [
            'label'      => esc_html__('Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-tags' => 'gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_wwa_style_tag_item', [
            'label' => esc_html__('Tag Item', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_wwa_tag_item_typography',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-tags li',
        ]);

        $this->add_control('gc_wwa_tag_item_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-type-tags li' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_wwa_tag_item_bg', [
            'label'     => esc_html__('Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-3d-anim-type-tags li' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_wwa_tag_item_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-tags li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_wwa_tag_item_radius', [
            'label'      => esc_html__('Border Radius', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .gc-3d-anim-type-tags li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Border::get_type(), [
            'name'     => 'gc_wwa_tag_item_border',
            'selector' => '{{WRAPPER}} .gc-3d-anim-type-tags li',
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_wwa_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_wwa_theme_mode_tabs');

        $this->start_controls_tab('gc_wwa_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwa_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-3d-anim-types',
        ]);

        $this->add_control('gc_wwa_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-types-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-types-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-types-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-types-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_card_label_color', [
            'label'     => esc_html__('Card Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_tag_item_color', [
            'label'     => esc_html__('Tag Item Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-tags li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_dark_tag_item_bg', [
            'label'     => esc_html__('Tag Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-3d-anim-type-tags li' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_wwa_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_wwa_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-3d-anim-types',
        ]);

        $this->add_control('gc_wwa_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-types-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-types-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_accent_color', [
            'label'     => esc_html__('Accent Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-types-accent' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_intro_color', [
            'label'     => esc_html__('Intro Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-types-intro' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_card_bg', [
            'label'     => esc_html__('Card Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-card' => 'background-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_card_label_color', [
            'label'     => esc_html__('Card Label Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-label' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_card_title_color', [
            'label'     => esc_html__('Card Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-card h3' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_card_desc_color', [
            'label'     => esc_html__('Card Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-card p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_tag_item_color', [
            'label'     => esc_html__('Tag Item Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-tags li' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_wwa_light_tag_item_bg', [
            'label'     => esc_html__('Tag Item Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-3d-anim-type-tags li' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_wwa_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .overflow-hidden {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    private function render_type_card(array $card)
    {
        $label       = trim((string) ($card['card_label'] ?? ''));
        $title       = trim((string) ($card['card_title'] ?? ''));
        $description = $this->get_paragraph_inner_content($card['card_description'] ?? '');
        $tags        = !empty($card['tags']) ? $card['tags'] : [];

        if ('' === $label && '' === $title && '' === $description && empty($tags)) {
            return;
        }
        ?>
        <div class="col-md-6 col-lg-4">
            <article class="gc-3d-anim-type-card fade-top">
                <?php if ('' !== $label) : ?>
                    <span class="gc-3d-anim-type-label"><?php echo esc_html($label); ?></span>
                <?php endif; ?>
                <?php if ('' !== $title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                <?php if ('' !== $description) : ?>
                    <p><?php echo wp_kses($description, ['br' => []]); ?></p>
                <?php endif; ?>
                <?php if (!empty($tags)) : ?>
                    <ul class="gc-3d-anim-type-tags">
                        <?php foreach ($tags as $tag) :
                            $tag_text = trim((string) ($tag['tag_text'] ?? ''));
                            if ('' === $tag_text) {
                                continue;
                            }
                            ?>
                            <li><?php echo esc_html($tag_text); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </article>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow      = trim((string) ($settings['gc_wwa_eyebrow'] ?? ''));
        $title_before = $settings['gc_wwa_title_before'] ?? '';
        $title_accent = $settings['gc_wwa_title_accent'] ?? '';
        $title_after  = $settings['gc_wwa_title_after'] ?? '';
        $intro        = $this->get_paragraph_inner_content($settings['gc_wwa_intro'] ?? '');
        $cards        = !empty($settings['gc_wwa_type_cards']) ? $settings['gc_wwa_type_cards'] : $this->get_default_type_cards();
        ?>

        <section class="gc-3d-anim-types pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="gc-3d-anim-types-header">
                    <?php if ('' !== $eyebrow) : ?>
                        <span class="gc-3d-anim-types-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ($title_before || $title_accent || $title_after) : ?>
                        <h2 class="gc-3d-anim-types-title overflow-hidden" data-text-animation data-split="word"
                            data-duration="1"><?php echo esc_html($title_before); ?><?php if ($title_accent) : ?><span
                                class="gc-3d-anim-types-accent"><?php echo esc_html($title_accent); ?></span><?php endif; ?><?php echo esc_html($title_after); ?></h2>
                    <?php endif; ?>
                    <?php if ('' !== $intro) : ?>
                        <p class="gc-3d-anim-types-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo wp_kses($intro, ['br' => []]); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (!empty($cards)) : ?>
                    <div class="row g-4 gc-3d-anim-types-grid">
                        <?php foreach ($cards as $card) {
                            $this->render_type_card($card);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_What_We_Animate_Widget());
