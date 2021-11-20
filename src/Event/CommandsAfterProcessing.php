<?php

namespace Drupal\drupal_telegram_sdk\Event;

use Drupal\Component\EventDispatcher\Event;

/**
 * Event after command execution.
 */
class CommandsAfterProcessing extends Event {

  /**
   * The update.
   *
   * @var \Telegram\Bot\Objects\Update
   */
  protected $update;

  /**
   * The telegram bot api.
   *
   * @var \Telegram\Bot\Api
   */
  protected $telegram;

  /**
   * Constructs a CommandsAfterProcessing object.
   *
   * @param \Telegram\Bot\Objects\Update $update
   *  The update.
   * @param \Telegram\Bot\Api $telegram
   *  The telegram bot api.
   */
  public function __construct($update, $telegram) {
    $this->update = $update;
    $this->telegram = $telegram;
  }

  /**
   * Gets the update
   *
   * @return \Telegram\Bot\Objects\Update
   *  The update.
   */
  public function getUpdate() {
    return $this->update;
  }

  /**
   * Gets the telegram bot api.
   *
   * @return \Telegram\Bot\Api
   *  The telegram bot api.
   */
  public function getTelegram() {
    return $this->telegram;
  }

}
