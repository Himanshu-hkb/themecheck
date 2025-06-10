<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Template for displaying a page.
 *
 * @package Fabula
 * @since   1.2
 */

/* Load header */
get_header();

if (class_exists('jkdcore')):
	jkdtheme::getInstance()->getHelper()->template('global/page');
else:
	get_template_part('template-parts/page');
endif;

/* Load footer */
get_footer();