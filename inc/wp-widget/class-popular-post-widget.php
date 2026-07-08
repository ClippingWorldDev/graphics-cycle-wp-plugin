<?php

/**
 * Popular Posts Widget
 */
class Egns_Popular_Post_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
            'egns_popular_post',
            __('Egns Popular Post', 'softro-core'),
            array(
                'description' => __('Displays popular posts based on comment count', 'softro-core'),
            )
        );
    }

    public function widget($args, $instance)
    {
        $title = isset($instance['title']) ? apply_filters('widget_title', $instance['title']) : 'Popular Post';
        $number_of_posts = isset($instance['number_of_posts']) ? absint($instance['number_of_posts']) : 3;

        echo $args['before_widget'];
?>

        <?php if (!empty($title)) : ?>
            <h4 class="widget-title"><?php echo esc_html($title); ?></h4>
        <?php endif; ?>

        <?php
        $query = new WP_Query(array(
            'post_type'           => 'post',
            'posts_per_page'      => $number_of_posts,
            'orderby'             => 'comment_count',
            'order'               => 'DESC',
            'ignore_sticky_posts' => true
        ));
        ?>

        <div class="popular-post-widgets">
            <?php
            while ($query->have_posts()) : $query->the_post();
                $post_class = ($query->current_post < $number_of_posts - 1) ? 'mb-25' : '';
            ?>
                <div class="recent-post-widget <?php echo esc_attr($post_class); ?>">
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="recent-post-img">
                            <a href="<?php the_permalink(); ?>">
                                <?php
                                the_post_thumbnail('thumbnail', array('alt' => get_the_title()));
                                ?>
                            </a>
                        </div>
                    <?php } ?>
                    <div class="recent-post-content">
                        <h5><a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a></h5>
                        <a href="<?php echo esc_url(get_permalink()); ?>"><?php echo get_the_date('d F, Y'); ?></a>
                    </div>
                </div>
            <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>

    <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        $title = isset($instance['title']) ? $instance['title'] : 'Popular Post';
        $number_of_posts = isset($instance['number_of_posts']) ? absint($instance['number_of_posts']) : 3;
    ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'softro-core'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>"><?php echo esc_html__('Number of posts to show:', 'softro-core'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number_of_posts')); ?>" name="<?php echo esc_attr($this->get_field_name('number_of_posts')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number_of_posts); ?>" size="3">
        </p>
<?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number_of_posts'] = absint($new_instance['number_of_posts']);
        return $instance;
    }
}

// Register the widget
function register_egns_popular_post_widget()
{
    register_widget('Egns_Popular_Post_Widget');
}
add_action('widgets_init', 'register_egns_popular_post_widget');
