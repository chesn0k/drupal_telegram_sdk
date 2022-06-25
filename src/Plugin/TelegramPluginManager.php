<?php

namespace Drupal\drupal_telegram_sdk\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Symfony\Component\DependencyInjection\Container;

/**
 * Telegram plugin manager.
 */
class TelegramPluginManager extends DefaultPluginManager {

  /**
   * Constructs TelegramPluginManager object.
   *
   * @param string $type
   *   The plugin type.
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(string $type, \Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    $type_camel = Container::camelize($type);
    parent::__construct(
      "Plugin/telegram/{$type_camel}",
      $namespaces,
      $module_handler,
      'Drupal\drupal_telegram_sdk\Plugin\TelegramPluginInterface',
      "Drupal\drupal_telegram_sdk\Annotation\\{$type_camel}"
    );

    $this->defaults += [
      'plugin_type' => $type,
    ];

    $this->alterInfo("telegram_{$type}_info");
    $this->setCacheBackend($cache_backend, "telegram_plugins:{$type}");
  }

}
