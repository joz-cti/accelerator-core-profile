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
