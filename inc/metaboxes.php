<?php

function rgbweb_metaboxes_enqueue() {

	if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

	wp_enqueue_script('rgbweb-metaboxes', get_template_directory_uri() . '/assets/js/rgb-metaboxes.js', array('jquery', 'jquery-ui-sortable'), true);
  
}
add_action('admin_enqueue_scripts', 'rgbweb_metaboxes_enqueue');

/*
 * Добавляем метабокс
 */
function true_meta_boxes_u() {
    add_meta_box('truediv', esc_html__('Добавить иконку для карточки', 'rgbweb'), 'true_print_box_u', 'metathumb', 'side');
	add_meta_box('popup-secondary-title', esc_html__('Добавить подзаголовок в описание карточки', 'rgbweb'), 'popup_secondary_title', 'metathumb', 'normal', 'high');
}
 
add_action( 'admin_menu', 'true_meta_boxes_u' );


function true_image_uploader_field( $name, $value = '', $w = auto, $h = 90) {
    $default = get_template_directory_uri() . '/assets/images/icons/noimage.jpeg';
    if( $value ) {
        $image_attributes = wp_get_attachment_image_src( $value, array($w, $h) );
        $src = $image_attributes[0];
    } else {
        $src = $default;
    }
    echo '
    <div>
        <img data-src="' . $default . '" src="' . $src . '" width="' . $w . '" height="' . $h . 'px" />
        <div>
            <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
            <button type="submit" class="upload_image_button button">Загрузить</button>
            <button type="submit" class="remove_image_button button">&times;</button>
        </div>
    </div>
    ';
}

function popup_secondary_title(){
	$popup_secondary = get_post_meta($post->ID, 'popup_secondary', true);

	?>
		<div>
			<input type="text" id="popup_secondary" name="popup_secondary" value="<?php echo esc_attr($popup_secondary); ?>" style="width:100%" /> 
		</div>
	<?php
}
 
/*
 * Заполняем метабокс
 */
function true_print_box_u($post) {
    if( function_exists( 'true_image_uploader_field' ) ) {
        true_image_uploader_field( 'uploader_custom', get_post_meta($post->ID, 'uploader_custom',true) );
    }
}
 
/*
 * Сохраняем данные произвольного поля
 */
function true_save_box_data_u( $post_id ) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	if(isset($_POST['uploader_custom'])) {
		update_post_meta($post_id, 'uploader_custom', $_POST['uploader_custom']);
	} else {
		delete_post_meta($post_id, 'uploader_custom');
	}

	//return $post_id;

	if(isset($_POST['popup_secondary'])) {
		update_post_meta($post_id, 'popup_secondary', $_POST['popup_secondary']);
	} else {
		delete_post_meta($post_id, 'popup_secondary');
	}
	return $post_id;
}
 
add_action('save_post', 'true_save_box_data_u', 10, 2);

?>