<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package CityLogic
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function citylogic_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'render'    => 'citylogic_infinite_scroll_render',
		'footer'    => false,
		'wrapper'	=> false
	) );
}
add_action( 'after_setup_theme', 'citylogic_jetpack_setup' );

/**
 * Custom render function for Infinite Scroll.
 */
function citylogic_infinite_scroll_render() {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'library/template-parts/content', get_post_format() );
	}
}

/**
 * Remove default Related Posts | Custom added to single.php.
 */
function citylogic_remove_default_related_posts() {
    if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
        $jprp     = Jetpack_RelatedPosts::init();
        $callback = array( $jprp, 'filter_add_target_to_dom' );
 
        remove_filter( 'the_content', $callback, 40 );
    }
}
add_action( 'wp', 'citylogic_remove_default_related_posts', 20 );
