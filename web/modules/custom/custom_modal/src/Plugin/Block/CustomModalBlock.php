<?php

/**
 * @file
 * Creates a block that displays the modal.
 */

namespace Drupal\custom_modal\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Session\AccountInterface;

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
    $form = \Drupal::formBuilder()->getForm('Drupal\rsvplist\Form\RSVPForm');
    $service = \Drupal::service('custom_modal.modal_service');
    $node = \Drupal::routeMatch()->getParameter('node');
    $modal_title = $service->getTitle($node);
    $modal_content = $service->getContent($node);
    $build = [
      '#theme' => 'custom_modal_backdrop',
      '#modal_title' => t($modal_title),
      '#content' => t($modal_content),
      '#attached' => [
        'library' => [
          'custom_modal/custom_modal_styles',
        ]
      ],
      '#cache' => [
        'max-age' => 0
      ],
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {
    $node = \Drupal::routeMatch()->getParameter('node');
    if (is_null($node)) { // ask
      return AccessResult::forbidden();
    }
    $node_type = $node->getType();
    $config = \Drupal::config('custom_modal.settings');
    $allowed_types = $config->get('allowed_types');

    if (!in_array($node_type, $allowed_types)) {
      return AccessResult::forbidden();
    }

    $service = \Drupal::service('custom_modal.modal_service');
    if (!$service->isEnabled($node)) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowedIfHasPermission($account, 'view custom modal');
  }
}
