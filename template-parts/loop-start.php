<?php defined('ABSPATH') || die('You do not have access to this page!');

$woo = class_exists('WooCommerce') && in_array(true, [
		is_woocommerce(), is_shop(), is_product(), is_product_category(),
		is_product_tag(), is_product_taxonomy(), is_checkout(), is_cart(),
		is_account_page(), is_wc_endpoint_url()
	], true);

$cs = !empty($_COOKIE['jkd-cs']) ? $_COOKIE['jkd-cs'] :jkdevkit\kirki::get_setting('gen-col-schm'); 

echo '<body ';

body_class();

echo ' data-cs="' . esc_attr($cs) . '">';

wp_body_open();

echo '<main class="main-wrp def-wrp" id="m-wrp">';

get_template_part('template-parts/navigation');
if (!is_single() && !is_404()) get_template_part('template-parts/page-header');

echo '<div class="jkd-c">';

if ($woo) echo '<div class="woo-wrp">';