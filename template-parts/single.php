<?php defined('ABSPATH') || die('You do not have access to this page!');

/* check have_posts */
if (have_posts()) :
	
	/* post loop */
	while (have_posts()) : the_post();
		
		/* global multipage var */
		global $multipage;
		
		/* get post */
		$post = get_post();
		
		/* categories */
		$cats = get_the_category();
		
		/* tags */
		$tags = get_the_tags();
		
		$date = get_the_date('', $post);
		
		/* comments */
		$comments = get_comments_number($post->ID);
		
		$title = get_the_title();
		
		$author_id = $post->post_author;
		
		echo '<article class="pt-75 pb-75 pt-50-md pb-50-md pt-30-sm pb-30-sm ' . implode(get_post_class()) . '" id="def-sp">';
		
		?>
		
		<div class="in-wrp d-f d-col">
			
			<div class="d-f d-col mb-30 <?php if (is_sticky()) echo 'sticky-header' ?>">
				
				<?php if (!empty($cats)): ?>
					
					<div class="mb-10 mb-7-sm">
						
						<div class="cat-list d-f ai-c f-wrp">
							
							<?php
							
							/* categories loop */
							foreach ($cats as $cat):
								
								?>
								
								<a href="<?php echo get_category_link($cat->term_id); ?>"
								   class="cat-lb-def">
									
									<?php echo esc_html($cat->name); ?>
								
								</a>
							
							<?php endforeach; ?>
						
						</div>
					
					</div>
				
				<?php endif; ?>
				
				<?php if (!empty($title)) echo '<h1 class="mb-10 mb-7-sm p-ttl">' . wp_kses_post($title) . '</h1>'; ?>
				
				<ul class="post-meta up-txt d-f ai-c">
					
					<?php if (!empty($author_id)): ?>
						
						<li class="meta-item lnh-1 d-f ai-c">
							
							<?php
							
							$author_url = get_author_posts_url($author_id);
							
							$avatar = get_avatar_url($author_id, ['size' => 100]);
							
							$author_name = get_the_author_meta('display_name', $author_id);
							
							?>
							
							<a <?php if (!empty($author_url)): ?>href="<?php echo esc_url($author_url); ?>"<?php endif; ?>
							   class="auth-img avtr mr-7 mr-5-sm avtr--xs">
								
								<img <?php if (!empty($avatar)): ?>src="<?php echo esc_url($avatar); ?>"
								     <?php endif;
								     if (!empty($author_name)): ?>alt="<?php echo esc_attr($author_name); ?>"<?php endif; ?>>
							
							</a>
							
							<div class="name-wrp d-f d-col">
								
								<span class="author-name">
									
									<span class="prefix h-col op-md mr-3">
										
										<?php echo esc_html__('By', 'fabula'); ?>
									
									</span>
									
									<a <?php if (!empty($author_url)): ?>href="<?php echo esc_url($author_url); ?>"<?php endif; ?>
									   class="name h-col ttl-link m-fw">
										
										<?php echo esc_html($author_name); ?>
									
									</a>
								
								</span>
								
								<span class="lnh-1 h-col op-md mt-3">
									
									<?php echo esc_html($date); ?>
								
								</span>
							
							</div>
						
						</li>
					
					<?php endif; ?>
				
				</ul>
			
			</div>
			
			<?php if (has_post_thumbnail()): ?>
				
				<header class="p-hdr d-f d-col o-hd mb-30 mb-15-sm ai-s jc-fs">
					
					<?php
					
					/* post thumbnail render */
					the_post_thumbnail('full', [
						'class' => 'img-fl br-md'
					]);
					
					?>
				
				</header>
			
			<?php
			
			endif; ?>
			
			<div class="entry-content">
				
				<?php
				
				/* content render */
				the_content();
				
				/* check if $multipage is not empty */
				if (0 !== $multipage):
					
					/* output link pages */
					wp_link_pages([
						'link_before' => '<span class="l-wrp">',
						'link_after' => '</span>',
					]);
				
				endif;
				
				?>
			
			</div>
			
			<?php if (!empty($tags)): ?>
				
				<div class="mt-15 pt-15 w-100 d-f bdt">
					
					<span class="up-txt mr-7">
						
						<?php echo esc_html__('Tags:', 'fabula'); ?>
					
					</span>
					
					<div class="tags-list-s d-f ai-c f-wrp">
						
						<?php foreach ($tags as $tag): ?>
							
							<a href="<?php echo get_category_link($tag->term_id); ?>"
							   class="tag-lb">
								
								<?php echo esc_html($tag->name); ?>
							
							</a>
						
						<?php endforeach; ?>
					
					</div>
				
				</div>
			
			<?php
			
			endif;
			
			/* comments template */
			comments_template();
			
			?>
		
		</div>
		
		<?php
		
		echo '</article>';
	
	endwhile;

endif;