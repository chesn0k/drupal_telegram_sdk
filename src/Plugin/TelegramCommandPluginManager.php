<?php

namespace Drupal\drupal_telegram_sdk\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * TelegramCommand plugin manager.
 */
class TelegramCommandPluginManager extends DefaultPluginManager {

  /**
   * Constructs TelegramCommandPluginManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/TelegramCommand',
      $namespaces,
      $module_handler,
      'Drupal\drupal_telegram_sdk\Plugin\TelegramCommand\TelegramCommandInterface',
      'Drupal\drupal_telegram_sdk\Annotation\TelegramCommand'
    );
    $this->alterInfo('telegram_command_info');
    $this->setCacheBackend($cache_backend, 'telegram_command_plugins');
  }

}
