<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of telegram bots.
 */
class TelegramBotListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header = [
      'label' => $this->t('Label'),
      'id' => $this->t('Machine name'),
      'status' => $this->t('Status'),
    ];

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $entity */
    $row = [
      'label' => $entity->label(),
      'id' => $entity->id(),
      'status' => $entity->status() ? $this->t('Enabled') : $this->t('Disabled'),
    ];

    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritDoc}
   */
  public function getDefaultOperations(EntityInterface $entity): array {
    $operations = parent::getDefaultOperations($entity);

    $operations['cats'] = [
      'title' => $this->t('Chats'),
      'weight' => 10,
      'url' => Url::fromRoute('entity.telegram_chat.collection', ['telegram_bot' => $entity->id()]),
    ];

    unset($operations['delete']);

    return $operations;
  }

}
