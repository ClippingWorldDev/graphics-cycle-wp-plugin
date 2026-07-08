<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Industries_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_industries';
    }

    public function get_title()
    {
        return esc_html__('GC Industries', 'softro-core');
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
     * @param string $content
     * @return string
     */
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
            'gc_industries_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_industries_eyebrow',
            [
                'label'       => esc_html__('Eyebrow', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Industries We Serve', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_industries_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Serving Every Industry with Expert Visuals', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'gc_industries_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'From real estate to sports, we deliver professional video editing and 3D design tailored to every industry.',
                    'softro-core'
                ),
            ]
        );

        $this->end_controls_section();

        $industry_repeater = new Repeater();

        $industry_repeater->add_control(
            'industry_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );

        $industry_repeater->add_control(
            'industry_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => 'fal fa-building',
                    'library' => 'fa-light',
                ],
            ]
        );

        $industry_repeater->add_control(
            'industry_label',
            [
                'label'       => esc_html__('Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Real Estate', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_industries_items_section',
            [
                'label' => esc_html__('Industry Items', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_industries_items',
            [
                'label'       => esc_html__('Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $industry_repeater->get_controls(),
                'default'     => [
                    [
                        'industry_label' => esc_html__('Real Estate', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-building', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('Fashion & Lifestyle', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-shirt', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('YouTube & Content', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-tv', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('Marketing & Ads', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-bullhorn', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('E-Commerce', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-store', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('Healthcare', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-stethoscope', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('Sports & Fitness', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-trophy', 'library' => 'fa-light'],
                    ],
                    [
                        'industry_label' => esc_html__('Events & Weddings', 'softro-core'),
                        'industry_icon'  => ['value' => 'fal fa-party-horn', 'library' => 'fa-light'],
                    ],
                ],
                'title_field' => '{{{ industry_label }}}',
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
            'gc_industries_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_industries_reset_elementor_spacing',
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
            'gc_industries_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_industries_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-video-3d-industries',
            ]
        );

        $this->add_responsive_control(
            'gc_industries_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_industries_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-video-3d-industries-eyebrow',
            ]
        );

        $this->add_control(
            'gc_industries_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industries-eyebrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_eyebrow_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_eyebrow_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-eyebrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_industries_title_typography',
                'selector' => '{{WRAPPER}} .gc-video-3d-industries-title',
            ]
        );

        $this->add_control(
            'gc_industries_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industries-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_industries_desc_typography',
                'selector' => '{{WRAPPER}} .gc-video-3d-industries-desc',
            ]
        );

        $this->add_control(
            'gc_industries_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industries-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_desc_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_heading_wrap_margin',
            [
                'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_heading_wrap_padding',
            [
                'label'      => esc_html__('Heading Wrap Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_grid',
            [
                'label' => esc_html__('Industries Grid', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_industries_grid_gap',
            [
                'label'      => esc_html__('Grid Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_grid_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-grid' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_grid_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industries-grid' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_item',
            [
                'label' => esc_html__('Industry Item', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_industries_item_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industry-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_industries_item_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industry-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_industries_item_hover_bg',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industry-item:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_item_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_item_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_icon',
            [
                'label' => esc_html__('Industry Icon', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_industries_icon_font_size',
            [
                'label'      => esc_html__('Icon Font Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .gc-video-3d-industry-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_icon_image_size',
            [
                'label'      => esc_html__('Icon Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 8,
                        'max' => 120,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_industries_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industry-icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .gc-video-3d-industry-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_icon_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_icon_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_industries_style_label',
            [
                'label' => esc_html__('Industry Label', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_industries_label_typography',
                'selector' => '{{WRAPPER}} .gc-video-3d-industry-label',
            ]
        );

        $this->add_control(
            'gc_industries_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-video-3d-industry-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_label_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_industries_label_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-video-3d-industry-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_industries_reset_elementor_spacing'] ?? 'yes')) {
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
     * @return bool
     */
    private function is_elementor_edit_mode()
    {
        return Plugin::$instance->editor->is_edit_mode();
    }

    /**
     * @return void
     */
    private function render_editor_preview_fix()
    {
        if (!$this->is_elementor_edit_mode()) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> .fade-top {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
        <script>
            (function ($) {
                function gcIndustriesEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });
                }

                gcIndustriesEditorPreviewFix();

                $(window).on('elementor/frontend/init', function () {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_industries.default',
                        gcIndustriesEditorPreviewFix
                    );
                });
            })(jQuery);
        </script>
        <?php
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_industry_icon($item)
    {
        echo '<span class="gc-video-3d-industry-icon" aria-hidden="true">';

        if (!empty($item['industry_icon']['value'])) {
            Icons_Manager::render_icon($item['industry_icon'], ['aria-hidden' => 'true']);
        } else {
            $icon_url = $this->get_media_url($item['industry_icon_image'] ?? [], '');

            if ($icon_url) {
                echo '<img src="' . esc_url($icon_url) . '" alt="">';
            }
        }

        echo '</span>';
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_industry_item($item)
    {
        $label = $item['industry_label'] ?? '';

        if (!$label) {
            return;
        }
        ?>
        <div class="gc-video-3d-industry-item">
            <?php $this->render_industry_icon($item); ?>
            <span class="gc-video-3d-industry-label"><?php echo esc_html($label); ?></span>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow     = $settings['gc_industries_eyebrow'] ?? '';
        $title       = $settings['gc_industries_title'] ?? '';
        $description = $settings['gc_industries_description'] ?? '';
        $items       = !empty($settings['gc_industries_items']) ? $settings['gc_industries_items'] : [];
        ?>

        <section class="gc-video-3d-industries pt-130 pb-130 fade-wrapper">
            <div class="container">
                <?php if ($eyebrow || $title || $description) : ?>
                    <div class="gc-video-3d-industries-heading fade-top">
                        <?php if ($eyebrow) : ?>
                            <span class="gc-video-3d-industries-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                        <?php endif; ?>
                        <?php if ($title) : ?>
                            <h2 class="gc-video-3d-industries-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                        <?php endif; ?>
                        <?php if ($description) : ?>
                            <p class="gc-video-3d-industries-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($items)) : ?>
                    <div class="gc-video-3d-industries-grid fade-top">
                        <?php foreach ($items as $item) {
                            $this->render_industry_item($item);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Industries_Widget());
