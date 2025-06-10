<?php

/* Prevent any PHP inside the file from being executed if the file is accessed directly outside a WordPress context */
defined('ABSPATH') || die("you do not have access to this page!");

/* default copyright placeholder (can be edited using translation plugins) */
$copyright = esc_html__('© {year} Fabula, Inc. All rights reserved.', 'fabula');

/* replace the {year} with the current year */
$copyright = str_replace('{year}', date('Y'), $copyright);

echo '<footer id="theme-footer">';

if (!empty($copyright)
	|| has_nav_menu('copyright_menu')):
	
	echo '<div class="jkd-c ai-c jc-sb d-col-lg pt-10 pb-10">';
	
	if (!empty($copyright)): ?>
		
		<div class="copyright wh-col m-fw ta-c-sm <?php if (!has_nav_menu('copyright_menu')) echo esc_attr('w-100 ta-c'); ?>">
			
			<?php echo esc_html($copyright); ?>
		
		</div>
	
	<?php
	
	endif;
	
	if (has_nav_menu('copyright_menu')) wp_nav_menu([
		'theme_location' => 'copyright_menu',
		'menu' => 'copyright_menu',
		'menu_class' => 'menu-list r-list wh-col m-fw mt-txt d-f ai-c f-wrp jc-c-lg mt-5-lg pt-5-lg',
		'container' => '',
	]);
	
	echo '</div>';

endif;

echo '</footer>';