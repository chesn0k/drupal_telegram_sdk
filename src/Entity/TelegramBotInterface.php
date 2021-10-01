<?php

namespace Drupal\drupal_telegram_sdk\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface defining a telegram bot entity type.
 */
interface TelegramBotInterface extends ConfigEntityInterface {

  /**
   * Gets the telegram bot label.
   *
   * @return string
   *  The telegram bot label.
   */
  public function getLabel();

  /**
   * Sets the telegram bot label.
   *
   * @param string $label
   *  The telegram bot label.
   *
   * @return $this
   */
  public function setLabel(string $label);

  /**
   * Gets the telegram bot token.
   *
   * @return string
   *  The telegram bot token.
   */
  public function getToken();

  /**
   * Sets the telegram bot token.
   *
   * @param string $token
   *  The telegram bot token.
   *
   * @return $this
   */
  public function setToken(string $token);

}
