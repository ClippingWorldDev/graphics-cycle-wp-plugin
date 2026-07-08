<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Faq_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_faq';
    }

    public function get_title()
    {
        return esc_html__('GC FAQ', 'softro-core');
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
    private function get_accordion_body_content($content)
    {
        if ('' === trim((string) $content)) {
            return '';
        }

        return wp_kses_post($content);
    }

    /**
     * Bootstrap accordion suffix IDs used by the theme markup.
     *
     * @param int $index
     * @return string
     */
    private function get_accordion_suffix($index)
    {
        $suffixes = ['Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten'];

        return $suffixes[$index] ?? (string) (4 + $index);
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
            'gc_faq_heading_section',
            [
                'label' => esc_html__('Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_faq_sub_title',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Just Ask us some question', 'softro-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'gc_faq_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Digital Solution That Improve Your Agency Growth', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
            ]
        );

        $this->end_controls_section();

        $faq_repeater = new Repeater();

        $faq_repeater->add_control(
            'faq_question',
            [
                'label'       => esc_html__('Question', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('What kind of services do you offer?', 'softro-core'),
                'label_block' => true,
            ]
        );

        $faq_repeater->add_control(
            'faq_answer',
            [
                'label'   => esc_html__('Answer', 'softro-core'),
                'type'    => Controls_Manager::WYSIWYG,
                'default' => esc_html__(
                    'Risus cum orci sollicitudin fringilla lectus neque rhoncus eget pretium magna, accumsan ante torquent a pellentesque tellus fermentum cursus.',
                    'softro-core'
                ),
            ]
        );

        $faq_repeater->add_control(
            'faq_default_open',
            [
                'label'        => esc_html__('Open by Default', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->start_controls_section(
            'gc_faq_items_section',
            [
                'label' => esc_html__('FAQ Items', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_faq_items',
            [
                'label'       => esc_html__('Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $faq_repeater->get_controls(),
                'default'     => [
                    [
                        'faq_question'     => esc_html__('What kind of services do you offer?', 'softro-core'),
                        'faq_answer'       => esc_html__(
                            'Risus cum orci sollicitudin fringilla lectus neque rhoncus eget pretium magna, accumsan ante torquent a pellentesque tellus fermentum cursus.',
                            'softro-core'
                        ),
                        'faq_default_open' => 'yes',
                    ],
                    [
                        'faq_question' => esc_html__('What kind of technology do you use?', 'softro-core'),
                        'faq_answer'   => esc_html__(
                            'Risus cum orci sollicitudin fringilla lectus neque rhoncus eget pretium magna, accumsan ante torquent a pellentesque tellus fermentum cursus.',
                            'softro-core'
                        ),
                    ],
                    [
                        'faq_question' => esc_html__('How much experience do you have?', 'softro-core'),
                        'faq_answer'   => esc_html__(
                            'Risus cum orci sollicitudin fringilla lectus neque rhoncus eget pretium magna, accumsan ante torquent a pellentesque tellus fermentum cursus.',
                            'softro-core'
                        ),
                    ],
                ],
                'title_field' => '{{{ faq_question }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_image_section',
            [
                'label' => esc_html__('Side Image', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_faq_image',
            [
                'label'   => esc_html__('Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/faq-img-1.png'),
                ],
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
            'gc_faq_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_faq_reset_elementor_spacing',
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
            'gc_faq_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_faq_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .faq-section',
            ]
        );

        $this->add_responsive_control(
            'gc_faq_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-section' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_subtitle',
            [
                'label' => esc_html__('Subtitle', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_faq_subtitle_typography',
                'selector' => '{{WRAPPER}} .faq-content .sub-heading',
            ]
        );

        $this->add_control(
            'gc_faq_subtitle_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_subtitle_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_subtitle_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_title',
            [
                'label' => esc_html__('Title', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_faq_title_typography',
                'selector' => '{{WRAPPER}} .faq-content .section-title',
            ]
        );

        $this->add_control(
            'gc_faq_title_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_title_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_title_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_accordion_item',
            [
                'label' => esc_html__('Accordion Item', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_faq_item_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_faq_item_border_color',
            [
                'label'     => esc_html__('Border Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-item' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_item_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .accordion-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_accordion_wrap_margin',
            [
                'label'      => esc_html__('Accordion Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .accordion' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_question',
            [
                'label' => esc_html__('Question', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_faq_question_typography',
                'selector' => '{{WRAPPER}} .faq-content .accordion-button',
            ]
        );

        $this->add_control(
            'gc_faq_question_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_faq_question_active_color',
            [
                'label'     => esc_html__('Active Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-button:not(.collapsed)' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_faq_question_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_faq_question_active_bg',
            [
                'label'     => esc_html__('Active Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-button:not(.collapsed)' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_question_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .accordion-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_answer',
            [
                'label' => esc_html__('Answer', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_faq_answer_typography',
                'selector' => '{{WRAPPER}} .faq-content .accordion-body',
            ]
        );

        $this->add_control(
            'gc_faq_answer_color',
            [
                'label'     => esc_html__('Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-body' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_faq_answer_bg',
            [
                'label'     => esc_html__('Background Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .faq-content .accordion-body' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_answer_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .accordion-body' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_answer_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-content .accordion-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_faq_style_image',
            [
                'label' => esc_html__('Side Image', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_faq_image_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min' => 50,
                        'max' => 900,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .faq-img-1 img' => 'width: {{SIZE}}{{UNIT}}; max-width: 100%; height: auto;',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_image_wrap_padding',
            [
                'label'      => esc_html__('Wrap Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-img-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_faq_image_wrap_margin',
            [
                'label'      => esc_html__('Wrap Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .faq-img-1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
        if ('yes' !== ($settings['gc_faq_reset_elementor_spacing'] ?? 'yes')) {
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
                function gcFaqEditorPreviewFix($scope) {
                    $scope = $scope && $scope.length ? $scope : $('.elementor-element-<?php echo $widget_id; ?>');

                    if (!$scope.length) {
                        return;
                    }

                    $scope.find('.fade-top').css({
                        opacity: 1,
                        transform: 'none'
                    });
                }

                gcFaqEditorPreviewFix();

                $(window).on('elementor/frontend/init', function () {
                    elementorFrontend.hooks.addAction(
                        'frontend/element_ready/gc_faq.default',
                        gcFaqEditorPreviewFix
                    );
                });
            })(jQuery);
        </script>
        <?php
    }

    /**
     * @param array $items
     * @return int
     */
    private function get_default_open_index($items)
    {
        foreach ($items as $index => $item) {
            if ('yes' === ($item['faq_default_open'] ?? '')) {
                return $index;
            }
        }

        return 0;
    }

    /**
     * @param array $item
     * @param int   $index
     * @param int   $open_index
     * @return void
     */
    private function render_faq_item($item, $index, $open_index)
    {
        $question = $item['faq_question'] ?? '';
        $answer   = $item['faq_answer'] ?? '';

        if (!$question) {
            return;
        }

        $suffix      = $this->get_accordion_suffix($index);
        $heading_id  = 'heading' . $suffix;
        $collapse_id = 'collapse' . $suffix;
        $is_open     = ($index === $open_index);

        $button_class = 'accordion-button';
        $collapse_class = 'accordion-collapse collapse';

        if ($is_open) {
            $collapse_class .= ' show';
        } else {
            $button_class .= ' collapsed';
        }

        $aria_expanded = $is_open ? 'true' : 'false';
        $aria_labelledby = (0 === $index) ? 'headingTwo' : $heading_id;
        ?>
        <div class="accordion-item fade-top">
            <h2 class="accordion-header" id="<?php echo esc_attr($heading_id); ?>">
                <button class="<?php echo esc_attr($button_class); ?>" type="button" data-bs-toggle="collapse"
                    data-bs-target="#<?php echo esc_attr($collapse_id); ?>" aria-expanded="<?php echo esc_attr($aria_expanded); ?>"
                    aria-controls="<?php echo esc_attr($collapse_id); ?>">
                    <?php echo esc_html($question); ?>
                </button>
            </h2>
            <div id="<?php echo esc_attr($collapse_id); ?>" class="<?php echo esc_attr($collapse_class); ?>"
                aria-labelledby="<?php echo esc_attr($aria_labelledby); ?>" data-bs-parent="#accordionExampleTwo">
                <div class="accordion-body">
                    <?php echo $this->get_accordion_body_content($answer); ?>
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

        $subtitle  = $settings['gc_faq_sub_title'] ?? '';
        $title     = $settings['gc_faq_title'] ?? '';
        $items     = !empty($settings['gc_faq_items']) ? $settings['gc_faq_items'] : [];
        $image_url = $this->get_media_url($settings['gc_faq_image'] ?? [], 'new-update/faq-img-1.png');
        $open_index = $this->get_default_open_index($items);
        ?>

        <section class="faq-section pt-130 pb-130">
            <div class="container">
                <div class="row gy-xl-0 gy-5 align-items-center">
                    <div class="col-xl-6 col-lg-12 col-md-12">
                        <div class="faq-content">
                            <?php if ($subtitle || $title) : ?>
                                <div class="section-heading">
                                    <?php if ($subtitle) : ?>
                                        <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
                                    <?php endif; ?>
                                    <?php if ($title) : ?>
                                        <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($items)) : ?>
                                <div class="accordion fade-wrapper" id="accordionExampleTwo">
                                    <?php foreach ($items as $index => $item) {
                                        $this->render_faq_item($item, $index, $open_index);
                                    } ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12">
                        <div class="faq-img-1 ml-30 text-center">
                            <?php if ($image_url) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="img">
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Faq_Widget());
