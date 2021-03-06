<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the telegram chat entity type.
 *
 * @ContentEntityType(
 *   id = "telegram_chat",
 *   label = @Translation("Telegram chat"),
 *   label_collection = @Translation("Telegram chats"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\drupal_telegram_sdk\TelegramChatListBuilder",
 *     "form" = {
 *        "add" = "Drupal\drupal_telegram_sdk\Form\TelegramChatForm",
 *        "edit" = "Drupal\drupal_telegram_sdk\Form\TelegramChatForm",
 *        "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\drupal_telegram_sdk\Entity\Routing\TelegramChatRouteProvider",
 *     },
 *   },
 *   base_table = "telegram_chat",
 *   admin_permission = "access telegram_chat overview",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "bot",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/{telegram_bot}/telegram-chats/add",
 *     "edit-form" = "/admin/structure/{telegram_bot}/telegram-chats/{telegram_chat}/edit",
 *     "delete-form" = "/admin/structure/{telegram_bot}/telegram-chats/{telegram_chat}/delete",
 *     "collection" = "/admin/structure/{telegram_bot}/telegram-chats",
 *   },
 *   bundle_entity_type = "telegram_bot",
 *   field_ui_base_route="entity.telegram_bot.edit_form"
 * )
 */
class TelegramChat extends ContentEntityBase implements TelegramChatInterface {

  /**
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);
    $uri_route_parameters['telegram_bot'] = $this->bundle();
    return $uri_route_parameters;
  }

  /**
   * {@inheritDoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['chat_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Chat ID'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->addConstraint('TelegramChatId')
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['type_chat'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Type chat'))
      ->setRequired(TRUE)
      ->setSetting('allowed_values', [
        'private' => t('Private'),
        'group' => t('Group'),
        'supergroup' => t('Supergroup'),
        'channel' => t('Channel'),
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 10
      ])
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }

  /**
   * {@inheritDoc}
   */
  public function getLabel(): string {
    return $this->get('label')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setLabel(string $label): TelegramChatInterface {
    $this->set('label', $label);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getChatId(): string {
    return $this->get('chat_id')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setChatId(string $chat_id): TelegramChatInterface {
    $this->set('chat_id', $chat_id);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getTypeChat(): string {
    return $this->get('type_chat')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setTypeChat(string $chat_type): TelegramChatInterface {
    $this->set('type_chat', $chat_type);

    return $this;
  }
}
