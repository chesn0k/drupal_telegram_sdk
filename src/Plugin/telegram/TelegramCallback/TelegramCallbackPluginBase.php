<?php

namespace Drupal\drupal_telegram_sdk\Plugin\telegram\TelegramCallback;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\drupal_telegram_sdk\Entity\TelegramBot;
use Drupal\drupal_telegram_sdk\Plugin\TelegramPluginInterface;
use Telegram\Bot\Answers\Answerable;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Base class for telegram_callback plugins.
 */
abstract class TelegramCallbackPluginBase extends PluginBase implements TelegramPluginInterface {

  use Answerable;
  use StringTranslationTrait;

  /**
   * The telegram bot.
   */
  protected TelegramBot $bot;

  /**
   * The argument's array.
   */
  protected array $arguments = [];

  /**
   * {@inheritDoc}
   */
  public function make(TelegramBot $bot, Api $telegram, Update $update, array $entity = []): void {
    $this->bot = $bot;
    $this->telegram = $telegram;
    $this->update = $update;
    $this->arguments = $this->getArguments();

    $this->handle();
  }

  /**
   * Handle callback query.
   */
  abstract public function handle(): void;

  /**
   * Parse arguments.
   *
   * @return array
   *   Arguments.
   */
  protected function getArguments(): array {

    if (!empty($this->getPluginDefinition()['pattern'])) {
      $data = $this->getUpdate()->callbackQuery->data;

      $required = $this->extractVariableNames('/\{([^\d]\w+?)\}/');
      $optional = $this->extractVariableNames('/\{([^\d]\w+?)\?\}/');
      $regex = $this->prepareRegex($required, $optional);
      preg_match($regex, $data, $matches);

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
  private function prepareRegex(array $required, array $optional): string {

    $required = \array_map(static fn($var) => "(?P<$var>[^ ]++)", $required);
    $required = \implode('\s+?', $required);

    $optional = \array_map(static fn($var) => "(?:\s+?(?P<$var>[^ ]++))?", $optional);
    $optional = \implode('\s+?', $optional);

    return "%/{$this->getPluginDefinition()['name']}\s+?{$required}{$optional}%si";
  }

}
