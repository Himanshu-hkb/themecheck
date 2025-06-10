<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Template for displaying a single post.
 *
 * @package Fabula
 * @since   1.2
 */

/* Load header */
get_header();

if (get_post_type() === 'elementor_library'):
	the_content();
elseif (class_exists('jkdcore')):
	jkdtheme::getInstance()->getHelper()->template('blog/single');
else:
	get_template_part('template-parts/single');
endif;

/* Load footer */
get_footer();