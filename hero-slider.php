<?php
/*
Plugin Name: Hero Slider
Description: Vídeo and Image Slider
*/

/* ==================================================
    UTILS FUNTIONS
================================================== */

require_once("functions.php");

/* ==================================================
    IMAGE SLIDER - POST TYPE
================================================== */

add_action('init', 'create_post_type');

function create_post_type()
{
    register_post_type('image_slider',
    array(
      'labels' => array(
        'name' => __('Image Slider'),
        'singular_name' => __('Slide')
      ),
      'public' => true,
      'has_archive' => true,
      'show_in_rest' => true,
      'supports' => array('title', 'thumbnail'),
      'menu_icon' => 'dashicons-money',
    )
  );
}

/* ==================================================
    MOVIE SLIDER - MENU
================================================== */

add_action('admin_menu', 'register_movie_slider_menu_page');

function register_movie_slider_menu_page()
{
    add_menu_page(
        'Movie Slider',  // page_title
        'Movie Slider',  // menu_title
        'manage_options',// capability
        'movie_slider',  // menu_slug
        'render_movie_slider_menu_page', // function
        'dashicons-format-video',
        6
    );
}

function render_movie_slider_menu_page()
{
    ?>
  <form action='options.php' method='post' enctype="multipart/form-data">

    <h1>Movie Slider</h1>

    <?php
    settings_fields('movie_slider');
    do_settings_sections('movie_slider');
    submit_button(); ?>

  </form>
  <?php
}

add_action('admin_init', 'movie_slider_settings_init');

function movie_slider_settings_init(){
  register_setting(
    'movie_slider', // option group - used to render the options page
    'movie_slider_settings', // option name - used with functions like get_option()
    'handle_file_upload' // function callback
  );

  add_settings_section(
    'movie_slider_section', //id - used to add fields to this section
    'Options', // Title
    'movie_slider_settings_section_render', // Function callback
    'movie_slider' // id - menu slug
  );

  // CHECKBOX field
  add_settings_field(
  'movie_slider_check_fld', // id
  'Usar movie Slider?', // Title
  'movie_slider_check_render', // Function callback
  'movie_slider', // menu slug
  'movie_slider_section' // Section id
  );

  // Caption field
add_settings_field(
  'movie_slider_caption_fld', // id
  'Caption', // Title
  'movie_slider_caption_fld_render', // Function callback
  'movie_slider', // menu slug
  'movie_slider_section' // Section id
);

// URL field
add_settings_field(
'movie_slider_url_fld', // id
'Movie URL', // Title
'movie_slider_url_fld_render', // Function callback
'movie_slider', // menu slug
'movie_slider_section' // Section id
);

// MEDIA UPLOAD field
add_settings_field(
'movie_slider_media_fld', // id
'Media', // Title
'movie_slider_media_fld_render', // Function callback
'movie_slider', // menu slug
'movie_slider_section' // Section id
);

}

function handle_file_upload($options)
{
  $uploadedfile = $_FILES['movie_slider_media_fld'];
  $upload_overrides = array( 'test_form' => false );

  if ($uploadedfile['tmp_name'] == "") {
    $old_options = get_option('movie_slider_settings')['movie_slider_media_fld'];
    $options['movie_slider_media_fld'] = $old_options;
    return $options;
  }else{
    $result = wp_handle_upload($uploadedfile, $upload_overrides);
    $media_url = $result['url'];
    $media_type = $result['type'];
    $media = array(
      'url' => $media_url,
      'type' => $media_type
    );
    $options['movie_slider_media_fld'] = $media;
    return $options;
  }
}


function movie_slider_settings_section_render($args)
{}

// CHECKBOX FIELD - RENDER
function movie_slider_check_render(){
  $setting = get_option( 'movie_slider_settings' );
  ?>
    <div id="checkbox-wrapper">
      <span>No</span>
      <input type='checkbox' id='movie_slider_check_fld' class='switch-toggle' name='movie_slider_settings[movie_slider_check_fld]' <?php checked($setting['movie_slider_check_fld'], 1); ?> value='1'>
      <label for="movie_slider_check_fld"></label>
      <span>Yes</span>
    </div>
  <?php
}

// CAPTION FIELD - RENDER
function movie_slider_caption_fld_render(){
  $setting = get_option( 'movie_slider_settings' );
  ?>
    <input type='text' id="movie_slider_caption_fld" name="movie_slider_settings[movie_slider_caption_fld]" value="<?php echo $setting['movie_slider_caption_fld']; ?>" />
  <?php
}

// URL FIELD - RENDER
function movie_slider_url_fld_render(){
  $setting = get_option( 'movie_slider_settings' );
  ?>
    <input type='text' id="movie_slider_url_fld" name="movie_slider_settings[movie_slider_url_fld]" value="<?php echo $setting['movie_slider_url_fld']; ?>" />
  <?php
}

// MEDIA UPLOAD FIELD - RENDER
function movie_slider_media_fld_render(){
  $setting = get_option( 'movie_slider_settings' );
  $media_type = $setting['movie_slider_media_fld']['type'];
  $type = returnMediaType($media_type);
  if ($type == 'IMAGE') {
    ?>
      <img src="<?php echo $setting['movie_slider_media_fld']['url']; ?>" width="200px" height="200px">
    <?php
  }else{
    ?>
    <video width="200" height="200" autoplay loop>
      <source src="<?php echo $setting['movie_slider_media_fld']['url']; ?>" type="<?php echo $setting['movie_slider_media_fld']['type']; ?>">
      <source src="mov_bbb.ogg" type="video/ogg">
      Your browser does not support HTML5 video.
    </video>
    <?php
  }
  ?>
    <input type='file' id="movie_slider_media_fld" name="movie_slider_media_fld" />
    <p>MAX FILE SIZE: 32MB</p>
    <input type="text" name="movie_slider_settings[movie_slider_media_fld]" value="<?php echo $setting['movie_slider_media_fld']['url']; ?>"  />
  <?php
}

/* =====

===== */

/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

 add_action('add_meta_boxes', 'animacao_add_meta_box');

 function animacao_add_meta_box()
 {
     add_meta_box(
        'animao-animao',
        __('Animação', 'animao'),
        'animacao_html',
        'image_slider',
        'normal',
        'default'
    );
 }

 function animacao_html($post)
 {
     wp_nonce_field('_animacao_nonce', 'animacao_nonce'); ?>

 	<p>
 		<label for="animacao_transion"><?php _e('Transição', 'animao'); ?></label><br>
 		<select name="animacao_transion" id="animacao_transion">
 			<option <?php echo (animacao_get_meta('animacao_transion') === 'type_1') ? 'selected' : '' ?>>type_1</option>
 			<option <?php echo (animacao_get_meta('animacao_transion') === 'type_2') ? 'selected' : '' ?>>type_2</option>
 			<option <?php echo (animacao_get_meta('animacao_transion') === 'type_3') ? 'selected' : '' ?>>type_3</option>
 			<option <?php echo (animacao_get_meta('animacao_transion') === 'type_4') ? 'selected' : '' ?>>type_4</option>
 			<option <?php echo (animacao_get_meta('animacao_transion') === 'type_5') ? 'selected' : '' ?>>type_5</option>
 		</select>
 	</p><?php
 }

function animacao_get_meta($value)
{
    global $post;

    $field = get_post_meta($post->ID, $value, true);
    if (! empty($field)) {
        return is_array($field) ? stripslashes_deep($field) : stripslashes(wp_kses_decode_entities($field));
    } else {
        return false;
    }
}

add_action('save_post', 'animacao_save');

function animacao_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (! isset($_POST['animacao_nonce']) || ! wp_verify_nonce($_POST['animacao_nonce'], '_animacao_nonce')) {
        return;
    }
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['animacao_transion'])) {
        update_post_meta($post_id, 'animacao_transion', esc_attr($_POST['animacao_transion']));
    }
}

/*
    Usage: animacao_get_meta( 'animacao_transion' )
*/

/* ==========
	STYLES - TOGGLE SWITCH
========== */

// load css into the admin pages
function mytheme_enqueue_options_style() {
  wp_enqueue_style( 'mytheme-options-style', plugins_url() . '/hero-slider/styles/admin-slider.css' );
}

add_action( 'admin_enqueue_scripts', 'mytheme_enqueue_options_style' );
?>
