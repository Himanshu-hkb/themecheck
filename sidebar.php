<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Template for displaying a blog sidebar.
 *
 * @package Fabula
 * @since   1.2
 */

/* Display sidebar if it's active and on the blog home page */
if (is_active_sidebar('blog-sidebar') && is_home()) dynamic_sidebar('blog-sidebar');