<?php

/**
 * This file contains overrides made to the default Drupal settings.php
 * values.
 * It is loaded by the main settings.php file, but we otherwise leave that
 * file unmodified so that it is easier to carry out Core updates, and so that
 * we can see all of our overrides in one file.
 */

/**
 * Override default Twig method whitelist.
 */
$settings['twig_sandbox_whitelisted_methods'] = [
  // Defaults:
  'id',
  'label',
  'bundle',
  'get',
  '__toString',
  'toString',
  // Additions:
  'toArray',
  'url'
];
