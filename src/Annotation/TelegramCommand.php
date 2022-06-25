<?php

namespace Drupal\drupal_telegram_sdk\Annotation;

use Drupal\Component\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * Defines telegram_command annotation object.
 *
 * @Annotation
 */
class TelegramCommand extends Plugin {

  /**
   * The plugin ID.
   */
  public string $id;

  /**
   * @see \Telegram\Bot\Commands\Command::$name
   */
  public string $name;

  /**
   * @see \Telegram\Bot\Commands\Command::$description
   *
   * @ingroup plugin_translatable
   */
  public Translation $description;

  /**
   * List of bots for which this command is available, empty array if
   * the command is available to all bots.
   */
  public array $bots_id = [];

  /**
   * @see \Telegram\Bot\Commands\Command::$aliases
   */
  public array $aliases = [];

  /**
   * @see \Telegram\Bot\Commands\Command::$pattern
   */
  public string $pattern = '';

}
