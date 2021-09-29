<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of telegram bots.
 */
class TelegramBotListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
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
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $entity */
    $row = [
      'label' => $entity->label(),
      'id' => $entity->id(),
      'status' => $entity->status() ? $this->t('Enabled') : $this->t('Disabled'),
    ];

    return $row + parent::buildRow($entity);
  }

}
