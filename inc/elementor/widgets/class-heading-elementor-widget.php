<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

use Elementor\core\Schemes;

class Softro_Heading_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'nexaq_heading';
    }

    public function get_title()
    {
        return esc_html__('EG Heading', 'nexaq-core');
    }

    public function get_icon()
    {
        return 'egns-widget-icon';
    }

    public function get_categories()
    {
        return ['gc_widgets'];
    }

    protected function register_controls()
    {

        //=====================General=======================//

        $this->start_controls_section(
            'nexaq_heading_genaral',
            [
                'label' => esc_html__('General', 'nexaq-core')
            ]
        );

        $this->add_control(
            'nexaq_heading_sub_title',
            [
                'label'       => esc_html__('Subtitle', 'nexaq-core'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Key Features', 'nexaq-core'),
                'placeholder' => esc_html__('write your Subtitle here', 'nexaq-core'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'nexaq_heading_genaral_title',
            [
                'label'       => esc_html__('Title', 'nexaq-core'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => esc_html__('Achieve Your Marketing Goals We\'re Here.', 'nexaq-core'),
                'placeholder' => esc_html__('write your title here', 'nexaq-core'),
                'label_block' => true,
            ]
        );

        $this->end_controls_section();


        //style start
        $this->start_controls_section(
            'nexaq_heading_genaral_title_style',
            [
                'label' => esc_html__('General ', 'nexaq-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'nexaq_heading_genaral_subtitle_style_title',
            [
                'label'     => esc_html__('Sub Title', 'nexaq-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'after',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Typography', 'nexaq-core'),
                'name'     => 'nexaq_heading_genaral_subtitle_style_typ',
                'selector' => '{{WRAPPER}} .section-title.five .sub-title',

            ]
        );

        $this->add_control(
            'nexaq_heading_genaral_subtitle_style_color',
            [
                'label'     => esc_html__('Color', 'nexaq-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-title.five .sub-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'nexaq_heading_genaral_title_style_title',
            [
                'label'     => esc_html__('Title', 'nexaq-core'),
                'type'      => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label'    => esc_html__('Typography', 'nexaq-core'),
                'name'     => 'nexaq_heading_genaral_title_style_title_typ',
                'selector' => '{{WRAPPER}} .section-title h2',

            ]
        );

        $this->add_control(
            'nexaq_heading_genaral_title_style_title_color',
            [
                'label'     => esc_html__('Color', 'nexaq-core'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .section-title h2' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    protected function render()
    {

        $settings = $this->get_settings_for_display();

?>

        <div class="section-title five text-center">
            <?php if (!empty($settings['nexaq_heading_sub_title'])) : ?>
                <span class="sub-title"><?php echo esc_html($settings['nexaq_heading_sub_title']); ?></span>
            <?php endif; ?>
            <?php if (!empty($settings['nexaq_heading_genaral_title'])) : ?>
                <h2><?php echo esc_html($settings['nexaq_heading_genaral_title']); ?></h2>
            <?php endif; ?>
        </div>

<?php

    }
}

Plugin::instance()->widgets_manager->register(new Softro_Heading_Widget());
