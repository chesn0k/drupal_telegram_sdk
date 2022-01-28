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
- Plugin for telegram query callback.
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

1. Add a bot and set webhook. `/admin/structure/telegram-bot/add`.
2. Add commands.
3. Go to telegram check the work of the bot.

Your site must support HTTPS.

### API

Get the API through the `drupal_telegram_sdk.bot_api` service.

Some code examples:

Send a message.
```php
$telegram_bot = \Drupal::entityTypeManager()->getStorage('telegram_bot')->load('drupal_bot');
/** @var \Telegram\Bot\Api $telegram */
$telegram = \Drupal::service('drupal_telegram_sdk.bot_api')->getTelegram($telegram_bot);
$telegram->sendMessage([
  'chat_id' => 'drupal',
  'text' => 'Hello world!',
]);
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

Plugin query callback annotation example.
```php
/**
 * @TelegramCallback(
 *   id = "set_language",
 *   name = "set_language",
 *   bots_id = {
 *     "drupal_bot"
 *   },
 *   pattern = "{langcode}"
 * )
 */
```
