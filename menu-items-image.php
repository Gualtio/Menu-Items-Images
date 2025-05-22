<?php
/*
Plugin Name: Menu Items Images
Description: Aggiunge la possibilità di inserire immagini alle voci di menu
Version: 1.0
Author: Giulio Gualtieri
Author URI: https://seolog.net/web-designer-freelance/
Text Domain: menu-items-images
*/


if (!defined('ABSPATH')) {
  exit; 
}



class Menu_Item_Images {

  public function __construct() {
      add_action('wp_nav_menu_item_custom_fields', array($this, 'add_image_field'), 10, 4);
      add_action('wp_update_nav_menu_item', array($this, 'save_image_field'), 10, 3);
      add_filter('wp_setup_nav_menu_item', array($this, 'add_image_to_menu_item'));
      add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
      add_filter('nav_menu_item_title', array($this, 'display_menu_image'), 10, 4);
  }

  // Aggiunge il campo immagine nell'admin
  public function add_image_field($item_id, $item, $depth, $args) {
      $image_id = get_post_meta($item_id, '_menu_item_image', true);
      $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
      ?>
      <div class="field-image description description-wide" style="margin: 5px 0;">
          <label for="edit-menu-item-image-<?php echo $item_id; ?>">
              <?php _e('Immagine Menu', 'menu-item-images'); ?><br />
              <input type="hidden" id="edit-menu-item-image-<?php echo $item_id; ?>" class="widefat edit-menu-item-image" name="menu-item-image[<?php echo $item_id; ?>]" value="<?php echo esc_attr($image_id); ?>" />
              <div class="menu-item-image-wrapper" style="margin: 5px 0;">
                  <?php if ($image_url) : ?>
                      <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100px; height: auto;" />
                  <?php endif; ?>
              </div>
              <button class="button button-secondary menu-item-image-upload"><?php _e('Aggiungi Immagine', 'menu-item-images'); ?></button>
              <button class="button button-link menu-item-image-remove" <?php echo $image_url ? '' : 'style="display:none;"'; ?>><?php _e('Rimuovi', 'menu-item-images'); ?></button>
          </label>
      </div>
      <?php
  }

  // Salva il campo immagine
  public function save_image_field($menu_id, $menu_item_db_id, $args) {
      if (isset($_POST['menu-item-image'][$menu_item_db_id])) {
          $image_id = sanitize_text_field($_POST['menu-item-image'][$menu_item_db_id]);
          update_post_meta($menu_item_db_id, '_menu_item_image', $image_id);
      }
  }

  // Aggiunge l'immagine all'oggetto menu
  public function add_image_to_menu_item($menu_item) {
      $menu_item->image = get_post_meta($menu_item->ID, '_menu_item_image', true);
      return $menu_item;
  }

  // Mostra l'immagine nel frontend
  public function display_menu_image($title, $item, $args, $depth) {
      if (!empty($item->image)) {
          $image = wp_get_attachment_image($item->image, 'full', false, array(
              'class' => 'menu-item-image',
              'style' => 'vertical-align: middle; margin-right: 5px;'
          ));
          $title = $image . $title;
      }
      return $title;
  }

  // Carica gli script necessari nell'admin
  public function enqueue_admin_scripts($hook) {
      if ($hook !== 'nav-menus.php') {
          return;
      }

      wp_enqueue_media();
      wp_enqueue_script('menu-item-images', plugins_url('js/admin.js', __FILE__), array('jquery'), '1.0', true);
      wp_enqueue_style('menu-item-images', plugins_url('css/admin.css', __FILE__));
  }
}

new Menu_Item_Images();


