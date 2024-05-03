<?php

namespace Drupal\custom_modal\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class CustomModalSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return ['custom_modal.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'custom_modal_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('custom_modal.settings');

    $form['custom_modal_types'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The content types to enable the modal for'),
      '#default_value' => $config->get('allowed_types'),
      '#options' => $types,
      '#description' => $this->t('On the selected node types a modal will be shown at start.'),
    ];

    return parent::buildForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $selected_types = array_filter($form_state->getValue('custom_modal_types'));
    sort($selected_types);

    $this->config('custom_modal.settings')
      ->set('allowed_types', $selected_types)
      ->save();

    parent::submitForm($form, $form_state);
  }
}
