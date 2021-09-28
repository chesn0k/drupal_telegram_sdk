<?php

namespace Drupal\drupal_telegram_sdk\Plugin\TelegramCommand;

use Telegram\Bot\Commands\Command;

/**
 * Base class for telegram_command plugins.
 */
abstract class TelegramCommandPluginBase extends Command implements TelegramCommandInterface {

  /**
   * The plugin_id.
   *
   * @var string
   */
  protected $pluginId;

  /**
   * Constructs a \Drupal\drupal_telegram_sdk\Plugin\TelegramCommand\TelegramCommandPluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   */
  public function __construct(array $configuration, $plugin_id) {
    $this->name = $configuration['name'];
    $this->description = $configuration['description']->render();
    $this->aliases = $configuration['aliases'];
    $this->pattern = $configuration['pattern'];
    $this->pluginId = $plugin_id;
  }

  /**
   * {@inheritDoc}
   */
  public function getPluginId() {
    return $this->pluginId;
  }

}
