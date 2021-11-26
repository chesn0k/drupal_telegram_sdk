<?php

namespace Drupal\drupal_telegram_sdk\Plugin\telegram\TelegramCommand;

/**
 * Interface for telegram_command plugins.
 */
interface TelegramCommandInterface {

  /**
   * Gets the plugin_id of the plugin instance.
   *
   * @return string
   *   The plugin_id of the plugin instance.
   */
  public function getPluginId();

}
