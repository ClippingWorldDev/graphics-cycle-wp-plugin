<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;  // Exit if accessed directly

use Elementor\core\Schemes;

class Softro_About_Web_Widget extends Widget_Base
{

    public function get_name()
    {
        return 'gc_about_web';
    }

    public function get_title()
    {
        return esc_html__('GC About Web', 'nexaq-core');
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


        $this->end_controls_section();


        //style start
        $this->start_controls_section(
            'nexaq_heading_genaral_title_style',
            [
                'label' => esc_html__('General ', 'nexaq-core'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );



        $this->end_controls_section();
    }
    protected function render()
    {

        $settings = $this->get_settings_for_display();

?>

    

<?php

    }
}

Plugin::instance()->widgets_manager->register(new Softro_About_Web_Widget());
