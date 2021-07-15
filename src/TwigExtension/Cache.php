<?php

namespace Drupal\gla_core_profile\TwigExtension;

use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Render\RendererInterface;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Helps bubble render array cache metadata without needing to render `content`.
 *
 * See https://www.drupal.org/project/drupal/issues/2660002 for more
 * information.
 * Code taken from https://git.drupalcode.org/project/sfc/-/commit/0e64f2f8348e3156d704d3fb66a725733e9ce606
 */
class Cache extends AbstractExtension {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * TwigExtension constructor.
   *
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer.
   */
  public function __construct(RendererInterface $renderer) {
    $this->renderer = $renderer;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new TwigFunction('gla_core_profile_cache', [$this, 'cache']),
    ];
  }

  /**
   * Allows component plugins to quickly add caching to templates.
   *
   * See https://git.drupalcode.org/project/sfc/-/commit/0e64f2f8348e3156d704d3fb66a725733e9ce606
   * for source.
   *
   * @param mixed $arg
   *   String, Object or Array.
   * @param string $type
   *   The type of metadata for string $args. Defaults to "tags".
   *   Valid options are "contexts", "max-age", or "tags".
   *
   * @return mixed
   *   The rendered output.
   */
  public function cache($arg, $type = 'tags') {
    $build = [];
    $metadata = new CacheableMetadata();
    $scalars = [];

    if (!is_array($arg)) {
      $arg = [$arg];
    }

    foreach ($arg as $current) {
      if (is_scalar($current)) {
        $scalars[] = $current;
      }
      elseif ($current instanceof CacheableDependencyInterface) {
        $metadata = CacheableMetadata::createFromObject($current)->merge($metadata);
      }
    }

    if (!empty($scalars)) {
      switch ($type) {
        case 'contexts':
          $metadata->addCacheContexts($scalars);
          break;

        case 'max-age':
          $metadata->setCacheMaxAge($scalars[0]);
          break;

        default:
          $metadata->addCacheTags($scalars);
      }
    }

    $metadata->applyTo($build);
    return $this->renderer->render($build);
  }

}
