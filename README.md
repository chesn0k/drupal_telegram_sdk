CONTENTS OF THIS FILE
---------------------

- Introduction
- Requirements
- Installation
- Configuration

INTRODUCTION
------------

This module integrates [Telegram Bot SDK](https://github.com/irazasyed/telegram-bot-sdk) for Drupal.

### Features

- Configuration entity for telegram bot.
- Unlimited telegram bots.
- Content entity for telegram chat.
- Plugin for telegram commands.
- Webhook.

This module does not provide any ready-made solutions and is intended for developers.

- For the description of the module visit: https://www.drupal.org/project/drupal_telegram_sdk
- To submit bug reports and feature suggestions, or to track changes: https://www.drupal.org/project/issues/search/drupal_telegram_sdk or https://github.com/chesn0k/drupal_telegram_sdk

REQUIREMENTS
------------

This module requires the following dependencies:

- [Telegram Bot SDK](https://github.com/irazasyed/telegram-bot-sdk).

INSTALLATION
------------
This module can only be installed via Composer. Visit https://www.drupal.org/node/1897420#s-add-a-module-with-composer for further
information.

CONFIGURATION
-------------

Visit the [official documentation](https://telegram-bot-sdk.readme.io/docs) before using.

1. Add and save bot `/admin/structure/telegram-bot/add`.
2. Add commands (run `drush gen telegram-command`).
3. Go to telegram check the work of the bot.

Your site must support HTTPS.

### API

Get the API through the `drupal_telegram_sdk.bot_api` service.

Some code examples:

Send a message.
```php
/** @var \Telegram\Bot\Api $bot_api */
$bot_api = \Drupal::service('drupal_telegram_sdk.bot_api')->getApi('drupal_bot');
$bot_api->sendMessage([
  'chat_id' => 100000,
  'text' => 'Hello world!',
]);
```
Build and run commands.
```php
/** @var \Telegram\Bot\Objects\Update $update */
$update = \Drupal::service('drupal_telegram_sdk.bot_api')->commandsHandler('drupal_bot');
```

Plugin command annotation example.
```php
/**
 * @TelegramCommand(
 *   id = "authorization",
 *   name = "authorization",
 *   description = @Translation("Chat authorization."),
 *   bots_id = {
 *     "drupal_bot",
 *   },
 *   aliases = {
 *     "login",
 *   },
 *   pattern "{password}"
 * )
 */
```
