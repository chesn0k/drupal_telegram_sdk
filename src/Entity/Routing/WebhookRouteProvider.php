<?php

namespace Drupal\drupal_telegram_sdk\Entity\Routing;

use Drupal\Core\Entity\EntityHandlerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class WebhookRouteProvider implements EntityRouteProviderInterface, EntityHandlerInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new WebhookRouteProvider.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritDoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
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
  protected function getWebhookRoute(EntityTypeInterface $entity_type) {
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
