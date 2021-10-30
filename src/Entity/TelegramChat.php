<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Annotation\PluralTranslation;
use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType(
 *   id = "telegram_chat",
 *   label = @Translation("Telegram chat"),
 *   label_collection = @Translation("Telegram chats"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\drupal_telegram_skd\TelegramChatListBuilder",
 *     "form" = {
 *        "add" = "Drupal\Core\Entity\ContentEntityForm",
 *        "edit" = "Drupal\Core\Entity\ContentEntityForm",
 *        "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "telegram_chat",
 *   admin_permission = "access telegram_chat overview",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/telegram-chat/add",
 *     "edit-form" = "/admin/{telegram_chat}/edit",
 *     "delete-form" = "/admin/{telegram_chat}/delete",
 *     "collection" = "/admin/content/telegram-chat",
 *   },
 * )
 */
class TelegramChat extends ContentEntityBase implements TelegramChatInterface {

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

    $fields['bot'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Telegram Bot'))
      ->setRequired(TRUE)
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setSetting('target_type', 'telegram_bot')
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 10
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['chat_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Chat ID'))
      ->setRequired(TRUE)
      ->addConstraint('TelegramChatId')
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'number',
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
  public function getLabel() {
    return $this->get('label')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setLabel(string $label) {
    $this->set('label', $label);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getChatId() {
    return $this->get('chat_id')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setChatId(int $chat_id) {
    $this->set('chat_id', $chat_id);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getBot() {
    return $this->get('bot')->entity;
  }

  /**
   * {@inheritDoc}
   */
  public function setBot(TelegramBot $bot) {
    $this->set('bot', $bot);

    return $this;
  }

  /**
   * {@inheritDoc}
   */
  public function getTypeChat() {
    return $this->get('type_chat')->value;
  }

  /**
   * {@inheritDoc}
   */
  public function setTypeChat(string $chat_type) {
    $this->set('type_chat', $chat_type);

    return $chat_type;
  }
}
