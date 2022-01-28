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
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot
   *   The telegram bot.
   */
  public function getTelegram(TelegramBotInterface $telegram_bot): Api {
    return new Api($telegram_bot->getToken());
  }


  /**
   * Process webhook.
   *
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot
   *   The telegram bot.
   */
  public function handler(TelegramBotInterface $telegram_bot): Update {
    $telegram = $this->getTelegram($telegram_bot);
    $update = $telegram->getWebhookUpdate();
    $processors = $this->telegramProcessorManager->getProcessors();

    /** @var \Drupal\drupal_telegram_sdk\TelegramProcessor\TelegramProcessorInterface $processor */
    foreach ($processors as $processor) {
      $processor->telegramProcessing($telegram_bot, $telegram, $update);
    }

    return $update;
  }

}
