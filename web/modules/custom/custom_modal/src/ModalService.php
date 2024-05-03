<?php

namespace Drupal\custom_modal;

use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;

class ModalService {

  protected $connection;

  public function __construct(Connection $connection) {
    $this->connection = $connection;
  }

  public function isEnabled(Node &$node) {
    if ($node->isNew()) {
      return false;
    }
    try {
      $select = $this->connection->select('custom_modal_enabled', 'cme');
      $select->fields('cme', ['nid']);
      $select->condition('nid', $node->id());
      $result = $select->execute()->fetch();

      return !empty($result);
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to determine custom modal settings at this time. Please try again later.')
      );
    }
  }

  public function getTitle(Node $node) {
    try {
      $select = $this->connection->select('custom_modal_enabled', 'cme');
      $select->fields('cme', ['title']);
      $select->condition('nid', $node->id());
      $result = $select->execute()->fetch();

      return $result->title;
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to determine custom modal settings at this time. Please try again later.')
      );
    }
  }

  public function getContent(Node $node) {
    try {
      $select = $this->connection->select('custom_modal_enabled', 'cme');
      $select->fields('cme', ['content']);
      $select->condition('nid', $node->id());
      $result = $select->execute()->fetch();

      return $result->content;
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to determine custom modal settings at this time. Please try again later.')
      );
    }
  }

  public function setEnabled(Node $node, string $title, $content) {
    try {
      $query = null;
      if (!$this->isEnabled($node)) {
        $query = $this->connection->insert('custom_modal_enabled');
      } else {
        $query = $this->connection->update('custom_modal_enabled')
          ->condition('nid', $node->id());
      }
      $query->fields([
        'nid' => $node->id(),
        'title' => $title,
        'content' => $content,
      ])
      ->execute();
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save custom modal settings at this time. Please try again later.')
      );
    }
  }

  public function deleteEnabled(Node $node) {
    try {
      $this->connection->delete('custom_modal_enabled')
        ->condition('nid', $node->id())
        ->execute();
    } catch (\Exception $e) {
      \Drupal::messenger()->addError(
        t('Unable to save custom modal settings at this time. Please try again later.')
      );
    }
  }
}
