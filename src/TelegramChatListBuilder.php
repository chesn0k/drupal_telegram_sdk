<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TelegramChatListBuilder extends EntityListBuilder {

  /**
   * Constructs a new TelegramChatListBuilder object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *  The entity type definition.
   * @param \Drupal\Core\Entity\EntityStorageInterface $storage
   *  The entity storage class.
   */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage) {
    parent::__construct($entity_type, $storage);
  }

  /**
   * {@inheritDoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage('telegram_chat'),
    );
  }

  /**
   * {@inheritDoc}
   */
  public function buildHeader() {
    $header = [
      'id' => $this->t('ID'),
      'label' => $this->t('label'),
      'bot_id' => $this->t('Bot'),
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
      'bot_id' => $entity->getBot()->id(),
      'chat_id' => $entity->getChatId(),
      'type_chat' => $entity->getTypeChat(),
    ];

    return $row + parent::buildRow($entity);
  }

}
