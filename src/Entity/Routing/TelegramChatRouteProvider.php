<?php

namespace Drupal\drupal_telegram_sdk\Entity\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\AdminHtmlRouteProvider;


class TelegramChatRouteProvider extends  AdminHtmlRouteProvider {

  /**
   * {@inheritDoc}
   */
  protected function getAddFormRoute(EntityTypeInterface $entity_type) {
    $route = parent::getAddFormRoute($entity_type);

    $route->setOption('parameters', [
        'telegram_bot' => [
          'type' => 'entity:telegram_bot',
        ],
      ]);

    return $route;
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditFormRoute(EntityTypeInterface $entity_type) {
    $route = parent::getEditFormRoute($entity_type);

    $route->setOption('parameters', [
      'telegram_chat' => [
        'type' => 'entity:telegram_chat',
      ],
      'telegram_bot' => [
        'type' => 'entity:telegram_bot'
      ],
    ]);

    return $route;
  }

  /**
   * {@inheritDoc}
   */
  protected function getDeleteFormRoute(EntityTypeInterface $entity_type) {
    $route = parent::getDeleteFormRoute($entity_type);

    $route->setOption('parameters', [
      'telegram_chat' => [
        'type' => 'entity:telegram_chat',
      ],
      'telegram_bot' => [
        'type' => 'entity:telegram_bot'
      ],
    ]);

    return $route;
  }

  /**
   * {@inheritDoc}
   */
  protected function getCollectionRoute(EntityTypeInterface $entity_type) {
    $route = parent::getCollectionRoute($entity_type);

    $route->setOption('parameters', [
      'telegram_bot' => [
        'type' => 'entity:telegram_bot'
      ],
    ]);

    return $route;
  }

}
