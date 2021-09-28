<?php

namespace Drupal\drupal_telegram_sdk\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines telegram_command annotation object.
 *
 * @Annotation
 */
class TelegramCommand extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * @see \Telegram\Bot\Commands\Command::$name
   *
   * @var string
   */
  public $name;

  /**
   * @see \Telegram\Bot\Commands\Command::$description
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

  /**
   * List of bots for which this command is available, pass an empty array if
   * the command is available to all bots
   *
   * @var array
   */
  public $bots_ids;

  /**
   * @see \Telegram\Bot\Commands\Command::$aliases
   *
   * @var array
   */
  public $aliases = [];

  /**
   * @see \Telegram\Bot\Commands\Command::$pattern
   *
   * @var string
   */
  public $pattern = '';

}
