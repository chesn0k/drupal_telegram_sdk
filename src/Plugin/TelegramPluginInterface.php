<?php

namespace Drupal\drupal_telegram_sdk\Plugin;

use Drupal\drupal_telegram_sdk\Entity\TelegramBot;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Defines an interface for telegram plugin.
 */
interface TelegramPluginInterface {

  /**
   * The make telegram plugin.
   *
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBot $bot
   *   The telegram bot.
   * @param \Telegram\Bot\Api $telegram
   *   The telegram bot api.
   * @param \Telegram\Bot\Objects\Update $update
   *   The update object.
   * @param array $entity
   *   The entity massage.
   */
  public function make(TelegramBot $bot, Api $telegram, Update $update, array $entity = []): void;

}
