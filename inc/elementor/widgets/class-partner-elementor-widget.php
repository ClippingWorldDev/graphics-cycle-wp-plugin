<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Partner_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_partner';
    }

    public function get_title()
    {
        return esc_html__('GC Partner', 'softro-core');
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

    private function get_default_partners()
    {
        $output = [];

        for ($i = 1; $i <= 6; $i++) {
            $output[] = [
                'partner_logo'     => ['url' => $this->get_theme_img_url('sponsor/sponsor-img-' . $i . '.png')],
                'partner_logo_alt' => esc_html__('sponsor', 'softro-core'),
                'partner_link'     => ['url' => '#'],
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
        $this->start_controls_section('gc_partner_header_section', [
            'label' => esc_html__('Section Header', 'softro-core'),
        ]);

        $this->add_control('gc_partner_section_id', [
            'label'       => esc_html__('Section ID', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => 'section1',
            'label_block' => true,
        ]);

        $this->add_control('gc_partner_title', [
            'label'       => esc_html__('Title', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('Trusted by Over 450 Businesses Around the World', 'softro-core'),
            'label_block' => true,
        ]);

        $this->end_controls_section();

        $partner_repeater = new Repeater();

        $partner_repeater->add_control('partner_logo', [
            'label'   => esc_html__('Logo', 'softro-core'),
            'type'    => Controls_Manager::MEDIA,
            'default' => ['url' => $this->get_theme_img_url('sponsor/sponsor-img-1.png')],
        ]);

        $partner_repeater->add_control('partner_logo_alt', [
            'label'       => esc_html__('Logo Alt', 'softro-core'),
            'type'        => Controls_Manager::TEXT,
            'default'     => esc_html__('sponsor', 'softro-core'),
            'label_block' => true,
        ]);

        $partner_repeater->add_control('partner_link', [
            'label'       => esc_html__('Link', 'softro-core'),
            'type'        => Controls_Manager::URL,
            'default'     => ['url' => '#'],
            'label_block' => true,
        ]);

        $this->start_controls_section('gc_partner_logos_section', [
            'label' => esc_html__('Partner Logos', 'softro-core'),
        ]);

        $this->add_control('gc_partner_items', [
            'label'       => esc_html__('Partners', 'softro-core'),
            'type'        => Controls_Manager::REPEATER,
            'fields'      => $partner_repeater->get_controls(),
            'default'     => $this->get_default_partners(),
            'title_field' => '{{{ partner_logo_alt }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_partner_style_layout', [
            'label' => esc_html__('Layout', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_control('gc_partner_reset_elementor_spacing', [
            'label'        => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'),
            'type'         => Controls_Manager::SWITCHER,
            'return_value' => 'yes',
            'default'      => 'yes',
        ]);

        $this->add_responsive_control('gc_partner_section_padding_top', [
            'label'      => esc_html__('Section Top Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 130, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .sponsor-section' => 'padding-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_partner_section_padding_bottom', [
            'label'      => esc_html__('Section Bottom Padding', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em'],
            'default'    => ['size' => 110, 'unit' => 'px'],
            'selectors'  => ['{{WRAPPER}} .sponsor-section' => 'padding-bottom: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_partner_section_margin', [
            'label'      => esc_html__('Section Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .sponsor-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_partner_style_section', [
            'label' => esc_html__('Section', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_partner_section_bg',
            'types'    => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .sponsor-section',
        ]);

        $this->add_control('gc_partner_section_border_bottom_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .sponsor-section' => 'border-bottom-color: {{VALUE}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_partner_style_title', [
            'label' => esc_html__('Title', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_group_control(Group_Control_Typography::get_type(), [
            'name'     => 'gc_partner_title_typography',
            'selector' => '{{WRAPPER}} .sponsor-text .title',
        ]);

        $this->add_control('gc_partner_title_color', [
            'label'     => esc_html__('Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .sponsor-text .title' => 'color: {{VALUE}};'],
        ]);

        $this->add_responsive_control('gc_partner_title_margin', [
            'label'      => esc_html__('Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .sponsor-text .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_partner_header_margin', [
            'label'      => esc_html__('Header Wrap Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .sponsor-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->end_controls_section();

        $this->start_controls_section('gc_partner_style_carousel', [
            'label' => esc_html__('Carousel', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->add_responsive_control('gc_partner_carousel_margin', [
            'label'      => esc_html__('Carousel Margin', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .sponsor-carousel-2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_partner_item_padding', [
            'label'      => esc_html__('Item Padding', 'softro-core'),
            'type'       => Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%', 'em'],
            'selectors'  => ['{{WRAPPER}} .sponsor-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_responsive_control('gc_partner_logo_width', [
            'label'      => esc_html__('Logo Width', 'softro-core'),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => ['px', '%'],
            'selectors'  => ['{{WRAPPER}} .sponsor-carousel-2 .sponsor-item img' => 'width: {{SIZE}}{{UNIT}}; height: auto;'],
        ]);

        $this->add_control('gc_partner_logo_opacity', [
            'label'     => esc_html__('Logo Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .sponsor-carousel-2 .sponsor-item img' => 'opacity: {{SIZE}};'],
        ]);

        $this->add_control('gc_partner_logo_hover_opacity', [
            'label'     => esc_html__('Logo Hover Opacity', 'softro-core'),
            'type'      => Controls_Manager::SLIDER,
            'range'     => ['px' => ['min' => 0, 'max' => 1, 'step' => 0.01]],
            'selectors' => ['{{WRAPPER}} .sponsor-carousel-2 .sponsor-item a:hover img' => 'opacity: {{SIZE}};'],
        ]);

        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_partner_style_theme_mode', [
            'label' => esc_html__('Dark / Light Mode Colors', 'softro-core'),
            'tab'   => Controls_Manager::TAB_STYLE,
        ]);

        $this->start_controls_tabs('gc_partner_theme_mode_tabs');

        $this->start_controls_tab('gc_partner_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_partner_dark_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=dark] {{WRAPPER}} .sponsor-section',
        ]);

        $this->add_control('gc_partner_dark_section_border_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.sponsor-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_partner_dark_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('dark', ['.sponsor-text .title' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('gc_partner_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);

        $this->add_group_control(Group_Control_Background::get_type(), [
            'name'     => 'gc_partner_light_section_bg',
            'label'    => esc_html__('Section Background', 'softro-core'),
            'types'    => ['classic', 'gradient'],
            'selector' => '[data-theme=light] {{WRAPPER}} .sponsor-section',
        ]);

        $this->add_control('gc_partner_light_section_border_color', [
            'label'     => esc_html__('Bottom Border Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.sponsor-section' => 'border-bottom-color: {{VALUE}};']),
        ]);

        $this->add_control('gc_partner_light_title_color', [
            'label'     => esc_html__('Title Color', 'softro-core'),
            'type'      => Controls_Manager::COLOR,
            'selectors' => $this->get_theme_mode_selectors('light', ['.sponsor-text .title' => 'color: {{VALUE}};']),
        ]);

        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_partner_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_partner_slide($partner)
    {
        $logo = $this->get_media_url($partner['partner_logo'] ?? [], 'sponsor/sponsor-img-1.png');
        $alt  = $partner['partner_logo_alt'] ?? esc_html__('sponsor', 'softro-core');
        $link = $partner['partner_link'] ?? [];

        if (!$logo) {
            return;
        }
        ?>
        <div class="swiper-slide">
            <div class="sponsor-item text-center">
                <a<?php echo $this->get_link_attributes($link); ?>>
                    <img src="<?php echo esc_url($logo); ?>" alt="<?php echo esc_attr($alt); ?>">
                </a>
            </div>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);

        $section_id = $settings['gc_partner_section_id'] ?? 'section1';
        $title      = $settings['gc_partner_title'] ?? '';
        $partners   = !empty($settings['gc_partner_items']) ? $settings['gc_partner_items'] : [];
        ?>

        <section id="<?php echo esc_attr($section_id); ?>" class="sponsor-section p-relative z-1 pt-130 pb-110">
            <div class="container">
                <?php if ($title) : ?>
                    <div class="sponsor-text text-center">
                        <h4 class="title mb-50"><?php echo esc_html($title); ?></h4>
                    </div>
                <?php endif; ?>

                <?php if (!empty($partners)) : ?>
                    <div class="sponsor-carousel-2 swiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($partners as $partner) {
                                $this->render_partner_slide($partner);
                            } ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Partner_Widget());
