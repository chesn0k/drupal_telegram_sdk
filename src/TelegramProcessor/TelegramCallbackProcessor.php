<?php

namespace Drupal\drupal_telegram_sdk\TelegramProcessor;

use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class TelegramCallbackProcessor implements TelegramProcessorInterface {

  /**
   * The telegram plugin manager.
   */
  protected TelegramPluginManager $telegramPluginManager;

  /**
   * Constructs telegram command processor.
   *
   * @param \Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager $telegram_plugin_manager
   *   The telegram plugin manager.
   */
  public function __construct(TelegramPluginManager $telegram_plugin_manager) {
    $this->telegramPluginManager = $telegram_plugin_manager;
  }

  /**
   * {@inheritDoc}
   */
  public function telegramProcessing(TelegramBotInterface $bot, Api $telegram, Update $update): void {
    if (!$update->isType('callback_query')) {
      return;
    }

    $data = $update->callbackQuery->data;

    if(!preg_match('%^/(?<callback_name>\w+)%si', $data, $matches)) {
      return;
    }

    $callback_name = $matches['callback_name'];
    $definitions = $this->telegramPluginManager->getDefinitions();

    foreach ($definitions as $plugin_id => $definition) {
      if ((empty($definition['bots_id']) || \in_array($bot->id(), $definition['bots_id'])) && $callback_name === $definition['name']) {
        $callback_query = $this->telegramPluginManager->createInstance($plugin_id, $definitions);
        $callback_query->make($bot, $telegram, $update);
      }
    }

  }

}
