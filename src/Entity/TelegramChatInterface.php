<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

interface TelegramChatInterface extends ContentEntityInterface {

  /**
   * Gets the telegram chat label.
   *
   * @return string
   *   The label telegram chat.
   */
  public function getLabel();

  /**
   * Sets the telegram chat label.
   *
   * @param string $label
   *   The label telegram chat.
   *
   * @return $this
   */
  public function setLabel(string $label);

  /**
   * Gets the telegram chat chat_id.
   *
   * @return int
   *  The telegram chat chat_id.
   */
  public function getChatId();

  /**
   * Sets the telegram chat_id.
   *
   * @param int $chat_id
   *   The telegram chat chat_id.
   *
   * @return $this
   */
  public function setChatId(int $chat_id);

  /**
   * Gets type telegram chat.
   *
   * @return string
   *   The type telegram chat.
   */
  public function getTypeChat();

  /**
   * Sets type telegram chat.
   *
   * @param string $chat_type
   *   The type telegram chat. Type of chat, can be either “private”, “group”,
   * “supergroup” or “channel”.
   *
   * @return $this
   */
  public function setTypeChat(string $chat_type);

}
