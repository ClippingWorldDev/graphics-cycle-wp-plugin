<?php

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

use Egns_Core\Egns_Helper;

class Softro_Blog_Two_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_blog_two';
    }

    public function get_title()
    {
        return esc_html__('GC Blog Two', 'softro-core');
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
     * @param array $link
     * @return array
     */
    private function get_link_data($link)
    {
        $data = [
            'url'         => '#',
            'is_external' => false,
            'rel'         => '',
        ];

        if (!is_array($link)) {
            return $data;
        }

        if (!empty($link['url'])) {
            $data['url'] = $link['url'];
        }

        $data['is_external'] = !empty($link['is_external']);

        $rels = [];
        if ($data['is_external']) {
            $rels[] = 'noopener';
        }
        if (!empty($link['nofollow'])) {
            $rels[] = 'nofollow';
        }

        if (!empty($rels)) {
            $data['rel'] = implode(' ', array_unique($rels));
        }

        return $data;
    }

    /**
     * @param array $link_settings
     * @return string
     */
    private function get_link_attributes($link_settings)
    {
        $link_data = $this->get_link_data($link_settings);

        $attributes = sprintf(' href="%s"', esc_url($link_data['url']));

        if ($link_data['is_external']) {
            $attributes .= ' target="_blank"';
        }

        if (!empty($link_data['rel'])) {
            $attributes .= ' rel="' . esc_attr($link_data['rel']) . '"';
        }

        return $attributes;
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
            'gc_blog_two_shape_section',
            [
                'label' => esc_html__('Background Shape', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_bg_shape',
            [
                'label'   => esc_html__('Shape Image', 'softro-core'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => $this->get_theme_img_url('new-update/blog-shape.png'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_request_heading_section',
            [
                'label' => esc_html__('Request Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_show_request_section',
            [
                'label'        => esc_html__('Show Request Section', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_blog_two_request_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Book Appointment', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'gc_blog_two_show_request_section' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_request_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__("Let's Talk About Our Awesome Services", 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
                'condition'   => [
                    'gc_blog_two_show_request_section' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $contact_link_repeater = new Repeater();

        $contact_link_repeater->add_control(
            'link_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('+1 (800) 123-4567', 'softro-core'),
                'label_block' => true,
            ]
        );

        $contact_link_repeater->add_control(
            'link_url',
            [
                'label'   => esc_html__('URL', 'softro-core'),
                'type'    => Controls_Manager::URL,
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $contact_list_repeater = new Repeater();

        $contact_list_repeater->add_control(
            'list_text',
            [
                'label'       => esc_html__('Text', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Mon to Fri: 08:00AM - 05:00PM', 'softro-core'),
                'label_block' => true,
            ]
        );

        $contact_repeater = new Repeater();

        $contact_repeater->add_control(
            'contact_item_class',
            [
                'label'       => esc_html__('Extra Item Class', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'description' => esc_html__('Example: item-2', 'softro-core'),
            ]
        );

        $contact_repeater->add_control(
            'contact_icon_image',
            [
                'label' => esc_html__('Icon Image', 'softro-core'),
                'type'  => Controls_Manager::MEDIA,
            ]
        );

        $contact_repeater->add_control(
            'contact_icon',
            [
                'label'   => esc_html__('Icon (Font / SVG)', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-sharp fa-solid fa-location-dot',
                    'library' => 'fa-sharp fa-solid',
                ],
            ]
        );

        $contact_repeater->add_control(
            'contact_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Head Office Address', 'softro-core'),
                'label_block' => true,
            ]
        );

        $contact_repeater->add_control(
            'contact_content_type',
            [
                'label'   => esc_html__('Content Type', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'text',
                'options' => [
                    'text'  => esc_html__('Paragraph', 'softro-core'),
                    'links' => esc_html__('Links', 'softro-core'),
                    'list'  => esc_html__('List', 'softro-core'),
                ],
            ]
        );

        $contact_repeater->add_control(
            'contact_text',
            [
                'label'     => esc_html__('Paragraph Text', 'softro-core'),
                'type'      => Controls_Manager::TEXTAREA,
                'default'   => esc_html__("Suite 456 Innovation Tower Metropolis City, MC 78910", 'softro-core'),
                'rows'      => 3,
                'condition' => [
                    'contact_content_type' => 'text',
                ],
            ]
        );

        $contact_repeater->add_control(
            'contact_links',
            [
                'label'       => esc_html__('Links', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $contact_link_repeater->get_controls(),
                'default'     => [
                    ['link_text' => '+1 (800) 123-4567', 'link_url' => ['url' => 'tel:+18001234567']],
                ],
                'title_field' => '{{{ link_text }}}',
                'condition'   => [
                    'contact_content_type' => 'links',
                ],
            ]
        );

        $contact_repeater->add_control(
            'contact_list_items',
            [
                'label'       => esc_html__('List Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $contact_list_repeater->get_controls(),
                'default'     => [
                    ['list_text' => esc_html__('Mon to Fri: 08:00AM - 05:00PM', 'softro-core')],
                    ['list_text' => esc_html__('Sat to Sun: 09:00Am - 05:00PM', 'softro-core')],
                ],
                'title_field' => '{{{ list_text }}}',
                'condition'   => [
                    'contact_content_type' => 'list',
                ],
            ]
        );

        $this->start_controls_section(
            'gc_blog_two_request_contact_section',
            [
                'label'     => esc_html__('Request Contact Info', 'softro-core'),
                'condition' => [
                    'gc_blog_two_show_request_section' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_request_contacts',
            [
                'label'       => esc_html__('Contact Items', 'softro-core'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $contact_repeater->get_controls(),
                'default'     => [
                    [
                        'contact_title'        => esc_html__('Head Office Address', 'softro-core'),
                        'contact_content_type' => 'text',
                        'contact_icon'         => ['value' => 'fa-sharp fa-solid fa-location-dot', 'library' => 'fa-sharp fa-solid'],
                    ],
                    [
                        'contact_title'        => esc_html__('Phone Number', 'softro-core'),
                        'contact_content_type' => 'links',
                        'contact_icon'         => ['value' => 'fa-solid fa-phone', 'library' => 'fa-solid'],
                    ],
                    [
                        'contact_title'        => esc_html__('Email Address', 'softro-core'),
                        'contact_content_type' => 'links',
                        'contact_icon'         => ['value' => 'fa-solid fa-envelope', 'library' => 'fa-solid'],
                    ],
                    [
                        'contact_title'        => esc_html__('Office Hours', 'softro-core'),
                        'contact_content_type' => 'list',
                        'contact_item_class'   => 'item-2',
                        'contact_icon'         => ['value' => 'fa-solid fa-clock', 'library' => 'fa-solid'],
                    ],
                ],
                'title_field' => '{{{ contact_title }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_request_form_section',
            [
                'label'     => esc_html__('Request Form', 'softro-core'),
                'condition' => [
                    'gc_blog_two_show_request_section' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_form_action',
            [
                'label'       => esc_html__('Form Action URL', 'softro-core'),
                'type'        => Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-form-handler.com', 'softro-core'),
                'default'     => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_form_first_name_label',
            [
                'label'   => esc_html__('First Name Label', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('First Name*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_first_name_placeholder',
            [
                'label'   => esc_html__('First Name Placeholder', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Your First Name*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_last_name_label',
            [
                'label'   => esc_html__('Last Name Label', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Last Name*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_last_name_placeholder',
            [
                'label'   => esc_html__('Last Name Placeholder', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Your Last Name*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_email_label',
            [
                'label'   => esc_html__('Email Label', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Email Address*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_email_placeholder',
            [
                'label'   => esc_html__('Email Placeholder', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Company Email*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_website_label',
            [
                'label'   => esc_html__('Website Label', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Company Website', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_website_placeholder',
            [
                'label'   => esc_html__('Website Placeholder', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Company Website*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_message_label',
            [
                'label'   => esc_html__('Message Label', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Message*', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_message_placeholder',
            [
                'label'   => esc_html__('Message Placeholder', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Message', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_button_text',
            [
                'label'   => esc_html__('Button Text', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Get a Quote', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_form_button_icon',
            [
                'label'   => esc_html__('Button Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-sharp fa-regular fa-arrow-right',
                    'library' => 'fa-sharp fa-regular',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_blog_heading_section',
            [
                'label' => esc_html__('Blog Section Heading', 'softro-core'),
            ]
        );

        $this->add_control(
            'gc_blog_two_show_blog_section',
            [
                'label'        => esc_html__('Show Blog Section', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Yes', 'softro-core'),
                'label_off'    => esc_html__('No', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_blog_two_blog_subtitle',
            [
                'label'       => esc_html__('Subtitle', 'softro-core'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Blog & News', 'softro-core'),
                'label_block' => true,
                'condition'   => [
                    'gc_blog_two_show_blog_section' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_blog_title',
            [
                'label'       => esc_html__('Title', 'softro-core'),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => esc_html__('Our latest news & articles from the blog', 'softro-core'),
                'label_block' => true,
                'rows'        => 2,
                'condition'   => [
                    'gc_blog_two_show_blog_section' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_blog_query_section',
            [
                'label'     => esc_html__('Blog Query', 'softro-core'),
                'condition' => [
                    'gc_blog_two_show_blog_section' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_posts_per_page',
            [
                'label'   => esc_html__('Posts Per Page', 'softro-core'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
            ]
        );

        $this->add_control(
            'gc_blog_two_show_pagination',
            [
                'label'        => esc_html__('Show Pagination', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'gc_blog_two_selected_category',
            [
                'label'       => esc_html__('Select Categories', 'softro-core'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => Egns_Helper::get_categories_for_select(),
            ]
        );

        $this->add_control(
            'gc_blog_two_selected_post',
            [
                'label'       => esc_html__('Select Posts', 'softro-core'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => Egns_Helper::get_blog_post_options(),
            ]
        );

        $this->add_control(
            'gc_blog_two_orderby',
            [
                'label'   => esc_html__('Order By', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'ID'         => esc_html__('Post ID', 'softro-core'),
                    'author'     => esc_html__('Post Author', 'softro-core'),
                    'title'      => esc_html__('Title', 'softro-core'),
                    'date'       => esc_html__('Date', 'softro-core'),
                    'rand'       => esc_html__('Random', 'softro-core'),
                    'menu_order' => esc_html__('Menu Order', 'softro-core'),
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_order',
            [
                'label'   => esc_html__('Order', 'softro-core'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'ASC'  => esc_html__('Ascending', 'softro-core'),
                    'DESC' => esc_html__('Descending', 'softro-core'),
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_show_category',
            [
                'label'        => esc_html__('Show Category', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_blog_two_show_date',
            [
                'label'        => esc_html__('Show Date', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_blog_two_date_format',
            [
                'label'     => esc_html__('Date Format', 'softro-core'),
                'type'      => Controls_Manager::TEXT,
                'default'   => 'd F, Y',
                'condition' => [
                    'gc_blog_two_show_date' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_show_author',
            [
                'label'        => esc_html__('Show Author', 'softro-core'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('Show', 'softro-core'),
                'label_off'    => esc_html__('Hide', 'softro-core'),
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'gc_blog_two_author_prefix',
            [
                'label'     => esc_html__('Author Prefix', 'softro-core'),
                'type'      => Controls_Manager::TEXT,
                'default'   => esc_html__('Post by:', 'softro-core'),
                'condition' => [
                    'gc_blog_two_show_author' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_date_icon',
            [
                'label'   => esc_html__('Date Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-sharp fa-regular fa-clock',
                    'library' => 'fa-sharp fa-regular',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_author_icon',
            [
                'label'   => esc_html__('Author Icon', 'softro-core'),
                'type'    => Controls_Manager::ICONS,
                'default' => [
                    'value'   => 'fa-light fa-user',
                    'library' => 'fa-light',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_empty_text',
            [
                'label'   => esc_html__('Empty Text', 'softro-core'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('No blog posts found.', 'softro-core'),
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
            'gc_blog_two_style_layout',
            [
                'label' => esc_html__('Layout', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'gc_blog_two_reset_elementor_spacing',
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
            'gc_blog_two_style_section',
            [
                'label' => esc_html__('Section', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'gc_blog_two_section_background',
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .blog-section.blog-11',
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_section_padding',
            [
                'label'      => esc_html__('Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .blog-section.blog-11' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_section_margin',
            [
                'label'      => esc_html__('Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .blog-section.blog-11' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_style_shape',
            [
                'label' => esc_html__('Background Shape', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_shape_width',
            [
                'label'      => esc_html__('Width', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .blog-section.blog-11 .bg-shape img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_style_headings',
            [
                'label' => esc_html__('Section Headings', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_subtitle_typography',
                'label'    => esc_html__('Subtitle Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .section-heading .sub-heading',
            ]
        );

        $this->add_control(
            'gc_blog_two_subtitle_color',
            [
                'label'     => esc_html__('Subtitle Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_title_typography',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .section-heading .section-title',
            ]
        );

        $this->add_control(
            'gc_blog_two_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-heading .section-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_style_request_contact',
            [
                'label' => esc_html__('Request Contact', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_req_contact_title_typography',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .req-contact-item .content .title',
            ]
        );

        $this->add_control(
            'gc_blog_two_req_contact_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .req-contact-item .content .title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_req_contact_text_typography',
                'label'    => esc_html__('Text Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .req-contact-item .content p, {{WRAPPER}} .req-contact-item .content a, {{WRAPPER}} .req-contact-item .content li',
            ]
        );

        $this->add_control(
            'gc_blog_two_req_contact_text_color',
            [
                'label'     => esc_html__('Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .req-contact-item .content p'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .req-contact-item .content a'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .req-contact-item .content li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_req_contact_icon_size',
            [
                'label'      => esc_html__('Icon Size', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .req-contact-item .icon i'   => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .req-contact-item .icon svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .req-contact-item .icon img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_req_contact_icon_color',
            [
                'label'     => esc_html__('Icon Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .req-contact-item .icon i'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .req-contact-item .icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_style_request_form',
            [
                'label' => esc_html__('Request Form', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_form_label_typography',
                'label'    => esc_html__('Label Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .request-form label',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_form_input_typography',
                'label'    => esc_html__('Input Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .request-form .form-control',
            ]
        );

        $this->add_control(
            'gc_blog_two_form_input_color',
            [
                'label'     => esc_html__('Input Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .request-form .form-control' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_form_input_bg',
            [
                'label'     => esc_html__('Input Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .request-form .form-control' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_form_button_typography',
                'label'    => esc_html__('Button Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .request-form .rr-primary-btn',
            ]
        );

        $this->add_control(
            'gc_blog_two_form_button_color',
            [
                'label'     => esc_html__('Button Text Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .request-form .rr-primary-btn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'gc_blog_two_form_button_bg',
            [
                'label'     => esc_html__('Button Background', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .request-form .rr-primary-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'gc_blog_two_style_blog_card',
            [
                'label' => esc_html__('Blog Cards', 'softro-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_card_image_height',
            [
                'label'      => esc_html__('Image Height', 'softro-core'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => [
                        'min' => 120,
                        'max' => 500,
                    ],
                ],
                'selectors'  => [
                    '{{WRAPPER}} .post-card-10 .post-thumb img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_card_category_typography',
                'label'    => esc_html__('Category Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .post-card-10 .category',
            ]
        );

        $this->add_control(
            'gc_blog_two_card_category_color',
            [
                'label'     => esc_html__('Category Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-card-10 .category' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_card_title_typography',
                'label'    => esc_html__('Title Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .post-card-10 .title, {{WRAPPER}} .post-card-10 .title a',
            ]
        );

        $this->add_control(
            'gc_blog_two_card_title_color',
            [
                'label'     => esc_html__('Title Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-card-10 .title'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .post-card-10 .title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'gc_blog_two_card_meta_typography',
                'label'    => esc_html__('Meta Typography', 'softro-core'),
                'selector' => '{{WRAPPER}} .post-card-10 .post-meta li',
            ]
        );

        $this->add_control(
            'gc_blog_two_card_meta_color',
            [
                'label'     => esc_html__('Meta Color', 'softro-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .post-card-10 .post-meta li' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_card_padding',
            [
                'label'      => esc_html__('Content Padding', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .post-card-10 .post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gc_blog_two_card_margin',
            [
                'label'      => esc_html__('Card Margin', 'softro-core'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .post-card-10' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * @param array $ids
     * @return array
     */
    private function sanitize_ids($ids)
    {
        if (!is_array($ids)) {
            return [];
        }

        $clean = array_map('absint', $ids);
        $clean = array_filter($clean);

        return array_values(array_unique($clean));
    }

    /**
     * @param array $settings
     * @return \WP_Query
     */
    private function get_query($settings)
    {
        $limit = !empty($settings['gc_blog_two_posts_per_page']) ? absint($settings['gc_blog_two_posts_per_page']) : 3;
        if ($limit < 1) {
            $limit = 1;
        }

        $orderby = !empty($settings['gc_blog_two_orderby']) ? $settings['gc_blog_two_orderby'] : 'date';
        $orderby = in_array($orderby, ['ID', 'author', 'title', 'date', 'rand', 'menu_order'], true) ? $orderby : 'date';

        $order = !empty($settings['gc_blog_two_order']) ? strtoupper($settings['gc_blog_two_order']) : 'DESC';
        $order = in_array($order, ['ASC', 'DESC'], true) ? $order : 'DESC';

        $selected_posts = $this->sanitize_ids(!empty($settings['gc_blog_two_selected_post']) ? $settings['gc_blog_two_selected_post'] : []);
        $selected_cats  = $this->sanitize_ids(!empty($settings['gc_blog_two_selected_category']) ? $settings['gc_blog_two_selected_category'] : []);

        $args = [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'posts_per_page'      => $limit,
            'orderby'             => $orderby,
            'order'               => $order,
            'paged'               => $this->get_current_paged(),
            'ignore_sticky_posts' => true,
        ];

        if (!empty($selected_posts)) {
            $args['post__in'] = $selected_posts;
        }

        if (!empty($selected_cats)) {
            $args['category__in'] = $selected_cats;
        }

        return new \WP_Query($args);
    }

    /**
     * @return int
     */
    private function get_current_paged()
    {
        $paged = get_query_var('paged');

        if (empty($paged)) {
            $paged = get_query_var('page');
        }

        $paged = absint($paged);

        return $paged > 0 ? $paged : 1;
    }

    /**
     * @param int $post_id
     * @return object|null
     */
    private function get_primary_category($post_id)
    {
        $categories = get_the_category($post_id);

        if (!empty($categories) && !is_wp_error($categories)) {
            return $categories[0];
        }

        return null;
    }

    /**
     * @param int $post_id
     * @return array
     */
    private function get_post_image_data($post_id)
    {
        $fallback = Utils::get_placeholder_image_src();
        $url      = get_the_post_thumbnail_url($post_id, 'large');
        $url      = !empty($url) ? $url : $fallback;

        $thumb_id = get_post_thumbnail_id($post_id);
        $alt      = '';

        if (!empty($thumb_id)) {
            $alt = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
        }

        if (empty($alt)) {
            $alt = get_the_title($post_id);
        }

        return [
            'url' => $url,
            'alt' => $alt,
        ];
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_elementor_spacing_fix($settings)
    {
        if ('yes' !== ($settings['gc_blog_two_reset_elementor_spacing'] ?? 'yes')) {
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
     * @return void
     */
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

    /**
     * @param array $icon_settings
     * @param array $image_settings
     * @return void
     */
    private function render_icon_or_image($icon_settings, $image_settings = [])
    {
        if (!empty($icon_settings['value'])) {
            Icons_Manager::render_icon($icon_settings, ['aria-hidden' => 'true']);
            return;
        }

        $icon_url = $this->get_media_url($image_settings, '');

        if ($icon_url) {
            echo '<img src="' . esc_url($icon_url) . '" alt="">';
        }
    }

    /**
     * @param array $item
     * @return void
     */
    private function render_contact_item($item)
    {
        $item_class = !empty($item['contact_item_class']) ? sanitize_html_class($item['contact_item_class']) : '';
        $title      = $item['contact_title'] ?? '';
        $type       = $item['contact_content_type'] ?? 'text';
        ?>
        <div class="req-contact-item<?php echo $item_class ? ' ' . esc_attr($item_class) : ''; ?>">
            <div class="icon"><?php $this->render_icon_or_image($item['contact_icon'] ?? [], $item['contact_icon_image'] ?? []); ?></div>
            <div class="content">
                <?php if ($title) : ?>
                    <h4 class="title"><?php echo esc_html($title); ?></h4>
                <?php endif; ?>
                <?php
                if ('links' === $type && !empty($item['contact_links'])) {
                    $links = $item['contact_links'];
                    $total = count($links);
                    foreach ($links as $index => $link_item) {
                        $link_text = $link_item['link_text'] ?? '';
                        if ('' === trim((string) $link_text)) {
                            continue;
                        }
                        ?><a<?php echo $this->get_link_attributes($link_item['link_url'] ?? []); ?>><?php echo esc_html($link_text); ?></a><?php
                        if ($index < $total - 1) {
                            echo ' ';
                        }
                        if (2 === $index) {
                            echo '<br>';
                        }
                    }
                } elseif ('list' === $type && !empty($item['contact_list_items'])) {
                    ?>
                    <ul>
                        <?php foreach ($item['contact_list_items'] as $list_item) :
                            $list_text = $list_item['list_text'] ?? '';
                            if ('' === trim((string) $list_text)) {
                                continue;
                            }
                            ?>
                            <li><?php echo esc_html($list_text); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php
                } else {
                    $text = $item['contact_text'] ?? '';
                    if ($text) {
                        echo '<p>' . wp_kses($text, ['br' => []]) . '</p>';
                    }
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * @param array  $settings
     * @param string $subtitle
     * @param string $title
     * @return void
     */
    private function render_section_heading($subtitle, $title)
    {
        if (!$subtitle && !$title) {
            return;
        }
        ?>
        <div class="section-heading text-center">
            <?php if ($subtitle) : ?>
                <h4 class="sub-heading" data-text-animation="fade-in" data-duration="1.5"><?php echo esc_html($subtitle); ?></h4>
            <?php endif; ?>
            <?php if ($title) : ?>
                <h2 class="section-title" data-text-animation data-split="word" data-duration="1"><?php echo wp_kses($title, ['br' => []]); ?></h2>
            <?php endif; ?>
        </div>
        <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_request_section($settings)
    {
        if ('yes' !== ($settings['gc_blog_two_show_request_section'] ?? 'yes')) {
            return;
        }

        $contacts   = !empty($settings['gc_blog_two_request_contacts']) ? $settings['gc_blog_two_request_contacts'] : [];
        $form_id    = 'gc-blog-two-form-' . $this->get_id();
        $form_action = $this->get_link_data($settings['gc_blog_two_form_action'] ?? []);
        ?>
        <div class="request-section pb-130">
            <div class="container">
                <?php $this->render_section_heading(
                    $settings['gc_blog_two_request_subtitle'] ?? '',
                    $settings['gc_blog_two_request_title'] ?? ''
                ); ?>
                <div class="row req-wrap req-wrap-3 align-items-center">
                    <div class="col-lg-5">
                        <div class="req-contact">
                            <?php foreach ($contacts as $item) {
                                $this->render_contact_item($item);
                            } ?>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="request-content">
                            <div class="request-form fade-wrapper">
                                <form action="<?php echo esc_url($form_action['url']); ?>" method="post">
                                    <div class="form-group row">
                                        <div class="col-md-6 fade-top">
                                            <div class="form-item">
                                                <label for="<?php echo esc_attr($form_id); ?>-fullname"><?php echo esc_html($settings['gc_blog_two_form_first_name_label'] ?? ''); ?></label>
                                                <input type="text" id="<?php echo esc_attr($form_id); ?>-fullname" name="fullname" class="form-control" placeholder="<?php echo esc_attr($settings['gc_blog_two_form_first_name_placeholder'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 fade-top">
                                            <div class="form-item">
                                                <label for="<?php echo esc_attr($form_id); ?>-lastname"><?php echo esc_html($settings['gc_blog_two_form_last_name_label'] ?? ''); ?></label>
                                                <input type="text" id="<?php echo esc_attr($form_id); ?>-lastname" name="lastname" class="form-control" placeholder="<?php echo esc_attr($settings['gc_blog_two_form_last_name_placeholder'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6 fade-top">
                                            <div class="form-item">
                                                <label for="<?php echo esc_attr($form_id); ?>-email"><?php echo esc_html($settings['gc_blog_two_form_email_label'] ?? ''); ?></label>
                                                <input type="text" id="<?php echo esc_attr($form_id); ?>-email" name="email" class="form-control" placeholder="<?php echo esc_attr($settings['gc_blog_two_form_email_placeholder'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6 fade-top">
                                            <div class="form-item">
                                                <label for="<?php echo esc_attr($form_id); ?>-website"><?php echo esc_html($settings['gc_blog_two_form_website_label'] ?? ''); ?></label>
                                                <input type="text" id="<?php echo esc_attr($form_id); ?>-website" name="website" class="form-control" placeholder="<?php echo esc_attr($settings['gc_blog_two_form_website_placeholder'] ?? ''); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 fade-top">
                                            <div class="form-item">
                                                <label for="<?php echo esc_attr($form_id); ?>-message"><?php echo esc_html($settings['gc_blog_two_form_message_label'] ?? ''); ?></label>
                                                <textarea id="<?php echo esc_attr($form_id); ?>-message" name="message" cols="30" rows="6" class="form-control address" placeholder="<?php echo esc_attr($settings['gc_blog_two_form_message_placeholder'] ?? ''); ?>"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-btn fade-top">
                                        <button class="rr-primary-btn" type="submit">
                                            <?php echo esc_html($settings['gc_blog_two_form_button_text'] ?? ''); ?> <?php $this->render_icon_or_image($settings['gc_blog_two_form_button_icon'] ?? []); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * @param int   $post_id
     * @param array $settings
     * @return void
     */
    private function render_post_card_meta($post_id, $settings)
    {
        $show_date   = 'yes' === ($settings['gc_blog_two_show_date'] ?? 'yes');
        $show_author = 'yes' === ($settings['gc_blog_two_show_author'] ?? 'yes');

        if (!$show_date && !$show_author) {
            return;
        }

        $date_format   = !empty($settings['gc_blog_two_date_format']) ? $settings['gc_blog_two_date_format'] : 'd F, Y';
        $author_prefix = !empty($settings['gc_blog_two_author_prefix']) ? $settings['gc_blog_two_author_prefix'] : esc_html__('Post by:', 'softro-core');
        ?>
        <ul class="post-meta">
            <?php if ($show_date) : ?>
                <li><?php $this->render_icon_or_image($settings['gc_blog_two_date_icon'] ?? []); ?><?php echo esc_html(get_the_date($date_format, $post_id)); ?></li>
            <?php endif; ?>
            <?php if ($show_author) : ?>
                <li><?php $this->render_icon_or_image($settings['gc_blog_two_author_icon'] ?? []); ?><?php echo esc_html(trim($author_prefix . ' ' . get_the_author_meta('display_name', get_post_field('post_author', $post_id)))); ?></li>
            <?php endif; ?>
        </ul>
        <?php
    }

    /**
     * @param \WP_Query $query
     * @return void
     */
    private function render_pagination($query)
    {
        if (!($query instanceof \WP_Query)) {
            return;
        }

        $total_pages = (int) $query->max_num_pages;

        if ($total_pages < 2) {
            return;
        }

        $current_page = $this->get_current_paged();

        if ($current_page > $total_pages) {
            $current_page = $total_pages;
        }

        $prev_url = $current_page > 1 ? get_pagenum_link($current_page - 1) : '';
        $next_url = $current_page < $total_pages ? get_pagenum_link($current_page + 1) : '';
        ?>
        <div class="row justify-content-center mt-100">
            <div class="col-lg-8">
                <div class="pagination-area">
                    <?php if (!empty($prev_url)) : ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url($prev_url); ?>">
                                <svg width="7" height="14" viewBox="0 0 7 14" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M0 7.00008L7 0L2.54545 7.00008L7 14L0 7.00008Z" />
                                </svg>
                                <?php echo esc_html__('Prev', 'softro-core'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <ul class="paginations">
                        <?php for ($page_number = 1; $page_number <= $total_pages; $page_number++) :
                            $is_active = $page_number === $current_page;
                            $page_url  = get_pagenum_link($page_number);
                            ?>
                            <li class="page-item<?php echo $is_active ? ' active' : ''; ?>">
                                <a href="<?php echo esc_url($page_url); ?>"><?php echo esc_html(sprintf('%02d', $page_number)); ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>

                    <?php if (!empty($next_url)) : ?>
                        <div class="paginations-button">
                            <a href="<?php echo esc_url($next_url); ?>">
                                <?php echo esc_html__('Next', 'softro-core'); ?>
                                <svg width="7" height="14" viewBox="0 0 7 14" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7 7.00008L0 0L4.45455 7.00008L0 14L7 7.00008Z" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * @param array $settings
     * @return void
     */
    private function render_blog_section($settings)
    {
        if ('yes' !== ($settings['gc_blog_two_show_blog_section'] ?? 'yes')) {
            return;
        }

        $query       = $this->get_query($settings);
        $empty_text  = $settings['gc_blog_two_empty_text'] ?? '';
        $show_category = 'yes' === ($settings['gc_blog_two_show_category'] ?? 'yes');
        $show_pagination = 'yes' === ($settings['gc_blog_two_show_pagination'] ?? '');
        ?>
        <div class="container">
            <?php $this->render_section_heading(
                $settings['gc_blog_two_blog_subtitle'] ?? '',
                $settings['gc_blog_two_blog_title'] ?? ''
            ); ?>
            <div class="row gy-lg-0 gy-4">
                <?php if ($query->have_posts()) : ?>
                    <?php while ($query->have_posts()) :
                        $query->the_post();
                        $post_id    = get_the_ID();
                        $permalink  = get_permalink($post_id);
                        $post_title = get_the_title($post_id);
                        $image_data = $this->get_post_image_data($post_id);
                        $category   = $this->get_primary_category($post_id);
                        ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="post-card-2 post-card-10 fade-top">
                                <div class="post-thumb">
                                    <div class="overlay"></div>
                                    <img src="<?php echo esc_url($image_data['url']); ?>" alt="<?php echo esc_attr($image_data['alt']); ?>">
                                </div>
                                <div class="post-content-wrap">
                                    <div class="post-content">
                                        <?php if ($show_category && $category) : ?>
                                            <span class="category"><?php echo esc_html($category->name); ?></span>
                                        <?php endif; ?>
                                        <h3 class="title"><a href="<?php echo esc_url($permalink); ?>"><?php echo esc_html($post_title); ?></a></h3>
                                        <?php $this->render_post_card_meta($post_id, $settings); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php elseif ($empty_text) : ?>
                    <div class="col-12">
                        <p><?php echo esc_html($empty_text); ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if ($show_pagination) {
                $this->render_pagination($query);
            } ?>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $this->render_elementor_spacing_fix($settings);
        $this->render_editor_preview_fix();

        $shape_url = $this->get_media_url($settings['gc_blog_two_bg_shape'] ?? [], 'new-update/blog-shape.png');
        ?>

        <section class="blog-section blog-11 pt-130 pb-130 fade-wrapper">
            <?php if ($shape_url) : ?>
                <div class="bg-shape"><img src="<?php echo esc_url($shape_url); ?>" alt="shape"></div>
            <?php endif; ?>
            <?php $this->render_request_section($settings); ?>
            <?php $this->render_blog_section($settings); ?>
        </section>

        <?php
    }
}

Plugin::instance()->widgets_manager->register(new Softro_Blog_Two_Widget());
