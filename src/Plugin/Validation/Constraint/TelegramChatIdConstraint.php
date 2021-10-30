<?php

namespace Drupal\drupal_telegram_sdk\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Constraint(
 *   id = "TelegramChatId",
 *   label = @Translation("The id of the telegram chat.", context = "Validation"),
 * )
 */
class TelegramChatIdConstraint extends Constraint {

  public $massage = 'The chat id %chat_id is already in registered and must be unique for this bot.';

}
