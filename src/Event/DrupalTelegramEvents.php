<?php

namespace Drupal\drupal_telegram_sdk\Event;

/**
 * Provides list of drupal_telegram_sdk events
 */
final class DrupalTelegramEvents {

  /**
   * Name of the event fired after command execution.
   *
   * @Event
   *
   * @see \Drupal\drupal_telegram_sdk\Event\CommandsAfterProcessing
   */
  const COMMANDS_AFTER_PROCESSING = 'drupal_telegram_sdk.commands.after_processing';

  /**
   * Name of the event fired before command execution.
   *
   * @Event
   *
   * @see \Drupal\drupal_telegram_sdk\Event\CommandsBeforeProcessing
   */
  const COMMANDS_BEFORE_PROCESSING = 'drupal_telegram_sdk.commands.before_processing';

}
