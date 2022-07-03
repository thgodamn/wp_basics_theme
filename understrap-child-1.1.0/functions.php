<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );

add_action('wp_ajax_estate_add', 'estate_add'); // wp_ajax_{ACTION HERE}
//add_action('wp_ajax_estate_add_loadmore', 'estate_add'); // wp_ajax_{ACTION HERE}
add_action('wp_ajax_nopriv_estate_add', 'estate_add');

//Ajax-форма отправить недваижимость
function estate_add(){
    if (!empty($_POST['address']) && !empty($_POST['coast']) && !empty($_POST['square']) && !empty($_POST['living_square']) && !empty($_POST['floor'])  ) {
        $post_data = array(
            'post_title'    => $_POST['address'],
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'=>'estate',
            'post_author'   => 1,
            'coast' => $_POST['coast'],
            'square' => $_POST['square'],
            'living_square' => $_POST['living_square'],
            'address' => $_POST['address'],
            'floor' => $_POST['floor'],
        );
        $post_id = wp_insert_post( $post_data );

        if ($post_id != 0)
            echo 'success';
        else echo 'denied';
    } else {
        echo 'denied';
    }
    die();
}

/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @param string $current_mod The current value of the theme_mod.
 * @return string
 */
function understrap_default_bootstrap_version( $current_mod ) {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

//Custom
//регистрируем таксономию недвижимость
function estate_type() {
    register_taxonomy(
        'estate_type',  					// This is a name of the taxonomy. Make sure it's not a capital letter and no space in between
        'estate',        			//post type name
        array(
            'hierarchical' => true,
            'label' => 'Тип недвижимости',  	//Display name
            'query_var' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'estate')
        )
    );
}
add_action( 'init', 'estate_type');

//Регистрируем post_type Недвижимость (estate)
function post_type_estate() {
    $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'page-attributes',
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );
    $labels = array(
        'name' => _x('Недвижимость', 'plural'),
        'singular_name' => _x('Недвижимость', 'singular'),
        'menu_name' => _x('Недвижимость', 'admin menu'),
        'name_admin_bar' => _x('Недвижимость', 'admin bar'),
        'add_new' => _x('Добавить Недвижимость', 'add new'),
        'add_new_item' => __('Добавить Недвижимость'),
        'new_item' => __('Новая Недвижимость'),
        'edit_item' => __('Редактировать'),
        'view_item' => __('Показать Недвижимость'),
        'all_items' => __('Вся Недвижимость'),
        'search_items' => __('Поиск Недвижимости'),
        'not_found' => __('Недвижимость не добавлена'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'estate'),
        'has_archive' => true,
        'hierarchical' => false,
        'taxonomies' => array('estate_type') #array('category', 'post_tag')

    );
    register_post_type('estate', $args);
}
add_action('init', 'post_type_estate');

//Регистрируем post_type Недвижимость (estate)
function post_type_city() {
    $supports = array(
        'title', // post title
        'editor', // post content
        'author', // post author
        'thumbnail', // featured images
        'excerpt', // post excerpt
        'custom-fields', // custom fields
        'page-attributes',
        'comments', // post comments
        'revisions', // post revisions
        'post-formats', // post formats
    );
    $labels = array(
        'name' => _x('Город', 'plural'),
        'singular_name' => _x('Город', 'singular'),
        'menu_name' => _x('Город', 'admin menu'),
        'name_admin_bar' => _x('Город', 'admin bar'),
        'add_new' => _x('Добавить Город', 'add new'),
        'add_new_item' => __('Добавить Город'),
        'new_item' => __('Новый Город'),
        'edit_item' => __('Редактировать'),
        'view_item' => __('Показать Город'),
        'all_items' => __('Все Города'),
        'search_items' => __('Поиск Города'),
        'not_found' => __('Город не добавлен'),
    );
    $args = array(
        'supports' => $supports,
        'labels' => $labels,
        'public' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'city'),
        'has_archive' => true,
        'hierarchical' => false,
        'taxonomies' => array('') #array('category', 'post_tag')

    );
    register_post_type('city', $args);
}
add_action('init', 'post_type_city');

// Добавим метабокс выбора города
add_action('add_meta_boxes', function () {
    add_meta_box( 'estate_city', 'Города', 'estate_city_metabox', 'estate', 'side', 'low'  );
}, 1);

// метабокс с селектом города
function estate_city_metabox( $post ){
    $citys = get_posts(array( 'post_type'=>'city', 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));

    if( $citys ){
        // чтобы портянка пряталась под скролл...
        echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';

        foreach( $citys as $city ){
            echo '
			<li><label>
				<input type="radio" name="post_parent" value="'. $city->ID .'" '. checked($city->ID, $post->post_parent, 0) .'> '. esc_html($city->post_title) .'
			</label></li>
			';
        }

        echo '
			</ul>
		</div>';
    }
    else
        echo 'Городов нет...';
}

// вывод недвижимости у городов
add_action('add_meta_boxes', function(){
    add_meta_box( 'estates', 'Недвижимость', 'city_estates_metabox', 'city', 'side', 'low'  );
}, 1);

function city_estates_metabox( $post ){
    $estates = get_posts(array( 'post_type'=>'estate', 'post_parent'=>$post->ID, 'posts_per_page'=>-1, 'orderby'=>'post_title', 'order'=>'ASC' ));

    if( $estates ){
        foreach( $estates as $estate ){
            echo $estate->post_title .'<br>';
        }
    }
    else
        echo 'Недвижимости нет...';
}
