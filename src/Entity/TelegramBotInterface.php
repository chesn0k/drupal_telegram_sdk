<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a telegram bot entity type.
 */
interface TelegramBotInterface extends ConfigEntityInterface {

  /**
   * Get API this Telegram Bot
   *
   * @return \Telegram\Bot\Api
   */
  public function getTelegramBotApi();

  /**
   * Set a Webhook to receive incoming updates via an outgoing webhook.
   *
   * @return bool
   */
  public function setTelegramBotWebhook();
}
