<?php
/**
 * CityLogic functions and definitions
 *
 * @package CityLogic
 */
define( 'CITYLOGIC_THEME_VERSION' , '1.1.56' );

global $solidify_breakpoint, $mobile_menu_breakpoint, $demo_slides;

if ( empty( $demo_slides ) ) {
	$demo_slides = array(
		'slide1' => array(
			'image' => get_template_directory_uri() . '/library/images/demo/slider-default01.jpg',
			'text' => sprintf( __( '<h1>Super Adaptable</h1><p>Anything and everything you need</p><p><a href="%1$s" target="_blank" class="button no-bottom-margin">%2$s</a></p>', 'citylogic' ), esc_url( 'https://www.outtheboxthemes.com/wordpress-themes/citylogic/' ), __( 'Read More', 'citylogic' ) )
		),
		'slide2' => array(
			'image' => get_template_directory_uri() . '/library/images/demo/slider-default02.jpg',
			'text' => sprintf( __( '<h2>Your Go-to Theme</h2><p>From Out the Box</p><p><a href="%1$s" target="_blank" class="button no-bottom-margin">%2$s</a></p>', 'citylogic' ), esc_url( 'https://www.outtheboxthemes.com/wordpress-themes/citylogic/' ), __( 'Read More', 'citylogic' ) )
		)
	);
}

if ( !function_exists( 'citylogic_theme_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function citylogic_theme_setup() {
	
	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 837; /* pixels */
	}
	
	$editor_styles = array( 'library/css/editor-style.css' );
	
	$editor_styles[] = citylogic_fonts_url();
	
	add_editor_style( $editor_styles );

	// Setting this to true can be used to test how the Premium theme will look for someone that had the free version intalled beforehand
	if ( !get_theme_mod( 'otb_citylogic_dot_org' ) ) set_theme_mod( 'otb_citylogic_dot_org', true );
	if ( !get_theme_mod( 'otb_citylogic_activated' ) ) set_theme_mod( 'otb_citylogic_activated', date('Y-m-d') );

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on CityLogic, use a find and replace
	 * to change 'citylogic' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'citylogic', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'citylogic' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'navigation-widgets'
		)
	);
	
	/*
	 * Setup Custom Logo Support for theme
	* Supported from WordPress version 4.5 onwards
	* More Info: https://make.wordpress.org/core/2016/03/10/custom-logo/
	*/
	if ( function_exists( 'has_custom_logo' ) ) {
		add_theme_support( 'custom-logo' );
	}
	
	// The custom header is used if no slider is enabled
	add_theme_support( 'custom-header', array(
        'default-image' => get_template_directory_uri() . '/library/images/headers/default04.jpg',
		'width'         => 1500,
		'height'        => 744,
		'flex-width'    => true,
		'flex-height'   => true,
		'header-text'   => false,
		'video' 		=> false,
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'citylogic_custom_background_args', array(
		'default-image' => '',
	) ) );
    
    add_theme_support( 'title-tag' );
    
	// Gutenberg Support
    add_theme_support( 'align-wide' );
	
	// Toggle WordPress 5.8+ block-based widgets
	if ( !get_theme_mod( 'citylogic-gutenberg-enable-block-based-widgets', customizer_library_get_default( 'citylogic-gutenberg-enable-block-based-widgets' ) ) ) {
		remove_theme_support( 'widgets-block-editor' );
	}
    
 	add_theme_support( 'woocommerce', array(
 		'gallery_thumbnail_image_width' => 300
 	) );
	
	if ( get_theme_mod( 'citylogic-woocommerce-product-image-zoom', true ) ) {	
		add_theme_support( 'wc-product-gallery-zoom' );
	}	
	
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-spacing' );
}
endif; // citylogic_theme_setup
add_action( 'after_setup_theme', 'citylogic_theme_setup' );

// Unhide modern markup setting in admin
add_filter( 'wpforms_admin_settings_modern_markup_register_field_is_hidden', '__return_false' );

if ( ! function_exists( 'citylogic_fonts_url' ) ) :
	/**
	 * Register custom fonts.
	 */
	function citylogic_fonts_url() {
		$fonts_url = '';
	
		$font_families = array();
		
		$font_families[] = 'Montserrat:100,300,400,500,600,700,800';
		$font_families[] = 'Open Sans:300,300italic,400,400italic,600,600italic,700,700italic';
		$font_families[] = 'Lora:400italic';
		
		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
	
		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	
		return esc_url_raw( $fonts_url );
	}
endif;

/**
 * Enqueue admin scripts and styles.
 */
function citylogic_admin_scripts() {
	wp_enqueue_style( 'citylogic-admin', get_template_directory_uri() . '/library/css/admin.css', array(), CITYLOGIC_THEME_VERSION );
	wp_enqueue_script( 'citylogic-admin', get_template_directory_uri() . '/library/js/admin.js', CITYLOGIC_THEME_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'citylogic_admin_scripts' );

// Adjust content_width for full width pages
function citylogic_adjust_content_width() {
    global $content_width;

	if ( citylogic_is_woocommerce_activated() && is_woocommerce() ) {
		$is_woocommerce = true;
	} else {
		$is_woocommerce = false;
	}

    if ( is_page_template( 'template-full-width.php' ) || is_page_template( 'template-full-width-no-bottom-margin.php' ) ) {
    	$content_width = 1140;
	} else if ( ( is_page_template( 'template-left-primary-sidebar.php' ) || basename( get_page_template() ) === 'page.php' ) && !is_active_sidebar( 'sidebar-1' ) ) {
		$content_width = 1140;
	} else if ( citylogic_is_woocommerce_activated() && is_shop() && get_theme_mod( 'citylogic-layout-woocommerce-shop-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-shop-full-width' ) ) ) {
		$content_width = 1140;
	} else if ( citylogic_is_woocommerce_activated() && is_product() && get_theme_mod( 'citylogic-layout-woocommerce-product-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-product-full-width' ) ) ) {
		$content_width = 1140;
	} else if ( citylogic_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) && get_theme_mod( 'citylogic-layout-woocommerce-category-tag-page-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-category-tag-page-full-width' ) ) ) {
		$content_width = 1140;
	} else if ( citylogic_is_woocommerce_activated() && !is_active_sidebar( 'sidebar-1' ) && ( is_shop() && !get_theme_mod( 'citylogic-layout-woocommerce-shop-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-shop-full-width' ) ) || is_product() && !get_theme_mod( 'citylogic-layout-woocommerce-product-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-product-full-width' ) ) ) ) {
		$content_width = 1140;
	}
}
add_action( 'template_redirect', 'citylogic_adjust_content_width' );

function citylogic_review_notice() {
	$user_id = get_current_user_id();
	$message = 'Thank you for using CityLogic! We hope you\'re enjoying the theme, please consider <a href="https://wordpress.org/support/theme/citylogic/reviews/#new-post" target="_blank">rating it on wordpress.org</a> :)';
	
	if ( !get_user_meta( $user_id, 'citylogic_review_notice_dismissed' ) ) {
		$class = 'notice notice-success is-dismissible';
		printf( '<div class="%1$s"><p>%2$s</p><p><a href="?citylogic-review-notice-dismissed">Dismiss this notice</a></p></div>', esc_attr( $class ), $message );
	}
}
$today = new DateTime( date( 'Y-m-d' ) );
$activate  = new DateTime( date( get_theme_mod( 'otb_citylogic_activated' ) ) );
if ( $activate->diff($today)->d >= 14 ) {
	add_action( 'admin_notices', 'citylogic_review_notice' );
}

function citylogic_review_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['citylogic-review-notice-dismissed'] ) ) {
		add_user_meta( $user_id, 'citylogic_review_notice_dismissed', 'true', true );
	}
}
add_action( 'admin_init', 'citylogic_review_notice_dismissed' );

/**
 * Get today's date in site timezone.
 */
function citylogic_today_ymd() {
    return date( 'Y-m-d', current_time( 'timestamp' ) );
}

/**
 * Get Black Friday (4th Friday of November) and Cyber Monday for the current year.
 *
 * @return array {
 *   'start'        => 'Y-m-d', // Black Friday
 *   'end'          => 'Y-m-d', // Cyber Monday
 *   'year'         => 'YYYY',
 *   'black_friday' => 'Y-m-d',
 *   'cyber_monday' => 'Y-m-d',
 * }
 */
function citylogic_get_black_friday_window() {
    $ts    = current_time( 'timestamp' );
    $year  = (int) date( 'Y', $ts );
    $month = 11;

    $friday_count    = 0;
    $black_friday_ts = null;

    // Find 4th Friday in November.
    for ( $day = 1; $day <= 30; $day++ ) {
        $day_ts = mktime( 0, 0, 0, $month, $day, $year );
        if ( date( 'N', $day_ts ) === '5' ) { // 5 = Friday
            $friday_count++;
            if ( 4 === $friday_count ) {
                $black_friday_ts = $day_ts;
                break;
            }
        }
    }

    if ( ! $black_friday_ts ) {
        // Fallback: last Friday of November.
        $black_friday_ts = strtotime( 'last friday of november ' . $year, $ts );
    }

    // Cyber Monday = Monday after Black Friday (3 days later).
    $cyber_monday_ts = strtotime( '+3 days', $black_friday_ts );

    $black_friday  = date( 'Y-m-d', $black_friday_ts );
    $cyber_monday  = date( 'Y-m-d', $cyber_monday_ts );

    return array(
        'start'        => $black_friday,
        'end'          => $cyber_monday,
        'year'         => $year,
        'black_friday' => $black_friday,
        'cyber_monday' => $cyber_monday,
    );
}


/**
 * Show Black Friday (Fri–Sun) admin notice.
 */
function citylogic_black_friday_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$window = citylogic_get_black_friday_window();
	$today  = citylogic_today_ymd();
	//$today = $window['black_friday']; // should show BF notice

	// Show ONLY from Black Friday up to the day BEFORE Cyber Monday.
	// i.e. Friday, Saturday, Sunday.
	if ( $today < $window['black_friday'] || $today >= $window['cyber_monday'] ) {
		return;
	}

	$user_id = get_current_user_id();

	// Per-year ID so it can reappear each year.
	$message = array(
		'id'      => 'black_friday_' . $window['year'],
		'heading' => __( 'Black Friday Weekend Sale', 'citylogic' ),
		'text'    => sprintf(
			__( '<a href="%1$s" target="_blank"><span style="font-size: 20px">🖤</span>Get 40%% off any of our Premium WordPress themes this Black Friday weekend!<span style="font-size: 20px">🖤</span></a>', 'citylogic' ),
			'https://www.outtheboxthemes.com/go/theme-notification-black-friday-2025-wordpress-themes/'
		),
		'link'    => 'https://www.outtheboxthemes.com/go/theme-notification-black-friday-2025-wordpress-themes/',
	);

	// Dismiss check (string ID).
	if ( ! empty( $message['text'] ) && get_user_meta( $user_id, 'citylogic_admin_notice_' . $message['id'] . '_dismissed', true ) ) {
		return;
	}

	$class = 'notice otb-notice notice-success is-dismissible';

	// Safer dismiss URL with nonce.
	$dismiss_url = wp_nonce_url(
		add_query_arg(
			array(
				'citylogic-admin-notice-dismissed' => '1',
				'citylogic-admin-notice-id'        => $message['id'],
			)
		),
		'citylogic_dismiss_notice_' . $message['id']
	);

	printf(
		'<div class="%1$s"><img src="%2$s" class="logo" /><h3>%3$s</h3><p>%4$s</p><p style="margin:0;"><a class="button button-primary" href="%5$s" target="_blank" rel="noopener noreferrer">%6$s</a> <a class="button button-dismiss" href="%7$s">%8$s</a></p></div>',
		esc_attr( $class ),
		esc_url( 'https://www.outtheboxthemes.com/wp-content/uploads/2025/11/logo-charcoal@2x.webp' ),
		esc_html( $message['heading'] ),
		$message['text'],
		esc_url( $message['link'] ),
		esc_html__( 'Read More', 'citylogic' ),
		esc_url( $dismiss_url ),
		esc_html__( 'Dismiss', 'citylogic' )
	);
}
add_action( 'admin_notices', 'citylogic_black_friday_notice' );

/**
 * Show Cyber Monday–only admin notice.
 */
function citylogic_cyber_monday_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$window = citylogic_get_black_friday_window();
	$today  = citylogic_today_ymd();
	//$today = $window['cyber_monday']; // BF notice should NOT show

	// Only show ON Cyber Monday.
	if ( $today !== $window['cyber_monday'] ) {
		return;
	}

	$user_id = get_current_user_id();

	$message = array(
		'id'      => 'cyber_monday_' . $window['year'],
		'heading' => __( 'Cyber Monday Sale', 'citylogic' ),
		'text'    => sprintf(
			__( '<a href="%1$s" target="_blank"><i class="fas fa-terminal"></i>Cyber Monday specials activated… <span class="otb-cursor" aria-hidden="true"></span></a>', 'citylogic' ),
			'https://www.outtheboxthemes.com/go/theme-notification-cyber-monday-2025-wordpress-themes/'
		),
		'link'    => 'https://www.outtheboxthemes.com/go/theme-notification-cyber-monday-2025-wordpress-themes/',
	);

	// Different ID → even if they dismissed BF, this can still show.
	if ( ! empty( $message['text'] ) && get_user_meta( $user_id, 'citylogic_admin_notice_' . $message['id'] . '_dismissed', true ) ) {
		return;
	}

	$class = 'notice otb-notice matrix notice-success is-dismissible';

	$dismiss_url = wp_nonce_url(
		add_query_arg(
			array(
				'citylogic-admin-notice-dismissed' => '1',
				'citylogic-admin-notice-id'        => $message['id'],
			)
		),
		'citylogic_dismiss_notice_' . $message['id']
	);

	printf(
		'<div class="%1$s"><img src="%2$s" class="logo" /><h3>%3$s</h3><p>%4$s</p><p style="margin:0;"><a class="button button-primary" href="%5$s" target="_blank" rel="noopener noreferrer">%6$s</a> <a class="button button-dismiss" href="%7$s">%8$s</a></p></div>',
		esc_attr( $class ),
		esc_url( 'https://www.outtheboxthemes.com/wp-content/uploads/2025/11/logo-matrix@2x.webp' ),
		esc_html( $message['heading'] ),
		$message['text'],
		esc_url( $message['link'] ),
		esc_html__( 'Read More', 'citylogic' ),
		esc_url( $dismiss_url ),
		esc_html__( 'Dismiss', 'citylogic' )
	);
}
add_action( 'admin_notices', 'citylogic_cyber_monday_notice' );

function citylogic_christmas_notice() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$ts    = current_time( 'timestamp' );
	$year  = (string) date( 'Y', $ts );
	$today = citylogic_today_ymd();
	//$today = '2025-12-10'; // any date between 2025-12-01 and 2025-12-25

	$start = $year . '-12-01';
	$end   = $year . '-12-25';

	// Only show 1–25 December (inclusive).
	if ( $today < $start || $today > $end ) {
		return;
	}

	$user_id = get_current_user_id();

	$message = array(
		'id'      => 'christmas_' . $year,
		'heading' => __( 'Christmas Sale', 'citylogic' ),
		'text'    => sprintf(
			__( '<a href="%1$s" target="_blank"><span style="font-size: 20px">🎄</span>Get 20%% off any of our Premium WordPress themes until Christmas Day!<span style="font-size: 20px">🎄</span></a>', 'citylogic' ),
			'https://www.outtheboxthemes.com/go/theme-notification-christmas-day-2025-wordpress-themes/'
		),
		'link'    => 'https://www.outtheboxthemes.com/go/theme-notification-christmas-day-2025-wordpress-themes/',
	);

	if ( ! empty( $message['text'] ) && get_user_meta( $user_id, 'citylogic_admin_notice_' . $message['id'] . '_dismissed', true ) ) {
		return;
	}

	$class = 'notice otb-notice red notice-success is-dismissible';

	$dismiss_url = wp_nonce_url(
		add_query_arg(
			array(
				'citylogic-admin-notice-dismissed' => '1',
				'citylogic-admin-notice-id'        => $message['id'],
			)
		),
		'citylogic_dismiss_notice_' . $message['id']
	);

	printf(
		'<div class="%1$s"><img src="%2$s" class="logo" /><h3>%3$s</h3><p>%4$s</p><p style="margin:0;"><a class="button button-primary" href="%5$s" target="_blank" rel="noopener noreferrer">%6$s</a> <a class="button button-dismiss" href="%7$s">%8$s</a></p></div>',
		esc_attr( $class ),
		esc_url( 'https://www.outtheboxthemes.com/wp-content/uploads/2025/11/logo-red@2x.webp' ),
		esc_html( $message['heading'] ),
		$message['text'],
		esc_url( $message['link'] ),
		esc_html__( 'Read More', 'citylogic' ),
		esc_url( $dismiss_url ),
		esc_html__( 'Dismiss', 'citylogic' )
	);
}
add_action( 'admin_notices', 'citylogic_christmas_notice' );

/*
function citylogic_admin_notice() {
	$user_id = get_current_user_id();
	
	$message = array (
		'id' => 21,
		'heading' => 'Christmas Sale',
		//'text' => '<a href="https://www.outtheboxthemes.com/go/theme-notification-black-friday-2024-wordpress-themes/">Get 40% off any of our Premium WordPress themes this Black Friday!</a>',
		'text' => '<a href="https://www.outtheboxthemes.com/go/theme-notification-christmas-day-2024-wordpress-themes/" target="_blank"><span style="font-size: 20px">🎄</span>Get 20% off any of our Premium WordPress themes until Christmas Day!<span style="font-size: 20px">🎄</span></a>',
		'link' => 'https://www.outtheboxthemes.com/go/theme-notification-christmas-day-2024-wordpress-themes/'
	);
	
	if ( !empty( $message['text'] ) && !get_user_meta( $user_id, 'citylogic_admin_notice_' .$message['id']. '_dismissed' ) ) {
		$class = 'notice otb-notice red notice-success is-dismissible';
		printf( '<div class="%1$s"><img src="https://www.outtheboxthemes.com/wp-content/uploads/2020/12/logo-red.png" class="logo" /><h3>%2$s</h3><p>%3$s</p><p style="margin:0;"><a class="button button-primary" href="%4$s" target="_blank">Read More</a> <a class="button button-dismiss" href="?citylogic-admin-notice-dismissed&citylogic-admin-notice-id=%5$s">Dismiss</a></p></div>', esc_attr( $class ), $message['heading'], $message['text'], $message['link'], $message['id'] );
	}
}

if ( date('Y-m-d') >= '2024-11-29' && date('Y-m-d') <= '2024-12-25' ) {
	add_action( 'admin_notices', 'citylogic_admin_notice' );
}

function citylogic_admin_notice_dismissed() {
    $user_id = get_current_user_id();
    if ( isset( $_GET['citylogic-admin-notice-dismissed'] ) ) {
    	$citylogic_admin_notice_id = absint( $_GET['citylogic-admin-notice-id'] );
		add_user_meta( $user_id, 'citylogic_admin_notice_' .$citylogic_admin_notice_id. '_dismissed', 'true', true );
	}
}
add_action( 'admin_init', 'citylogic_admin_notice_dismissed' );
*/

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function citylogic_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Primary Sidebar', 'citylogic' ),
		'id'            => 'sidebar-1',
		'description'   => 'This sidebar will appear on the Blog or any page that uses either the Default or Left Primary Sidebar template.',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>'
	) );
	
	register_sidebar(array(
		'name' => __( 'Footer', 'citylogic' ),
		'id' => 'footer',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div><div class="divider"></div>' 
	));
	
	register_sidebar(array(
		'name' => __( 'Footer Bottom Bar - Right', 'citylogic' ),
		'id' => 'footer-bottom-bar-right',
        'description'   => '',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
    	'after_widget' => '</div>' 
	));
}
add_action( 'widgets_init', 'citylogic_widgets_init' );

function citylogic_set_variables() {
	global $solidify_breakpoint, $mobile_menu_breakpoint;
	
	$mobile_menu_breakpoint = 1000;
	$solidify_breakpoint = 1000;
}
add_action('init', 'citylogic_set_variables', 10);

/**
 * Enqueue scripts and styles.
 */
function citylogic_theme_scripts() {
	global $solidify_breakpoint;
	
	wp_enqueue_style( 'citylogic-fonts', citylogic_fonts_url(), array(), CITYLOGIC_THEME_VERSION );
	wp_enqueue_style( 'citylogic-header-left-aligned', get_template_directory_uri().'/library/css/header-left-aligned.css', array(), CITYLOGIC_THEME_VERSION );
	
	if ( get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) ) == '4.7.0' ) {
		wp_enqueue_style( 'otb-font-awesome-otb-font-awesome', get_template_directory_uri().'/library/fonts/otb-font-awesome/css/otb-font-awesome.css', array(), '4.7.0' );
		wp_enqueue_style( 'otb-font-awesome-font-awesome-min', get_template_directory_uri().'/library/fonts/otb-font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
	} else if ( get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) ) == '5.5.0' ) {
		wp_enqueue_style( 'otb-font-awesome', '//use.fontawesome.com/releases/v5.5.0/css/all.css', array(), '5.5.0' );
	} else {
		wp_enqueue_style( 'otb-font-awesome', '//use.fontawesome.com/releases/v6.7.2/css/all.css', array(), '6.7.2' );
	}
	
	wp_enqueue_style( 'citylogic-style', get_stylesheet_uri(), array(), CITYLOGIC_THEME_VERSION );
	
	if ( citylogic_is_woocommerce_activated() ) {
    	wp_enqueue_style( 'citylogic-woocommerce-custom', get_template_directory_uri().'/library/css/woocommerce-custom.css', array(), CITYLOGIC_THEME_VERSION );
	}

	if ( class_exists( 'Wp_Travel_Engine' ) ) {
		wp_enqueue_style( 'citylogic-wp-travel-engine', get_template_directory_uri().'/library/css/wp-travel-engine.css', array(), CITYLOGIC_THEME_VERSION );
	}
	
	wp_enqueue_script( 'citylogic-navigation', get_template_directory_uri() . '/library/js/navigation.js', array(), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'caroufredsel', get_template_directory_uri() . '/library/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'citylogic-touchswipe', get_template_directory_uri() . '/library/js/jquery.touchSwipe.min.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'citylogic-color', get_template_directory_uri() . '/library/js/jquery.color.min.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'citylogic-fittext', get_template_directory_uri() . '/library/js/jquery.fittext.min.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'citylogic-fitbutton', get_template_directory_uri() . '/library/js/jquery.fitbutton.min.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	wp_enqueue_script( 'citylogic-custom', get_template_directory_uri() . '/library/js/custom.js', array('jquery'), CITYLOGIC_THEME_VERSION, true );
	
    $citylogic_client_side_variables = array(
    	'site_url' 				=> site_url(),
    	'solidify_breakpoint' 	=> $solidify_breakpoint,
    	'sliderTransitionSpeed' => intval( get_theme_mod( 'citylogic-slider-transition-speed', customizer_library_get_default( 'citylogic-slider-transition-speed' ) ) ),
    	'fontAwesomeVersion'	=> get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) )
    );
    
    wp_localize_script( 'citylogic-custom', 'citylogic', $citylogic_client_side_variables );

	wp_enqueue_script( 'citylogic-skip-link-focus-fix', get_template_directory_uri() . '/library/js/skip-link-focus-fix.js', array(), CITYLOGIC_THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'citylogic_theme_scripts' );

function citylogic_set_elementor_default_schemes( $config ) {
	// Primary
	$config['schemes']['items']['color']['items']['1']['value'] = get_theme_mod( 'citylogic-heading-font-color', customizer_library_get_default( 'citylogic-heading-font-color' ) );
	
	// Secondary
	$config['schemes']['items']['color']['items']['2']['value'] = get_theme_mod( 'citylogic-primary-color', customizer_library_get_default( 'citylogic-primary-color' ) );
	
	// Text
	$config['schemes']['items']['color']['items']['3']['value'] = get_theme_mod( 'citylogic-body-font-color', customizer_library_get_default( 'citylogic-body-font-color' ) );
	
	// Accent
	$config['schemes']['items']['color']['items']['4']['value'] = get_theme_mod( 'citylogic-primary-color', customizer_library_get_default( 'citylogic-primary-color' ) );

	// Primary Headline
	$config['schemes']['items']['typography']['items']['1']['value'] = [
		'font-family' => get_theme_mod( 'citylogic-heading-font', customizer_library_get_default( 'citylogic-heading-font' ) ),
		'font-weight' => get_theme_mod( 'citylogic-heading-font-weight', customizer_library_get_default( 'citylogic-heading-font-weight' ) )
	];
	
	// Secondary Headline
	$config['schemes']['items']['typography']['items']['2']['value'] = [
		'font-family' => get_theme_mod( 'citylogic-heading-font', customizer_library_get_default( 'citylogic-heading-font' ) ),
		'font-weight' => get_theme_mod( 'citylogic-heading-font-weight', customizer_library_get_default( 'citylogic-heading-font-weight' ) )
	];

	// Body Text
	$config['schemes']['items']['typography']['items']['3']['value'] = [
		'font-family' => get_theme_mod( 'citylogic-body-font', customizer_library_get_default( 'citylogic-body-font' ) ),
		'font-weight' => get_theme_mod( 'citylogic-body-font-weight', customizer_library_get_default( 'citylogic-body-font-weight' ) )
	];

	// Accent Text
	$config['schemes']['items']['typography']['items']['4']['value'] = [
		'font-family' => get_theme_mod( 'citylogic-heading-font', customizer_library_get_default( 'citylogic-heading-font' ) ),
		'font-weight' => '400'
	];

	$config['schemes']['items']['color-picker']['items']['1']['value'] = get_theme_mod( 'citylogic-primary-color', customizer_library_get_default( 'citylogic-primary-color' ) );
	$config['schemes']['items']['color-picker']['items']['2']['value'] = get_theme_mod( 'citylogic-secondary-color', customizer_library_get_default( 'citylogic-secondary-color' ) );
	$config['schemes']['items']['color-picker']['items']['3']['value'] = get_theme_mod( 'citylogic-body-font-color', customizer_library_get_default( 'citylogic-body-font-color' ) );
	$config['schemes']['items']['color-picker']['items']['4']['value'] = get_theme_mod( 'citylogic-link-color', customizer_library_get_default( 'citylogic-link-color' ) );
	$config['schemes']['items']['color-picker']['items']['5']['value'] = get_theme_mod( 'citylogic-footer-color', customizer_library_get_default( 'citylogic-footer-color' ) );
	$config['schemes']['items']['color-picker']['items']['6']['value'] = '';
	$config['schemes']['items']['color-picker']['items']['7']['value'] = '';
	$config['schemes']['items']['color-picker']['items']['8']['value'] = '';
	
	return $config;
};
add_filter('elementor/editor/localize_settings', 'citylogic_set_elementor_default_schemes', 100);

/**
 * Load Gutenberg stylesheet.
*/
function citylogic_gutenberg_assets() {
	wp_enqueue_style( 'citylogic-gutenberg-editor', get_theme_file_uri( '/library/css/gutenberg-editor-style.css' ), false, CITYLOGIC_THEME_VERSION );
	
	// Output inline styles based on theme customizer selections
	require get_template_directory() . '/library/includes/gutenberg-editor-styles.php';
}
add_action( 'enqueue_block_editor_assets', 'citylogic_gutenberg_assets' );

// Recommended plugins installer
require_once get_template_directory() . '/library/includes/class-tgm-plugin-activation.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/library/includes/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/library/includes/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/library/includes/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/library/includes/jetpack.php';

// Helper library for the theme customizer.
require get_template_directory() . '/customizer/customizer-library/customizer-library.php';

// Define options for the theme customizer.
require get_template_directory() . '/customizer/customizer-options.php';

// Output inline styles based on theme customizer selections.
require get_template_directory() . '/customizer/styles.php';

// Additional filters and actions based on theme customizer selections.
require get_template_directory() . '/customizer/mods.php';

// Include TRT Customize Pro library
require_once( get_template_directory() . '/trt-customize-pro/class-customize.php' );

/**
 * Premium Upgrade Page
 */
include get_template_directory() . '/upgrade/upgrade.php';

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function citylogic_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'citylogic_pingback_header' );

if ( !function_exists( 'citylogic_load_dynamic_css' ) ) :

	/**
	 * Add Dynamic CSS
	 */
	function citylogic_load_dynamic_css() {
		global $solidify_breakpoint, $mobile_menu_breakpoint;
		
		$citylogic_slider_has_min_width 	   = get_theme_mod( 'citylogic-slider-has-min-width', customizer_library_get_default( 'citylogic-slider-has-min-width' ) );
		$citylogic_slider_min_width 		   = floatVal( get_theme_mod( 'citylogic-slider-min-width', customizer_library_get_default( 'citylogic-slider-min-width' ) ) );
		$citylogic_header_image_has_min_width = get_theme_mod( 'citylogic-header-image-has-min-width', customizer_library_get_default( 'citylogic-header-image-has-min-width' ) );
		$citylogic_header_image_min_width 	   = floatVal( get_theme_mod( 'citylogic-header-image-min-width', customizer_library_get_default( 'citylogic-header-image-min-width' ) ) );

		$include_dir;
		
		if ( file_exists( get_stylesheet_directory() . '/library/includes/dynamic-css.php' ) ) {
			$include_dir = get_stylesheet_directory();
		} else {
			$include_dir = get_template_directory();
		}
		
		require $include_dir . '/library/includes/dynamic-css.php';
	}
endif;
add_action( 'wp_head', 'citylogic_load_dynamic_css' );

// Create function to check if WooCommerce exists.
if ( !function_exists( 'citylogic_is_woocommerce_activated' ) ) :
	function citylogic_is_woocommerce_activated() {
	    if ( class_exists( 'woocommerce' ) ) {
	    	return true;
		} else {
			return false;
		}
	}
endif; // citylogic_is_woocommerce_activated

if ( citylogic_is_woocommerce_activated() ) {
    require get_template_directory() . '/library/includes/woocommerce-inc.php';
}

// Add CSS class to body by filter
function citylogic_add_body_class( $classes ) {
	
	$classes[] = get_theme_mod( 'citylogic-paragraph-line-height', customizer_library_get_default( 'citylogic-paragraph-line-height' ) );
	$classes[] = 'font-awesome-' . get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) );
	
	if( wp_is_mobile() ) {
		$classes[] = 'mobile-device';
	}
	
	if ( get_theme_mod( 'citylogic-media-crisp-images', customizer_library_get_default( 'citylogic-media-crisp-images' ) ) ) {
		$classes[] = 'crisp-images';
	}

	if ( !get_theme_mod( 'citylogic-show-recaptcha-badge', customizer_library_get_default( 'citylogic-show-recaptcha-badge' ) ) ) {
		$classes[] = 'hide-recaptcha-badge';
	}

	if ( get_theme_mod( 'citylogic-content-links-have-underlines', customizer_library_get_default( 'citylogic-content-links-have-underlines' ) ) ) {
		$classes[] = 'content-links-have-underlines';
	}

	if ( get_theme_mod( 'citylogic-page-builders-use-theme-styles', customizer_library_get_default( 'citylogic-page-builders-use-theme-styles' ) ) ) {
		$classes[] = 'citylogic-page-builders-use-theme-styles';
	}
	
	if ( get_theme_mod( 'citylogic-bbpress-use-theme-styles', customizer_library_get_default( 'citylogic-bbpress-use-theme-styles' ) ) ) {
		$classes[] = 'citylogic-bbpress-use-theme-styles';
	}
	
	if ( get_theme_mod( 'citylogic-bookingpress-use-theme-styles', customizer_library_get_default( 'citylogic-bookingpress-use-theme-styles' ) ) ) {
		$classes[] = 'citylogic-bookingpress-use-theme-styles';
	}
	
	if ( citylogic_is_woocommerce_activated() && is_shop() && get_theme_mod( 'citylogic-layout-woocommerce-shop-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-shop-full-width' ) ) ) {
		$classes[] = 'citylogic-shop-full-width';
	}
	
	if ( citylogic_is_woocommerce_activated() && is_product() && get_theme_mod( 'citylogic-layout-woocommerce-product-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-product-full-width' ) ) ) {
		$classes[] = 'citylogic-product-full-width';
	}
	
	if ( citylogic_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) && get_theme_mod( 'citylogic-layout-woocommerce-category-tag-page-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-category-tag-page-full-width' ) ) ) {
		$classes[] = 'citylogic-shop-full-width';
	}
	
	if ( !get_theme_mod( 'citylogic-woocommerce-breadcrumbs', customizer_library_get_default( 'citylogic-woocommerce-breadcrumbs' ) ) ) {
		$classes[] = 'citylogic-shop-no-breadcrumbs';
	}
	
	if ( citylogic_is_woocommerce_activated() && is_woocommerce() ) {
		$is_woocommerce = true;
	} else {
		$is_woocommerce = false;
	}

	if ( citylogic_is_woocommerce_activated() && is_shop() && !is_active_sidebar( 'sidebar-1' ) && !get_theme_mod( 'citylogic-layout-woocommerce-shop-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-shop-full-width' ) ) ) {
		$classes[] = 'full-width';
	} else if ( citylogic_is_woocommerce_activated() && is_product() && !is_active_sidebar( 'sidebar-1' ) && !get_theme_mod( 'citylogic-layout-woocommerce-product-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-product-full-width' ) ) ) {
		$classes[] = 'full-width';
	} else if ( citylogic_is_woocommerce_activated() && ( is_product_category() || is_product_tag() ) && !is_active_sidebar( 'sidebar-1' ) && !get_theme_mod( 'citylogic-layout-woocommerce-category-tag-page-full-width', customizer_library_get_default( 'citylogic-layout-woocommerce-category-tag-page-full-width' ) ) ) {
		$classes[] = 'full-width';
	}

	return $classes;
}
add_filter( 'body_class', 'citylogic_add_body_class' );

/**
 * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
 */
if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Fire the wp_body_open action.
	 */
	function wp_body_open() {
		/**
		 * Triggered after the opening <body> tag.
		 */
		do_action( 'wp_body_open' );
	}
endif;

add_action( 'woocommerce_before_shop_loop_item_title', function() {
	if ( get_theme_mod( 'citylogic-woocommerce-shop-display-thumbnail-loader-animation', customizer_library_get_default( 'citylogic-woocommerce-shop-display-thumbnail-loader-animation' ) ) ) {
		echo '<div class="hiddenUntilLoadedImageContainer loading">';
	}
}, 9 );

add_action( 'woocommerce_before_shop_loop_item_title', function() {
	if ( get_theme_mod( 'citylogic-woocommerce-shop-display-thumbnail-loader-animation', customizer_library_get_default( 'citylogic-woocommerce-shop-display-thumbnail-loader-animation' ) ) ) {
		echo '</div>';
	}
}, 11 );

// Set the number or products per page
if ( ! function_exists( 'citylogic_loop_shop_per_page' ) ) {
	function citylogic_loop_shop_per_page( $cols ) {
		// $cols contains the current number of products per page based on the value stored on Options -> Reading
		// Return the number of products you wanna show per page.
		$cols = get_theme_mod( 'citylogic-woocommerce-products-per-page' );
		
		return $cols;
	}
}
add_filter( 'loop_shop_per_page', 'citylogic_loop_shop_per_page', 20 );

if ( ! function_exists( 'citylogic_woocommerce_product_thumbnails_columns' ) ) {
	function citylogic_woocommerce_product_thumbnails_columns() {
		return 3;
	}
}
add_filter ( 'woocommerce_product_thumbnails_columns', 'citylogic_woocommerce_product_thumbnails_columns' );

/**
 * Replace Read more buttons for out of stock items
 */
// Display an Out of Stock label on out of stock products
if ( ! function_exists( 'citylogic_out_of_stock_notice' ) ) {
	function citylogic_out_of_stock_notice() {
	    global $product;
	    if ( !$product->is_in_stock() ) {
			echo '<p class="stock out-of-stock">';
			echo __( 'Out of Stock', 'citylogic' );
			echo '</p>';
	    }
	}
}
add_action( 'woocommerce_after_shop_loop_item_title', 'citylogic_out_of_stock_notice', 10 );

// Set the blog excerpt length
if ( ! function_exists( 'citylogic_excerpt_length' ) ) {
	function citylogic_excerpt_length( $length ) {
		if ( is_admin() || ( !is_home() && !is_category() && !is_tag() && !is_search() ) ) {
			return $length;
		} else {
			return intval( get_theme_mod( 'citylogic-blog-excerpt-length', customizer_library_get_default( 'citylogic-blog-excerpt-length' ) ) );
		}
	}
}
add_filter( 'excerpt_length', 'citylogic_excerpt_length', 999 );

// Set the blog excerpt read more text
if ( ! function_exists( 'citylogic_excerpt_more' ) ) {
	function citylogic_excerpt_more( $more ) {
		// If in the admin then display the default read more
		if ( is_admin() ) {
			return $more;
		} else {
			return ' <a class="read-more" href="' . esc_url( get_permalink( get_the_ID() ) ) . '">' . wp_kses_post( pll__( get_theme_mod( 'citylogic-blog-read-more-text', customizer_library_get_default( 'citylogic-blog-read-more-text' ) ) ) ) . '</a>';
		}
	}
}
add_filter( 'excerpt_more', 'citylogic_excerpt_more' );

// Set the site logo URL
function citylogic_custom_logo_url( $html ) {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	
	$logo_link_content = home_url( '/' );
	
	$html = sprintf( '<a href="%1$s" title="%2$s" rel="home" itemprop="url">%3$s</a>',
				esc_url( $logo_link_content ),
				esc_attr( get_bloginfo( 'name', 'display' ) ) .' - '. esc_attr( get_bloginfo( 'description', 'display' ) ),
	        	wp_get_attachment_image( $custom_logo_id, 'full', false, array(
	            	'class' => 'custom-logo',
	        		'alt' => esc_attr( get_bloginfo( 'name' ) ) .' - '. esc_attr( get_bloginfo( 'description', 'display' ) )
				) )
	    	);

	return $html;    
}
add_filter( 'get_custom_logo', 'citylogic_custom_logo_url' );

/**
 * Adjust is_home query if citylogic-slider-categories is set
 */
function citylogic_set_blog_queries( $query ) {
    
    $slider_categories = get_theme_mod( 'citylogic-slider-categories' );
    $slider_type = get_theme_mod( 'citylogic-slider-type', customizer_library_get_default( 'citylogic-slider-type' ) );
    
    if ( $slider_categories && $slider_type == 'citylogic-slider-default' ) {
    	
    	$is_front_page = ( $query->get('page_id') == get_option('page_on_front') || is_front_page() );
    	
    	if ( count($slider_categories) > 0) {
    		// do not alter the query on wp-admin pages and only alter it if it's the main query
    		if ( !is_admin() && !$is_front_page  && $query->get('id') != 'slider' || !is_admin() && $is_front_page && $query->get('id') != 'slider' ){
				$query->set( 'category__not_in', $slider_categories );
    		}
    	}
    }
	    
}
add_action( 'pre_get_posts', 'citylogic_set_blog_queries' );

function citylogic_filter_recent_posts_widget_parameters( $params ) {

	$slider_categories = get_theme_mod( 'citylogic-slider-categories' );
    $slider_type = get_theme_mod( 'citylogic-slider-type', customizer_library_get_default( 'citylogic-slider-type' ) );
	
	if ( $slider_categories && $slider_type == 'citylogic-slider-default' ) {
		if ( count($slider_categories) > 0) {
			// do not alter the query on wp-admin pages and only alter it if it's the main query
			$params['category__not_in'] = $slider_categories;
		}
	}
	
	return $params;
}
add_filter( 'widget_posts_args', 'citylogic_filter_recent_posts_widget_parameters' );

/**
 * Adjust the widget categories query if citylogic-slider-categories is set
 */
function citylogic_set_widget_categories_args($args){
	$slider_categories = get_theme_mod( 'citylogic-slider-categories' );
    $slider_type = get_theme_mod( 'citylogic-slider-type', customizer_library_get_default( 'citylogic-slider-type' ) );
	
	if ( $slider_categories && $slider_type == 'citylogic-slider-default' ) {
		if ( count($slider_categories) > 0) {
			$exclude = implode(',', $slider_categories);
			$args['exclude'] = $exclude;
		}
	}
	
	return $args;
}
add_filter( 'widget_categories_args', 'citylogic_set_widget_categories_args' );

function citylogic_set_widget_categories_dropdown_arg($args){
	$slider_categories = get_theme_mod( 'citylogic-slider-categories' );
    $slider_type = get_theme_mod( 'citylogic-slider-type', customizer_library_get_default( 'citylogic-slider-type' ) );
	
	if ( $slider_categories && $slider_type == 'citylogic-slider-default' ) {
		if ( count($slider_categories) > 0) {
			$exclude = implode(',', $slider_categories);
			$args['exclude'] = $exclude;
		}
	}
	
	return $args;
}
add_filter( 'widget_categories_dropdown_args', 'citylogic_set_widget_categories_dropdown_arg' );

if ( !function_exists( 'citylogic_add_menu_items' ) ) :
	function citylogic_add_menu_items( $items, $args ) {
		
		if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'primary' ) ) {
			return $items;
		}
		
	    if ( $args->theme_location == 'primary' ) {
	    	
	    	$navigation_menu_search_type = get_theme_mod( 'citylogic-navigation-menu-search-type', customizer_library_get_default( 'citylogic-navigation-menu-search-type' ) );
	
			if( get_theme_mod( 'citylogic-navigation-menu-search-button', customizer_library_get_default( 'citylogic-navigation-menu-search-button' ) ) ) :
				$items .= '<li class="search-button ' .esc_attr( $navigation_menu_search_type). '">';
				
				if ( $navigation_menu_search_type == 'default' ) {
					if ( get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) ) == '4.7.0' ) {
						$font_awesome_code = 'otb-fa';
						$font_awesome_icon_prefix = 'otb-';
					} else {
						$font_awesome_code = 'fa-solid';
						$font_awesome_icon_prefix = '';
					}
					
		        	$items .= '<a>Search<i class="' .$font_awesome_code. ' ' .$font_awesome_icon_prefix. 'fa-search search-btn"></i></a>';
				} else {
					$items .= do_shortcode( get_theme_mod( 'citylogic-navigation-menu-search-plugin-shortcode', customizer_library_get_default( 'citylogic-navigation-menu-search-plugin-shortcode' ) ) );
				}
				
		        $items .= '</li>';
			endif;
	
	    }
	    return $items;
	}
endif;
add_filter( 'wp_nav_menu_items', 'citylogic_add_menu_items', 10, 2 );

function citylogic_allowed_tags() {
	global $allowedtags;
	$allowedtags["h1"] = array();
	$allowedtags["h2"] = array();
	$allowedtags["h3"] = array();
	$allowedtags["h4"] = array();
	$allowedtags["h5"] = array();
	$allowedtags["h6"] = array();
	$allowedtags["p"] = array();
	$allowedtags["br"] = array();
	$allowedtags["a"] = array(
		'href' => true,
		'class' => true
	);
	$allowedtags["i"] = array(
		'class' => true
	);
}
add_action('init', 'citylogic_allowed_tags', 10);

function citylogic_register_required_plugins() {
	$plugins = array(
		array(
			'name'      => __( 'Elementor', 'citylogic' ),
			'slug'      => 'elementor',
			'required'  => false
		),
		array(
			'name'      => __( 'You can quote me on that', 'citylogic' ),
			'slug'      => 'you-can-quote-me-on-that',
			'required'  => false
		),
		array(
			'name'      => __( 'SiteOrigin Widgets Bundle', 'citylogic' ),
			'slug'      => 'so-widgets-bundle',
			'required'  => false
		),
		array(
			'name'      => __( 'Beam me up Scotty', 'citylogic' ),
			'slug'      => 'beam-me-up-scotty',
			'required'  => false
		),
		array(
			'name'      => __( 'Recent Posts Widget Extended', 'citylogic' ),
			'slug'      => 'recent-posts-widget-extended',
			'required'  => false
		),
		array(
			'name'      => __( 'WPForms', 'citylogic' ),
			'slug'      => 'wpforms-lite',
			'required'  => false
		),
		array(
			'name'      => __( 'Photo Gallery by Supsystic', 'citylogic' ),
			'slug'      => 'gallery-by-supsystic',
			'required'  => false
		),
		array(
			'name'      => __( 'Recent Posts Widget Extended', 'citylogic' ),
			'slug'      => 'recent-posts-widget-extended',
			'required'  => false
		),
		array(
			'name'      => __( 'MailChimp for WordPress', 'citylogic' ),
			'slug'      => 'mailchimp-for-wp',
			'required'  => false
		),
		array(
			'name'      => __( 'BookingPress', 'citylogic' ),
			'slug'      => 'bookingpress-appointment-booking',
			'required'  => false
		),
		array(
			'name'      => __( 'WooCommerce', 'citylogic' ),
			'slug'      => 'woocommerce',
			'required'  => false
		)
	);

	$config = array(
		'id'           => 'citylogic',            // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false,                   // Automatically activate plugins after installation or not.
		'message'      => ''                       // Message to output right before the plugins table.
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'citylogic_register_required_plugins' );

/**
 * Determine if Custom Post Type
 * usage: if ( is_this_a_custom_post_type() )
 *
 * References/Modified from:
 * @link https://codex.wordpress.org/Function_Reference/get_post_types
 * @link http://wordpress.stackexchange.com/users/73/toscho <== love this person!
 * @link http://wordpress.stackexchange.com/a/95906/64742
 */
function citylogic_is_this_a_custom_post_type( $post = NULL ) {

    $all_custom_post_types = get_post_types( array ( '_builtin' => false ) );

    //* there are no custom post types
    if ( empty ( $all_custom_post_types ) ) return false;

    $custom_types      = array_keys( $all_custom_post_types );
    $current_post_type = get_post_type( $post );

    //* could not detect current type
    if ( ! $current_post_type )
        return false;

    return in_array( $current_post_type, $custom_types );
}

/**
 * Remove blog menu link class 'current_page_parent' when on an unrelated CPT
 * or search results page
 * or 404 page
 * dep: is_this_a_custom_post_type() function
 * modified from: https://gist.github.com/ajithrn/1f059b2201d66f647b69
 */
function citylogic_if_cpt_or_search_or_404_remove_current_page_parent_on_blog_page_link( $classes, $item, $args ) {
    if ( citylogic_is_this_a_custom_post_type() || is_search() || is_404() ) {
        $blog_page_id = intval( get_option('page_for_posts') );

        if ( $blog_page_id != 0 && $item->object_id == $blog_page_id ) {
			unset( $classes[array_search( 'current_page_parent', $classes )] );
        }

	}

    return $classes;
}
add_filter( 'nav_menu_css_class', 'citylogic_if_cpt_or_search_or_404_remove_current_page_parent_on_blog_page_link', 10, 3 );

if ( function_exists( 'pll_register_string' ) ) {
	/**
	* Register some string from the customizer to be translated with Polylang
	*/
	function citylogic_pll_register_string() {
		// Header
		pll_register_string( 'citylogic-header-info-text-one', get_theme_mod( 'citylogic-header-info-text-one', customizer_library_get_default( 'citylogic-header-info-text-one' ) ), 'citylogic', false );
		
		// Search
		pll_register_string( 'citylogic-search-placeholder-text', get_theme_mod( 'citylogic-search-placeholder-text', customizer_library_get_default( 'citylogic-search-placeholder-text' ) ), 'citylogic', false );
		pll_register_string( 'citylogic-website-text-no-search-results-heading', get_theme_mod( 'citylogic-website-text-no-search-results-heading', customizer_library_get_default( 'citylogic-website-text-no-search-results-heading' ) ), 'citylogic', false );
		pll_register_string( 'citylogic-website-text-no-search-results-text', get_theme_mod( 'citylogic-website-text-no-search-results-text', customizer_library_get_default( 'citylogic-website-text-no-search-results-text' ) ), 'citylogic', true );
		
		// Header media
		pll_register_string( 'citylogic-header-image-text', get_theme_mod( 'citylogic-header-image-text', customizer_library_get_default( 'citylogic-header-image-text' ) ), 'citylogic', true );
		
		// Blog read more
		pll_register_string( 'citylogic-blog-read-more-text', get_theme_mod( 'citylogic-blog-read-more-text', customizer_library_get_default( 'citylogic-blog-read-more-text' ) ), 'citylogic', true );
		
		// 404
		pll_register_string( 'citylogic-website-text-404-page-heading', get_theme_mod( 'citylogic-website-text-404-page-heading', customizer_library_get_default( 'citylogic-website-text-404-page-heading' ) ), 'citylogic', true );
		pll_register_string( 'citylogic-website-text-404-page-text', get_theme_mod( 'citylogic-website-text-404-page-text', customizer_library_get_default( 'citylogic-website-text-404-page-text' ) ), 'citylogic', true );
	}
	add_action( 'admin_init', 'citylogic_pll_register_string' );
}

/**
 * A fallback function that outputs a non-translated string if Polylang is not active
 *
 * @param $string
 *
 * @return  void
 */
if ( !function_exists( 'pll_e' ) ) {
	function pll_e( $str ) {
		echo $str;
	}
}

/**
 * A fallback function that returns a non-translated string if Polylang is not active
 *
 * @param $string
 *
 * @return string
 */
if ( !function_exists( 'pll__' ) ) {
	function pll__( $str ) {
		return $str;
	}
}

function citylogic_singular_or_plural( $singular, $plural, $value ) {
	$locale = get_locale();

	$plural_exceptions = array(
		'fr_CA',
		'fr_FR',
		'fr_BE',
		'pt_BR'
	);

	if ( ( $value == 0 && !in_array( $locale, $plural_exceptions ) ) || $value > 1 ) {
		return $plural;
	} else {
		return $singular;
	}
}
