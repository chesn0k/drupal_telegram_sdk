<?php

namespace Drupal\drupal_telegram_sdk\Plugin\telegram\TelegramCommand;

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
   * The plugin implementation definition.
   *
   * @var array
   */
  protected $pluginDefinition;

  /**
   * Configuration information passed into the plugin.
   *
   * When using an interface like
   * \Drupal\Component\Plugin\ConfigurableInterface, this is where the
   * configuration should be stored.
   *
   * Plugin configuration is optional, so plugin implementations must provide
   * their own setters and getters.
   *
   * @var array
   */
  protected $configuration;

  /**
   * Constructs a \Drupal\drupal_telegram_sdk\Plugin\TelegramCommand\TelegramCommandPluginBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    $this->configuration = $configuration;
    $this->pluginId = $plugin_id;
    $this->pluginDefinition = $plugin_definition;


    $this->name = $configuration['name'];
    $this->description = $configuration['description']->render();
    $this->aliases = $configuration['aliases'];
    $this->pattern = $configuration['pattern'];
  }

  /**
   * {@inheritDoc}
   */
  public function getPluginId() {
    return $this->pluginId;
  }

}
