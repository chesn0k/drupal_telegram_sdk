<?php

namespace Drupal\drupal_telegram_sdk\TelegramProcessor;

use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Defines an interface for classes that process telegram request.
 */
interface TelegramProcessorInterface {

  /**
   * Processes telegram request.
   *
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $bot
   *   The entity telegram bot.
   * @param \Telegram\Bot\Api $telegram
   *   The telegram object.
   * @param \Telegram\Bot\Objects\Update $update
   *   The update object.
   */
  public function telegramProcessing(TelegramBotInterface $bot, Api $telegram, Update $update): void;

}
