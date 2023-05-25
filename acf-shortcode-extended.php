<?php

/**
 * ACF Shortcode Extended
 *
 * @package           ShortcodeExtended
 * @author            3n3a
 * @copyright         2023 3n3a
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       ACF Shortcode Extended
 * Plugin URI:        https://github.com/3n3a/acf-shortcode-extended
 * Description:       Enable ACF custom field usage, in a shortcode, in Query Loop block
 * Version:           0.0.2
 * Requires at least: 6.0
 * Author:            3n3a
 * Author URI:        https://3n3a.ch
 * Text Domain:       acf-shortcode-extended
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if(!class_exists('ACFShortcodeExtended'))
{
  class ACFShortcodeExtended
  {
    public function render($attributes, $content, $data)
    {
      // just a failsafe
      if(!($data instanceof WP_Block))
      {
        return $content;
      }

      // if no ACF not activated or installed, then return early.
      if(!function_exists('get_field'))
      {
        return $content;
      }

      // if no ACF shortcode found in content, then return early.
      if(strpos($content, '[acf field="') === false)
      {
        return $content;
      }

      // Native functionality is to call `wpautop`, which means we lose access to the Post ID and ACF related data
      return do_shortcode($content);
    }
  }

  add_filter('register_block_type_args', function ($args, $name)
  {
    // Here we list the native blocks we are likely to include ACF shortcodes in.
    // This list probably needs to be expanded, but suits my immediate requirements.
    $validBlocks = ['core/shortcode', 'core/paragraph', 'core/list'];

    // override the native render_callback function to ensure ACF shortcodes run as expected.
    if(in_array($name, $validBlocks))
    {
      $args['render_callback'] = [new ShortcodeExtended, 'render'];
    }

    return $args;
  }, 10, 2);

}
