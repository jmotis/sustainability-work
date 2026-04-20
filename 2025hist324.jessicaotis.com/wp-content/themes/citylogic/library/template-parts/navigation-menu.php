<?php
global $is_navigation_menu_transparent;

if ( get_theme_mod( 'citylogic-navigation-menu-uppercase', customizer_library_get_default( 'citylogic-navigation-menu-uppercase' ) ) ) {
	$navigation_menu_classes[] = 'uppercase';
}

$navigation_menu_classes[] = get_theme_mod( 'citylogic-navigation-menu-alignment', customizer_library_get_default( 'citylogic-navigation-menu-alignment' ) );
$navigation_menu_classes[] = get_theme_mod( 'citylogic-navigation-menu-rollover-style', customizer_library_get_default( 'citylogic-navigation-menu-rollover-style' ) );

$alignment = get_theme_mod( 'citylogic-navigation-menu-alignment', customizer_library_get_default( 'citylogic-navigation-menu-alignment' ) );

if ( function_exists( 'max_mega_menu_is_enabled' ) && max_mega_menu_is_enabled( 'primary' ) ) {
?>
<nav id="site-navigation" class="main-navigation-mega-menu" style="background: linear-gradient(to bottom, <?php echo( mmm_get_theme_for_location('primary')['container_background_from'] ); ?>, <?php echo( mmm_get_theme_for_location('primary')['container_background_to'] ); ?>);" role="navigation">
	<div id="main-menu" class="main-menu-container">
		<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
	</div>
</nav><!-- #site-navigation -->
<?php 
} else {
	if ( get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) ) == '4.7.0' ) {
		$font_awesome_code = 'otb-fa';
		$font_awesome_icon_prefix = 'otb-';
	} else {
		$font_awesome_code = 'fa-solid';
		$font_awesome_icon_prefix = '';
	}
?>
<nav id="site-navigation" class="main-navigation centered-submenu <?php echo esc_attr( implode( ' ', $navigation_menu_classes ) ); ?> <?php echo( $alignment == 'left-aligned' ? 'border-bottom' : '' ); ?> <?php echo ( $is_navigation_menu_transparent && $alignment == 'left-aligned' ) ? 'transparent' : ''; ?>" role="navigation">
	<span
		class="header-menu-button"
		role="button"
		tabindex="0"
		aria-expanded="false"
		aria-controls="main-menu"
		aria-label="<?php esc_attr_e( 'Navigation menu', 'citylogic' ); ?>"
	>
		<i class="<?php echo esc_attr( $font_awesome_code ); ?> <?php echo esc_attr( $font_awesome_icon_prefix ); ?>fa-bars"></i>
	</span>
	<div id="main-menu" class="main-menu-container">
		<div class="main-menu-close"><i class="<?php echo esc_attr( $font_awesome_code ); ?> <?php echo $font_awesome_icon_prefix; ?>fa-angle-right"></i><i class="<?php echo esc_attr( $font_awesome_code ); ?> <?php echo $font_awesome_icon_prefix; ?>fa-angle-left"></i></div>
		<div class="main-navigation-inner">
			<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
		</div>
        
		<div class="search-slidedown">
			<div class="container">
				<div class="padder">
					<div class="search-block">
					<?php
					if( get_theme_mod( 'citylogic-navigation-menu-search-button', customizer_library_get_default( 'citylogic-navigation-menu-search-button' ) ) && get_theme_mod( 'citylogic-navigation-menu-search-type', customizer_library_get_default( 'citylogic-navigation-menu-search-type' ) ) == 'default' ) :
						get_search_form();
					endif;
					?>
					</div>
				</div>
			</div>
		</div>
        
	</div>
</nav><!-- #site-navigation -->
<?php 
}
