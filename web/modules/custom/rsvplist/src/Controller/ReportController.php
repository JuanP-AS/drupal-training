<?php

/**
 * @file
 * Provide site administrators with a list of all the RSVP List signups.
 * so they know who is attending their events.
 */

namespace Drupal\rsvplist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class ReportController extends ControllerBase {

  protected function load() {
    try {
      $database = Database::getConnection();
      $select_query = $database->select('rsvplist', 'r');

      // Join the user table
      $select_query->join('users_field_data', 'u', 'r.uid = u.uid');
      // Join the node table, to get the event's name
      $select_query->join('node_field_data', 'n', 'r.nid = n.nid');

      // Select these specific fields for the output
      $select_query->fields('u', ['name']);
      $select_query->fields('r', ['email']);
      $select_query->fields('n', ['title']);

      $entries = $select_query->execute()->fetchAll(\PDO::FETCH_ASSOC);
      return $entries;
    } catch (\Exception $e) {
      var_dump($e);
      $this->messenger()->addStatus($this->t('Unable to access the database at this time. Please try again later.'));
      return NULL;
    }
  }

  public function report() {
    $content = [];
    $content['message'] = [
      '#markup' => $this->t('Below is a list of all the RSVPs for the site.'),
    ];

    $headers = [
      $this->t('Username'),
      $this->t('Email'),
      $this->t('Event'),
    ];

    $table_rows = $this->load();

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $table_rows,
      '#empty' => $this->t('No entries available.'),
    ];

    // Do not cache this page by setting the max-age to 0
    $content['#cache']['max-age'] = 0;

    return $content;
  }
}
