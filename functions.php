<?php

// editor style
function finch_editor_style() {
  add_editor_style( get_template_directory_uri() . '/assets/css/editor-style.css' );
}
add_action('after_setup_theme', 'finch_editor_style');

// set content width
if (!isset($content_width)) {$content_width = 705;}

// theme setup
if (!function_exists('finch_setup')):
	function finch_setup() {
		register_nav_menus( array(
			'primary' => __('Primary Menu', 'finch'),
			'footer' => __('Footer Menu', 'finch')
		));
		add_theme_support('post-thumbnails');
		add_theme_support('html5', array('search-form'));
		add_theme_support('automatic-feed-links');
		add_image_size('featured', 705);
		add_image_size('featured-cropped', 705, 220, true);
	}
endif;
add_action('after_setup_theme', 'finch_setup');

// load css
function finch_css() {
	wp_enqueue_style('finch_oxygen', '//fonts.googleapis.com/css?family=Oxygen:400,700');
	wp_enqueue_style('finch_bootstrap_css', get_template_directory_uri() . '/assets/css/bootstrap.min.css');
	wp_enqueue_style('finch_style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'finch_css');

// load javascript
function finch_javascript() {
	wp_enqueue_script('finch_script', get_template_directory_uri() . '/assets/js/finch.js', array('jquery'), '1.0', true);
	if (is_singular() && comments_open()) {wp_enqueue_script('comment-reply');}
}
add_action('wp_enqueue_scripts', 'finch_javascript');

// sidebar
function finch_widgets_init() {
	register_sidebar(array(
		'name' => __('Primary Sidebar', 'finch'),
		'id' => 'primary-sidebar',
		'description' => __('Widgets in this area will appear in the right sidebar on all pages and posts.', 'finch'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h4>',
		'after_title' => '</h4>'
	));
}
add_action('widgets_init', 'finch_widgets_init');

// page titles
function finch_title($title, $sep) {
	global $paged, $page;
	if (is_feed()) {
		return $title;
	}
	$title .= get_bloginfo('name');
	$site_description = get_bloginfo('description', 'display');
	if ( $site_description && (is_home() || is_front_page())) {
		$title = "$title $sep $site_description";
	}
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __('Page %s', 'finch'), max($paged, $page));
	}
	return $title;
}
add_filter('wp_title', 'finch_title', 10, 2);

// pagination
if (!function_exists('finch_pagination')):
	function finch_pagination() {
		global $wp_query;
		$big = 999999999;
		echo '<div class="page-links">';
		echo paginate_links( array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'current' => max(1, get_query_var('paged')),
			'total' => $wp_query->max_num_pages,
			'prev_next' => False,
		));
		echo '</div>';
	}
endif;

// comments
if (!function_exists('finch_comment')) :
	function finch_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-header">
				<div class="comment-author">
					<?php echo get_avatar($comment, 40); ?>
					<p class="comment-author-name"><?php comment_author(); ?><br /><a href="<?php echo esc_url(get_comment_link( $comment->comment_ID)); ?>"><?php echo get_comment_date() . ' - ' . get_comment_time() ?></a></p>
				</div>
			</div>
			<div class="comment-body">
				<?php comment_text(); ?>
				<?php if ('0' == $comment->comment_approved) : ?>
					<p class="comment-awaiting-moderation"><?php _e('Comment is awaiting moderation!', 'finch'); ?></p>
				<?php endif; ?>
			</div>
		</div>
	<?php
	}
endif;

?>
