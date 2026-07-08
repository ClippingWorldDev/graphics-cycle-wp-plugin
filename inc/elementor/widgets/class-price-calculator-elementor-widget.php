<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_GC_Price_Calculation_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_price_calculation';
    }

    public function get_title()
    {
        return esc_html__('GC Price Calculator', 'softro-core');
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

    private function build_services_config(array $services)
    {
        $config = [];

        foreach ($services as $service) {
            $value = sanitize_title($service['service_value'] ?? '');
            $label = $service['service_label'] ?? '';

            if (!$value || !$label) {
                continue;
            }

            $config[$value] = [
                'name'      => $label,
                'unit'      => $service['service_unit'] ?? 'image',
                'basePrice' => max(0, (float) ($service['service_price'] ?? 0)),
                'preview'   => [
                    $this->get_media_url($service['service_preview_one'] ?? [], 'new-update/hero-img-1.png'),
                    $this->get_media_url($service['service_preview_two'] ?? [], 'new-update/hero-img-1.png'),
                ],
            ];
        }

        return $config;
    }

    private function build_discount_tiers(array $tiers)
    {
        $output = [];

        foreach ($tiers as $tier) {
            $min_quantity = absint($tier['min_quantity'] ?? 0);
            $discount     = (float) ($tier['discount_percent'] ?? 0);

            if ($min_quantity < 1 || $discount <= 0) {
                continue;
            }

            $output[] = [
                'min'  => $min_quantity,
                'rate' => min(100, $discount) / 100,
            ];
        }

        usort(
            $output,
            static function ($left, $right) {
                return $right['min'] <=> $left['min'];
            }
        );

        return $output;
    }

    private function get_default_service_config(array $services_config, $default_service)
    {
        if (!empty($services_config[$default_service])) {
            return $services_config[$default_service];
        }

        return reset($services_config) ?: [
            'name'      => '',
            'unit'      => 'image',
            'basePrice' => 0,
            'preview'   => ['', ''],
        ];
    }

    private function format_money($value)
    {
        return '$' . number_format((float) $value, 2, '.', '');
    }

    private function format_unit_price($value)
    {
        return '$ ' . number_format((float) $value, 2, '.', '');
    }

    private function get_discount_rate($quantity, array $discount_tiers)
    {
        foreach ($discount_tiers as $tier) {
            if ($quantity >= (int) ($tier['min'] ?? 0)) {
                return (float) ($tier['rate'] ?? 0);
            }
        }

        return 0.0;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section(
            'gc_price_calculator_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_price_calculator_eyebrow',
            [
                'label'       => esc_html__('Eyebrow Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Pricing Calculator', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Calculate Your Estimate Price', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_description',
            [
                'label'   => esc_html__('Description', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('We value your order as much as you do. The process below is to help you pricing math. Select the service and enter the quantity as you need. And, see the estimated price after quantity discount.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_price_calculator_section_aria_label',
            [
                'label'       => esc_html__('Section Aria Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Calculate your estimate price', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();

        $service_repeater = new Repeater();

        $service_repeater->add_control(
            'service_value',
            [
                'label'       => esc_html__('Service Value', 'softro-core'),
                'description' => esc_html__('Must match theme calculator keys (e.g. photo-retouching).', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'photo-retouching',
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_label',
            [
                'label'       => esc_html__('Service Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Photo retouching', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_price',
            [
                'label'       => esc_html__('Price Per Unit', 'softro-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 1.2,
                'min'         => 0,
                'step'        => 0.01,
                'description' => esc_html__('Base price for one item (e.g. per image).', 'softro-core'),
            ]
        );

        $service_repeater->add_control(
            'service_unit',
            [
                'label'       => esc_html__('Unit Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('image', 'softro-core'),
                'description' => esc_html__('Used in quantity summary (e.g. image / images).', 'softro-core'),
                'label_block' => true,
            ]
        );

        $service_repeater->add_control(
            'service_preview_one',
            [
                'label'   => esc_html__('Preview Image One', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('service/service-1.jpg'),
                ],
            ]
        );

        $service_repeater->add_control(
            'service_preview_two',
            [
                'label'   => esc_html__('Preview Image Two', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/hero-img-1.png'),
                ],
            ]
        );

        $discount_repeater = new Repeater();

        $discount_repeater->add_control(
            'min_quantity',
            [
                'label'       => esc_html__('Minimum Quantity', 'softro-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 100,
                'min'         => 1,
                'description' => esc_html__('Discount applies when quantity is equal or greater.', 'softro-core'),
            ]
        );

        $discount_repeater->add_control(
            'discount_percent',
            [
                'label'       => esc_html__('Discount (%)', 'softro-core'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 10,
                'min'         => 0,
                'max'         => 100,
                'step'        => 0.1,
                'description' => esc_html__('Percentage off subtotal (e.g. 10 = 10% discount).', 'softro-core'),
            ]
        );

        $this->start_controls_section(
            'gc_price_calculator_form_section',
            [
                'label' => esc_html__('Calculator Form', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_price_calculator_service_label',
            [
                'label'       => esc_html__('Services Field Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Services', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_services',
            [
                'label'       => esc_html__('Services', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $service_repeater->get_controls(),
                'default'     => [
                    [
                        'service_value'       => 'photo-retouching',
                        'service_label'       => esc_html__('Photo retouching', 'softro-core'),
                        'service_price'       => 1.2,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('service/service-1.jpg')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('new-update/hero-img-1.png')],
                    ],
                    [
                        'service_value'       => 'background-removal',
                        'service_label'       => esc_html__('Background removal', 'softro-core'),
                        'service_price'       => 0.85,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('new-update/hero-img-2.png')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('service/service-2.jpg')],
                    ],
                    [
                        'service_value'       => 'image-masking',
                        'service_label'       => esc_html__('Image masking', 'softro-core'),
                        'service_price'       => 1.5,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('new-update/hero-img-3.png')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('service/service-3.jpg')],
                    ],
                    [
                        'service_value'       => 'clipping-path',
                        'service_label'       => esc_html__('Clipping path', 'softro-core'),
                        'service_price'       => 0.75,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('new-update/hero-img-4.png')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('service/service-1.jpg')],
                    ],
                    [
                        'service_value'       => 'color-correction',
                        'service_label'       => esc_html__('Color correction', 'softro-core'),
                        'service_price'       => 1.0,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('new-update/service-img-1.png')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
                    ],
                    [
                        'service_value'       => 'shadow-creation',
                        'service_label'       => esc_html__('Shadow creation', 'softro-core'),
                        'service_price'       => 0.95,
                        'service_unit'        => esc_html__('image', 'softro-core'),
                        'service_preview_one' => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
                        'service_preview_two' => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
                    ],
                ],
                'title_field' => '{{{ service_label }}} — {{{ service_price }}}',
            ]
        );

        $this->add_control(
            'gc_price_calculator_discount_tiers',
            [
                'label'       => esc_html__('Quantity Discount Tiers', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $discount_repeater->get_controls(),
                'default'     => [
                    ['min_quantity' => 500, 'discount_percent' => 15],
                    ['min_quantity' => 100, 'discount_percent' => 10],
                    ['min_quantity' => 50, 'discount_percent' => 5],
                ],
                'title_field' => 'From {{{ min_quantity }}}+ → {{{ discount_percent }}}%',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'gc_price_calculator_default_service',
            [
                'label'       => esc_html__('Default Selected Service Value', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'photo-retouching',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_quantity_label',
            [
                'label'       => esc_html__('Quantity Field Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Quantity', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_default_quantity',
            [
                'label'   => esc_html__('Default Quantity', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 9,
                'min'     => 1,
                'max'     => 99999,
            ]
        );

        $this->add_control(
            'gc_price_calculator_button_text',
            [
                'label'       => esc_html__('Button Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Calculate Price', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_note',
            [
                'label'   => esc_html__('Form Note', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__('The Price Mention Here Is Subject To Change According To The Complexity And The Quantity Of Image.', 'softro-core'),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_result_section',
            [
                'label' => esc_html__('Result Panel', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_price_calculator_preview_image_one_alt',
            [
                'label'       => esc_html__('Preview Image One Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Service preview before', 'softro-core'),
                'label_block' => true,
                'description' => esc_html__('Preview images are set per service in the Services list above.', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_price_calculator_preview_image_two_alt',
            [
                'label'       => esc_html__('Preview Image Two Alt', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Service preview after', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_unit_price_label',
            [
                'label'       => esc_html__('Unit Price Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Starting price per image:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_quantity_summary_label',
            [
                'label'       => esc_html__('Quantity Summary Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Quantity:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_service_summary_label',
            [
                'label'       => esc_html__('Service Summary Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Service:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_discount_label',
            [
                'label'       => esc_html__('Discount Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Quantity discount:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_subtotal_label',
            [
                'label'       => esc_html__('Subtotal Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Subtotal:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_price_calculator_estimated_label',
            [
                'label'       => esc_html__('Estimated Price Label', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Estimated price:', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section(
            'gc_price_calculator_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_price_calculator_reset_elementor_spacing',
            [
                'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_section_padding_top',
            [
                'label'      => esc_html__('Section Top Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-section' => 'padding-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_section_padding_bottom',
            [
                'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'default'    => ['size' => 130, 'unit' => 'px'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-section' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_price_calculator_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .gc-price-calculator-section',
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_eyebrow',
            [
                'label' => esc_html__('Eyebrow', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_eyebrow_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_price_calculator_eyebrow_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_title_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-title',
            ]
        );

        $this->add_control(
            'gc_price_calculator_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_heading_margin',
            [
                'label'      => esc_html__('Heading Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_description',
            [
                'label' => esc_html__('Description', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_desc_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-desc',
            ]
        );

        $this->add_control(
            'gc_price_calculator_desc_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_desc_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_card',
            [
                'label' => esc_html__('Calculator Card', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_price_calculator_card_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_price_calculator_card_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-card' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_card_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_form',
            [
                'label' => esc_html__('Form', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_form_label_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-form label',
            ]
        );

        $this->add_control(
            'gc_price_calculator_form_label_color',
            [
                'label'     => esc_html__('Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-form label' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_form_input_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-form .form-control',
            ]
        );

        $this->add_control(
            'gc_price_calculator_form_input_color',
            [
                'label'     => esc_html__('Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-form .form-control' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_price_calculator_form_input_bg',
            [
                'label'     => esc_html__('Input Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-form .form-control' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_form_padding',
            [
                'label'      => esc_html__('Form Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-form' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_button',
            [
                'label' => esc_html__('Button', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_button_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-btn',
            ]
        );

        $this->add_control(
            'gc_price_calculator_button_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_price_calculator_button_bg',
            [
                'label'     => esc_html__('Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_button_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_note',
            [
                'label' => esc_html__('Form Note', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_note_typography',
                'selector' => '{{WRAPPER}} .gc-price-calculator-note',
            ]
        );

        $this->add_control(
            'gc_price_calculator_note_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-calculator-note' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_preview',
            [
                'label' => esc_html__('Preview Images', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_preview_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-preview-item img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_preview_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-preview-item, {{WRAPPER}} .gc-price-preview-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_price_calculator_result_padding',
            [
                'label'      => esc_html__('Result Panel Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .gc-price-calculator-result' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_price_calculator_style_summary',
            [
                'label' => esc_html__('Summary List', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_summary_typography',
                'selector' => '{{WRAPPER}} .gc-price-summary-list li',
            ]
        );

        $this->add_control(
            'gc_price_calculator_summary_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-price-summary-list li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_price_calculator_estimated_typography',
                'selector' => '{{WRAPPER}} .gc-estimated-price',
            ]
        );

        $this->add_control(
            'gc_price_calculator_estimated_color',
            [
                'label'     => esc_html__('Estimated Price Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .gc-estimated-price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section(
            'gc_price_calculator_style_theme_mode',
            [
                'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('gc_price_calculator_theme_mode_color_tabs');

        $this->start_controls_tab('gc_price_calculator_theme_mode_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_price_calculator_dark_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=dark] {{WRAPPER}} .gc-price-calculator-section',
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_form_label_color',
            [
                'label'     => esc_html__('Form Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-form label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_form_input_color',
            [
                'label'     => esc_html__('Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-form .form-control' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_note_color',
            [
                'label'     => esc_html__('Note Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-calculator-note' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_summary_color',
            [
                'label'     => esc_html__('Summary Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-price-summary-list li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_dark_estimated_color',
            [
                'label'     => esc_html__('Estimated Price Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('dark', [
                    '.gc-estimated-price, .gc-estimated-label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('gc_price_calculator_theme_mode_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_price_calculator_light_section_bg',
                'label'    => esc_html__('Section Background', 'softro-core'),
                'types'    => ['classic', 'gradient'],
                'selector' => '[data-theme=light] {{WRAPPER}} .gc-price-calculator-section',
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_eyebrow_color',
            [
                'label'     => esc_html__('Eyebrow Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'default'   => 'var(--rr-color-theme-primary)',
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-heading .sub-heading' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-title' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_desc_color',
            [
                'label'     => esc_html__('Description Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-desc' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_card_bg',
            [
                'label'     => esc_html__('Card Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-card' => 'background-color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_form_label_color',
            [
                'label'     => esc_html__('Form Label Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-form label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_form_input_color',
            [
                'label'     => esc_html__('Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-form .form-control' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_note_color',
            [
                'label'     => esc_html__('Note Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-calculator-note' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_summary_color',
            [
                'label'     => esc_html__('Summary Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-price-summary-list li' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->add_control(
            'gc_price_calculator_light_estimated_color',
            [
                'label'     => esc_html__('Estimated Price Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => $this->get_theme_mode_selectors('light', [
                    '.gc-estimated-price, .gc-estimated-label' => 'color: {{VALUE}};',
                ]),
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_price_calculator_reset_elementor_spacing'] ?? 'yes')) {
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
            .elementor-element-<?php echo $widget_id; ?> .fade-wrapper .fade-top {
                opacity: 1 !important;
                transform: none !important;
                visibility: visible !important;
            }
        </style>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow           = $settings['gc_price_calculator_eyebrow'] ?? '';
        $title             = $settings['gc_price_calculator_title'] ?? '';
        $description       = $settings['gc_price_calculator_description'] ?? '';
        $section_aria      = $settings['gc_price_calculator_section_aria_label'] ?? esc_html__('Calculate your estimate price', 'softro-core');
        $services          = !empty($settings['gc_price_calculator_services']) ? $settings['gc_price_calculator_services'] : [];
        $default_service   = $settings['gc_price_calculator_default_service'] ?? 'photo-retouching';
        $service_label     = $settings['gc_price_calculator_service_label'] ?? esc_html__('Services', 'softro-core');
        $quantity_label    = $settings['gc_price_calculator_quantity_label'] ?? esc_html__('Quantity', 'softro-core');
        $default_quantity  = absint($settings['gc_price_calculator_default_quantity'] ?? 9);
        $button_text       = $settings['gc_price_calculator_button_text'] ?? esc_html__('Calculate Price', 'softro-core');
        $note              = $settings['gc_price_calculator_note'] ?? '';
        $preview_one_alt   = $settings['gc_price_calculator_preview_image_one_alt'] ?? esc_html__('Service preview before', 'softro-core');
        $preview_two_alt   = $settings['gc_price_calculator_preview_image_two_alt'] ?? esc_html__('Service preview after', 'softro-core');
        $unit_price_label  = $settings['gc_price_calculator_unit_price_label'] ?? esc_html__('Starting price per image:', 'softro-core');
        $quantity_summary  = $settings['gc_price_calculator_quantity_summary_label'] ?? esc_html__('Quantity:', 'softro-core');
        $service_summary   = $settings['gc_price_calculator_service_summary_label'] ?? esc_html__('Service:', 'softro-core');
        $discount_label    = $settings['gc_price_calculator_discount_label'] ?? esc_html__('Quantity discount:', 'softro-core');
        $subtotal_label    = $settings['gc_price_calculator_subtotal_label'] ?? esc_html__('Subtotal:', 'softro-core');
        $estimated_label   = $settings['gc_price_calculator_estimated_label'] ?? esc_html__('Estimated price:', 'softro-core');
        $discount_tiers    = $this->build_discount_tiers(!empty($settings['gc_price_calculator_discount_tiers']) ? $settings['gc_price_calculator_discount_tiers'] : []);
        $services_config   = $this->build_services_config($services);
        $default_config    = $this->get_default_service_config($services_config, sanitize_title($default_service));

        if ($default_quantity < 1) {
            $default_quantity = 1;
        }

        $discount_rate   = $this->get_discount_rate($default_quantity, $discount_tiers);
        $subtotal        = $default_config['basePrice'] * $default_quantity;
        $discount_amount = $subtotal * $discount_rate;
        $estimated       = $subtotal - $discount_amount;
        $unit_label      = 1 === $default_quantity ? $default_config['unit'] : $default_config['unit'] . 's';
        $preview_one     = $default_config['preview'][0] ?? $this->get_theme_img_url('new-update/hero-img-1.png');
        $preview_two     = $default_config['preview'][1] ?? $this->get_theme_img_url('new-update/hero-img-1.png');
        $services_json   = wp_json_encode($services_config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $discounts_json  = wp_json_encode($discount_tiers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        ?>

        <section
            class="gc-price-calculator-section pt-130 pb-130 fade-wrapper"
            aria-label="<?php echo esc_attr($section_aria); ?>"
            data-gc-services="<?php echo esc_attr($services_json); ?>"
            data-gc-discounts="<?php echo esc_attr($discounts_json); ?>"
        >
            <div class="container">
                <div class="section-heading text-center gc-price-calculator-heading">
                    <?php if ($eyebrow) : ?>
                        <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></h4>
                    <?php endif; ?>

                    <?php if ($title) : ?>
                        <h2 class="section-title gc-price-calculator-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>

                    <?php if ($description) : ?>
                        <p class="gc-price-calculator-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
                    <?php endif; ?>
                </div>

                <div class="gc-price-calculator-card fade-top">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-lg-6">
                            <div class="gc-price-calculator-form">
                                <div class="form-item form-item--service">
                                    <label for="gc-service-select"><?php echo esc_html($service_label); ?></label>
                                    <select id="gc-service-select" class="form-control gc-service-select gc-native-select" aria-label="<?php echo esc_attr($service_label); ?>">
                                        <?php foreach ($services as $service) :
                                            $value = sanitize_title($service['service_value'] ?? '');
                                            $label = $service['service_label'] ?? '';

                                            if (!$value || !$label) {
                                                continue;
                                            }

                                            $selected = selected(sanitize_title($default_service), $value, false);
                                            ?>
                                            <option value="<?php echo esc_attr($value); ?>"<?php echo $selected; ?>><?php echo esc_html($label); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-item">
                                    <label for="gc-quantity-input"><?php echo esc_html($quantity_label); ?></label>
                                    <input id="gc-quantity-input" class="form-control gc-quantity-input" type="number" min="1" max="99999" value="<?php echo esc_attr($default_quantity); ?>" inputmode="numeric" aria-label="<?php echo esc_attr($quantity_label); ?>">
                                </div>
                                <button type="button" class="rr-primary-btn gc-price-calculator-btn" id="gc-complete-quote-btn"><?php echo esc_html($button_text); ?></button>

                                <?php if ($note) : ?>
                                    <p class="gc-price-calculator-note"><?php echo $this->get_paragraph_inner_content($note); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="gc-price-calculator-result">
                                <div class="gc-price-preview-grid">
                                    <div class="gc-price-preview-item">
                                        <img id="gc-preview-image-1" src="<?php echo esc_url($preview_one); ?>" alt="<?php echo esc_attr($preview_one_alt); ?>">
                                    </div>
                                    <div class="gc-price-preview-item">
                                        <img id="gc-preview-image-2" src="<?php echo esc_url($preview_two); ?>" alt="<?php echo esc_attr($preview_two_alt); ?>">
                                    </div>
                                </div>
                                <ul class="gc-price-summary-list">
                                    <li><span class="label"><?php echo esc_html($unit_price_label); ?></span> <strong class="value gc-unit-price"><?php echo esc_html($this->format_unit_price($default_config['basePrice'])); ?></strong></li>
                                    <li><span class="label"><?php echo esc_html($quantity_summary); ?></span> <strong class="value gc-quantity-label"><?php echo esc_html($default_quantity . ' ' . $unit_label); ?></strong></li>
                                    <li><span class="label"><?php echo esc_html($service_summary); ?></span> <strong class="value gc-service-label"><?php echo esc_html($default_config['name']); ?></strong></li>
                                    <li class="gc-discount-row<?php echo $discount_rate > 0 ? '' : ' is-hidden'; ?>"><span class="label"><?php echo esc_html($discount_label); ?></span> <strong class="value gc-discount-label"><?php echo $discount_rate > 0 ? esc_html((string) round($discount_rate * 100) . '% (-' . $this->format_money($discount_amount) . ')') : esc_html('0%'); ?></strong></li>
                                    <li class="gc-subtotal-row<?php echo $discount_rate > 0 ? '' : ' is-hidden'; ?>"><span class="label"><?php echo esc_html($subtotal_label); ?></span> <strong class="value gc-subtotal-price"><?php echo esc_html($this->format_money($subtotal)); ?></strong></li>
                                </ul>
                                <div class="gc-estimated-price-wrap">
                                    <span class="gc-estimated-label"><?php echo esc_html($estimated_label); ?></span>
                                    <span class="gc-estimated-price" id="gc-estimated-price"><?php echo esc_html($this->format_money($estimated)); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_GC_Price_Calculation_Widget());
