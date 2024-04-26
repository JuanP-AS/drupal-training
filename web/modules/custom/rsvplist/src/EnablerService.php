<?php

/**
 * @file
 * Contains RSVP enabler service.
 */

namespace Drupal\rsvplist;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class EnablerService {

  protected $database_connection;

  public function __construct(Connection $connection) {
    $this->database_connection = $connection;
  }

  public function isEnabled(Node &$node) {
    if ($node->isNew()) {
      return FALSE;
    }
    try {
      $select = $this->database_connection->select('rsvplist_enabled', 're');
      $select->fields('re', ['nid']);
      $select->condition('nid', $node->id());
      $result = $select->execute();

      return !empty($result->fetch());
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to determine RSVP settings at this time. Please try again later.')
      );
    }
  }

  public function setEnabled(Node $node) {
    try {
      if (!$this->isEnabled($node)) {
        $this->database_connection->insert('rsvplist_enabled')
          ->fields(['nid' => $node->id()])
          ->execute();
      }
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save RSVP settings at this time. Please try again later.')
      );
    }
  }

  public function deleteEnabled(Node $node) {
    try {
      $this->database_connection->delete('rsvplist_enabled')
        ->condition('nid', $node->id())
        ->execute();
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save RSVP settings at this time. Please try again later.')
      );
    }
  }
}
