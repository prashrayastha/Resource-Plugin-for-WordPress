<?php
/**
 * Plugin Name: Resources Custom Post & Shortcode
 * Description: Adds a "Resources" custom post type with a [latest_resources] shortcode and fallback placeholder images.
 * Version: 1.1
 * Author: Prashraya Shrestha
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 *  Register Custom Post Type: "Resources"
 */
function rs_register_resources_cpt() {

    $labels = array(
        'name'          => __( 'Resources', 'textdomain' ),
        'singular_name' => __( 'Resource', 'textdomain' ),
    );

    $args = array(
        'labels'        => $labels,
        'public'        => true,
        'menu_icon'     => 'dashicons-media-document',
        'supports'      => array( 'title', 'thumbnail', 'excerpt' ),
        'has_archive'   => true,
        'rewrite'       => array( 'slug' => 'resources' ),
        'show_in_rest'  => true,
    );

    register_post_type( 'resources', $args );
}
add_action( 'init', 'rs_register_resources_cpt' );


/**
 *  Enqueue External CSS File
 */
function rs_enqueue_resources_styles() {
    $css_path = plugins_url( 'assets/styles/resources.css', __FILE__ );
    wp_enqueue_style(
        'rs-resources-style',
        esc_url( $css_path ),
        array(),
        filemtime( plugin_dir_path( __FILE__ ) . 'assets/styles/resources.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'rs_enqueue_resources_styles' );


/**
 *  Return thumbnail or fallback placeholder
 */
function rs_get_resource_thumbnail( $post_id, $size = 'medium' ) {

    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size );
    }

    // Fallback image
    $placeholder = plugins_url( 'assets/images/placeholder-768x768.png', __FILE__ );
    $placeholder = esc_url( $placeholder );

    return '<img src="' . $placeholder . '" alt="Placeholder Image" />';
}


/**
 *  Shortcode: [latest_resources limit="5"]
 */
// Shortcode: [latest_resources limit="5" item=""]
function rs_latest_resources_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 5,
        'item'  => ''   // new attribute
    ), $atts, 'latest_resources');

    $args = array(
        'post_type'      => 'resources',
        'posts_per_page' => intval($atts['limit']),
        'post_status'    => 'publish',
    );

    // If "item" attribute is provided, fetch only that post ID
    if (!empty($atts['item']) && is_numeric($atts['item'])) {
        $args['post__in'] = array(intval($atts['item']));
        $args['posts_per_page'] = 1; // override limit
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '<p>No resources found.</p>';
    }

    ob_start();
    ?>
    <div class="rs-resources-grid">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <div class="rs-resource-item">
                <div class="rs-resource-thumb">
                    <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('medium');
                    } else {
                        // fallback placeholder
                        echo '<img src="' . plugin_dir_url(__FILE__) . 'assets/images/placeholder-768x768.png" alt="Placeholder">';
                    }
                    ?>
                </div>
                    <div class="rs-resource-content">
                        <h3 class="rs-resource-title"><?php echo esc_html(get_the_title()); ?></h3>

                        <div class="rs-resource-excerpt">
                            <?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?>
                        </div>

                        <a class="rs-resource-link" href="<?php echo esc_url(get_permalink()); ?>">
                            Read More ->
                        </a>
                    </div>
            </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('latest_resources', 'rs_latest_resources_shortcode');
/**
 * Automatically display featured image on single Resource pages.
 */
function rs_add_featured_image_to_single( $content ) {

    if ( is_singular( 'resources' ) && in_the_loop() && is_main_query() ) {

        if ( has_post_thumbnail() ) {
            $img = get_the_post_thumbnail( get_the_ID(), 'large', [
    'style' => 'display:block;margin:0 auto 20px auto;max-width:100%;height:auto;border-radius:10px;'
]);

        } else {
            $placeholder = esc_url( plugins_url( 'assets/images/placeholder.jpg', __FILE__ ) );
           $img = '<img src="' . $placeholder . '" style="display:block;margin:0 auto 20px auto;max-width:100%;height:auto;border-radius:10px;" />';

        }

        return $img . $content;
    }

    return $content;
}
add_filter( 'the_content', 'rs_add_featured_image_to_single' );
