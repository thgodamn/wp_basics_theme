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
    wp_nonce_field( 'parent_city_meta_box_nonce', 'meta_box_nonce' );

    if( $citys ){
        // чтобы портянка пряталась под скролл...
        echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';

        foreach( $citys as $city ){
            echo '
			<li><label>
				<input type="radio" name="parent_city" value="'. $city->ID .'" '. checked($city->ID, $post->parent_city, 0) .'> '. esc_html($city->post_title) .'
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

add_action( 'save_post', 'estate_city_metabox_save' );
function estate_city_metabox_save( $post_id )
{
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'parent_city_meta_box_nonce' ) ) return;

    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post' ) ) return;

    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchors can only have href attribute
        )
    );

    if( isset( $_POST['parent_city'] ) )
        update_post_meta( $post_id, 'parent_city', wp_kses( $_POST['parent_city'], $allowed ) );

}

add_action('wp_ajax_estate_add', 'estate_add'); // wp_ajax_{ACTION HERE}
//add_action('wp_ajax_estate_add_loadmore', 'estate_add'); // wp_ajax_{ACTION HERE}
add_action('wp_ajax_nopriv_estate_add', 'estate_add');

//Ajax-форма отправить недваижимость
function estate_add(){
    //debug_log($_FILES['file']);
    debug_log($_POST);
    //debug_log(wp_upload_dir());

    if( !isset( $_POST['form_nonce'] ) || !wp_verify_nonce( $_POST['form_nonce'], 'estate_form_nonce' ) ) {
        echo 'denied';
        return;
    }

    if (!empty($_POST['address']) && !empty($_POST['coast']) && !empty($_POST['square']) && !empty($_POST['living_square']) && !empty($_POST['floor']) && isset($_FILES['file'])
    && $_POST['coast'] >= 1 && $_POST['square'] >= 1 && $_POST['living_square'] >= 1 && $_POST['floor'] >= 1) {
        $post_data = array(
            'post_title'    => $_POST['address'],
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'=>'estate',
            'post_author'   => 1,
            //'coast' => $_POST['coast'],
            //'square' => $_POST['square'],
            //'living_square' => $_POST['living_square'],
            //'address' => $_POST['address'],
            //'floor' => $_POST['floor'],
        );
        $post_id = wp_insert_post( $post_data );

        update_field( 'coast', $_POST['coast'], $post_id );
        update_field( 'square', $_POST['square'], $post_id );
        update_field( 'living_square', $_POST['living_square'], $post_id );
        update_field( 'address', $_POST['address'], $post_id );
        update_field( 'floor', $_POST['floor'], $post_id );

        $upload = wp_upload_bits($_FILES["file"]["name"], null, file_get_contents($_FILES["file"]["tmp_name"]));
        $filename = $upload['file'];
        $uploadfile = wp_upload_dir();

        move_uploaded_file($filename, $uploadfile);  // (file name , designation)

        $wp_filetype = wp_check_filetype($filename, null );
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );
        $attach_id = wp_insert_attachment( $attachment, $filename, $post_id );
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata( $attach_id, $filename );

        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $post_id, $attach_id );  // set post thumnail (featured image) for the given post

        if ($post_id != 0)
            echo 'success';
        else echo 'denied';
    } else {
        echo 'denied';
    }
    die();
}

function debug_log($obj)
{
    $h = fopen('D:/OpenServer/domains/wordpress_basicstech/debug.html', 'a');
    ob_start();
    var_dump($obj);
    fwrite($h, ob_get_clean());
    fwrite($h, "---------------------------------\n");
    fclose($h);
}