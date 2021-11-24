<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager;
use Telegram\Bot\Api;

/**
 * Telegram Bot API Service.
 */
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
   * Constructs a TelegramBotApi object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager $telegram_command_manager
   *   The telegram command plugin manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TelegramCommandPluginManager $telegram_command_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->telegramCommandManager = $telegram_command_manager;
  }

  /**
   * Gets telegram bot api.
   *
   * @param string $id
   *   The id telegram bot.
   *
   * @return \Telegram\Bot\Api
   *   Telegram SDK Api.
   */
  public function getApi(string $id) {
    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot */
    $telegram_bot = $this->entityTypeManager->getStorage('telegram_bot')
      ->load($id);
    $telegram_api = new Api($telegram_bot->getToken());

    return $telegram_api;
  }

  /**
   * Collects and registers commands. You must use this method of getting the
   *  API if you want to execute the command.
   *
   * @param string $id
   *   The id telegram bot.
   *
   * @return \Telegram\Bot\Api
   *   Telegram SDK Api.
   */
  public function registerCommands(string $id) {
    $telegram_api = $this->getApi($id);

    $plugin_definitions = $this->telegramCommandManager->getDefinitions();
    foreach ($plugin_definitions as $plugin_id => $definition) {
      if (empty($definition['bots_id']) || in_array($id, $definition['bots_id'])) {
        $command = $this->telegramCommandManager->createInstance($plugin_id, $definition);
        $telegram_api->addCommand($command);
      }
    }

    return $telegram_api;
  }


}
