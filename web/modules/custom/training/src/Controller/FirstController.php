<?php

/**
 * @file
 * Generates markup to be displayed. Functionality in this controller is wired
 * to Drupal in training.routing.yml.
 */

 namespace Drupal\training\Controller;

 use Drupal\Core\Controller\ControllerBase;

 class FirstController extends ControllerBase {

  public function simpleContent() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    ];
  }

  public function variableContent($name_1, $name_2) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, @name_1 and @name_2!', ['@name_1' => $name_1, '@name_2' => $name_2]),
    ];
  }
 }
