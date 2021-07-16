<?php

namespace Drupal\gla_core_profile;

use Drupal\node\NodeInterface;
use Drupal\paragraphs\ParagraphInterface;

/**
 * Paragraph utility service.
 *
 * These are useful for dealing with basic Paragraph types like rich text
 * or button that may need to be styled or behave differently depending on
 * what other components they are nested inside.
 */
class ParagraphHelper {

  /**
   * Finds the parent node of a Paragraph, no matter how nested it is.
   *
   * @param object $entity
   *   The Paragraph entity to start the search from.
   *
   * @return object|null
   *   The parent node, if found, otherwise null.
   */
  public function findParentNode(object $entity) {
    $entity = $entity->getParentEntity();

    if ($entity instanceof NodeInterface) {
      return $entity;
    }
    elseif ($entity instanceof ParagraphInterface) {
      return self::findParentNode($entity);
    }

    return NULL;
  }

  /**
   * Finds the closest parent bundle to a Paragraph.
   *
   * @param object $entity
   *   The Paragraph entity to start the search from.
   * @param string $bundle
   *   The Paragraph bundle to look for.
   *
   * @return object|null
   *   The closest Paragraph of the target bundle, if found, otherwise null.
   */
  public function findClosestBundle(object $entity, string $bundle) {
    if ($entity instanceof ParagraphInterface && $entity->bundle() == $bundle) {
      return $entity;
    }

    $parent = $entity->getParentEntity();

    if ($parent instanceof ParagraphInterface && $parent->bundle() == $bundle) {
      return $parent;
    }
    elseif ($parent instanceof ParagraphInterface) {
      return self::findClosestBundle($parent, $bundle);
    }

    return NULL;
  }

  /**
   * Finds the parent Page section of a Paragraph.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $entity
   *   The Paragraph entity to start the search from.
   *
   * @return object|null
   *   The parent Page section, if found
   */
  public function findParentPageSection(ParagraphInterface $entity) {
    return self::findClosestBundle($entity, 'page_section');
  }

  /**
   * Is the Paragraph top level and attached to a node?
   *
   * @param \Drupal\paragraphs\ParagraphInterface $entity
   *   The Paragraph entity to start the search from.
   *
   * @return bool
   *   Whether the Paragraph is top level and attached to a node.
   */
  public function isTopLevelNodeParagraph(ParagraphInterface $entity) {
    if ($entity->getParentEntity() instanceof NodeInterface) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

  /**
   * Get the parent Paragraph of a Paragraph.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $entity
   *   The Paragraph entity to start the search from.
   *
   * @return \Drupal\Core\Entity\ContentEntityInterface|bool
   *   The parent Paragraph or FALSE if the parent entity is not a Paragraph.
   */
  public function getParentParagraph(ParagraphInterface $entity) {
    if ($entity->getParentEntity() instanceof ParagraphInterface) {
      return $entity->getParentEntity();
    }
    else {
      return FALSE;
    }
  }

}
