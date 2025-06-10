<?php defined('ABSPATH') || exit('You do not have access to this page!'); ?>

<div class="top-section error-section d-f ai-c jc-c w-100"
     data-aos-delay="150"
     data-aos="jkd-fade-up">
	<div class="in-wrp jkd-c pt-50 pt-30-md pb-50 pb-30-md d-col ai-c jc-c w-100-d">
		<h1 class="lnh-1 mb-15 mb-10-sm ta-c">
			<?php echo esc_html__('404', 'fabula'); ?>
		</h1>
		<p class="mb-12 mb-10-sm mt-txt w-100 ta-c d-f jc-c ai-c">
			<?php echo esc_html__('Perhaps searching can help', 'fabula'); ?>
		</p>
		<div class="w-100">
			<?php get_search_form(); ?>
		</div>
	</div>
</div>