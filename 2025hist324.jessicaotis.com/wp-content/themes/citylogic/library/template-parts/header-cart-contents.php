<?php 
if ( get_theme_mod( 'citylogic-font-awesome-version', customizer_library_get_default( 'citylogic-font-awesome-version' ) ) == '4.7.0' ) {
	$font_awesome_code = 'otb-fa';
	$font_awesome_icon_prefix = 'otb-';
} else {
	$font_awesome_code = 'fa-solid';
	$font_awesome_icon_prefix = '';
}
?>

<a class="header-cart-contents" href="<?php echo esc_url( wc_get_cart_url() ); ?>">
	<span class="header-cart-amount">
		<?php
		$singular_text = '%d item';
		$plural_text   = '%d items';
		
		echo esc_html( sprintf( citylogic_singular_or_plural( $singular_text, $plural_text, WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ) ); ?> - <?php echo wp_kses_data( WC()->cart->get_cart_subtotal() );
		?>
	</span>
	<span class="header-cart-checkout <?php echo ( WC()->cart->get_cart_contents_count() > 0 ) ? 'cart-has-items' : ''; ?>">
		<span><?php esc_html_e('Checkout', 'citylogic'); ?></span> <i class="<?php echo $font_awesome_code; ?> <?php echo $font_awesome_icon_prefix; ?>fa-shopping-cart"></i>
	</span>
</a>
