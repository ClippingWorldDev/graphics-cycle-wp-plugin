<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

class Softro_MegaMenu_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'softro_mega_menu';
    }

    public function get_title()
    {
        return esc_html__('Mega Menu', 'softro-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['softro_widgets'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'services_title',
            [
                'label'   => esc_html__('Services Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Company Services', 'softro-core'),
            ]
        );

        $this->add_control(
            'services',
            [
                'label'  => esc_html__('Services', 'softro-core'),
                'type'   => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name'    => 'service_icon',
                        'label'   => esc_html__('Icon', 'softro-core'),
                        'type'    => \Elementor\Controls_Manager::ICONS,
                        'default' => [
                            'value'   => 'fas fa-circle',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'name'    => 'service_title',
                        'label'   => esc_html__('Title', 'softro-core'),
                        'type'    => \Elementor\Controls_Manager::TEXT,
                        'default' => esc_html__('Service Title', 'softro-core'),
                    ],
                    [
                        'name'        => 'service_link',
                        'label'       => esc_html__('Link', 'softro-core'),
                        'type'        => \Elementor\Controls_Manager::URL,
                        'placeholder' => esc_html__('https://your-link.com', 'softro-core'),
                    ],
                ],
                'default' => [
                    [
                        'service_title' => esc_html__('Product Development', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                    [
                        'service_title' => esc_html__('UI/UX Design', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                    [
                        'service_title' => esc_html__('eCommerce Solutions', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                    [
                        'service_title' => esc_html__('Product Management', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                    [
                        'service_title' => esc_html__('Cloud & DevOps', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                    [
                        'service_title' => esc_html__('Technical Support', 'softro-core'),
                        'service_link'  => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ service_title }}}',
            ]
        );

        $this->add_control(
            'industries_title',
            [
                'label'   => esc_html__('Industries Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Industries', 'softro-core'),
            ]
        );

        $this->add_control(
            'industries',
            [
                'label'  => esc_html__('Industries', 'softro-core'),
                'type'   => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name'  => 'industry_title',
                        'label' => esc_html__('Title', 'softro-core'),
                        'type'  => \Elementor\Controls_Manager::TEXT,
                    ],
                    [
                        'name'  => 'industry_link',
                        'label' => esc_html__('Link', 'softro-core'),
                        'type'  => \Elementor\Controls_Manager::URL,
                    ],
                ],
                'default' => [
                    [
                        'industry_title' => esc_html__('Financial & Fintech', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                    [
                        'industry_title' => esc_html__('Healthcare & Medical', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                    [
                        'industry_title' => esc_html__('Entertainment', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                    [
                        'industry_title' => esc_html__('Education & EdTech', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                    [
                        'industry_title' => esc_html__('Hospitality & Travel', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                    [
                        'industry_title' => esc_html__('eCommerce Solutions', 'softro-core'),
                        'industry_link'  => ['url' => '#'],
                    ],
                ],
                'title_field' => '{{{ industry_title }}}',
            ]
        );
        $this->add_control(
            'industry_btn_text',
            [
                'label'   => esc_html__('Industry Button Text', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('View All Industries', 'softro-core'),
            ]
        );

        $this->add_control(
            'industry_btn_link',
            [
                'label'   => esc_html__('Industry Button Link', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'contact_title',
            [
                'label'   => esc_html__('Contact Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Don’t Hesitate to Collaborate with Us', 'softro-core'),
            ]
        );

        $this->add_control(
            'contact_button_text',
            [
                'label'   => esc_html__('Contact Button Text', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Contact us', 'softro-core'),
            ]
        );

        $this->add_control(
            'contact_link',
            [
                'label'   => esc_html__('Contact Link', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'support_title',
            [
                'label'   => esc_html__('Support Title', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Always Here to Support You', 'softro-core'),
            ]
        );

        $this->add_control(
            'support_button_text',
            [
                'label'   => esc_html__('Support Button Text', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Let’s Talk', 'softro-core'),
            ]
        );

        $this->add_control(
            'support_button_link',
            [
                'label'   => esc_html__('Support Button Link', 'softro-core'),
                'type'    => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'support_images',
            [
                'label' => esc_html__('Support Images', 'softro-core'),
                'type'  => \Elementor\Controls_Manager::GALLERY,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'softro-core'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .mega-menu-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'link_color',
            [
                'label'     => esc_html__('Link Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .service-list a, {{WRAPPER}} .industry-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_bg_color',
            [
                'label'     => esc_html__('Button Background Color', 'softro-core'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .primary-btn1' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        // Extra 
        $this->add_control(
            'softro_mega_menu_contact_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-area h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_mega_menu_contact_title_typo',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .contact-area h3',
            ]
        );

        // Button Styles
        $this->add_control(
            'softro_mega_menu_contact_button_color',
            [
                'label'     => esc_html__('Button Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-area a span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_mega_menu_contact_button_bg',
            [
                'label'     => esc_html__('Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-area a' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_mega_menu_contact_button_hover_color',
            [
                'label'     => esc_html__('Button Hover Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-area a:hover span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'softro_mega_menu_contact_button_hover_bg',
            [
                'label'     => esc_html__('Button Hover Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .contact-area a:hover' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'softro_mega_menu_contact_button_typo',
                'label'    => esc_html__('Button Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .contact-area a span',
            ]
        );

        $this->add_responsive_control(
            'softro_mega_menu_contact_button_padding',
            [
                'label'      => esc_html__('Button Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .contact-area a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }


    protected function render()
    {
        $settings = $this->get_settings_for_display();
?>

        <div class="menu-row">
            <div class="service-wrap">
                <?php if (!empty($settings['services_title'])): ?>
                    <h3 class="mega-menu-title"><?php echo esc_html($settings['services_title']); ?></h3>
                <?php endif; ?>

                <?php if (!empty($settings['services'])): ?>
                    <ul class="service-list">
                        <?php foreach ($settings['services'] as $service) :
                            $service_link   = !empty($service['service_link']['url']) ? $service['service_link']['url'] : '#';
                            $service_target = !empty($service['service_link']['is_external']) ? '_blank'               : '_self';
                        ?>
                            <li class="single-service">
                                <a href="<?php echo esc_url($service_link); ?>" target="<?php echo esc_attr($service_target); ?>">
                                    <span class="service-icon">
                                        <?php \Elementor\Icons_Manager::render_icon($service['service_icon']); ?>
                                    </span>
                                    <?php echo esc_html($service['service_title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <div class="contact-area">
                    <?php if (!empty($settings['contact_title'])): ?>
                        <h3><?php echo esc_html($settings['contact_title']); ?></h3>
                    <?php endif; ?>

                    <?php
                    $contact_link   = !empty($settings['contact_link']['url']) ? $settings['contact_link']['url'] : '#';
                    $contact_target = !empty($settings['contact_link']['is_external']) ? '_blank' : '_self';
                    ?>
                    <a href="<?php echo esc_url($contact_link); ?>" target="<?php echo esc_attr($contact_target); ?>">
                        <span><?php echo esc_html($settings['contact_button_text']); ?></span>
                        <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <g>
                                <path d="M6.36368 4.94946C6.37916 5.45612 8.04593 6.14424 8.42688 6.15312L12.6747 6.15311L4.95888 13.8689C4.68565 14.1421 4.68564 14.5851 4.95888 14.8584C5.23212 15.1316 5.67512 15.1316 5.94836 14.8584L13.6641 7.14259L13.6641 11.3904C13.6642 11.7767 14.4626 13.4347 14.849 13.4347C15.2353 13.4347 15.0633 11.7767 15.0633 11.3904L15.0633 5.45351C15.0633 5.06717 14.7501 4.754 14.3637 4.75392L8.42689 4.75392C8.02301 4.75884 6.35398 4.48604 6.36368 4.94946Z" />
                            </g>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="industry-wrap">
                <div class="indutry-menu-area">
                    <?php if (!empty($settings['industries_title'])): ?>
                        <h3 class="mega-menu-title"><?php echo esc_html($settings['industries_title']); ?></h3>
                    <?php endif; ?>

                    <?php if (!empty($settings['industries'])): ?>
                        <ul class="industry-menu">
                            <?php foreach ($settings['industries'] as $industry) :
                                $industry_link   = !empty($industry['industry_link']['url']) ? $industry['industry_link']['url'] : '#';
                                $industry_target = !empty($industry['industry_link']['is_external']) ? '_blank'                 : '_self';
                            ?>
                                <li>
                                    <a href="<?php echo esc_url($industry_link); ?>" target="<?php echo esc_attr($industry_target); ?>">
                                        <?php echo esc_html($industry['industry_title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <li>
                                <?php
                                $industry_btn_link   = !empty($settings['industry_btn_link']['url']) ? $settings['industry_btn_link']['url'] : '#';
                                ?>
                                <a href="<?php echo esc_url($industry_btn_link); ?>" class="view-all-btn">
                                    <span><?php echo esc_html($settings['industry_btn_text']) ?></span>
                                    <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M2.97387 1.75541C2.98713 2.18968 4.4158 2.7795 4.74232 2.78711L8.38329 2.78711L1.76976 9.40064C1.53556 9.63484 1.53555 10.0146 1.76976 10.2488C2.00396 10.483 2.38368 10.483 2.61788 10.2488L9.23141 3.63523L9.23141 7.27619C9.23144 7.60735 9.9158 9.02846 10.247 9.0285C10.5781 9.02847 10.4307 7.60734 10.4307 7.27618L10.4307 2.18745C10.4307 1.8563 10.1622 1.58787 9.83107 1.5878L4.74234 1.5878C4.39615 1.59202 2.96556 1.35818 2.97387 1.75541Z" />
                                        </g>
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>

                <div class="support-wrap">
                    <div class="support-area">
                        <div class="support-content">
                            <?php if (!empty($settings['support_title'])): ?>
                                <h3><?php echo esc_html($settings['support_title']); ?></h3>
                            <?php endif; ?>

                            <?php
                            $support_link   = !empty($settings['support_button_link']['url']) ? $settings['support_button_link']['url'] : '#';
                            $support_target = !empty($settings['support_button_link']['is_external']) ? '_blank' : '_self';
                            ?>
                            <a class="primary-btn1" href="<?php echo esc_url($support_link); ?>" target="<?php echo esc_attr($support_target); ?>">
                                <span>
                                    <?php echo esc_html($settings['support_button_text']); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <?php echo esc_html($settings['support_button_text']); ?>
                                    <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <g>
                                            <path d="M6.36416 4.94971C6.37964 5.45636 8.04642 6.14449 8.42737 6.15336L12.6752 6.15336L4.95937 13.8691C4.68614 14.1424 4.68613 14.5854 4.95937 14.8586C5.23261 15.1319 5.67561 15.1319 5.94884 14.8586L13.6646 7.14283L13.6646 11.3906C13.6647 11.777 14.4631 13.4349 14.8494 13.435C15.2358 13.4349 15.0638 11.777 15.0638 11.3906L15.0638 5.45375C15.0637 5.06741 14.7506 4.75424 14.3642 4.75416L8.42738 4.75416C8.0235 4.75908 6.35447 4.48628 6.36416 4.94971Z" />
                                        </g>
                                    </svg>
                                </span>
                            </a>
                        </div>

                        <?php if (!empty($settings['support_images'])): ?>
                            <div class="people-img-list">
                                <?php foreach ($settings['support_images'] as $image): ?>
                                    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr('image'); ?>">
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

<?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_MegaMenu_Widget());
