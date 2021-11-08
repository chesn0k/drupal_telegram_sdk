<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TelegramChatListBuilder extends EntityListBuilder {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface;
   */
  protected $routeMatch;

  /**
   * Constructs a new TelegramChatListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *  The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *  The entity storage class.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, RouteMatchInterface $route_match) {
    parent::__construct($entity_type, $storage);

    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritDoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage('telegram_chat'),
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function load() {
    $telegram_bot = $this->routeMatch->getParameter('telegram_bot');
    return $this->storage->loadByProperties(['bot' => $telegram_bot->id()]);
  }

  /**
   * {@inheritDoc}
   */
  public function buildHeader() {
    $header = [
      'id' => $this->t('ID'),
      'label' => $this->t('label'),
      'chat_id' => $this->t('Chat id'),
      'type_chat' => $this->t('Type chat'),
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritDoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row = [
      'id' => $entity->id(),
      'label' => $entity->getLabel(),
      'chat_id' => $entity->getChatId(),
      'type_chat' => $entity->getTypeChat(),
    ];

    return $row + parent::buildRow($entity);
  }

}
