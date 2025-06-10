<?php

// Prevent direct access to the file outside of a WordPress context
defined('ABSPATH') || exit('You do not have access to this page!');

if (is_front_page()) return;

// Get the current post
$post = get_post();

// Start the breadcrumb navigation with a link to the homepage
echo '<nav class="jkd-breadcrumbs mt-7 d-f ai-c f-wrp jc-c">';
echo '<a href="' . esc_url(home_url()) . '">' . esc_html__('Home', 'fabula') . '</a><svg xmlns="http://www.w3.org/2000/svg" class="divider" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';

// Determine the type of content being viewed
if (is_category()) :
	echo single_cat_title('', false);

elseif (is_single()) :
	// Display the primary category if available
	$category = get_the_category();
	if (!empty($category)) :
		echo '<a href="' . esc_url(get_category_link($category[0]->term_id)) . '">' . esc_html($category[0]->name) . '</a><svg xmlns="http://www.w3.org/2000/svg" class="divider" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';
	endif;
	echo esc_html(get_the_title());

elseif (is_page()) :
	// Display parent pages if any
	if ($post->post_parent) :
		$breadcrumbs = [];
		$parent_id = $post->post_parent;
		while ($parent_id) :
			$page = get_post($parent_id);
			$breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html(get_the_title($page->ID)) . '</a>';
			$parent_id = $page->post_parent;
		endwhile;
		$breadcrumbs = array_reverse($breadcrumbs);
		foreach ($breadcrumbs as $crumb) :
			echo esc_html($crumb) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="divider"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>';
		endforeach;
	endif;
	echo esc_html(get_the_title());

elseif (is_tag()) :
	echo esc_html__('Tag: ', 'fabula') . single_tag_title('', false);

elseif (is_day()) :
	echo esc_html__('Archive for ', 'fabula') . esc_html(get_the_time('F jS, Y'));

elseif (is_month()) :
	echo esc_html__('Archive for ', 'fabula') . esc_html(get_the_time('F, Y'));

elseif (is_year()) :
	echo esc_html__('Archive for ', 'fabula') . esc_html(get_the_time('Y'));

elseif (is_author()) :
	echo esc_html__('Author Archive', 'fabula');

elseif (is_search()) :
	echo esc_html__('Search Results for: ', 'fabula') . '<strong class="ml-5">"' . esc_html(get_search_query()) . '"</strong>';

endif;

echo '</nav>';