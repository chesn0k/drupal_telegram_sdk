<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Telegram Bot API Service.
 */
class TelegramBotApi {

  /**
   * The entity type manager.
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * The telegram manager.
   */
  protected TelegramProcessorManager $telegramProcessorManager;

  /**
   * The telegram api.
   */
  protected ?Api $telegram;

  /**
   * The telegram bot.
   */
  private ?TelegramBotInterface $telegramBot;

  /**
   * Constructs a TelegramBotApi object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\drupal_telegram_sdk\TelegramProcessorManager $telegram_processor_manager
   *   The telegram plugin manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TelegramProcessorManager $telegram_processor_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->telegramProcessorManager = $telegram_processor_manager;
  }

  /**
   * Set telegram bot api.
   *
   * @param string $id
   *   The telegram bot id.
   *
   * @return \Drupal\drupal_telegram_sdk\TelegramBotApi
   */
  public function setTelegram(string $id): TelegramBotApi {
    $this->telegramBot = $this->entityTypeManager->getStorage('telegram_bot')
      ->load($id);

    $this->telegram = new Api($this->telegramBot->getToken());

    return $this;
  }

  /**
   * Gets telegram bot api.
   *
   * @return \Telegram\Bot\Api|NULL
   *   Telegram SDK Api.
   */
  public function getTelegram(): ?Api {
    return $this->telegram;
  }

  /**
   * Process webhook.
   *
   * @return Update
   *  The update object.
   */
  public function handler(): Update {
    $update = $this->getTelegram()->getWebhookUpdate();
    $processors = $this->telegramProcessorManager->getProcessors();

    /** @var \Drupal\drupal_telegram_sdk\TelegramProcessor\TelegramProcessorInterface $processor */
    foreach ($processors as $processor) {
      $processor->telegramProcessing($this->telegramBot, $this->getTelegram(), $update);
    }

    return $update;
  }

}
