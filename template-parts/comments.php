<?php

/* Prevent any PHP inside the file from being executed if the file is accessed directly outside a WordPress context */
defined('ABSPATH') || die("you do not have access to this page!");

/* get the ID */
$post_id = get_the_ID();

/* empty classes array */
$cls = [];

/* check if option show avatars is true */
if (get_option('show_avatars')) $cls[] = 'show-avtr';

/* if comments > 0 then add 'active-com' to classes */
if (have_comments()) $cls[] = 'active-comments';

/* implode array to string */
$cls = implode(' ', $cls);

echo '<section id="comments" class="w-100 d-f d-col ' . esc_attr($cls) . '">';

if (comments_open() || get_comments_number()):
	
	/* check if comments is open */
	comment_form(
		[
			'logged_in_as' => null,
			'title_reply' => esc_html__('Leave a comment', 'fabula'),
			'title_reply_before' => '<h5 id="rep-ttl" class="mb-7">',
			'title_reply_after' => '</h5>',
		]
	);

else:
	
	?>
	
	<div class="closed-comments w-100 d-f ai-c jc-c ta-c up-txt br-md bg-col m-fw trns">
		
		<div class="ic-wrp mr-7 d-f ai-c jc-c br-xl bg-col-sec">
			<svg xmlns="http://www.w3.org/2000/svg"
			     viewBox="0 0 24 24"
			     fill="none"
			     stroke="currentColor"
			     stroke-width="2"
			     stroke-linecap="round"
			     stroke-linejoin="round"
			     class="lucide lucide-lock-keyhole">
				<circle cx="12" cy="16" r="1" />
				<rect x="3" y="10" width="18" height="12" rx="2" />
				<path d="M7 10V7a5 5 0 0 1 10 0v3" />
			</svg>
		</div>
		
		<?php echo esc_html__('Comments are closed', 'fabula'); ?>
	
	</div>

<?php

endif;

if (have_comments()) :
	
	echo '<ol class="comments-list d-f d-col r-list">';
	
	/* list comments */
	wp_list_comments(
		[
			'style' => 'ul',
			'short_ping' => true,
			'avatar_size' => 64,
			'callback' => class_exists('jkdcore') ? ['jkdcore', 'custom_comment_template'] : ['jkdtheme', 'custom_comment_template']
		]
	);
	
	echo '</ol>';
	
	if (get_comment_pages_count() > 1):
		
		echo '<div class="jkd-pag">';
		
		/* comments pagination */
		the_comments_pagination(
			[
				'before_page_number' => '',
				'end_size' => 1,
				'mid_size' => 1,
				'prev_text' => '<div class="pag-arrow prev-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg></div>',
				'next_text' => '<div class="pag-arrow next-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></div>',
			]
		);
		
		echo '</div>';
	
	endif;

endif;

echo '</section>';