<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Delivery_Speed_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_delivery_speed';
    }

    public function get_title()
    {
        return esc_html__('GC Delivery Speed', 'softro-core');
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
            'gc_delivery_content_section',
            [
                'label' => esc_html__('Content', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_delivery_badge',
            [
                'label'       => esc_html__('Badge', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Delivery speed', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_delivery_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('What Makes Us Different from Other Agencies', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->add_control(
            'gc_delivery_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'There are many reasons why businesses in the US, Europe, Asia, Australia, and Africa choose us over other companies.',
                    'softro-core'
                ),
            ]
        );

        $this->end_controls_section();

        $stat_repeater = new Repeater();

        $stat_repeater->add_control(
            'stat_value',
            [
                'label'       => esc_html__('Value', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('12hr', 'softro-core'),
                'label_block' => true,
            ]
        );

        $stat_repeater->add_control(
            'stat_label',
            [
                'label'       => esc_html__('Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Turnaround', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->start_controls_section(
            'gc_delivery_stats_section',
            [
                'label' => esc_html__('Stat Cards', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_delivery_stats',
            [
                'label'       => esc_html__('Stats', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $stat_repeater->get_controls(),
                'default'     => [
                    [
                        'stat_value' => esc_html__('12hr', 'softro-core'),
                        'stat_label' => esc_html__('Turnaround', 'softro-core'),
                    ],
                    [
                        'stat_value' => esc_html__('100%', 'softro-core'),
                        'stat_label' => esc_html__('AI-Powered Approach', 'softro-core'),
                    ],
                    [
                        'stat_value' => esc_html__('65+', 'softro-core'),
                        'stat_label' => esc_html__('Dedicated Team Members', 'softro-core'),
                    ],
                    [
                        'stat_value' => esc_html__('100%', 'softro-core'),
                        'stat_label' => esc_html__('Fast Project Delivery', 'softro-core'),
                    ],
                    [
                        'stat_value' => esc_html__('100%', 'softro-core'),
                        'stat_label' => esc_html__('Money-Back Guarantee', 'softro-core'),
                    ],
                    [
                        'stat_value' => esc_html__('100%', 'softro-core'),
                        'stat_label' => esc_html__('Unlimited Revisions', 'softro-core'),
                    ],
                ],
                'title_field' => '{{{ stat_value }}} — {{{ stat_label }}}',
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
            'gc_delivery_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_delivery_reset_elementor_spacing',
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
            'gc_delivery_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_delivery_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .delivery-speed-section-11',
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-section-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-section-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_panel',
            [
                'label' => esc_html__('Panel', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_delivery_panel_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .delivery-speed-panel',
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_panel_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_panel_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_badge',
            [
                'label' => esc_html__('Badge', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_delivery_badge_typography',
                'selector' => '{{WRAPPER}} .delivery-speed-badge',
            ]
        );

        $this->add_control(
            'gc_delivery_badge_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-speed-badge' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_delivery_badge_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-speed-badge' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_badge_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-badge' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_badge_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-badge' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_delivery_title_typography',
                'selector' => '{{WRAPPER}} .delivery-speed-title',
            ]
        );

        $this->add_control(
            'gc_delivery_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-speed-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_delivery_desc_typography',
                'selector' => '{{WRAPPER}} .delivery-speed-desc',
            ]
        );

        $this->add_control(
            'gc_delivery_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-speed-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_desc_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_content_wrap_padding',
            [
                'label'      => esc_html__('Content Wrap Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'separator'  => 'before',
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_content_wrap_margin',
            [
                'label'      => esc_html__('Content Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_stats_wrap',
            [
                'label' => esc_html__('Stats Column', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stats_gap',
            [
                'label'      => esc_html__('Cards Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-stats' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stats_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-stats' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stats_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-speed-stats' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_stat_card',
            [
                'label' => esc_html__('Stat Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_delivery_stat_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_delivery_stat_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_delivery_stat_card_hover_bg',
            [
                'label'     => esc_html__('Hover Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-card:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_card_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_stat_value',
            [
                'label' => esc_html__('Stat Value', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_delivery_stat_value_typography',
                'selector' => '{{WRAPPER}} .delivery-stat-value',
            ]
        );

        $this->add_control(
            'gc_delivery_stat_value_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_delivery_stat_value_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-card:hover .delivery-stat-value' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_value_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-value' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_value_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-value' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_delivery_style_stat_label',
            [
                'label' => esc_html__('Stat Label', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_delivery_stat_label_typography',
                'selector' => '{{WRAPPER}} .delivery-stat-label',
            ]
        );

        $this->add_control(
            'gc_delivery_stat_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_delivery_stat_label_hover_color',
            [
                'label'     => esc_html__('Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .delivery-stat-card:hover .delivery-stat-label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_label_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_delivery_stat_label_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .delivery-stat-label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        if ('yes' !== ($settings['gc_delivery_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?>.fade-top {
                opacity: 1 !important;
                transform: none !important;
            }
        </style>
        <script>
            (function($) {
                function gcDeliverySpeedEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });
                }

                gcDeliverySpeedEditorPreviewFix();

                $(window).on('elementor/frontend/init', function() {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_delivery_speed.default',
                        gcDeliverySpeedEditorPreviewFix
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
    private function render_stat_card($item)
    {
        $value = $item['stat_value'] ?? '';
        $label = $item['stat_label'] ?? '';

        if ('' === $value && '' === $label) {
            return;
        }
    ?>
        <div class="delivery-stat-card fade-top">
            <?php if ('' !== $value) : ?>
                <span class="delivery-stat-value"><?php echo esc_html($value); ?></span>
            <?php endif; ?>
            <?php if ($label) : ?>
                <span class="delivery-stat-label"><?php echo esc_html($label); ?></span>
            <?php endif; ?>
        </div>
    <?php
    }

    /**
     * @param array $items
     * @return void
     */
    private function render_stats_column($items)
    {
    ?>
        <div class="col-lg-4">
            <div class="delivery-speed-stats">
                <?php foreach ($items as $item) {
                    $this->render_stat_card($item);
                } ?>
            </div>
        </div>
    <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $badge       = $settings['gc_delivery_badge'] ?? '';
        $title       = $settings['gc_delivery_title'] ?? '';
        $description = $settings['gc_delivery_description'] ?? '';
        $stats       = !empty($settings['gc_delivery_stats']) ? $settings['gc_delivery_stats'] : [];
        $stat_chunks = array_chunk($stats, 3);
        $left_stats  = $stat_chunks[0] ?? [];
        $right_stats = $stat_chunks[1] ?? [];

        if (isset($stat_chunks[2])) {
            $right_stats = array_merge($right_stats, $stat_chunks[2]);
        }
    ?>

        <section class="delivery-speed-section-11 pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="delivery-speed-panel">
                    <div class="row gx-xl-3">
                        <div class="col-lg-4">
                            <div class="delivery-speed-content">
                                <?php if ($badge) : ?>
                                    <span class="delivery-speed-badge" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($badge); ?></span>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <h2 class="delivery-speed-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                <?php endif; ?>
                                <?php if ($description) : ?>
                                    <p class="delivery-speed-desc" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        $this->render_stats_column($left_stats);
                        $this->render_stats_column($right_stats);
                        ?>
                    </div>
                </div>
            </div>
        </section>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Delivery_Speed_Widget());
