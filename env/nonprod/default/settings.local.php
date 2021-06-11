<?php

/**
 * @file
 * Local environment override configuration.
 *
 * To enable this feature, go to the bottom of 'sites/default/settings.php' and
 * uncomment the commented lines that mention 'settings.local.php'.
 */

use Drupal\Core\Installer\InstallerKernel;

/**
 * Database settings:.
 *
 * The $databases array specifies the database connection or
 * connections that Drupal may use.  Drupal is able to connect
 * to multiple databases, including multiple types of databases,
 * during the same request.
 *
 * One example of the simplest connection array is shown below. To use the
 * sample settings, copy and uncomment the code below between the @code and
 *
 * @endcode lines and paste it after the $databases declaration. You will need
 * to replace the database username and password and possibly the host and port
 * with the appropriate credentials for your database system.
 *
 * default.settings.php describes how to customize the $databases array for more
 * specific needs.
 *
 * @code
 * $databases['default']['default'] = array (
 *   'database' => 'databasename',
 *   'username' => 'sqlusername',
 *   'password' => 'sqlpassword',
 *   'host' => 'localhost',
 *   'port' => '3306',
 *   'driver' => 'mysql',
 *   'prefix' => '',
 *   'collation' => 'utf8mb4_general_ci',
 * );
 * @endcode
 */
$databases['default']['default'] = [
  'database' => '__DB_NAME__',
  'username' => '__DB_USER__',
  'password' => '__DB_PASS__',
  'host' => '__DB_HOST__',
  'driver' => 'mysql',
];

/**
 * Enable local services.
 */
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/services.local.yml';

$config['environment_indicator.indicator']['name'] = '__DRUPAL_ENVIRONMENT__';
$config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';
$config['environment_indicator.indicator']['bg_color'] = '#1B5E20';

/**
 * Enable QA and stage config split.
 */
$config['config_split.config_split.development']['status'] = FALSE;
$config['config_split.config_split.non_prod']['status'] = TRUE;
$config['config_split.config_split.prod']['status'] = FALSE;

$settings['file_private_path'] = getenv('DRUPAL_PUBLIC_FILES_PATH');
$settings['file_temp_path'] = getenv('DRUPAL_TEMP_FILES_PATH');

/**
 * Redis.
 */
$settings['redis.connection']['host'] = '__REDIS_HOST__';
$settings['redis.connection']['port'] = '__REDIS_PORT__';
