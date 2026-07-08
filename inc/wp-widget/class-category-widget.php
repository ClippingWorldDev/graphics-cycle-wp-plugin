<?php

/**
 * Blog Category Widget with Custom HTML Structure
 */
class Egns_Blog_Category_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'egns_blog_category',
            __('Egns Blog Category', 'softro-core'),
            array(
                'description' => __('Displays blog categories with custom SVG icon', 'softro-core'),
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : 'Category';
        $number_of_categories = isset($instance['number_of_categories']) ? absint($instance['number_of_categories']) : 6;

        echo $args['before_widget'];

        // Display widget title
        if (!empty($title)) {
            echo '<h4 class="widget-title">' . esc_html($title) . '</h4>';
        }

        // Get categories
        $categories = get_categories(array(
            'orderby'    => 'count',
            'order'      => 'DESC',
            'number'     => $number_of_categories,
            'hide_empty' => true
        ));

        if (!empty($categories)) {
            echo '<ul class="category-list">';
            foreach ($categories as $category) {
                echo '<li>';
                echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">';
                echo '<span>';
                echo esc_html($category->name) . '(' . esc_html($category->count) . ')';
                echo '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No categories found.', 'softro-core') . '</p>';
        }

        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'Category';
        $number_of_categories = isset($instance['number_of_categories']) ? absint($instance['number_of_categories']) : 6;
?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'softro-core'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_of_categories')); ?>"><?php echo esc_html__('Number of categories to show:', 'softro-core'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number_of_categories')); ?>" name="<?php echo esc_attr($this->get_field_name('number_of_categories')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number_of_categories); ?>" size="3">
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number_of_categories'] = absint($new_instance['number_of_categories']);
        return $instance;
    }
}

// Register the widget
function register_egns_blog_category_widget()
{
    register_widget('Egns_Blog_Category_Widget');
}
add_action('widgets_init', 'register_egns_blog_category_widget');
