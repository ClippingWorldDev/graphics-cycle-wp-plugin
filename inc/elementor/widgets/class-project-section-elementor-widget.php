<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Project_Section_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_project_section';
    }

    public function get_title()
    {
        return esc_html__('GC Project Section', 'softro-core');
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

    private function get_link_attributes($link_settings)
    {
        $url = !empty($link_settings['url']) ? $link_settings['url'] : '#';

        $attributes = ['href' => esc_url($url)];

        if (!empty($link_settings['is_external'])) {
            $attributes['target'] = '_blank';
        }

        if (!empty($link_settings['nofollow'])) {
            $attributes['rel'] = 'nofollow';
        }

        if (!empty($link_settings['custom_attributes'])) {
            foreach (Utils::parse_custom_attributes($link_settings['custom_attributes']) as $key => $value) {
                $attributes[$key] = $value;
            }
        }

        $html = '';

        foreach ($attributes as $key => $value) {
            $html .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }

        return $html;
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

    private function render_project_btn_icon(array $item, array $settings)
    {
        if (!empty($item['project_btn_icon']['value'])) {
            $this->render_icon($item['project_btn_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($item['project_btn_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_project_sec_default_btn_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true">';
            return;
        }

        if (!empty($settings['gc_project_sec_default_btn_icon']['value'])) {
            $this->render_icon($settings['gc_project_sec_default_btn_icon'], ['aria-hidden' => 'true']);
        } else {
            echo '<i class="fa-light fa-arrow-right-long" aria-hidden="true"></i>';
        }
    }

    private function get_default_projects()
    {
        return [
            [
                'project_category' => esc_html__('Team Members', 'softro-core'),
                'project_title'    => esc_html__('Advertisement Design', 'softro-core'),
                'project_link'     => ['url' => '#'],
                'project_image'    => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
            ],
            [
                'project_category' => esc_html__('Team Members', 'softro-core'),
                'project_title'    => esc_html__('Web Application Design', 'softro-core'),
                'project_link'     => ['url' => '#'],
                'project_image'    => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
            ],
            [
                'project_category' => esc_html__('Team Members', 'softro-core'),
                'project_title'    => esc_html__('Advertisement Design', 'softro-core'),
                'project_link'     => ['url' => '#'],
                'project_image'    => ['url' => $this->get_theme_img_url('new-update/project-img-1.png')],
            ],
            [
                'project_category' => esc_html__('Team Members', 'softro-core'),
                'project_title'    => esc_html__('Web Application Design', 'softro-core'),
                'project_link'     => ['url' => '#'],
                'project_image'    => ['url' => $this->get_theme_img_url('new-update/project-img-2.png')],
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
        $this->start_controls_section('gc_project_sec_heading_section', [
            'label' => esc_html__('Section Heading', 'softro-core'),
        ]);

        $this->add_control('gc_project_sec_subtitle', [
            'label'       => esc_html__('Subtitle', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Recent Project', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_project_sec_title', [
            'label'   => esc_html__('Title', 'softro-core'),
            'type'    => Controls_Manager::WYSIWYG,
            'default' => '<p>' . esc_html__('Let\'s Look Our Recent', 'softro-core') . '<br>' . esc_html__('Project Gallery', 'softro-core') . '</p>',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_defaults_section', [
            'label' => esc_html__('Project Icon Defaults', 'softro-core'),
        ]);

        $this->add_control('gc_project_sec_default_btn_icon', [
            'label'   => esc_html__('Default Project Button Icon', 'softro-core'),
            'type'    => Controls_Manager::ICONS,
            'default' => [
                'value'   => 'fa-light fa-arrow-right-long',
                'library' => 'fa-light',
            ],
        ]);

        $this->add_control('gc_project_sec_default_btn_icon_image', [
            'label'       => esc_html__('Default Project Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $this->add_control('gc_project_sec_default_shape_image', [
            'label'       => esc_html__('Default Thumb Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a background image on the project thumb shape layer.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $project_repeater = new Repeater();

        $project_repeater->add_control('project_image', [
            'label'       => esc_html__('Project Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $project_repeater->add_control('project_image_alt', [
            'label'       => esc_html__('Image Alt Text', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('project', 'softro-core'),
            'label_block' => true,
        ]);

        $project_repeater->add_control('project_shape_image', [
            'label'       => esc_html__('Thumb Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $project_repeater->add_control('project_link', [
            'label'       => esc_html__('Project Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
            'default'     => ['url' => '#'],
        ]);

        $project_repeater->add_control('project_btn_icon', [
            'label' => esc_html__('Button Icon', 'softro-core'),
            'type'  => Controls_Manager::ICONS,
        ]);

        $project_repeater->add_control('project_btn_icon_image', [
            'label'       => esc_html__('Button Icon Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
        ]);

        $project_repeater->add_control('project_category', [
            'label'       => esc_html__('Category Label', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Team Members', 'softro-core'),
            'label_block' => true,
        ]);

        $project_repeater->add_control('project_title', [
            'label'       => esc_html__('Project Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Advertisement Design', 'softro-core'),
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_project_sec_projects_section', [
            'label' => esc_html__('Project Items', 'softro-core'),
        ]);

        $this->add_control('gc_project_sec_projects', [
            'label'       => esc_html__('Projects', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $project_repeater->get_controls(),
            'default'     => $this->get_default_projects(),
            'title_field' => '{{{ project_title }}}',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_shape_section', [
            'label' => esc_html__('Section Shape', 'softro-core'),
        ]);

        $this->add_control('gc_project_sec_section_shape', [
            'label'       => esc_html__('Section Shape Image', 'softro-core'),
            'type'        => Controls_Manager::MEDIA,
            'media_types' => ['image'],
            'description' => esc_html__('Applied as a CSS background image on the section.', 'softro-core'),
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_project_sec_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__('Yes', 'softro-core'),
            'label_off'    => esc_html__('No', 'softro-core'),
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_project_sec_section_background',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .project-section.project-11',
        ]);

        $this->add_responsive_control('gc_project_sec_section_padding', [
            'label'      => esc_html__('Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-section.project-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_project_sec_section_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-section.project-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_heading_wrap', [
            'label' => esc_html__('Heading Wrap', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_project_sec_heading_wrap_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-top.heading-space' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_project_sec_heading_wrap_align', [
            'label'     => esc_html__('Alignment', 'softro-core'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'flex-start' => [
                    'title' => esc_html__('Left', 'softro-core'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center'     => [
                    'title' => esc_html__('Center', 'softro-core'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'flex-end'   => [
                    'title' => esc_html__('Right', 'softro-core'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'selectors' => ['{{WRAPPER}} .project-top.heading-space' => 'justify-content: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_subtitle', [
            'label' => esc_html__('Subtitle', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_subtitle_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .project-top .section-heading .sub-heading' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_project_sec_subtitle_typography',
            'selector' => '{{WRAPPER}} .project-top .section-heading .sub-heading',
        ]);

        $this->add_responsive_control('gc_project_sec_subtitle_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-top .section-heading .sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .project-top .section-heading .section-title' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_project_sec_title_typography',
            'selector' => '{{WRAPPER}} .project-top .section-heading .section-title',
        ]);

        $this->add_responsive_control('gc_project_sec_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-top .section-heading .section-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_project_sec_heading_text_align', [
            'label'     => esc_html__('Text Align', 'softro-core'),
            'type'      => Controls_Manager::CHOOSE,
            'options'   => [
                'left'   => [
                    'title' => esc_html__('Left', 'softro-core'),
                    'icon'  => 'eicon-text-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'softro-core'),
                    'icon'  => 'eicon-text-align-center',
                ],
                'right'  => [
                    'title' => esc_html__('Right', 'softro-core'),
                    'icon'  => 'eicon-text-align-right',
                ],
            ],
            'default'   => 'center',
            'selectors' => ['{{WRAPPER}} .project-top .section-heading' => 'text-align: {{VALUE}}; width: 100%;'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_card', [
            'label' => esc_html__('Project Card', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_project_sec_thumb_height', [
            'label'      => esc_html__('Thumb Height', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 200, 'max' => 800]],
            'selectors'  => ['{{WRAPPER}} .project-item-2 .project-thumb' => 'height: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_project_sec_row_spacing', [
            'label'      => esc_html__('Row Spacing', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 0, 'max' => 80]],
            'selectors'  => ['{{WRAPPER}} .project-item-wrapper + .project-item-wrapper' => 'margin-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_shape', [
            'label' => esc_html__('Thumb Shape Layer', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_shape_bg', [
            'label'     => esc_html__('Background Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .project-item-2 .project-thumb .shape' => 'background-color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_project_sec_shape_opacity', [
            'label'     => esc_html__('Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .project-item-2 .project-thumb .shape' => 'opacity: {{SIZE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_btn', [
            'label' => esc_html__('Project Button', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_btn_color', [
            'label'     => esc_html__('Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .project-item-2 > .project-btn i'   => 'color: {{VALUE}};',
                '{{WRAPPER}} .project-item-2 > .project-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ],
        ]);

        $this->add_responsive_control('gc_project_sec_btn_icon_size', [
            'label'      => esc_html__('Icon Size', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range'      => ['px' => ['min' => 10, 'max' => 60]],
            'selectors'  => [
                '{{WRAPPER}} .project-item-2 > .project-btn i'   => 'font-size: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .project-item-2 > .project-btn svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .project-item-2 > .project-btn img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
            ],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_project_sec_style_content', [
            'label' => esc_html__('Project Content', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_project_sec_category_color', [
            'label'     => esc_html__('Category Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .project-box .project-content span' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('gc_project_sec_project_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .project-box .project-content .title a' => 'color: {{VALUE}};'],
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_project_sec_category_typography',
            'selector' => '{{WRAPPER}} .project-box .project-content span',
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_project_sec_project_title_typography',
            'selector' => '{{WRAPPER}} .project-box .project-content .title',
        ]);

        $this->add_responsive_control('gc_project_sec_content_padding', [
            'label'      => esc_html__('Content Offset', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .project-box .project-content' => 'bottom: {{BOTTOM}}{{UNIT}}; left: {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_project_sec_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_project_sec_theme_mode_tabs');

        $this->start_controls_tab('gc_project_sec_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_project_sec_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .project-section.project-11.bg-dark-1',
        ]);

        $this->add_control('gc_project_sec_dark_subtitle_color', [
            'label'     => esc_html__('Subtitle Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.project-top .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.project-top .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_dark_category_color', [
            'label'     => esc_html__('Category Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.project-box .project-content span' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_dark_project_title_color', [
            'label'     => esc_html__('Project Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.project-box .project-content .title a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_dark_btn_color', [
            'label'     => esc_html__('Button Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', [
                '.project-item-2 > .project-btn i'   => 'color: {{VALUE}};',
                '.project-item-2 > .project-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_project_sec_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_project_sec_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .project-section.project-11.bg-dark-1',
        ]);

        $this->add_control('gc_project_sec_light_subtitle_color', [
            'label'     => esc_html__('Subtitle Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.project-top .section-heading .sub-heading' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.project-top .section-heading .section-title' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_light_category_color', [
            'label'     => esc_html__('Category Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.project-box .project-content span' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_light_project_title_color', [
            'label'     => esc_html__('Project Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.project-box .project-content .title a' => 'color: {{VALUE}};']),
        ]);

        $this->add_control('gc_project_sec_light_btn_color', [
            'label'     => esc_html__('Button Icon Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', [
                '.project-item-2 > .project-btn i'   => 'color: {{VALUE}};',
                '.project-item-2 > .project-btn svg' => 'fill: {{VALUE}}; color: {{VALUE}};',
            ]),
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if (empty($settings['gc_project_sec_reset_elementor_spacing']) || 'yes' !== $settings['gc_project_sec_reset_elementor_spacing']) {
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

    private function render_section_shape_background($settings)
    {
        $shape_url = $this->get_media_url($settings['gc_project_sec_section_shape'] ?? [], '');

        if (!$shape_url) {
            return;
        }
        ?>
        <style>
            .elementor-element-<?php echo esc_attr($this->get_id()); ?> .project-section.project-11 {
                background-image: url('<?php echo esc_url($shape_url); ?>');
                background-repeat: no-repeat;
                background-position: center;
            }
        </style>
        <?php
    }

    private function get_shape_style(array $item, array $settings)
    {
        $shape_url = $this->get_media_url($item['project_shape_image'] ?? [], '');

        if (!$shape_url) {
            $shape_url = $this->get_media_url($settings['gc_project_sec_default_shape_image'] ?? [], '');
        }

        if (!$shape_url) {
            return '';
        }

        return ' style="background-image:url(' . esc_url($shape_url) . '); background-size:cover; background-position:center;"';
    }

    private function render_project_card(array $item, array $settings)
    {
        $image_url = $this->get_media_url($item['project_image'] ?? [], 'new-update/project-img-1.png');
        $image_alt = !empty($item['project_image_alt']) ? $item['project_image_alt'] : esc_attr__('project', 'softro-core');
        $link      = $item['project_link'] ?? ['url' => '#'];
        $category  = $item['project_category'] ?? '';
        $title     = $item['project_title'] ?? '';
        $shape_attr = $this->get_shape_style($item, $settings);
        ?>
        <div class="col-lg-6">
            <div class="project-box project-item-2">
                <div class="project-thumb">
                    <div class="shape"<?php echo $shape_attr; ?>></div>
                    <?php if ($image_url) : ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
                    <?php endif; ?>
                </div>
                <a<?php echo $this->get_link_attributes($link); ?> class="project-btn"><?php $this->render_project_btn_icon($item, $settings); ?></a>
                <div class="project-content">
                    <?php if ('' !== $category) : ?>
                        <span><?php echo esc_html($category); ?></span>
                    <?php endif; ?>
                    <?php if ('' !== $title) : ?>
                        <h3 class="title"><a<?php echo $this->get_link_attributes($link); ?>><?php echo esc_html($title); ?></a></h3>
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
        $this->render_section_shape_background($settings);

        $subtitle = $settings['gc_project_sec_subtitle'] ?? '';
        $title    = $this->get_paragraph_inner_content($settings['gc_project_sec_title'] ?? '');
        $projects = !empty($settings['gc_project_sec_projects']) ? $settings['gc_project_sec_projects'] : $this->get_default_projects();
        $rows     = array_chunk($projects, 2);

        ?>
        <section class="project-section project-11 pt-130 pb-130 bg-dark-1 overflow-hidden">
            <div class="container">
                <div class="project-top heading-space fade-wrapper">
                    <div class="section-heading mb-0 text-center">
                        <?php if ('' !== trim((string) $subtitle)) : ?>
                            <h4 class="sub-heading after-none" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
                        <?php endif; ?>
                        <?php if ('' !== $title) : ?>
                            <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo wp_kses($title, ['br' => []]); ?></h2>
                        <?php endif; ?>
                    </div>
                    <!-- <div class="project-btn fade-top">
                            <a href="project.html" class="rr-primary-btn">View More Project <i
                                    class="fa-sharp fa-regular fa-arrow-right"></i></a>
                        </div> -->
                </div>
                <?php foreach ($rows as $row_index => $row_items) : ?>
                    <div class="row project-item-wrapper gy-lg-0 gy-4">
                        <?php foreach ($row_items as $item) : ?>
                            <?php $this->render_project_card($item, $settings); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Project_Section_Widget());
