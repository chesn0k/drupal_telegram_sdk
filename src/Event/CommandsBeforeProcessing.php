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
   * Constructs a CommandsBeforeProcessing object.
   *
   * @param \Telegram\Bot\Api $telegram
   *   The telegram bot api.
   */
  public function __construct($telegram) {
    $this->telegram = $telegram;
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

}
