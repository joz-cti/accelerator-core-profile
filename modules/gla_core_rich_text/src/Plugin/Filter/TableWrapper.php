<?php

namespace Drupal\gla_core_rich_text\Plugin\Filter;

use Drupal\Core\Render\RendererInterface;
use Drupal\filter\FilterProcessResult;
use Drupal\filter\Plugin\FilterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

define('GLA_CORE_RICH_TEXT_TABLE_WRAPPER_START_REGEX', '/\<table.*?\>/s');
define('GLA_CORE_RICH_TEXT_TABLE_WRAPPER_END_REGEX', '/\<\/table.*?\>/s');


/**
 * Provides a filter to wrap tables in a div to make them scrollable.
 *
 * @Filter(
 *   id = "gla_core_rich_text_table_wrapper",
 *   title = @Translation("Add wrapper to tables to make them scrollable on small screens"),
 *   type = Drupal\filter\Plugin\FilterInterface::TYPE_TRANSFORM_IRREVERSIBLE,
 *   weight = -10
 * )
 */
class TableWrapper extends FilterBase implements ContainerFactoryPluginInterface {

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\RendererInterface
   */
  protected $renderer;

  /**
   * Constructs a ContainerFactoryPluginInterface object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Render\RendererInterface $renderer
   *   The renderer service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, RendererInterface $renderer) {
    $this->renderer = $renderer;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('renderer'),
    );
  }

  /**
   * Get the two parts of our scrollable table wrapper component.
   *
   * One part goes before the <table> element and one comes after it.
   *
   * @return array
   *   The two parts of our scrollable table wrapper, as an array.
   */
  private function getTableWrapperParts() {
    // An arbitrary string that we will use later on to split our scrollable
    // table component into a part that comes before the content it will
    // wrap, and a part that comes after the content that it will wrap.
    $divider = '|||';
    $table_wrapper = [
      '#theme' => 'gla_core_rich_text_scrollable_table',
      '#content' => $divider,
    ];
    // Render our component with our divider string and turn it into HTML.
    $table_wrapper_markup = $this->renderer->render($table_wrapper);

    // Return an array containing the two parts of the component.
    return explode($divider, $table_wrapper_markup);
  }

  /**
   * {@inheritdoc}
   */
  public function process($text, $langcode) {
    // Find the start and end of the table and use a callback to transform the
    // markup.
    $text = preg_replace_callback(GLA_CORE_RICH_TEXT_TABLE_WRAPPER_START_REGEX, [
      &$this,
      'startReplace',
    ], $text);
    $text = preg_replace_callback(GLA_CORE_RICH_TEXT_TABLE_WRAPPER_END_REGEX, [
      &$this,
      'endReplace',
    ], $text);

    return new FilterProcessResult($text);
  }

  /**
   * Callback to add a scrollable table wrapper around the start of a table.
   *
   * @param string $match
   *   The markup for our <table> element.
   *
   * @return string
   *   The table element plus the start of our scrollable table wrapper.
   */
  private function startReplace($match) {
    return $this->getTableWrapperParts()[0] . reset($match);
  }

  /**
   * Callback to add a scrollable table wrapper around the end of a table.
   *
   * @param string $match
   *   The markup for our </table> element.
   *
   * @return string
   *   The closing table element plus the end of our scrollable table wrapper.
   */
  private function endReplace($match) {
    return reset($match) . $this->getTableWrapperParts()[1];
  }

}
