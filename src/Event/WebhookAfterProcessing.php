<?php

namespace Drupal\drupal_telegram_sdk\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Event after webhook processing.
 */
class WebhookAfterProcessing extends Event {

  /**
   * The telegram bot.
   */
  protected TelegramBotInterface $telegramBot;

  /**
   * The telegram api.
   */
  protected Api $telegram;

  /**
   * The update object.
   */
  protected Update $update;

  /**
   * The construct WebhookAfterProcessing object.
   *
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot
   *   The telegram bot.
   * @param \Telegram\Bot\Api $telegram
   *   The telegram api.
   * @param \Telegram\Bot\Objects\Update $update
   *   The update.
   */
  public function __construct(TelegramBotInterface $telegram_bot, Api $telegram, Update $update) {
    $this->telegramBot = $telegram_bot;
    $this->telegram = $telegram;
    $this->update = $update;
  }

  /**
   * Gets the telegram bot.
   *
   * @return \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   *   The telegram bot.
   */
  public function getTelegramBot(): TelegramBotInterface{
    return $this->telegramBot;
  }

  /**
   * Gets the telegram bot api.
   *
   * @return \Telegram\Bot\Api
   *   The telegram bot api.
   */
  public function getTelegram(): Api {
    return $this->telegram;
  }

  /**
   * Gets the update
   *
   * @return \Telegram\Bot\Objects\Update
   *   The update.
   */
  public function getUpdate(): Update {
    return $this->update;
  }

}
