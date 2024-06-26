<?php

/**
 * @file
 * Contains the settings for adminitering the RSVP Form.
 */

namespace Drupal\rsvplist\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RSVPSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['rsvplist.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rsvplist_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('rsvplist.settings');

    $form['rsvplist_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable RSVP collection for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On the selected node types, an RSVP option will be available and can be enabled while content is being created or edited. RSVPs can be collected and displayed on the site.'),
    ];

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected_types = array_filter($form_state->getValue('rsvplist_types'));
    sort($selected_types);

    $this->config('rsvplist.settings')
      ->set('allowed_types', $selected_types)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
