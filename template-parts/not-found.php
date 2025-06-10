<?php defined('ABSPATH') || die('You do not have access to this page!'); ?>

<header class="not-fount-header">
	
	<div class="in-wrp jkd-c">
		
		<h1 class="search-term">
			
			<?php
			
			/* output search query */
			echo esc_html(get_search_query());
			
			?>
		
		</h1>
		
		<p class="subtitle">
			
			<?php
			
			/* output nothing found message */
			echo esc_html__('Nothing Found', 'fabula');
			
			?>
		
		</p>
		
		<a href="<?php echo esc_url(get_home_url()); ?>"
		   class="jkd-btn">
			
			<?php
			
			/* back to home message */
			echo esc_html__('Back to Home', 'fabula');
			
			?>
		
		</a>
	
	</div>

</header>