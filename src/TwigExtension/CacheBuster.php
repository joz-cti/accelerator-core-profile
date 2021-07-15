<?php

namespace Drupal\gla_core_profile\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Twig extension.
 */
class CacheBuster extends AbstractExtension {

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      /*
       * Returns a cachbuster string provided by Drupal core (or a fallback)
       *
       * This is useful to prevent browser caching of assets like SVG icon
       * sprites.
       */
      new TwigFunction('gla_core_profile_get_cachebuster', function () {
        return \Drupal::state()->get('system.css_js_query_string') ?: '0';
      }),
    ];
  }

}
