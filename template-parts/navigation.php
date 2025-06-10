<?php

/* Prevent any PHP inside the file from being executed if the file is accessed directly outside a WordPress context */
defined('ABSPATH') || die("you do not have access to this page!");

$default_logo = get_template_directory_uri() . '/assets/img/logo-default.svg';

/* color scheme */
$cs = !empty($_COOKIE['jkd-cs']) ? $_COOKIE['jkd-cs'] :jkdevkit\kirki::get_setting('gen-col-schm'); 

/* default copyright placeholder (can be edited using translation plugins) */
$copyright = esc_html__('© {year} Fabula, Inc. All rights reserved.', 'fabula');

/* replace the {year} with the current year */
$copyright = str_replace('{year}', date('Y'), $copyright);

?>

<header id="jkd-nav">
	
	<div class="in-wrp jkd-c ai-c jc-c bg-col br-lg trns">
		
		<a href="<?php echo esc_url(home_url('/')); ?>" class="logo-wrp mr-a pos-rel z-nav">
			
			<img class="theme-logo"
			     src="<?php echo esc_url($default_logo); ?>"
			     alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
		
		</a>
		
		<span class="menu-tg a-itm d-f d-col jc-c ai-c pos-abs">
			
			<span class="d-f pos-rel w-100 bg-col-hd"></span>
			<span class="d-f pos-rel w-100 bg-col-hd"></span>
			<span class="d-f pos-rel w-100 bg-col-hd"></span>
		
		</span>
		
		<div class="menu-wrp pos-abs d-f d-n-lg jc-c ai-c h-100 w-100 z-sm">
			
			<?php
			
			/* check if menu is existing */
			if (has_nav_menu('primary_menu')) :
				
				/* render navigation menu */
				wp_nav_menu([
					'theme_location' => 'primary_menu',
					'menu' => 'primary_menu',
					'menu_class' => 'menu-list r-list d-f d-n-md d-col-md ai-c ai-s-md',
					'container' => '',
				]);
			
			else:
				
				if (is_user_logged_in()):
					
					echo wp_kses_post('<span class="mt-txt op-sm d-f ai-c"><svg class="mr-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zM11 7h2v2h-2V7zm0 4h2v6h-2v-6z"/></svg>') . esc_html__('Navigation menu does not exist, please create it in Appearance->Menus', 'fabula') . wp_kses_post('</span>');
				
				endif;
			
			endif;
			
			?>
		
		</div>
		
		<ul class="nav-actions r-list d-f ai-c ml-a pos-rel z-nav h-100">
			
			<li class="act-itm search-toggle">
				
				<svg xmlns="http://www.w3.org/2000/svg"
				     viewBox="0 0 24 24"
				     fill="none"
				     stroke="currentColor"
				     stroke-width="2"
				     stroke-linecap="round"
				     stroke-linejoin="round"
				     class="nav-icon">
					<circle cx="11" cy="11" r="8" />
					<path d="m21 21-4.3-4.3" />
				</svg>
			
			</li>
			
			<li class="cs-toggle act-itm">
				
				<div class="cs-switch d-f jc-c ai-c"
				     data-selected-cs="<?php echo esc_attr($cs); ?>">
					
					<svg xmlns="http://www.w3.org/2000/svg"
					     viewBox="0 0 24 24"
					     class="icon-sun"
					     fill="none"
					     stroke="currentColor"
					     stroke-width="2"
					     stroke-linecap="round"
					     stroke-linejoin="round">
						<circle cx="12" cy="12" r="4" />
						<path d="M12 2v2" />
						<path d="M12 20v2" />
						<path d="m4.93 4.93 1.41 1.41" />
						<path d="m17.66 17.66 1.41 1.41" />
						<path d="M2 12h2" />
						<path d="M20 12h2" />
						<path d="m6.34 17.66-1.41 1.41" />
						<path d="m19.07 4.93-1.41 1.41" />
					</svg>
					<svg xmlns="http://www.w3.org/2000/svg"
					     viewBox="0 0 24 24"
					     fill="none"
					     class="icon-moon"
					     stroke="currentColor"
					     stroke-width="2"
					     stroke-linecap="round"
					     stroke-linejoin="round">
						<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z" />
					</svg>
				
				</div>
			
			</li>
		
		</ul>
		
		<div class="nav-search trns d-f ai-c pos-abs d-f t-0 l-0 r-0 b-0 w-100 bg-col z-nav pl-0-md pr-0-md">
			
			<div class="w-100 d-f pos-rel p-10-md ai-c">
				
				<?php get_search_form(); ?>
				
				<div class="btn-off d-f ai-c jc-c z-ui trns h-5 ml-10">
					<svg xmlns="http://www.w3.org/2000/svg"
					     viewBox="0 0 24 24"
					     fill="none"
					     stroke="currentColor"
					     stroke-width="2"
					     stroke-linecap="round"
					     stroke-linejoin="round"
					     class="">
						<path d="M18 6 6 18" />
						<path d="m6 6 12 12" />
					</svg>
				</div>
			
			</div>
		
		</div>
	
	</div>
	
	<div class="nav-ovrl pos-fix w-100 h-100 z-modal bg-col-dark jc-c d-f trns d-n d-f-md hidden">
		
		<div class="btn-off pos-abs d-f ai-c jc-c z-ui trns"
		     aria-label="<?php echo esc_attr__('Close','fabula'); ?>">
			
			<svg xmlns="http://www.w3.org/2000/svg"
			     viewBox="0 0 24 24"
			     fill="none"
			     stroke="currentColor"
			     stroke-width="2"
			     stroke-linecap="round"
			     stroke-linejoin="round"
			     class="">
				<path d="M18 6 6 18" />
				<path d="m6 6 12 12" />
			</svg>
		
		</div>
		
		<div class="ovrl-in d-f ai-c d-col o-hd">
			
			<div class="mob-menu-wrp d-f">
				
				<?php
				
				if (has_nav_menu('primary_menu')) :
					
					wp_nav_menu([
						'theme_location' => 'primary_menu',
						'menu' => 'primary_menu',
						'menu_class' => 'menu-list w-100 d-f d-col r-list',
						'container' => ''
					]);
				
				else:
					
					if (is_user_logged_in()) echo '<span class=" op-sm d-f d-col jc-c ai-c wh-col ta-c"><span aria-hidden="true" class="icon-info h-2 mb-15"></span>' . esc_html__('Navigation menu does not exist, please create it in Appearance->Menus', 'fabula') . '</span>';
				
				endif;
				
				?>
			
			</div>
			
			<?php if (!empty($copyright)): ?>
				
				<div class="b-wrp d-f d-col ai-c jc-c mt-30">
					
					<p class="copyright mt-15 op-lg ta-c h-col">
						
						<?php echo esc_html($copyright); ?>
					
					</p>
				
				</div>
			
			<?php endif; ?>
		
		</div>
	
	</div>

</header>