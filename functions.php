<?php
/**
 * rgbweb functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package rgbweb
 */

if ( ! defined( '_S_VERSION' ) ) {
	define( '_S_VERSION', '1.0.0' );
}

function rgbweb_setup() {

	load_theme_textdomain( 'rgbweb', get_template_directory() . '/languages' );

	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'title-tag' );

	add_theme_support( 'post-thumbnails' );

	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'rgbweb' ),
		)
	);

	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	add_theme_support(
		'custom-background',
		apply_filters(
			'rgbweb_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'rgbweb_setup' );


function rgbweb_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'rgbweb_content_width', 640 );
}
add_action( 'after_setup_theme', 'rgbweb_content_width', 0 );

function rgbweb_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'rgbweb' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'rgbweb' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'rgbweb_widgets_init' );


function rgbweb_scripts() {
	wp_enqueue_style( 'rgbweb-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'rgbweb-style', 'rtl', 'replace' );
	wp_enqueue_style('font-gothampro', get_template_directory_uri() . '/assets/fonts/stylesheet.css', array(), '1.0', 'all');
	wp_enqueue_style('bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '5.2.2', 'all');
	wp_enqueue_style('maincss', get_template_directory_uri() . '/assets/css/style.css', array(), '1.0', 'all');

	wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '5.2.2', true);
	wp_enqueue_script( 'rgbweb-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script('AjaxLoad', get_template_directory_uri().'/assets/js/ajaxload.js', array('jquery'), false, null,  false );
	$php_array = array( 'admin_ajax' => admin_url( 'admin-ajax.php' ) );
    wp_localize_script( 'AjaxLoad', 'php_array', $php_array );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'rgbweb_scripts' );

function metathumb_posttype() {
	$args = array(
		'label' => esc_html__('Карточки', 'metathumb'),
		'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
	);

	register_post_type('metathumb', $args);
}
add_action('init', 'metathumb_posttype');


//---------- AJAX LOAD POST ------------//
add_action( 'wp_ajax_theme_post_example', 'theme_post_example_init' );
add_action( 'wp_ajax_nopriv_theme_post_example', 'theme_post_example_init' );
 
function theme_post_example_init() {
    $args = array( 
		'post_type' => 'metathumb',
		'p' => $_POST['id'] 
	);
    $theme_post_query = new WP_Query( $args );
    while( $theme_post_query->have_posts() ) : $theme_post_query->the_post();
?>
	<div class="modal-thumb">
		<div class="modal_thumb-head">
			<?php the_post_thumbnail('full'); ?>
		</div>
		<div class="modal_thumb-bottom">
			<?php 
				$post_ID = get_the_id();
				$images = get_post_meta($post_ID, 'vdw_gallery_id', true);
				foreach ($images as $image) {
					echo wp_get_attachment_image($image, 'large');
				}
			?>
		</div>
	</div>
	<div class="modal-info">
		<h1 class="modal-title" id="exampleModalLabel"><?php the_title(); ?></h1>
			<p class="modal-title-desc"><?php 
				$popup_secondary_title = get_post_meta($post_ID, 'popup_secondary', true);
				echo  $popup_secondary_title;
				?></p>
				<div class="modal-list">
					<?php the_content(); ?>
				</div>
	</div>
	
<?php
endwhile;
exit;
}  


require get_template_directory() . '/inc/metaboxes.php';


require_once 'inc/gallery.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

