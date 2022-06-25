<?php

namespace Drupal\drupal_telegram_sdk\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Telegram\Bot\Api;

/**
 * Event before webhook processing.
 */
class WebhookBeforeProcessing extends Event {

  /**
   * The telegram bot.
   */
  protected TelegramBotInterface $telegramBot;

  /**
   * The telegram api.
   */
  protected Api $telegram;

  /**
   * TRUE, if processing is stopped. Default FALSE.
   */
  protected bool $lockProcessing = FALSE;

  /**
   * The construct WebhookBeforeProcessing object.
   *
   * @param \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot
   *   The telegram bot.
   * @param \Telegram\Bot\Api $telegram
   *   The telegram api.
   */
  public function __construct(TelegramBotInterface $telegram_bot, Api $telegram) {
    $this->telegramBot = $telegram_bot;
    $this->telegram = $telegram;
  }

  /**
   * Gets the telegram bot.
   *
   * @return \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   *   The telegram bot.
   */
  public function getTelegramBot(): TelegramBotInterface {
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
   * Locked processing.
   *
   * @return $this
   */
  public function lockingProcessing(): WebhookBeforeProcessing {
    $this->lockProcessing = TRUE;

    return $this;
  }

  /**
   * Unlock processing.
   *
   * @return $this
   */
  public function unlockProcessing(): WebhookBeforeProcessing {
    $this->lockProcessing = FALSE;

    return $this;
  }

  /**
   * TRUE if processing locked.
   *
   * @return bool
   */
  public function isLockProcessing(): bool {
    return $this->lockProcessing;
  }

}
