<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_Service_Web_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_service_web';
    }

    public function get_title()
    {
        return esc_html__('GC Service Web', 'softro-core');
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

    private function get_media_url($media, $fallback_path = '')
    {
        if (!empty($media['url'])) {
            return esc_url($media['url']);
        }

        if ($fallback_path) {
            return esc_url(get_template_directory_uri() . '/assets/img/' . ltrim($fallback_path, '/'));
        }

        return '';
    }

    private function render_card_icon($card, $settings)
    {
        if (!empty($card['card_icon']['value'])) {
            $this->render_icon($card['card_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($card['card_icon_image'] ?? [], '');

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_service_web_default_card_icon']['value'])) {
            $this->render_icon($settings['gc_service_web_default_card_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_list_icon($feature, $settings)
    {
        if (!empty($feature['feature_icon']['value'])) {
            $this->render_icon($feature['feature_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($feature['feature_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_service_web_default_list_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_service_web_default_list_icon']['value'])) {
            $this->render_icon($settings['gc_service_web_default_list_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function render_link_icon($card, $settings)
    {
        if (!empty($card['link_icon']['value'])) {
            $this->render_icon($card['link_icon'], ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($card['link_icon_image'] ?? [], '');

        if (!$icon_url) {
            $icon_url = $this->get_media_url($settings['gc_service_web_default_link_icon_image'] ?? [], '');
        }

        if ($icon_url) {
            echo '<i><img src="' . esc_url($icon_url) . '" alt="" aria-hidden="true"></i>';
            return;
        }

        if (!empty($settings['gc_service_web_default_link_icon']['value'])) {
            $this->render_icon($settings['gc_service_web_default_link_icon'], ['aria-hidden' => 'true']);
        }
    }

    private function get_card_style_class($style)
    {
        $allowed = ['blue', 'green', 'orange', 'teal'];

        return in_array($style, $allowed, true) ? $style : 'blue';
    }

    private function get_default_feature($text)
    {
        return [
            'feature_text' => esc_html__($text, 'softro-core'),
            'feature_icon' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ];
    }

    private function get_default_cards()
    {
        $link_icon = ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'];

        return [
            [
                'card_style' => 'blue',
                'card_icon' => ['value' => 'fa-light fa-building', 'library' => 'fa-light'],
                'card_title' => esc_html__('Corporate Website Design', 'softro-core'),
                'card_description' => esc_html__('A professional digital headquarters that builds trust, communicates your brand value, and drives qualified leads from day one.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Custom multi-page design'),
                    $this->get_default_feature('About, services & team pages'),
                    $this->get_default_feature('Contact forms & lead capture'),
                    $this->get_default_feature('Core Web Vitals optimized'),
                    $this->get_default_feature('Google Analytics integration'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'green',
                'card_icon' => ['value' => 'fa-light fa-cart-shopping', 'library' => 'fa-light'],
                'card_title' => esc_html__('E-Commerce Website', 'softro-core'),
                'card_description' => esc_html__('Full-featured online stores built on WooCommerce or Shopify — optimized for product discovery, checkout conversions, and repeat sales.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Product catalogue & filters'),
                    $this->get_default_feature('Secure payment gateways'),
                    $this->get_default_feature('Cart & checkout optimization'),
                    $this->get_default_feature('Inventory management'),
                    $this->get_default_feature('Mobile-first shopping UX'),
                ],
                'link_text' => esc_html__('Start Business Website', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'orange',
                'card_icon' => ['value' => 'fa-light fa-graduation-cap', 'library' => 'fa-light'],
                'card_title' => esc_html__('School & College Website', 'softro-core'),
                'card_description' => esc_html__('Purpose-built education portals with admission forms, course listings, event calendars, and faculty directories that serve students, parents, and staff.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Admission & enquiry forms'),
                    $this->get_default_feature('Course & department pages'),
                    $this->get_default_feature('Events & news calendar'),
                    $this->get_default_feature('Photo & video gallery'),
                    $this->get_default_feature('Accessible design (WCAG)'),
                ],
                'link_text' => esc_html__('Create Education Portal', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'teal',
                'card_icon' => ['value' => 'fa-light fa-code', 'library' => 'fa-light'],
                'card_title' => esc_html__('Custom Software Development', 'softro-core'),
                'card_description' => esc_html__('Tailored web applications and internal tools designed around your workflows — scalable, secure, and built for long-term growth.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Custom dashboards & portals'),
                    $this->get_default_feature('API & third-party integrations'),
                    $this->get_default_feature('Role-based access control'),
                    $this->get_default_feature('Cloud-ready architecture'),
                    $this->get_default_feature('Ongoing feature updates'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'blue',
                'card_icon' => ['value' => 'fa-light fa-mobile-screen', 'library' => 'fa-light'],
                'card_title' => esc_html__('Mobile App Development', 'softro-core'),
                'card_description' => esc_html__('Native and cross-platform apps for iOS and Android that deliver smooth performance, intuitive UX, and seamless backend connectivity.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('iOS & Android builds'),
                    $this->get_default_feature('Cross-platform (React Native / Flutter)'),
                    $this->get_default_feature('Push notifications & analytics'),
                    $this->get_default_feature('App Store & Play Store launch'),
                    $this->get_default_feature('Post-launch maintenance'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'green',
                'card_icon' => ['value' => 'fa-light fa-pen-ruler', 'library' => 'fa-light'],
                'card_title' => esc_html__('UI/UX Design', 'softro-core'),
                'card_description' => esc_html__('Research-driven interface design that improves usability, reduces friction, and turns visitors into engaged users and paying customers.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Wireframes & prototypes'),
                    $this->get_default_feature('User journey mapping'),
                    $this->get_default_feature('Design systems & style guides'),
                    $this->get_default_feature('Mobile & desktop layouts'),
                    $this->get_default_feature('Developer-ready handoff files'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'orange',
                'card_icon' => ['value' => 'fa-brands fa-wordpress', 'library' => 'fa-brands'],
                'card_title' => esc_html__('WordPress & CMS Development', 'softro-core'),
                'card_description' => esc_html__('Flexible content-managed websites that your team can update easily — fast, SEO-friendly, and built with clean, maintainable code.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Custom WordPress themes'),
                    $this->get_default_feature('Plugin setup & customization'),
                    $this->get_default_feature('Easy content editing training'),
                    $this->get_default_feature('Security hardening & backups'),
                    $this->get_default_feature('Multilingual site support'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
            ],
            [
                'card_style' => 'teal',
                'card_icon' => ['value' => 'fa-light fa-screwdriver-wrench', 'library' => 'fa-light'],
                'card_title' => esc_html__('Website Maintenance & Support', 'softro-core'),
                'card_description' => esc_html__('Keep your site secure, fast, and up to date with proactive monitoring, regular updates, and reliable technical support when you need it.', 'softro-core'),
                'card_features' => [
                    $this->get_default_feature('Monthly updates & patches'),
                    $this->get_default_feature('Uptime & performance monitoring'),
                    $this->get_default_feature('Bug fixes & content changes'),
                    $this->get_default_feature('SSL & security scans'),
                    $this->get_default_feature('Priority support response'),
                ],
                'link_text' => esc_html__('Get started', 'softro-core'),
                'link_icon' => $link_icon,
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
        $this->start_controls_section('gc_service_web_header_section', ['label' => esc_html__('Section Header', 'softro-core')]);

        $this->add_control('gc_service_web_eyebrow', [
            'label' => esc_html__('Eyebrow', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Our Services', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_service_web_title_before', [
            'label' => esc_html__('Title (Before Accent)', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('End-to-end', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_service_web_title_accent', [
            'label' => esc_html__('Title Accent', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('web solutions', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_service_web_title_after', [
            'label' => esc_html__('Title (After Accent)', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('for every business', 'softro-core'),
            'label_block' => true,
        ]);

        $this->add_control('gc_service_web_intro', [
            'label' => esc_html__('Intro', 'softro-core'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => esc_html__('From corporate websites and online stores to custom software and ongoing support — we design, build, and maintain digital products that perform across devices and grow with your goals.', 'softro-core'),
        ]);

        $this->end_controls_section();

        $feature_repeater = new Repeater();

        $feature_repeater->add_control('feature_text', [
            'label' => esc_html__('Feature Text', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Custom multi-page design', 'softro-core'),
            'label_block' => true,
        ]);

        $feature_repeater->add_control('feature_icon', [
            'label' => esc_html__('Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-solid'],
        ]);

        $feature_repeater->add_control('feature_icon_image', [
            'label' => esc_html__('Custom Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $card_repeater = new Repeater();

        $card_repeater->add_control('card_style', [
            'label' => esc_html__('Card Style', 'softro-core'),
            'type' => Controls_Manager::SELECT,
            'default' => 'blue',
            'options' => [
                'blue' => esc_html__('Blue', 'softro-core'),
                'green' => esc_html__('Green', 'softro-core'),
                'orange' => esc_html__('Orange', 'softro-core'),
                'teal' => esc_html__('Teal', 'softro-core'),
            ],
        ]);

        $card_repeater->add_control('card_icon', [
            'label' => esc_html__('Card Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-building', 'library' => 'fa-light'],
        ]);

        $card_repeater->add_control('card_icon_image', [
            'label' => esc_html__('Card Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $card_repeater->add_control('card_title', [
            'label' => esc_html__('Card Title', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Corporate Website Design', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('card_description', [
            'label' => esc_html__('Card Description', 'softro-core'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => esc_html__('A professional digital headquarters that builds trust, communicates your brand value, and drives qualified leads from day one.', 'softro-core'),
        ]);

        $card_repeater->add_control('card_features', [
            'label' => esc_html__('Feature List', 'softro-core'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $feature_repeater->get_controls(),
            'default' => [
                ['feature_text' => esc_html__('Custom multi-page design', 'softro-core')],
                ['feature_text' => esc_html__('About, services & team pages', 'softro-core')],
                ['feature_text' => esc_html__('Contact forms & lead capture', 'softro-core')],
            ],
            'title_field' => '{{{ feature_text }}}',
        ]);

        $card_repeater->add_control('link_text', [
            'label' => esc_html__('Link Text', 'softro-core'),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__('Get started', 'softro-core'),
            'label_block' => true,
        ]);

        $card_repeater->add_control('link_url', [
            'label' => esc_html__('Link URL', 'softro-core'),
            'type' => Controls_Manager::URL,
            'default' => ['url' => '#'],
            'label_block' => true,
        ]);

        $card_repeater->add_control('link_icon', [
            'label' => esc_html__('Link Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => '', 'library' => 'fa-regular'],
        ]);

        $card_repeater->add_control('link_icon_image', [
            'label' => esc_html__('Link Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->start_controls_section('gc_service_web_cards_section', ['label' => esc_html__('Service Cards', 'softro-core')]);

        $this->add_control('gc_service_web_default_card_icon', [
            'label' => esc_html__('Default Card Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-light fa-building', 'library' => 'fa-light'],
        ]);

        $this->add_control('gc_service_web_default_list_icon', [
            'label' => esc_html__('Default List Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-solid fa-circle-check', 'library' => 'fa-solid'],
        ]);

        $this->add_control('gc_service_web_default_list_icon_image', [
            'label' => esc_html__('Default List Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_service_web_default_link_icon', [
            'label' => esc_html__('Default Link Icon', 'softro-core'),
            'type' => Controls_Manager::ICONS,
            'default' => ['value' => 'fa-regular fa-arrow-right', 'library' => 'fa-regular'],
        ]);

        $this->add_control('gc_service_web_default_link_icon_image', [
            'label' => esc_html__('Default Link Icon Image', 'softro-core'),
            'type' => Controls_Manager::MEDIA,
            'default' => ['url' => ''],
        ]);

        $this->add_control('gc_service_web_cards', [
            'label' => esc_html__('Cards', 'softro-core'),
            'type' => Controls_Manager::REPEATER,
            'fields' => $card_repeater->get_controls(),
            'default' => $this->get_default_cards(),
            'title_field' => '{{{ card_title }}}',
        ]);

        $this->end_controls_section();
    }

    private function register_style_controls()
    {
        $this->start_controls_section('gc_service_web_style_layout', ['label' => esc_html__('Layout', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_control('gc_service_web_reset_elementor_spacing', ['label' => esc_html__('Reset Elementor Wrapper Spacing', 'softro-core'), 'type' => Controls_Manager::SWITCHER, 'return_value' => 'yes', 'default' => 'yes']);
        $this->add_responsive_control('gc_service_web_section_padding_top', ['label' => esc_html__('Section Top Padding', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'default' => ['size' => 130, 'unit' => 'px'], 'selectors' => ['{{WRAPPER}} .gc-web-services-section' => 'padding-top: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('gc_service_web_section_padding_bottom', ['label' => esc_html__('Section Bottom Padding', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'default' => ['size' => 130, 'unit' => 'px'], 'selectors' => ['{{WRAPPER}} .gc-web-services-section' => 'padding-bottom: {{SIZE}}{{UNIT}};']]);
        $this->add_responsive_control('gc_service_web_section_margin', ['label' => esc_html__('Section Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-services-section' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_service_web_style_section', ['label' => esc_html__('Section', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_section_bg', 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} .gc-web-services-section']);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_section_overlay', 'label' => esc_html__('Section Overlay', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '{{WRAPPER}} .gc-web-services-section::before']);
        $this->end_controls_section();

        $this->start_controls_section('gc_service_web_style_header', ['label' => esc_html__('Header', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_eyebrow_typography', 'label' => esc_html__('Eyebrow Typography', 'softro-core'), 'selector' => '{{WRAPPER}} .gc-web-services-eyebrow']);
        $this->add_control('gc_service_web_eyebrow_color', ['label' => esc_html__('Eyebrow Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-services-eyebrow' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_eyebrow_margin', ['label' => esc_html__('Eyebrow Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-services-eyebrow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_title_typography', 'selector' => '{{WRAPPER}} .gc-web-services-title']);
        $this->add_control('gc_service_web_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-services-title' => 'color: {{VALUE}};']]);
        $this->add_control('gc_service_web_title_accent_color', ['label' => esc_html__('Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-services-accent' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_header_margin', ['label' => esc_html__('Header Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-services-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_intro_typography', 'selector' => '{{WRAPPER}} .gc-web-services-intro']);
        $this->add_control('gc_service_web_intro_color', ['label' => esc_html__('Intro Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-services-intro' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_intro_margin', ['label' => esc_html__('Intro Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-services-intro' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_service_web_style_card', ['label' => esc_html__('Service Card', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_responsive_control('gc_service_web_grid_gap', ['label' => esc_html__('Grid Gap', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px'], 'selectors' => ['{{WRAPPER}} .gc-web-services-grid' => 'gap: {{SIZE}}{{UNIT}};']]);
        $this->add_control('gc_service_web_card_bg', ['label' => esc_html__('Card Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card' => 'background-color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_card_padding', ['label' => esc_html__('Card Padding', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('gc_service_web_card_margin', ['label' => esc_html__('Card Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('gc_service_web_card_radius', ['label' => esc_html__('Card Border Radius', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_card_title_typography', 'selector' => '{{WRAPPER}} .gc-web-service-card-title']);
        $this->add_control('gc_service_web_card_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-title' => 'color: {{VALUE}};']]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_card_desc_typography', 'selector' => '{{WRAPPER}} .gc-web-service-card-desc']);
        $this->add_control('gc_service_web_card_desc_color', ['label' => esc_html__('Description Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-desc' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_card_desc_margin', ['label' => esc_html__('Description Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_control('gc_service_web_card_icon_color', ['label' => esc_html__('Card Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-icon i' => 'color: {{VALUE}};', '{{WRAPPER}} .gc-web-service-card-icon svg' => 'fill: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_card_icon_margin', ['label' => esc_html__('Card Icon Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->add_responsive_control('gc_service_web_card_icon_size', ['label' => esc_html__('Card Icon Size', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-icon i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_service_web_style_list', ['label' => esc_html__('Feature List', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_list_typography', 'selector' => '{{WRAPPER}} .gc-web-service-card-list li']);
        $this->add_control('gc_service_web_list_color', ['label' => esc_html__('Text Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-list li' => 'color: {{VALUE}};']]);
        $this->add_control('gc_service_web_list_icon_color', ['label' => esc_html__('Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-list li i' => 'color: {{VALUE}};', '{{WRAPPER}} .gc-web-service-card-list li svg' => 'fill: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_list_icon_size', ['label' => esc_html__('Icon Size', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-list li i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-list li svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-list li i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_service_web_list_item_spacing', ['label' => esc_html__('List Item Spacing', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->start_controls_section('gc_service_web_style_link', ['label' => esc_html__('Card Link', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->add_group_control(Group_Control_Typography::get_type(), ['name' => 'gc_service_web_link_typography', 'selector' => '{{WRAPPER}} .gc-web-service-card-link']);
        $this->add_control('gc_service_web_link_color', ['label' => esc_html__('Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-link' => 'color: {{VALUE}};']]);
        $this->add_control('gc_service_web_link_hover_color', ['label' => esc_html__('Hover Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => ['{{WRAPPER}} .gc-web-service-card-link:hover' => 'color: {{VALUE}};']]);
        $this->add_responsive_control('gc_service_web_link_icon_size', ['label' => esc_html__('Link Icon Size', 'softro-core'), 'type' => Controls_Manager::SLIDER, 'size_units' => ['px', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-link i' => 'font-size: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};', '{{WRAPPER}} .gc-web-service-card-link i img' => 'width: {{SIZE}}{{UNIT}}; height: auto;']]);
        $this->add_responsive_control('gc_service_web_link_margin', ['label' => esc_html__('Link Margin', 'softro-core'), 'type' => Controls_Manager::DIMENSIONS, 'size_units' => ['px', '%', 'em'], 'selectors' => ['{{WRAPPER}} .gc-web-service-card-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']]);
        $this->end_controls_section();

        $this->register_theme_mode_style_controls();
    }

    private function register_theme_mode_style_controls()
    {
        $this->start_controls_section('gc_service_web_style_theme_mode', ['label' => esc_html__('Dark / Light Mode Colors', 'softro-core'), 'tab' => Controls_Manager::TAB_STYLE]);
        $this->start_controls_tabs('gc_service_web_theme_mode_tabs');

        $this->start_controls_tab('gc_service_web_dark_tab', ['label' => esc_html__('Dark Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_dark_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=dark] {{WRAPPER}} .gc-web-services-section']);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_dark_section_overlay', 'label' => esc_html__('Section Overlay', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=dark] {{WRAPPER}} .gc-web-services-section::before']);
        $this->add_control('gc_service_web_dark_eyebrow_color', ['label' => esc_html__('Eyebrow Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-services-eyebrow' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-services-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_title_accent_color', ['label' => esc_html__('Title Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-services-accent' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_intro_color', ['label' => esc_html__('Intro Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-services-intro' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_card_bg', ['label' => esc_html__('Card Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card' => 'background-color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_card_icon_color', ['label' => esc_html__('Card Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-icon i' => 'color: {{VALUE}};', '.gc-web-service-card-icon svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_card_title_color', ['label' => esc_html__('Card Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_card_desc_color', ['label' => esc_html__('Card Description Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-desc' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_list_color', ['label' => esc_html__('List Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-list li' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_list_icon_color', ['label' => esc_html__('List Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-list li i' => 'color: {{VALUE}};', '.gc-web-service-card-list li svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_link_color', ['label' => esc_html__('Link Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-link' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_dark_link_hover_color', ['label' => esc_html__('Link Hover Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('dark', ['.gc-web-service-card-link:hover' => 'color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->start_controls_tab('gc_service_web_light_tab', ['label' => esc_html__('Light Mode', 'softro-core')]);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_light_section_bg', 'label' => esc_html__('Section Background', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=light] {{WRAPPER}} .gc-web-services-section']);
        $this->add_group_control(Group_Control_Background::get_type(), ['name' => 'gc_service_web_light_section_overlay', 'label' => esc_html__('Section Overlay', 'softro-core'), 'types' => ['classic', 'gradient'], 'selector' => '[data-theme=light] {{WRAPPER}} .gc-web-services-section::before']);
        $this->add_control('gc_service_web_light_eyebrow_color', ['label' => esc_html__('Eyebrow Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-services-eyebrow' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_title_color', ['label' => esc_html__('Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-services-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_title_accent_color', ['label' => esc_html__('Title Accent Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-services-accent' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_intro_color', ['label' => esc_html__('Intro Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-services-intro' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_card_bg', ['label' => esc_html__('Card Background', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card' => 'background-color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_card_icon_color', ['label' => esc_html__('Card Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-icon i' => 'color: {{VALUE}};', '.gc-web-service-card-icon svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_card_title_color', ['label' => esc_html__('Card Title Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-title' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_card_desc_color', ['label' => esc_html__('Card Description Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-desc' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_list_color', ['label' => esc_html__('List Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-list li' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_list_icon_color', ['label' => esc_html__('List Icon Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-list li i' => 'color: {{VALUE}};', '.gc-web-service-card-list li svg' => 'fill: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_link_color', ['label' => esc_html__('Link Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-link' => 'color: {{VALUE}};'])]);
        $this->add_control('gc_service_web_light_link_hover_color', ['label' => esc_html__('Link Hover Color', 'softro-core'), 'type' => Controls_Manager::COLOR, 'selectors' => $this->get_theme_mode_selectors('light', ['.gc-web-service-card-link:hover' => 'color: {{VALUE}};'])]);
        $this->end_controls_tab();

        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    private function render_editor_preview_fix()
    {
        if (!Plugin::$instance->editor->is_edit_mode()) {
            return;
        }

        $widget_id = esc_attr($this->get_id());
        ?>
        <style>
            .elementor-element-<?php echo $widget_id; ?> [data-text-animation],
            .elementor-element-<?php echo $widget_id; ?> .overflow-hidden { opacity: 1 !important; transform: none !important; visibility: visible !important; }
        </style>
        <?php
    }

    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_service_web_reset_elementor_spacing'] ?? 'yes')) {
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

    private function render_service_card($card, $settings)
    {
        $style = $this->get_card_style_class($card['card_style'] ?? 'blue');
        $title = $card['card_title'] ?? '';
        $description = $card['card_description'] ?? '';
        $features = !empty($card['card_features']) ? $card['card_features'] : [];
        $link_text = $card['link_text'] ?? '';
        $link_url = $card['link_url'] ?? [];

        if (!$title) {
            return;
        }
        ?>
        <article class="gc-web-service-card gc-web-service-card--<?php echo esc_attr($style); ?>">
            <div class="gc-web-service-card-icon" aria-hidden="true"><?php $this->render_card_icon($card, $settings); ?></div>
            <h3 class="gc-web-service-card-title"><?php echo esc_html($title); ?></h3>
            <?php if ($description) : ?>
                <p class="gc-web-service-card-desc"><?php echo $this->get_paragraph_inner_content($description); ?></p>
            <?php endif; ?>
            <?php if (!empty($features)) : ?>
                <ul class="gc-web-service-card-list">
                    <?php foreach ($features as $feature) :
                        $feature_text = $feature['feature_text'] ?? '';
                        if (!$feature_text) {
                            continue;
                        }
                        ?>
                        <li><?php $this->render_list_icon($feature, $settings); ?> <?php echo esc_html($feature_text); ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
            <?php if ($link_text) : ?>
                <a class="gc-web-service-card-link"<?php echo $this->get_link_attributes($link_url); ?>>
                    <?php echo esc_html($link_text); ?>
                    <?php $this->render_link_icon($card, $settings); ?>
                </a>
            <?php endif; ?>
        </article>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $eyebrow = $settings['gc_service_web_eyebrow'] ?? '';
        $title_before = $settings['gc_service_web_title_before'] ?? '';
        $title_accent = $settings['gc_service_web_title_accent'] ?? '';
        $title_after = $settings['gc_service_web_title_after'] ?? '';
        $intro = $settings['gc_service_web_intro'] ?? '';
        $cards = !empty($settings['gc_service_web_cards']) ? $settings['gc_service_web_cards'] : [];
        ?>

        <section class="gc-web-services-section pt-130 pb-130">
            <div class="container">
                <div class="gc-web-services-header">
                    <?php if ($eyebrow) : ?>
                        <span class="gc-web-services-eyebrow" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($eyebrow); ?></span>
                    <?php endif; ?>

                    <?php if ($title_before || $title_accent || $title_after) : ?>
                        <h2 class="gc-web-services-title overflow-hidden" data-text-animation data-split="word" data-duration="1">
                            <?php echo esc_html($title_before); ?>
                            <?php if ($title_accent) : ?>
                                <span class="gc-web-services-accent"><?php echo esc_html($title_accent); ?></span>
                            <?php endif; ?>
                            <?php echo esc_html($title_after); ?>
                        </h2>
                    <?php endif; ?>

                    <?php if ($intro) : ?>
                        <p class="gc-web-services-intro" data-text-animation="fade-in" data-duration="1.5"><?php echo $this->get_paragraph_inner_content($intro); ?></p>
                    <?php endif; ?>
                </div>

                <?php if (!empty($cards)) : ?>
                    <div class="gc-web-services-grid">
                        <?php foreach ($cards as $card) {
                            $this->render_service_card($card, $settings);
                        } ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Service_Web_Widget());
