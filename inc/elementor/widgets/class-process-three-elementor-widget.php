<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Process_Three_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_process_three';
    }

    public function get_title()
    {
        return esc_html__('GC Process Three', 'softro-core');
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

    private function normalize_fontawesome_class($icon_value)
    {
        $icon_value = trim((string) $icon_value);

        if ('' === $icon_value) {
            return '';
        }

        $replacements = [
            'fas ' => 'fa-solid ',
            'far ' => 'fa-regular ',
            'fab ' => 'fa-brands ',
            'fal ' => 'fa-light ',
            'fat ' => 'fa-thin ',
        ];

        foreach ($replacements as $search => $replace) {
            if (0 === strpos($icon_value, $search)) {
                return $replace . substr($icon_value, strlen($search));
            }
        }

        return $icon_value;
    }

    private function should_use_elementor_icon_renderer($icon_settings)
    {
        if (empty($icon_settings['value'])) {
            return false;
        }

        $library = $icon_settings['library'] ?? '';

        return 'eicons' === $library || 'svg' === $library || false !== strpos($library, 'svg');
    }

    private function render_icon($icon_settings, $args = [])
    {
        if (empty($icon_settings['value'])) {
            return;
        }

        $args = wp_parse_args($args, ['aria-hidden' => 'true']);

        if ($this->should_use_elementor_icon_renderer($icon_settings)) {
            Icons_Manager::render_icon($icon_settings, $args);
            return;
        }

        $icon_class = $this->normalize_fontawesome_class($icon_settings['value']);

        if ('' === $icon_class) {
            return;
        }

        $attributes = '';

        foreach ($args as $key => $value) {
            $attributes .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        echo '<i class="' . esc_attr($icon_class) . '"' . $attributes . '></i>';
    }

    private function get_default_steps()
    {
        $steps = [
            ['title' => esc_html__('Project Processing', 'softro-core'), 'dark' => 'icon/process-1.png', 'light' => 'icon/process-1-light.png'],
            ['title' => esc_html__('High Quality Products', 'softro-core'), 'dark' => 'icon/process-2.png', 'light' => 'icon/process-2-light.png'],
            ['title' => esc_html__('Huge Choice Products', 'softro-core'), 'dark' => 'icon/process-3.png', 'light' => 'icon/process-3-light.png'],
            ['title' => esc_html__('Quality Finished', 'softro-core'), 'dark' => 'icon/process-4.png', 'light' => 'icon/process-4-light.png'],
        ];

        $output = [];

        foreach ($steps as $step) {
            $output[] = [
                'step_icon_dark'       => ['url' => $this->get_theme_img_url($step['dark'])],
                'step_icon_light'      => ['url' => $this->get_theme_img_url($step['light'])],
                'step_title'           => $step['title'],
                'step_description'     => esc_html__('Cursus euismod dictumst a non dis nisi sociosqu mauris.', 'softro-core'),
            ];
        }

        return $output;
    }

    protected function register_controls()
    {
        $this->register_content_controls();
        $this->register_style_controls();
    }

    private function register_content_controls()
    {
        $this->start_controls_section('gc_process_three_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_process_three_eyebrow', [
            'label'       => esc_html__('Eyebrow', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Work Process', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_process_three_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Follow 4 Easy Work Steps', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $step_repeater = new Repeater();

        $step_repeater->add_control('step_icon_dark', [
            'label'   => esc_html__('Dark Mode Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('icon/process-1.png')],
        ]);

        $step_repeater->add_control('step_icon_light', [
            'label'   => esc_html__('Light Mode Icon Image', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('icon/process-1-light.png')],
        ]);

        $step_repeater->add_control('step_icon', [
            'label'       => esc_html__('Fallback Icon', 'softro-core'),
            'description' => esc_html__('Used when icon images are not set.', 'softro-core'),
            'type'        => Controls_Manager::ICONS,
            'default'     => ['value' => 'fa-light fa-gears', 'library' => 'fa-light'],
        ]);

        $step_repeater->add_control('step_icon_image', [
            'label' => esc_html__('Fallback Icon Image', 'softro-core'),
            'type'  => Controls_Manager::MEDIA,
        ]);

        $step_repeater->add_control('step_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Project Processing', 'softro-core'),
            'label_block' => true,
        ]);

        $step_repeater->add_control('step_description', [
            'label'   => esc_html__('Description', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => esc_html__('Cursus euismod dictumst a non dis nisi sociosqu mauris.', 'softro-core'),
        ]);

        $this->start_controls_section('gc_process_three_steps_section', [
            'label' => esc_html__('Process Steps', 'softro-core'),
        ]);

        $this->add_control('gc_process_three_default_icon', [
            'label'   => esc_html__('Default Fallback Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-gears', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_process_three_steps', [
            'label'       => esc_html__('Steps', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $step_repeater->get_controls(),
            'default'     => $this->get_default_steps(),
            'title_field' => '{{{ step_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_process_three_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_process_three_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_process_three_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_three_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_three_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_three_row_gap', [
            'label'      => esc_html__('Row Gap', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process-row' => 'row-gap: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_three_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_three_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .gc-video-3d-process',
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'      => 'gc_process_three_section_overlay',
            'label'     => esc_html__('Section Shape / Overlay', 'softro-core'),
            'types'     => ['classic', 'gradient'],
            'selector'  => '{{WRAPPER}} .gc-video-3d-process::before',
            'separator' => 'before',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_three_style_header', [
            'label' => esc_html__('Header', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_process_three_heading_margin', [
            'label'      => esc_html__('Heading Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_process_three_eyebrow_typography',
            'label'    => esc_html__('Eyebrow Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-process-eyebrow',
        ]);

        $this->add_control('gc_process_three_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-eyebrow' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_process_three_title_typography',
            'label'     => esc_html__('Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-process-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_process_three_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-title' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_process_three_style_step', [
            'label' => esc_html__('Process Step', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_process_three_icon_wrap_height', [
            'label'      => esc_html__('Icon Wrap Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process-icon' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_three_icon_circle_size', [
            'label'      => esc_html__('Icon Circle Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => ['{{WRAPPER}} .gc-video-3d-process-icon-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_process_three_icon_image_size', [
            'label'      => esc_html__('Icon Image Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'selectors'  => [
                '{{WRAPPER}} .gc-video-3d-process-icon-circle img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                '{{WRAPPER}} .gc-video-3d-process-icon-circle i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .gc-video-3d-process-icon-circle svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
            ],
        ]);

        $this->add_control('gc_process_three_icon_circle_bg', [
            'label'     => esc_html__('Icon Circle Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-icon-circle' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_control('gc_process_three_icon_arc_color', [
            'label'     => esc_html__('Icon Arc Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-icon-arc' => 'border-color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'      => 'gc_process_three_step_title_typography',
            'label'     => esc_html__('Step Title Typography', 'softro-core'),
            'selector'  => '{{WRAPPER}} .gc-video-3d-process-step-title',
            'separator' => 'before',
        ]);

        $this->add_control('gc_process_three_step_title_color', [
            'label'     => esc_html__('Step Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-step-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_process_three_step_desc_typography',
            'label'    => esc_html__('Step Description Typography', 'softro-core'),
            'selector' => '{{WRAPPER}} .gc-video-3d-process-copy p',
        ]);

        $this->add_control('gc_process_three_step_desc_color', [
            'label'     => esc_html__('Step Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .gc-video-3d-process-copy p' => 'color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_process_three_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_process_three_theme_mode_tabs');

        $this->start_controls_tab('gc_process_three_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_three_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .gc-video-3d-process',
        ]);

        $this->add_control('gc_process_three_dark_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-process-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-process-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_dark_step_title_color', [
            'label'     => esc_html__('Step Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-process-step-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_dark_step_desc_color', [
            'label'     => esc_html__('Step Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-process-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_dark_icon_circle_bg', [
            'label'     => esc_html__('Icon Circle Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-video-3d-process-icon-circle' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_process_three_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_process_three_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .gc-video-3d-process',
        ]);

        $this->add_control('gc_process_three_light_eyebrow_color', [
            'label'     => esc_html__('Eyebrow Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-process-eyebrow' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-process-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_light_step_title_color', [
            'label'     => esc_html__('Step Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-process-step-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_light_step_desc_color', [
            'label'     => esc_html__('Step Description Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-process-copy p' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_process_three_light_icon_circle_bg', [
            'label'     => esc_html__('Icon Circle Background', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.gc-video-3d-process-icon-circle' => 'background-color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_process_three_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_step_icon_circle($step, $settings)
    {
        $dark_url  = $this->get_media_url($step['step_icon_dark'] ?? [], '');
        $light_url = $this->get_media_url($step['step_icon_light'] ?? [], '');

        if ($dark_url || $light_url) {
            if ($dark_url) {
                echo '<img class="dark-img" src="' . esc_url($dark_url) . '" alt="">';
            }
            if ($light_url) {
                echo '<img class="light-img" src="' . esc_url($light_url) . '" alt="">';
            }
            return;
        }

        $fallback_image = $this->get_media_url($step['step_icon_image'] ?? [], '');

        if ($fallback_image) {
            echo '<img class="dark-img" src="' . esc_url($fallback_image) . '" alt="">';
            echo '<img class="light-img" src="' . esc_url($fallback_image) . '" alt="">';
            return;
        }

        if (!empty($step['step_icon']['value'])) {
            $this->render_icon($step['step_icon'], ['aria-hidden' => 'true']);
            return;
        }

        if (!empty($settings['gc_process_three_default_icon']['value'])) {
            $this->render_icon($settings['gc_process_three_default_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_process_step($step, $settings)
    {
        $title = $step['step_title'] ?? '';
        $desc  = $step['step_description'] ?? '';

        if (!$title && !$desc) {
            return;
        }
        ?>
        <div class="col-lg-3 col-md-6">
            <div class="gc-video-3d-process-step fade-top">
                <div class="gc-video-3d-process-icon">
                    <span class="gc-video-3d-process-icon-arc" aria-hidden="true"></span>
                    <span class="gc-video-3d-process-icon-circle">
                        <?php $this->render_step_icon_circle($step, $settings); ?>
                    </span>
                </div>
                <div class="gc-video-3d-process-copy">
                    <?php if ($title) : ?>
                        <h3 class="gc-video-3d-process-step-title"><?php echo esc_html($title); ?></h3>
                    <?php endif; ?>
                    <?php if ($desc) : ?>
                        <p><?php echo $this->get_paragraph_inner_content($desc); ?></p>
                    <?php endif; ?>
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

        $eyebrow = $settings['gc_process_three_eyebrow'] ?? '';
        $title   = $settings['gc_process_three_title'] ?? '';
        $steps   = !empty($settings['gc_process_three_steps']) ? $settings['gc_process_three_steps'] : [];
        ?>

        <section class="gc-video-3d-process pt-130 pb-130 fade-wrapper">
            <div class="container">
                <div class="section-heading text-center gc-video-3d-process-heading">
                    <?php if ($eyebrow) : ?>
                        <span class="gc-video-3d-process-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>
                    <?php if ($title) : ?>
                        <h2 class="gc-video-3d-process-title overflow-hidden" data-text-animation data-split="word" data-duration="1"><?php echo esc_html($title); ?></h2>
                    <?php endif; ?>
                </div>
                <?php if (!empty($steps)) : ?>
                    <div class="row gy-lg-0 gy-5 gc-video-3d-process-row">
                        <?php foreach ($steps as $step) {
                            $this->render_process_step($step, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Process_Three_Widget());
