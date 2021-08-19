<?php

/**
 * @file
 * Code and configuration relating to the Core profile.
 */

/**
 * Implements hook_preprocess_page().
 */
function gla_core_profile_preprocess_page(&$variables) {
  /** @var \Drupal\Core\Routing\AdminContext $admin_context */
  $admin_context = \Drupal::service('router.admin_context');
  $variables['page']['#cache']['contexts'][] = 'route';

  if ($admin_context->isAdminRoute()) {
    // We can't use the theme.manager service here otherwise it would always
    // return the name of the admin theme. Instead we check the frontend theme
    // via config.
    $active_theme = \Drupal::config('system.theme')->get('default');
    $variables['#attached']['library'][] = $active_theme . '/admin-styles';
  }
}

/**
 * Implements hook_install_tasks().
 */
function gla_core_profile_install_tasks(&$install_state) {
  $tasks = [
    'gla_core_profile_install_content' => [
      'display_name' => t('Enable example content'),
      'type' => 'normal',
    ],
  ];

  return $tasks;
}

/**
 * Callback function to install default profile content.
 *
 * @see PROFILE_install_tasks()
 */
function gla_core_profile_install_content() {
  // To avoid dependency issues, it's safest to install the example content
  // module right at the end of the trash and rebuild.
  // Note that the example content module will only create new content on a
  // trash and rebuild/initial site creation.
  \Drupal::service('module_installer')->install(['gla_core_example_content']);
}

/**
 * Set a boolean field to TRUE if a particular paragraph is present.
 *
 * This is useful for automatically hiding page titles and breadcrumbs when
 * components that must be flush with the site header are used, or hiding the
 * main page title if a component that provides a H1 is being used.
 *
 * @param \Drupal\Core\Entity\EntityInterface $node
 *   The entity type definition.
 * @param array $paragraphs_objects
 *   The paragraph object.
 * @param array $target_boolean_fields
 *   The target fields.
 * @param array $target_paragraphs
 *   The target paragraphs.
 */
function _check_booleans_if_paragraphs_present(EntityInterface $node, array $paragraphs_objects, array $target_boolean_fields, array $target_paragraphs) {
  /** @var \Drupal\paragraphs\Entity\Paragraph $paragraph */
  foreach ($paragraphs_objects as $paragraph) {
    if (in_array($paragraph->bundle(), $target_paragraphs)) {
      foreach ($target_boolean_fields as $field) {
        if ($node->$field->value !== 1) {
          $node->$field->value = 1;

          \Drupal::messenger()
            ->addStatus(t("The '@label' field was automatically checked as a @component component is in use on this page.", [
              '@label' => $node->$field->getFieldDefinition()->getLabel(),
              '@component' => $paragraph->getParagraphType()->label(),
            ]));
        }
      }
    }
  }
}

/**
 * Implements hook_ENTITY_TYPE_presave().
 */
function gla_core_profile_node_presave(EntityInterface $node) {
  if ($node->hasField('field_c_gc_page_components')) {
    if ($paragraph_field_items = $node->get('field_c_gc_page_components')->getValue()) {
      // If certain page components are in use then we may want to automatically
      // tick the 'hide page title' or 'hide page breadcrumbs' fields on behalf
      // of the CMS user, in case they have forgotten to do so.
      $paragraph_storage = \Drupal::entityTypeManager()->getStorage('paragraph');
      $ids = array_column($paragraph_field_items, 'target_id');
      $paragraphs_objects = $paragraph_storage->loadMultiple($ids);

      _check_booleans_if_paragraphs_present($node, $paragraphs_objects, ['field_c_gc_remove_title'], ['hero_header']);
      _check_booleans_if_paragraphs_present($node, $paragraphs_objects, ['field_c_gc_remove_title'], ['page_header']);
    }
  }
}
