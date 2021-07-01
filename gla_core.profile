<?php

/**
 * @file
 * Code and configuration relating to the Core profile.
 */

use Drupal\Core\Form\FormStateInterface;

/**
 * Implements hook_field_widget_WIDGET_TYPE_form_alter().
 */
function gla_core_field_widget_paragraphs_form_alter(&$element, FormStateInterface $form_state, $context) {

  // Use Drupal States to hide the image field if the CTA type is not image.
  if ($element['#paragraph_type'] == 'cta') {
    if (isset($element['subform']['field_p_c_image'])) {
      if (!empty($element['subform']['#parents'])) {
        $parents = $element['subform']['#parents'];
        $name = array_shift($parents) . '[';
        $name .= implode('][', $parents);
        $name .= '][field_p_c_type]';
        $element['subform']['field_p_c_image']['#states'] = [
          'visible' => [
            ':input[name="' . $name . '"]' => ['value' => 'image'],
          ],
        ];
      }
    }
  }
}
