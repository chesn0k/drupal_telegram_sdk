<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\Annotation\ConfigEntityType;
use Drupal\Core\Entity\EntityStorageInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Defines the telegram bot entity type.
 *
 * @ConfigEntityType(
 *   id = "telegram_bot",
 *   label = @Translation("Telegram bot"),
 *   label_collection = @Translation("Telegram bots"),
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
 *     },
 *    "route_provider" = {
 *      "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *      "webhook" = "Drupal\drupal_telegram_sdk\Entity\Routing\WebhookRouteProvider",
 *    },
 *   },
 *   config_prefix = "telegram_bot",
 *   bundle_of = "telegram_chat",
 *   admin_permission = "administer telegram_bot",
 *   links = {
 *     "collection" = "/admin/structure/telegram-bot",
 *     "add-form" = "/admin/structure/telegram-bot/add",
 *     "edit-form" = "/admin/structure/telegram-bot/{telegram_bot}",
 *     "delete-form" = "/admin/structure/telegram-bot/{telegram_bot}/delete",
 *     "webhook" = "/telegram-bot/{token}/api",
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
 *   },
 * )
 */
class TelegramBot extends ConfigEntityBundleBase implements TelegramBotInterface {

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

    /** @var \Telegram\Bot\Api $telegram_api */
    $telegram_api = \Drupal::service('drupal_telegram_sdk.bot_api')
      ->getTelegramBotApi($this->id);

    $string_url = $this->toUrl('webhook', ['absolute' => TRUE])->toString();
    try {
      $telegram_api->setWebhook(['url' => $string_url]);
    } catch (TelegramSDKException $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }
  }

  /**
   * {@inheritDoc}
   */
  public static function preDelete(EntityStorageInterface $storage, array $entities) {
    parent::preDelete($storage, $entities);
    $telegram_chat_storage = \Drupal::entityTypeManager()->getStorage('telegram_chat');

    foreach ($entities as $entity) {
      $chats = $telegram_chat_storage->loadByProperties([
        'bot' => $entity->id(),
      ]);

      $telegram_chat_storage->delete($chats);
    }
  }

  /**
   * {@inheritDoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);

    if ($rel === 'webhook') {
      $uri_route_parameters = ['token' => $this->getToken()];
    }

    return $uri_route_parameters;
  }

  /**
   * {@inheritDoc}
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * {@inheritDoc}
   */
  public function setLabel(string $label) {
    $this->label = $label;

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getToken() {
    return $this->token;
  }

  /**
   * {@inheritDoc}
   */
  public function setToken(string $token) {
    $this->token = $token;

    return $this;
  }

}
