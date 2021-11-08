<?php

namespace Drupal\drupal_telegram_sdk\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RouteMatchInterface;

/**
 * Defines the add and edit form for telegram chat.
 */
class TelegramChatForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function getEntityFromRouteMatch(RouteMatchInterface $route_match, $entity_type_id) {
    if ($route_match->getRawParameter('telegram_chat') !== NULL) {
      $entity = $route_match->getParameter('telegram_chat');
    }
    else {
      /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $product */
      $telegram_bot = $route_match->getParameter('telegram_bot');
      $entity = $this->entityTypeManager
        ->getStorage('telegram_chat')
        ->create(['bot' => $telegram_bot->id()]);
    }

    return $entity;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    parent::save($form, $form_state);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
  }

}
