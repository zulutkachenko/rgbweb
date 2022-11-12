<?php

  function gallery_metabox_enqueue($hook) {
    
      wp_enqueue_script('gallery-metabox', get_template_directory_uri() . '/assets/js/gallery-metabox.js', array('jquery', 'jquery-ui-sortable'), true);
      wp_enqueue_style('gallery-metabox', get_template_directory_uri() . '/assets/css/gallery-metabox.css', 'all');
    
  }
  add_action('admin_enqueue_scripts', 'gallery_metabox_enqueue');

  function add_gallery_metabox($post_type) {
    $types = 'metathumb';

    if ($post_type and $types) {
      add_meta_box(
        'gallery-metabox',
        'Галерея для карточки',
        'gallery_meta_callback',
        $post_type,
        'normal',
        'high'
      );
    }
  }
  add_action('add_meta_boxes', 'add_gallery_metabox');

  function gallery_meta_callback($post) {
    wp_nonce_field( basename(__FILE__), 'gallery_meta_nonce' );
    $ids = get_post_meta($post->ID, 'vdw_gallery_id', true);

    ?>
    <table class="form-table">
      <tr><td>
        <a class="gallery-add button" href="#" data-uploader-title="Добавьте изображения в галерею" data-uploader-button-text="Добавить изображения">Добавить изображения</a>

        <ul id="gallery-metabox-list">
        <?php if ($ids) : foreach ($ids as $key => $value) : $image = wp_get_attachment_image_src($value); ?>

          <li>
            <input type="hidden" name="vdw_gallery_id[<?php echo $key; ?>]" value="<?php echo $value; ?>">
            <img class="image-preview" src="<?php echo $image[0]; ?>">
            <a class="change-image button button-small" href="#" data-uploader-title="Change image" data-uploader-button-text="Change image">Изменить изображение</a><br>
            <small><a class="remove-image" href="#">Удалить изображение</a></small>
          </li>

        <?php endforeach; endif; ?>
        </ul>

      </td></tr>
    </table>
  <?php }

  function gallery_meta_save($post_id) {
    if (!isset($_POST['gallery_meta_nonce']) || !wp_verify_nonce($_POST['gallery_meta_nonce'], basename(__FILE__))) return;

    if (!current_user_can('edit_post', $post_id)) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if(isset($_POST['vdw_gallery_id'])) {
      update_post_meta($post_id, 'vdw_gallery_id', $_POST['vdw_gallery_id']);
    } else {
      delete_post_meta($post_id, 'vdw_gallery_id');
    }
  }
  add_action('save_post', 'gallery_meta_save');

?>