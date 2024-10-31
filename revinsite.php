<?php
/*
Plugin Name: RevInSite
Plugin URI: https://wordpress.org/plugins/revinsite/
Description: Allows users to place ads by RevInSite
Version: 1.1.0
Author: krupenik
*/

function revinsite_add_adserver_javascript_snippet() {
  ?>

  <script>
  var REVINSITE_TOKEN = '<?php echo esc_attr(get_option('revinsite_token')); ?>';
  (function(a,d,v,e,r,t){r=a.createElement(d),t=a.getElementsByTagName(d)[0];r.async=1;r.src=v;t.parentNode.insertBefore(r,t);})(document,'script','//as.rev-insite.com/assets/bundle.js')
  </script>

  <?php
}

function revinsite_shortcode($attrs) {
  $html = '<div class="revinsite-ad"';

  if ($attrs['token']) {
    $html .= " data-token=\"{$attrs['token']}\"";
  }

  $html .= '></div>';

  return $html;
}

function revinsite_add_options_page() {
  add_options_page('RevInSite', 'RevInSite', 'manage_options', 'revinsite', 'revinsite_options_page');
}

function revinsite_options_page() {
  if (!current_user_can('manage_options')) {
    return;
  }

  settings_errors('revinsite_messages');
  ?>

  <div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
  <form action="options.php" method="post">

  <?php
  settings_fields('revinsite');
  do_settings_sections('revinsite_options_page');
  submit_button('Save Settings');
  ?>

  </form>
  </div>

  <?php
}

function revinsite_init_settings() {
  register_setting('revinsite', 'revinsite_token');
  add_settings_section('revinsite_settings_section', 'RevInSite Token', 'revinsite_settings_section_cb', 'revinsite_options_page');
  add_settings_field('revinsite_token_field', 'RevInSite Token', 'revinsite_settings_field_cb', 'revinsite_options_page', 'revinsite_settings_section');
}

function revinsite_settings_section_cb() {
}

function revinsite_settings_field_cb() {
  $setting = get_option('revinsite_token');

  ?>
  <input type="text" name="revinsite_token" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
  <?php
}

add_action('admin_init', 'revinsite_init_settings');
add_action('admin_menu', 'revinsite_add_options_page');
add_action('wp_footer', 'revinsite_add_adserver_javascript_snippet');
add_shortcode('revinsite-ad', 'revinsite_shortcode');

