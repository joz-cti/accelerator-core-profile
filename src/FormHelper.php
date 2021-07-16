<?php

namespace Drupal\gla_core_profile;

/**
 * Form utility functions for working with #states in the Form API.
 */
class FormHelper {

  /**
   * Builds a selector from an array of widget parent parts.
   *
   * @param array $field
   *   Field render array.
   *
   * @return string
   *   The selector.
   */
  public function buildNameSelector(array $field) {
    $selector = '';
    foreach ($field['widget']['#parents'] as $k => $part) {
      $selector .= $k === 0 ? $part : '[' . $part . ']';
    }
    return $selector;
  }

}
