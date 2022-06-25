<?php

namespace Drupal\drupal_telegram_sdk\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines telegram_callback annotation object.
 *
 * @Annotation
 */
class TelegramCallback extends Plugin {

  /**
   * The plugin ID.
   */
  public string $id;

  /**
   * The name query callback.
   */
  public string $name;

  /**
   * List of bots for which this callback is available.
   *
   * Empty if the callback is available to all bots.
   *
   * @var array
   */
  public array $bots_id;

  /**
   * The pattern arguments callback.
   */
  public string $pattern;

}
