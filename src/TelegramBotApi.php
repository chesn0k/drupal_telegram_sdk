<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager;
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
   * The telegram manager.
   *
   * @var \Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager
   */
  protected $telegramCommandManager;

  /**
   * The telegram api.
   *
   * @var \Telegram\bot\Api|NULL
   */
  protected $telegram;

  /**
   * The telegram bot.
   *
   * @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface|NULL
   */
  private $telegramBot;

  /**
   * Constructs a TelegramBotApi object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager $telegram_command_manager
   *   The telegram plugin manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TelegramPluginManager $telegram_command_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->telegramCommandManager = $telegram_command_manager;
  }

  /**
   * Set telegram bot api.
   *
   * @param string $id
   *   The telegram bot id.
   *
   * @return \Drupal\drupal_telegram_sdk\TelegramBotApi
   */
  public function setTelegram(string $id) {
    $this->telegramBot = $this->entityTypeManager->getStorage('telegram_bot')
      ->load($id);

    $this->telegram = new Api($this->telegramBot->getToken());

    return $this;
  }

  /**
   * Gets telegram bot api.
   *
   * @return \Telegram\Bot\Api|NULL
   *   Telegram SDK Api.
   */
  public function getTelegram() {
    return $this->telegram;
  }

  /**
   * Collects and registers commands. You must use this method of getting the
   *  API if you want to execute the command.
   *
   * @return \Drupal\drupal_telegram_sdk\TelegramBotApi
   */
  public function registerCommands() {
    $plugin_definitions = $this->telegramCommandManager->getDefinitions();
    foreach ($plugin_definitions as $plugin_id => $definition) {
      if (empty($definition['bots_id']) || in_array($this->telegramBot->id(), $definition['bots_id'])) {
        $command = $this->telegramCommandManager->createInstance($plugin_id, $definition);
        $this->telegram->addCommand($command);
      }
    }

    return $this;
  }

  /**
   * Process Inbound сommands.
   *
   * @return \Telegram\Bot\Objects\Update
   *   Update object.
   */
  public function commandsHandler() {
    $this->registerCommands();

    return $this->telegram->commandsHandler(TRUE);
  }

}
