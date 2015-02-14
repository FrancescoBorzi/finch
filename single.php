<?php get_header(); ?>
<div id="content-holder" class="row" role="main">
	<div class="col-md-8">
		<?php
			while (have_posts()) : the_post();
				get_template_part('content', get_post_format());
				if (comments_open()) {
					comments_template();
				}
			endwhile;
		?>
	</div>
	<?php get_sidebar('primary-sidebar'); ?>
</div>
<?php get_footer(); ?>
