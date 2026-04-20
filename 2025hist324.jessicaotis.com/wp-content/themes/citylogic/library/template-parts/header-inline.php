<?php
$logo = '';
$logo_link_content = '';

if ( get_theme_mod( 'citylogic-logo-link-content', customizer_library_get_default( 'citylogic-logo-link-content' ) ) == "" ) {
	$logo_link_content = home_url( '/' );
} else {
	$logo_link_content = esc_url( get_permalink( get_theme_mod( 'citylogic-logo-link-content' ) ) );
}

if ( function_exists( 'has_custom_logo' ) ) {
	if ( has_custom_logo() ) {
		$logo = get_custom_logo();
	}
} else if ( get_theme_mod( 'citylogic-logo' ) ) {
	$logo = "<a href=\"". esc_url( $logo_link_content ) ."\" title=\"". esc_attr( get_bloginfo( 'name', 'display' ) ) .' - '. esc_attr( get_bloginfo( 'description', 'display' ) ) ."\" class=\"custom-logo-link\"><img src=\"". esc_url( get_theme_mod( 'citylogic-logo' ) ) ."\" alt=\"". esc_attr( get_bloginfo( 'name' ) ) .' - '. esc_attr( get_bloginfo( 'description', 'display' ) ) ."\" class=\"custom-logo\" /></a>";
}
?>

<div class="site-logo-area border-bottom">
	<div class="site-container">
	    
	    <div class="branding">
	        <?php
	        if ( $logo ) {
	       		echo $logo;
	        
	        } else {
			?>
				<a href="<?php echo esc_url( $logo_link_content ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" class="title"><?php bloginfo( 'name' ); ?></a>
				<div class="description"><?php bloginfo( 'description' ); ?></div>
	        <?php
	        }
	        ?>
		</div>
		
		<?php
		$top_right = '';
	        
		if ( citylogic_is_woocommerce_activated() && get_theme_mod( 'citylogic-header-shop-links', customizer_library_get_default( 'citylogic-header-shop-links' ) ) ) {
			$top_right = 'shop-links';
		} else {
			$top_right = 'info-text';
		}
		?>		
	    
	    <div class="site-header-right <?php echo $top_right == '' ? 'top-empty' : ''; ?>">
	        
	        <div class="top <?php echo $top_right == '' ? 'empty' : $top_right; ?>">
		        <?php
		        switch ($top_right) {
		    		case 'shop-links':
		    			get_template_part( 'library/template-parts/shop-links' );
		    			break;
		    			
		    		case 'info-text':
		    			get_template_part( 'library/template-parts/info-text' );
		    			break;
		    	}
		    	?>	        
	        </div>
	
	        <div class="bottom navigation-menu">
	        	
	        	<div class="main-navigation-container">

					<?php
					get_template_part( 'library/template-parts/navigation-menu' );
					?>

				</div>
				
			</div>
			        
	    </div>
	    <div class="clearboth"></div>
	    
	</div>
</div>
