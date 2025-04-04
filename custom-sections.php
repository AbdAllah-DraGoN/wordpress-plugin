<?php
/**
 * Plugin Name: Custom Sections Shortcodes
 * Description: Shortcode to display posts in a custom flexbox layout.
 * Version: 1.0
 * Author: Abdullah Mohamed
 */

// Load CSS & JS assets
function custom_posts_enqueue_assets() {
    wp_enqueue_style('custom-posts-style', plugin_dir_url(__FILE__) . 'assets/style.css');
    wp_enqueue_script('custom-posts-script', plugin_dir_url(__FILE__) . 'assets/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'custom_posts_enqueue_assets');

// Shortcode to display posts
function custom_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => 5,
        'category' => '',
    ), $atts, 'custom_posts');

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['count']),
    );

    if (!empty($atts['category'])) {
        $args['category_name'] = sanitize_text_field($atts['category']);
    }

    $query = new WP_Query($args);
    $output = '';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $title = get_the_title();
            $excerpt = get_the_excerpt();
            $permalink = get_permalink();

            $output .= '
                <div class="custom-post-box">
                    <img src="' . esc_url($image) . '" alt="' . esc_attr($title) . '" class="custom-post-img" />
                    <div class="custom-post-content">
                        <h1 class="custom-post-title">' . esc_html($title) . '</h1>
                        <p class="custom-post-excerpt">' . esc_html($excerpt) . '</p>
                        <a href="' . esc_url($permalink) . '" class="custom-post-readmore">اقرأ المزيد</a>
                    </div>
                </div>';
        }
        wp_reset_postdata();
    } else {
        $output = '<p>لم يتم العثور على مقالات.</p>';
    }

    return $output;
}
add_shortcode('custom_posts', 'custom_posts_shortcode');
