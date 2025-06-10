<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Displays the comments section and the comment form.
 *
 * @package Fabula
 * @since   1.2
 */

/* Check if the post is password protected */
if (post_password_required()) return;

/* Include comments template */
get_template_part('template-parts/comments');