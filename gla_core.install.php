<?php

/**
 * @file
 * Install functions for the GLA Core installation profile.
 */

use Drupal\user\Entity\User;

/**
 * Implements hook_install().
 *
 * Perform actions to set up the site for this profile.
 *
 * @see system_install()
 */
function gla_core_install() {

  // Assign user 1 the "administrator" role.
  $user = User::load(1);
  $user->roles[] = 'administrator';
  $user->save();
}