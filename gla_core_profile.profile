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
