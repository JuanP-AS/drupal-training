<?php

/**
 * @file
 * Creates a block that displays the modal.
 */

namespace Drupal\custom_modal\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * @Block(
 *   id = "custom_modal_block",
 *   admin_label = @Translation("The Modal Block"),
 * )
 */
class CustomModalBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $test = \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
    $build = [
      '#theme' => 'custom_modal_backdrop',
      '#modal_title' => 'This is the title of the modal',
      '#content' => $test,
      '#attached' => [
        'library' => [
          'custom_modal/custom_modal_styles'
        ]
      ],
      '#cache' => [
        'max-age' => 0
      ],
    ];

    return $build;
  }
}
