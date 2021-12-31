<?php

namespace Drupal\drupal_telegram_sdk\TelegramProcessor;

use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginInterface;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginManager;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;
use InvalidArgumentException;

/**
 * Provides the definition and processing of telegram commands.
 */
class TelegramCommandProcessor implements TelegramProcessorInterface {

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
    $commands = $this->getCommand($update);
    if (empty($commands)) {
      return;
    }

    $plugin_definitions = $this->telegramPluginManager->getDefinitions();

    array_filter(
      $plugin_definitions,
      fn($definition) => empty($definition['bots_id']) || in_array($bot->id(), $definition['bots_id'], TRUE),
    );

    foreach ($commands as $command) {

      $command_name = $this->parserCommand(
        $update->getMessage()->text,
        $command['offset'],
        $command['length']
      );

      $plugin = $this->getPluginCommand($plugin_definitions, $command_name);
      if (isset($plugin)) {
        $plugin->make($bot, $telegram, $update, $command);
      }

    }
  }

  /**
   * Get instance of the telegram command plugin.
   *
   * @param array $plugin_definitions
   *   The plugin definitions.
   * @param string $command_name
   *   The command name.
   *
   * @return object|null
   *   The plugin instance for this command.
   */
  private function getPluginCommand(array $plugin_definitions, string $command_name) {

    foreach ($plugin_definitions as $plugin_id => $definition) {
      if ($definition['name'] === $command_name || $definition['aliases'] === $command_name ) {
        return $this->telegramPluginManager->createInstance($plugin_id, $definition);
      }
    }

    return NULL;
  }

  /**
   * Parse command name.
   *
   * @param string $text
   *   The text message.
   * @param int $offset
   *   The offset command.
   * @param int $length
   *   The length command.
   *
   * @return string
   *   The command name.
   */
  public function parserCommand(string $text, int $offset, int $length): string {
    if (trim($text) === '') {
      throw new InvalidArgumentException('Message is empty, Cannot parse for command');
    }

    $command = substr($text, $offset + 1, $length - 1);

    if ($command_sanitized = stristr($command, '@')) {
      $command = $command_sanitized;
    }

    return $command;
  }

  /**
   * Gets a command from the message.
   *
   * @param \Telegram\Bot\Objects\Update $update
   *   The update object.
   *
   * @return array
   *   The command array.
   */
  protected function getCommand(Update $update): array {
    $commands = [];
    $message = $update->getMessage()->toArray();

    if (isset($message['entities'])) {

      $commands = array_filter(
        $message['entities'],
        static fn($entity) => $entity['type'] === 'bot_command'
      );

    }

    return $commands;
  }

}
