<?php

/**
 * @file
 * A form to collect an email address for RSVP details.
 */

 namespace Drupal\rsvplist\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;

  class RSVPForm extends FormBase {
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
      return 'rsvplist_email_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
      // Attempt to get the fully loaded node object of the viewed page.
      $node = \Drupal::routeMatch()->getParameter('node');
      $node_id = !is_null($node) ? $node->id() : 0;

      $form['email'] = [
        '#title' => $this->t('Email address'),
        '#type' => 'textfield',
        '#size' => 25,
        '#description' => $this->t('We\'ll send updates to the email address you provide.'),
        '#required' => TRUE,
      ];
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('RSVP'),
      ];
      $form['nid'] = [
        '#type' => 'hidden',
        '#value' => $node_id,
      ];

      return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
      $email = $form_state->getValue('email');
      if (!\Drupal::service('email.validator')->isValid($email)) {
        $form_state->setErrorByName('email', $this->t('The email address %email is not valid.', ['%email' => $email]));
      }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
      // $submitted_email = $form_state->getValue('email');
      // $this->messenger()
      //   ->addMessage($this->t(
      //       'The email address @email has been added to the RSVP list.',
      //       ['@email' => $submitted_email],
      //   ));
      try {
        $uid = \Drupal::currentUser()->id();
        $full_user = \Drupal\user\Entity\User::load($uid); // not necessary here, but good to know

        // Form values are stored in the $form_state object.
        $nid = $form_state->getValue('nid');
        $email = $form_state->getValue('email');

        $current_timestamp = \Drupal::time()->getRequestTime();

        $query = \Drupal::database()->insert('rsvplist');

        $query->fields([
          'uid',
          'nid',
          'email',
          'created',
        ])
        ->values([
          $uid,
          $nid,
          $email,
          $current_timestamp,
        ])
        ->execute();

      $this->messenger()
        ->addMessage($this->t('Thank you for your RSVP, you are now on the list.'));
      } catch (\Exception $e) {
        $this->messenger()
          ->addError($this->t('Something went wrong :(\nPlease try again later.'));
      }
    }
  }
