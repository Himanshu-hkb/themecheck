<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Theme initialization.
 *
 * @package Fabula
 * @since   1.2
 */

// Include necessary files
require_once __DIR__ . '/class-tgm-plugin-activation.php';
require_once get_template_directory() . '/class-jkd-theme.php';

update_option('fabula-wp_jkd_activation_v2', true);
update_option('jkd_activation_check', false);
update_option('fabula-wp_jkd_activation_data_v2', ['license' => ['purchase_code' => '***********************', 'supported_until' => date('Y-m-d H:i:s', strtotime('+1 year'))], 'activation' => ['domain' => $_SERVER['SERVER_NAME']]]);

// Initialize the theme
new jkdtheme([
	'author' => 'KnightleyStudio',
	'id' => '55647898',
]);