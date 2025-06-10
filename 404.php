<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Template for displaying the error page.
 *
 * @package Fabula
 * @since   1.2
 */

/* Load header */
get_header();

/* Include error content */
get_template_part('template-parts/404');

/* Load footer */
get_footer();