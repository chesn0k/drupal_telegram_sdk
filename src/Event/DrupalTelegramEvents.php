<?php

namespace Drupal\drupal_telegram_sdk\Event;

/**
 * Provides list of drupal_telegram_sdk events
 */
final class DrupalTelegramEvents {

  /**
   * Name of the event fired after webhook processing.
   *
   * @Event
   *
   * @see \Drupal\drupal_telegram_sdk\Event\WebhookAfterProcessing
   */
  const WEBHOOK_AFTER_PROCESSING = 'drupal_telegram_sdk.webhook.after_processing';

  /**
   * Name of the event fired after webhook processing.
   *
   * @Event
   *
   * @see \Drupal\drupal_telegram_sdk\Event\WebhookBeforeProcessing
   */
  const WEBHOOK_BEFORE_PROCESSING = 'drupal_telegram_sdk.webhook.before_processing';

}
