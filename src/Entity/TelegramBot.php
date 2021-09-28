<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Url;
use Telegram\Bot\Api;

/**
 * Defines the telegram bot entity type.
 *
 * @ConfigEntityType(
 *   id = "telegram_bot",
 *   label = @Translation("Telegram Bot"),
 *   label_collection = @Translation("Telegram Bots"),
 *   label_singular = @Translation("telegram bot"),
 *   label_plural = @Translation("telegram bots"),
 *   label_count = @PluralTranslation(
 *     singular = "@count telegram bot",
 *     plural = "@count telegram bots",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\drupal_telegram_sdk\TelegramBotListBuilder",
 *     "form" = {
 *       "add" = "Drupal\drupal_telegram_sdk\Form\TelegramBotForm",
 *       "edit" = "Drupal\drupal_telegram_sdk\Form\TelegramBotForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm"
 *     }
 *   },
 *   config_prefix = "telegram_bot",
 *   admin_permission = "administer telegram_bot",
 *   links = {
 *     "collection" = "/admin/structure/telegram-bot",
 *     "add-form" = "/admin/structure/telegram-bot/add",
 *     "edit-form" = "/admin/structure/telegram-bot/{telegram_bot}",
 *     "delete-form" = "/admin/structure/telegram-bot/{telegram_bot}/delete"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "token"
 *   }
 * )
 */
class TelegramBot extends ConfigEntityBase implements TelegramBotInterface {

  /**
   * The telegram bot ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The telegram bot label.
   *
   * @var string
   */
  protected $label;

  /**
   * The telegram bot status.
   *
   * @var bool
   */
  protected $status;

  /**
   * The telegram bot token
   *
   * @var string
   */
  protected $token;

  /**
   * {@inheritDoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);
    $this->setTelegramBotWebhook();
  }

  /**
   * {@inheritDoc}
   */
  public function getTelegramBotApi($async = FALSE) {
    $telegram_api = new Api($this->token, $async);

    /** @var \Drupal\Component\Plugin\PluginManagerInterface $plugin_manager */
    $plugin_manager = \Drupal::service('plugin.manager.telegram_command');
    $plugin_definitions = $plugin_manager->getDefinitions();
    foreach ($plugin_definitions as $plugin_id => $definition) {
      if (empty($definition['bots_ids']) || in_array($this->id, $definition['bots_ids'])) {
        $telegram_api->addCommand($plugin_manager->createInstance($plugin_id, $definition));
      }
    }

    return $telegram_api;
  }

  /**
   * {@inheritDoc}
   */
  public function setTelegramBotWebhook($params = []) {
    if (!isset($params['url'])) {
      $params['url'] = Url::fromRoute('telegram_bot.webhook', ['token' => $this->token], ['absolute' => TRUE])
        ->toString();
    }

    return $this->getTelegramBotApi()->setWebhook($params);
  }

}
