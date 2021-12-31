<?php

namespace Drupal\drupal_telegram_sdk\Entity\Routing;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class WebhookRouteProvider implements EntityRouteProviderInterface {

  /**
   * {@inheritDoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type): RouteCollection {
    $collection = new RouteCollection();
    $entity_type_id = $entity_type->id();

    if ($webhook_route = $this->getWebhookRoute($entity_type)){
      $collection->add("entity.{$entity_type_id}.webhook", $webhook_route);
    }

    return $collection;
  }

  /**
   * Gets webhook route.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type.
   *
   * @return \Symfony\Component\Routing\Route|null
   *   The generated route.
   */
  protected function getWebhookRoute(EntityTypeInterface $entity_type): ?Route{
    if ($entity_type->hasLinkTemplate('webhook')) {
      $route = new Route($entity_type->getLinkTemplate('webhook'));
      $route
        ->setDefaults(['_controller' => 'Drupal\drupal_telegram_sdk\Controller\TelegramBotController::webhook'])
        ->setMethods(['POST'])
        ->setRequirements(['_access' => 'TRUE'])
        ->setOptions([]);

      return $route;
    }

    return NULL;
  }

}
