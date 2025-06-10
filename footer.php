<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Displays the footer content.
 *
 * @package Fabula
 * @since   1.2
 */

if (class_exists('jkdcore')):
	jkdtheme::getInstance()->getHelper()->template('global/loop-end');
else:
	get_template_part('template-parts/loop-end');
endif;

/* Footer content */
wp_footer();

echo '</body>';
echo '</html>';