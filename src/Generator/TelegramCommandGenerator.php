<?php

namespace Drupal\drupal_telegram_sdk\Generator;

use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class TelegramCommandGenerator extends BaseGenerator {

  /**
   * {@inheritdoc}
   */
  protected $name = 'plugin-telegram-command';

  /**
   * {@inheritdoc}
   */
  protected $alias = 'telegram-command';

  /**
   * {@inheritdoc}
   */
  protected $description = 'Generates telegram command plugin.';

  /**
   * {@inheritdoc}
   */
  protected $templatePath = __DIR__ . '/templates';

  /**
   * {@inheritDoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $questions = Utils::moduleQuestions();

    $questions['plugin_name'] = new Question('Name telegram command', 'start');

    $default_plugins_id = function (array $vars) {
      return $vars['machine_name'] . '_' . Utils::human2machine($vars['plugin_name']);
    };
    $questions['plugin_id'] = new Question('Plugin ID', $default_plugins_id);
    $questions['class'] = Utils::pluginClassQuestion('TelegramCommand');
    $questions['description'] = new Question('Description', 'Very useful command.');
    $questions['bots_id'] = new Question('ID of the bots that owns this command, leave it blank for the team to work with all bots. (Example: bot_1 bot_2)');
    $questions['aliases'] = new Question('Command aliases, leave it blank to not using aliases. (Example: aliases_1 aliases_2)');
    $questions['pattern'] = new Question('Template for command arguments, leave blank to not use');

    $vars = &$this->collectVars($input, $output, $questions);

    if ($vars['bots_id']) {
      $vars['bots_id'] = explode(' ', $vars['bots_id']);
    }
    if ($vars['aliases']) {
      $vars['aliases'] = explode(' ', $vars['aliases']);
    }

    $this->addFile()
      ->path('src/Plugin/TelegramCommand/{class}.php')
      ->template('telegram_command.html.twig');
  }

}
