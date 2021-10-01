<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager;
use Telegram\Bot\Api;

class TelegramBotApi {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The telegram command manager.
   *
   * @var \Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager
   */
  protected $telegramCommandManager;

  /**
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TelegramCommandPluginManager $telegram_command_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->telegramCommandManager = $telegram_command_manager;
  }

  /**
   * Gets telegram bot api.
   *
   * @param string $id
   *  The id telegram bot.
   *
   * @return \Telegram\Bot\Api
   */
  public function getTelegramBotApi(string $id) {
    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot */
    $telegram_bot = $this->entityTypeManager->getStorage('telegram_bot')
      ->load($id);
    $telegram_api = new Api($telegram_bot->getToken());

    $plugin_definitions = $this->telegramCommandManager->getDefinitions();
    foreach ($plugin_definitions as $plugin_id => $definition) {
      if (empty($definition['bots_id']) || in_array($telegram_bot->id(), $definition['bots_id'])) {
        $command = $this->telegramCommandManager->createInstance($plugin_id, $definition);
        $telegram_api->addCommand($command);
      }
    }

    return $telegram_api;
  }


}
