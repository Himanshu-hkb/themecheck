<?php
defined('ABSPATH') || exit('You do not have access to this page!');

/**
 * Template for displaying a search form.
 *
 * @package Fabula
 * @since   1.2
 */

?>

<form role="search" method="get" class="sf-form" action="<?php echo esc_url(home_url('/')); ?>">
	
	<label for="sf-search-field" class="screen-reader-text">
		<?php esc_html_e('Search for:', 'fabula'); ?>
	</label>
	
	<input type="search" id="sf-search-field" class="search-field"
	       placeholder="<?php esc_attr_e('Enter some keywords...', 'fabula'); ?>"
	       value="<?php echo esc_attr(get_search_query()); ?>" name="s"
	       title="<?php esc_attr_e('Enter some keywords...', 'fabula'); ?>"
	       aria-label="<?php esc_attr_e('Search', 'fabula'); ?>">
	
	<button type="submit" class="search-submit"
	        aria-label="<?php esc_attr_e('Submit search', 'fabula'); ?>">
		<svg xmlns="http://www.w3.org/2000/svg"
		     viewBox="0 0 24 24"
		     fill="none"
		     stroke="currentColor"
		     stroke-width="2"
		     stroke-linecap="round"
		     stroke-linejoin="round"
		     class="lucide lucide-search">
			<circle cx="11" cy="11" r="8" />
			<path d="m21 21-4.3-4.3" />
		</svg>
	</button>

</form>