<?php

namespace Drupal\drupal_telegram_sdk\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Telegram Bot form.
 *
 * @property \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $entity
 */
class TelegramBotForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {

    $form = parent::form($form, $form_state);

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $this->getEntity()->label(),
      '#description' => $this->t('Label for the telegram bot.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->getEntity()->id(),
      '#machine_name' => [
        'exists' => '\Drupal\drupal_telegram_sdk\Entity\TelegramBot::load',
      ],
      '#disabled' => !$this->getEntity()->isNew(),
    ];

    $form['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Token'),
      '#default_value' => $this->getEntity()->get('token'),
      '#description' => $this->t('Bot token.'),
      '#attributes' => [
        'autocomplete' => 'off',
      ],
      '#required' => TRUE,
    ];

    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $this->getEntity()->status(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);

    $message_args = ['%label' => $this->getEntity()->label()];
    $message = $result == $this->getEntity()->isNew()
      ? $this->t('Created new telegram bot %label.', $message_args)
      : $this->t('Updated telegram bot %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->getEntity()->toUrl('collection'));

    return $result;
  }

}
