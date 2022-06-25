<?php

namespace Drupal\drupal_telegram_sdk\Plugin\telegram\TelegramCommand;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drupal_telegram_sdk\Entity\TelegramBot;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginInterface;
use Telegram\Bot\Answers\Answerable;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Base class for telegram_command plugins.
 */
abstract class TelegramCommandPluginBase extends PluginBase implements TelegramPluginInterface {

  use Answerable;
  use StringTranslationTrait;

  /**
   * The telegram bot.
   */
  protected TelegramBot $bot;

  /**
   * The argument's command array.
   */
  protected array $arguments = [];

  /**
   * The entity being processed.
   */
  protected array $entity = [];

  /**
   * {@inheritDoc}
   */
  public function make(TelegramBot $bot, Api $telegram, Update $update, array $entity = []): void {
    $this->bot = $bot;
    $this->telegram = $telegram;
    $this->update = $update;
    $this->entity = $entity;
    $this->arguments = $this->getArguments();

    $this->handle();
  }

  /**
   * Handle telegram command.
   */
  abstract public function handle(): void;

  /**
   * Parse arguments.
   */
  protected function getArguments(): array {

    if (!empty($this->getPluginDefinition()['pattern'])) {
      $required = $this->extractVariableNames('/\{([^\d]\w+?)\}/');
      $optional = $this->extractVariableNames('/\{([^\d]\w+?)\?\}/');
      $regex = $this->prepareRegex($required, $optional);
      $text = $this->relevantMessageSubString();

      preg_match($regex, $text, $matches);
      return $matches;
    }

    return [];
  }

  /**
   * Extract the name of the arguments.
   *
   * @param string $regex
   *   Regular expression to extract the name of an argument.
   *
   * @return array
   *   The argument's names.
   */
  protected function extractVariableNames(string $regex): array {
    preg_match_all($regex, $this->getPluginDefinition()['pattern'], $matches);

    return $matches[1];
  }

  /**
   * Prepares regex.
   *
   * @param array $required
   *   The required arguments.
   * @param array $optional
   *   The required arguments.
   *
   * @return string
   *   The regex.
   */
  protected function prepareRegex(array $required, array $optional): string {
    $bot_name = '(?:@.+?bot)?\s+?';

    $required = array_map(static fn($var) => "(?P<$var>[^ ]++)", $required);
    $required = implode('\s+?', $required);

    $optional = array_map(static fn($var) => "(?:\s+?(?P<$var>[^ ]++))?", $optional);
    $optional = implode('\s+?', $optional);

    return "%/{$this->getPluginDefinition()['name']}{$bot_name}{$required}{$optional}%si";
  }

  /**
   * Get relevant message substring.
   *
   * @return string
   *   The relevant message substring.
   */
  protected function relevantMessageSubString(): string {
    $message = $this->getUpdate()->getMessage()->toArray();
    $text = $this->getUpdate()->getMessage()->text;

    $commands = array_filter(
      $message['entities'],
      static fn($entity) => $entity['type'] === 'bot_command'
    );

    $offsets = array_map(static fn($command) => $command['offset'], $commands);

    if (empty($offsets)) {
      return $text;
    }

    $current_offset = array_search($this->entity['offset'], $offsets);
    $splice = array_splice(
      $offsets,
      $current_offset,
      2
    );

    return count($splice) === 2 ? substr($text, $splice[0], $splice[1]) : substr($text, $splice[0]);
  }

}
