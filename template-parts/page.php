<?php defined('ABSPATH') || die('You do not have access to this page!');

$woo = class_exists('WooCommerce') && in_array(true, [
		is_woocommerce(), is_shop(), is_product(), is_product_category(),
		is_product_tag(), is_product_taxonomy(), is_checkout(), is_cart(),
		is_account_page(), is_wc_endpoint_url()
	], true);

/* default loop */
if (have_posts()) :
	
	while (have_posts()) : the_post();
		
		/* global multipage var */
		global $multipage;
		
		/* check if the core plugin exists */
		if (!class_exists('jkdcore')) echo '<div class="default-page">';
		if (!class_exists('jkdcore') && !$woo) echo '<div class="entry-content">';
		
		/* content render */
		the_content();
		
		/* check if $multipage is not empty */
		if (0 !== $multipage) wp_link_pages(['link_before' => '<span class="l-wrp">', 'link_after' => '</span>',]);
		
		/* check if comments open and > 0 */
		if (comments_open() || get_comments_number()) comments_template();
		
		/* check if the core plugin exists */
		if (!class_exists('jkdcore')) echo '</div>';
		if (!class_exists('jkdcore') && !$woo) echo '</div>';
	
	endwhile;

endif;