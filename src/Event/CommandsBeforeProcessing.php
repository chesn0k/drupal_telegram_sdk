<?php

namespace Drupal\drupal_telegram_sdk\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Event before command execution.
 */
class CommandsBeforeProcessing extends Event {

  /**
   * The telegram bot api.
   *
   * @var \Telegram\Bot\Api
   */
  protected $telegram;

  /**
   * The telegram bot.
   *
   * @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   */
  protected $telegramBot;

  /**
   * Constructs a CommandsBeforeProcessing object.
   *
   * @param \Telegram\Bot\Api $telegram
   *   The telegram bot api.
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   *   The telegram bot.
   */
  public function __construct($telegram, $telegram_bot) {
    $this->telegram = $telegram;
    $this->telegramBot = $telegram_bot;
  }

  /**
   * Gets the telegram bot api.
   *
   * @return \Telegram\Bot\Api
   *   The telegram bot api.
   */
  public function getTelegram() {
    return $this->telegram;
  }

  /**
   * Gets the telegram bot.
   *
   * @return \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   *   The telegram bot.
   */
  public function getTelegramBot() {
    return $this->telegramBot;
  }
}
