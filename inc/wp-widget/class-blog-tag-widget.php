<?php

/**
 * Blog Tags Widget with Custom HTML Structure
 */
class Egns_Blog_Tags_Custom_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'egns_blog_tags_custom',
            __('Egns Blog Tags (Custom)', 'softro-core'),
            array(
                'description' => __('Displays blog tags in custom HTML structure', 'softro-core'),
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : 'Tags';
        $number_of_tags = isset($instance['number_of_tags']) ? absint($instance['number_of_tags']) : 9;

        echo $args['before_widget'];


        if (!empty($title)) {
            echo '<h4 class="widget-title">' . esc_html($title) . '</h4>';
        }


        $tags = get_tags(array(
            'number'     => $number_of_tags,
            'orderby'    => 'count',
            'order'      => 'DESC',
            'hide_empty' => true
        ));

        if (!empty($tags)) {
            echo '<ul class="tag-list">';
            foreach ($tags as $tag) {
                $tag_link = get_tag_link($tag->term_id);
                echo '<li>';
                echo '<a href="' . esc_url($tag_link) . '">' . esc_html($tag->name) . '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No tags found.', 'softro-core') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'New Tags';
        $number_of_tags = isset($instance['number_of_tags']) ? absint($instance['number_of_tags']) : 9;
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'softro-core'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_of_tags')); ?>"><?php echo esc_html__('Number of tags to show:', 'softro-core'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number_of_tags')); ?>" name="<?php echo esc_attr($this->get_field_name('number_of_tags')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number_of_tags); ?>" size="3">
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number_of_tags'] = absint($new_instance['number_of_tags']);
        return $instance;
    }
}

function register_egns_blog_tags_custom_widget()
{
    register_widget('Egns_Blog_Tags_Custom_Widget');
}
add_action('widgets_init', 'register_egns_blog_tags_custom_widget');
