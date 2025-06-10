<?php

/* Prevent any PHP inside the file from being executed if the file is accessed directly outside a WordPress context */
defined('ABSPATH') || die("you do not have access to this page!");

/* define $wp_query */
global $wp_query;

/* get total results */
$total_results = $wp_query->found_posts;

/* blog loop */

?>
	
	<section class="w-100 index-wrp d-f ai-s jc-fs d-col-md f-wrp pt-50 pb-50 pt-30-sm pb-30-sm <?php if (is_active_sidebar('blog-sidebar')) echo esc_attr('sdb-enbl'); ?>">
		
		<?php if (have_posts()) : ?>
			
			<div class="p-grd-wrp grid-wrp o-hd d-f ai-s jc-s d-col">
				
				<div class="p-grid f-wrp d-f w-100">
					
					<?php
					
					/* posts loop */
					while (have_posts()) :the_post();
						
						/* grid item inner */
						echo '<article class="' . implode(' ', get_post_class('grid-item w-100 ')) . '">';
						
						/* get post */
						$post = get_post();
						
						/* category */
						$categories = get_the_category();
						
						?>
						
						<div class="in-wrp d-f p-20 w-100 ai-c br-md bg-col w-100 <?php if (is_sticky()) echo 'sticky'; ?>">
							
							<?php if (has_post_thumbnail()):
								
								/* get current image id (based on the post thumbnail) */
								$image_id = get_post_thumbnail_id(get_the_ID());
								
								?>
								
								<div class="p-hdr pos-rel d-f o-hd ai-c jc-c mr-30 bg-col-sec">
									
									<a href="<?php the_permalink(); ?>" class="link-ovrl"></a>
									
									<?php
									
									/* post thumbnail render */
									the_post_thumbnail('large', [
										'class' => 'img-fl ml-a mr-a'
									]);
									
									?>
								
								</div>
							
							<?php endif; ?>
							
							<div class="post-body w-100-d d-f d-col ai-s jc-c">
								
								<?php
								
								/* check if category is not empty */
								if (!empty($categories)):
									
									?>
									
									<div class="mb-10 mb-7-sm">
										
										<div class="cat-list d-f f-wrp">
											
											<?php foreach ($categories as $category): ?>
												
												<a href="<?php echo get_category_link($category->term_id); ?>"
												   class="cat-lb-def">
													
													<?php echo esc_html($category->name); ?>
												
												</a>
											
											<?php endforeach; ?>
										
										</div>
									
									</div>
								
								<?php endif;
								
								if (!empty(get_the_title())):
									
									?>
									
									<h3 class="p-ttl mb-5">
										
										<a href="<?php the_permalink(); ?>" class=" ttl-link">
											
											<?php the_title(); ?>
										
										</a>
									
									</h3>
								
								<?php
								
								else:
									
									echo '<a class="mb-5 d-f f-wrp h-col rm-link" href="' . get_the_permalink() . '">' . esc_html__('Read More', 'fabula') . '</a>';
								
								endif;
								
								if (!empty(get_the_excerpt())):
									
									$content = wp_trim_words(get_the_excerpt(), 30, '...');
									
									$content = str_replace('&nbsp;', '', $content);
									
									if (!empty($content)):
										
										?>
										
										<p class="excerpt  mb-5">
											
											<?php echo esc_html($content); ?>
										
										</p>
									
									<?php endif; ?>
								
								<?php endif; ?>
								
								<div class="auth-wrp d-f  ai-c mt-5">
									
									<a href="<?php echo get_author_posts_url($post->post_author); ?>"
									   class="auth-img avtr avtr--md mr-10">
										
										<img src="<?php echo esc_url(get_avatar_url($post->post_author, ['size' => 100])); ?>"
										     class="img-fl"
										     alt="<?php echo esc_attr(get_the_author_meta('display_name', $post->post_author)); ?>">
									
									</a>
									
									<div class="nm-wrp d-f d-col ai-s">
										
										<a href="<?php echo get_author_posts_url($post->post_author); ?>"
										   class="name h-col w-100">
											
											<?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?>
										
										</a>
										
										<a href="<?php the_permalink(); ?>" class="date mt-txt">
											
											<?php echo esc_html(get_the_date()); ?>
										
										</a>
									
									</div>
								
								</div>
							
							</div>
						
						</div>
						
						<?php
						
						echo '</article>';
					
					endwhile;
					
					?>
				
				</div>
				
				<?php if ($total_results > 1): ?>
					
					<div class="jkd-pag mt-30 mt-20-sm d-f jc-c ai-c w-100">
						
						<?php
						
						/* paginate links */
						echo paginate_links([
							'format' => '?paged=%#%',
							'end_size' => 1,
							'mid_size' => 1,
							'prev_next' => true,
							'prev_text' => '<div class="pag-arrow prev-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg></div>',
							'next_text' => '<div class="pag-arrow next-arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></div>',
						]);
						
						?>
					
					</div>
				
				<?php endif; ?>
			
			</div>
			
			<?php
			
			/* check if blog sidebar is active */
			if (is_active_sidebar('blog-sidebar')):
				
				echo '<aside class="sb-wrp pl-30 pl-0-md mt-30-md pt-30-md jkd-sticky">';
				
				/* display blog sidebar */
				dynamic_sidebar('blog-sidebar');
				
				echo '</aside>';
			
			endif;
		
		else:
			
			?>
			
			<div class="not-found w-100" data-aos="jkd-fade-up">
				<svg xmlns="http://www.w3.org/2000/svg"
				     width="24"
				     height="24"
				     viewBox="0 0 24 24"
				     fill="none"
				     stroke="currentColor"
				     stroke-width="2"
				     stroke-linecap="round"
				     stroke-linejoin="round"
				     class="mb-15 pr-col">
					<path d="m2 2 20 20" />
					<path d="M8.35 2.69A10 10 0 0 1 21.3 15.65" />
					<path d="M19.08 19.08A10 10 0 1 1 4.92 4.92" />
				</svg>
				<h4 class="mb-10 h-col"><?php echo esc_html__('Nothing Found', 'fabula'); ?></h4>
				<p class="mt-txt txt-col"><?php echo esc_html__('No matching results found.', 'fabula'); ?></p>
				<div class="mt-15 form-wrp sf-form txt-col">
					<?php get_search_form(); ?>
				</div>
			</div>
		
		<?php endif; ?>
	
	</section>

<?php