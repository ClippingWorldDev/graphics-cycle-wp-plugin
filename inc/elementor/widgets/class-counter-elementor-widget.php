<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Counter_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_counter';
    }

    public function get_title()
    {
        return esc_html__('GC Counter', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['nexaq_widgets'];
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
        $counter_repeater = new Repeater();

        $counter_repeater->add_control(
            'counter_icon_image',
            [
                'label'   => esc_html__('Icon Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $counter_repeater->add_control(
            'counter_icon',
            [
                'label'       => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'description' => esc_html__('If selected, this icon is used instead of the image upload.', 'softro-core'),
                'type'        => Controls_Manager::ICONS,
                'default'     => [
                    'value'   => '',
                    'library' => 'solid',
                ],
            ]
        );

        $counter_repeater->add_control(
            'counter_number',
            [
                'label'   => esc_html__('Number', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 42,
                'min'     => 0,
            ]
        );

        $counter_repeater->add_control(
            'counter_suffix',
            [
                'label'       => esc_html__('Suffix', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '+',
                'label_block' => true,
            ]
        );

        $counter_repeater->add_control(
            'counter_label',
            [
                'label'       => esc_html__('Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Years of Experience', 'softro-core'),
                'label_block' => true,
            ]
        );

        $counter_repeater->add_control(
            'counter_card_class',
            [
                'label'   => esc_html__('Card Modifier Class', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    ''       => esc_html__('Default', 'softro-core'),
                    'card-4' => esc_html__('Card 4 (No Right Border)', 'softro-core'),
                ],
            ]
        );

        $this->start_controls_section(
            'gc_counter_items_section',
            [
                'label' => esc_html__('Counter Items', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_counter_items',
            [
                'label'       => esc_html__('Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $counter_repeater->get_controls(),
                'default'     => [
                    [
                        'counter_number'     => 42,
                        'counter_suffix'     => '+',
                        'counter_label'      => esc_html__('Years of Experience', 'softro-core'),
                        'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-1.png')],
                        'counter_card_class' => '',
                    ],
                    [
                        'counter_number'     => 200,
                        'counter_suffix'     => '+',
                        'counter_label'      => esc_html__('Project\'s Complete', 'softro-core'),
                        'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-2.png')],
                        'counter_card_class' => '',
                    ],
                    [
                        'counter_number'     => 68,
                        'counter_suffix'     => '+',
                        'counter_label'      => esc_html__('Team Members', 'softro-core'),
                        'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-3.png')],
                        'counter_card_class' => '',
                    ],
                    [
                        'counter_number'     => 99,
                        'counter_suffix'     => '+',
                        'counter_label'      => esc_html__('Total Award Wins', 'softro-core'),
                        'counter_icon_image' => ['url' => $this->get_theme_img_url('icon/counter-4.png')],
                        'counter_card_class' => 'card-4',
                    ],
                ],
                'title_field' => '{{{ counter_label }}}',
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
            'gc_counter_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_counter_reset_elementor_spacing',
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
            'gc_counter_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_counter_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .process-counter',
            ]
        );

        $this->add_control(
            'gc_counter_section_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .process-counter' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .process-counter' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .process-counter' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_counter_style_card',
            [
                'label' => esc_html__('Counter Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_counter_card_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_counter_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-card' => 'border-right-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_card_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_card_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_card_gap',
            [
                'label'      => esc_html__('Icon / Content Gap', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 80,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card' => 'grid-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_counter_style_icon',
            [
                'label' => esc_html__('Icon / Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_counter_icon_width',
            [
                'label'      => esc_html__('Image Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_icon_box_size',
            [
                'label'      => esc_html__('Icon Box Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card' => 'grid-template-columns: {{SIZE}}{{UNIT}} 1fr;',
                    '{{WRAPPER}} .counter-card .icon' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_icon_font_size',
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
                    '{{WRAPPER}} .counter-card .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .counter-card .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'gc_counter_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-card .icon'     => 'color: {{VALUE}};',
                    '{{WRAPPER}} .counter-card .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_icon_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_icon_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_counter_style_number',
            [
                'label' => esc_html__('Number', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_counter_number_typography',
                'selector' => '{{WRAPPER}} .counter-card .content .title',
            ]
        );

        $this->add_control(
            'gc_counter_number_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-card .content .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_number_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .content .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_number_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .content .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_counter_style_label',
            [
                'label' => esc_html__('Label', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_counter_label_typography',
                'selector' => '{{WRAPPER}} .counter-card .content p',
            ]
        );

        $this->add_control(
            'gc_counter_label_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .counter-card .content p' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_label_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .content p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_counter_label_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .counter-card .content p' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        if ('yes' !== ($settings['gc_counter_reset_elementor_spacing'] ?? 'yes')) {
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
    private function render_counter_icon($item)
    {
        if (!empty($item['counter_icon']['value'])) {
            Icons_Manager::render_icon($item['counter_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['counter_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="counter">';
        }
    }

    /**
     * @param string $modifier
     * @return string
     */
    private function get_counter_card_class($modifier)
    {
        $classes = ['counter-card'];

        if ('card-4' === ($modifier ?? '')) {
            $classes[] = 'card-4';
        }

        return implode(' ', $classes);
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $items    = !empty($settings['gc_counter_items']) ? $settings['gc_counter_items'] : [];

        $this->render_elementor_spacing_fix($settings);

        if (empty($items)) {
            return;
        }
        ?>

        <div class="process-counter">
            <div class="container">
                <div class="row process-counter-wrap">

                    <?php foreach ($items as $item) :
                        $number   = isset($item['counter_number']) ? $item['counter_number'] : 0;
                        $suffix   = $item['counter_suffix'] ?? '';
                        $label    = $item['counter_label'] ?? '';
                        $modifier = $item['counter_card_class'] ?? '';
                        ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="<?php echo esc_attr($this->get_counter_card_class($modifier)); ?>">
                                <div class="icon"><?php $this->render_counter_icon($item); ?></div>
                                <div class="content">
                                    <h3 class="title">
                                        <span class="odometer" data-count="<?php echo esc_attr($number); ?>">0</span><?php echo esc_html($suffix); ?>
                                    </h3>
                                    <?php if ($label) : ?>
                                        <p><?php echo esc_html($label); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Counter_Widget());
