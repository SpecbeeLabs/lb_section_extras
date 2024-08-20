<?php

namespace Drupal\lb_section_extras\Plugin\Layout;

use Drupal\Core\Layout\LayoutDefault;

/**
 * Class to provides extra configurations for layouts.
 */
class StormLayout extends LayoutDefault {

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);
    $build['#attributes']['class'][] = [
      'layout',
    ];

    return $build;
  }

}
