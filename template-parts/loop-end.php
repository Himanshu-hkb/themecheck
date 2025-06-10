<?php defined('ABSPATH') || die('You do not have access to this page!');

$woo = class_exists('WooCommerce') && in_array(true, [
		is_woocommerce(), is_shop(), is_product(), is_product_category(),
		is_product_tag(), is_product_taxonomy(), is_checkout(), is_cart(),
		is_account_page(), is_wc_endpoint_url()
	], true);

if ($woo) echo '</div>';

echo '</div>';

get_template_part('template-parts/footer');

echo '</main>';