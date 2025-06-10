<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Header template.
 *
 * Displays the <head> section and loop start template.
 *
 * @package Fabula
 * @since   1.2
 */

$rtl = class_exists('jkdevkit') && jkdevkit\kirki::get_setting('gen-rtl-tg');
$dir = $rtl ? ' dir="rtl"' : ' dir="ltr"';

echo '<!doctype html><html ' . get_language_attributes() . $dir . '>';

echo '<head>';

echo '<meta charset="' . esc_attr(get_bloginfo('charset')) . '" />';
wp_head();

echo '</head>';

if (class_exists('jkdcore')):
	jkdtheme::getInstance()->getHelper()->template('global/loop-start');
else:
	get_template_part('template-parts/loop-start');
endif;