<?php
/**
 * Plugin Name: Custom sections Shortcodes
 * Description: Shortcode to display posts in a custom flexbox layout.
 * Version: 1.0
 * Author: abdullah mohamed
 */

 // add assets
function custom_posts_enqueue_assets() {
    wp_enqueue_style('custom-posts-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('custom-posts-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_posts_enqueue_assets');

// to display posts services
function custom_posts_shortcode($atts) {
    // shortcode attributes
    $atts = shortcode_atts(array(
        'count' => 5, // default number of posts to display
        'category'=> 'services',
    ), $atts, 'custom_posts');

    // get posts
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['count']),
        'post_category'=> $atts['category'],
    );

    $query = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $title = get_the_title();
            $excerpt = get_the_excerpt();

            $output .= '
                <div style="display: flex; justify-content: space-evenly; padding: 10px 40px; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #ccc;">
                    <img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" width="200px" />
                    <div style="max-width: 600px;">
                        <h1 style="margin: 0 0 10px;">' . esc_html($title) . '</h1>
                        <p>' . esc_html($excerpt) . '</p>
                    </div>
                </div>';
        }
        wp_reset_postdata();
    } else {
        $output = '<p>No posts found.</p>';
    }

    return $output;
}
add_shortcode('custom_posts', 'custom_posts_shortcode');
