<?php

/**
 * Blog Tags Widget with Custom HTML Structure
 */
class Egns_Search_Custom_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'egns_search_custom',
            __('Egns Search', 'softro-core'),
            array(
                'description' => __('Displays search in custom HTML structure', 'softro-core'),
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : 'Tags';



        if (!empty($title)) {
            echo '<h4 class="widget-title">' . esc_html($title) . '</h4>';
        }

        get_search_form();

    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'New Tags';
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Search Label:', 'softro-core'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}

function register_egns_search_custom_widget()
{
    register_widget('Egns_Search_Custom_Widget');
}
add_action('widgets_init', 'register_egns_search_custom_widget');
