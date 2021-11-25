<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\drupal_telegram_sdk\Event\CommandsAfterProcessing;
use Drupal\drupal_telegram_sdk\Event\CommandsBeforeProcessing;
use Drupal\drupal_telegram_sdk\Event\DrupalTelegramEvents;
use Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * The telegram bot.
   *
   * @var NULL|\Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface
   */
  private $telegramBot = NULL;

  /**
   * Constructs a TelegramBotApi object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\drupal_telegram_sdk\Plugin\TelegramCommandPluginManager $telegram_command_manager
   *   The telegram command plugin manager.
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
   *   The event dispatcher.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TelegramCommandPluginManager $telegram_command_manager, EventDispatcherInterface $event_dispatcher) {
    $this->entityTypeManager = $entity_type_manager;
    $this->telegramCommandManager = $telegram_command_manager;
    $this->eventDispatcher = $event_dispatcher;
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
    $this->telegramBot = $this->entityTypeManager->getStorage('telegram_bot')
      ->load($id);

    $telegram_api = new Api($this->telegramBot->getToken());

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

  /**
   * Process Inbound сommands.
   *
   * @param string $id
   *   The id telegram bot.
   *
   * @return \Telegram\Bot\Objects\Update
   *   Update object.
   */
  public function commandsHandler(string $id) {
    $telegram_api = $this->registerCommands($id);

    $event_before = new CommandsBeforeProcessing($telegram_api, $this->telegramBot);
    $this->eventDispatcher->dispatch(DrupalTelegramEvents::COMMANDS_BEFORE_PROCESSING, $event_before);

    $update = $telegram_api->commandsHandler(TRUE);

    $event_after = new CommandsAfterProcessing($update, $telegram_api, $this->telegramBot);
    $this->eventDispatcher->dispatch(DrupalTelegramEvents::COMMANDS_AFTER_PROCESSING, $event_after);

    return $update;
  }

}
