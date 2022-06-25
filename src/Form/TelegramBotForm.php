<?php

namespace Drupal\drupal_telegram_sdk\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface;
use Drupal\drupal_telegram_sdk\TelegramBotApi;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Telegram Bot form.
 *
 * @property \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $entity
 */
final class TelegramBotForm extends EntityForm {

  /**
   * The telegram bot api.
   */
  protected TelegramBotApi $telegramBotApi;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container): self {
    $instance = parent::create($container);
    $instance->telegramBotApi = $container->get('drupal_telegram_sdk.bot_api');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state): array {
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
   * {@inheritDoc}
   */
  protected function actionsElement(array $form, FormStateInterface $form_state): array {
    $element = parent::actionsElement($form, $form_state);

    if (!$this->getEntity()->isNew()) {
      $element['set_webhook'] = [
        '#type' => 'submit',
        '#button_type' => 'secondary',
        '#value' => $this->t('Set webhook'),
        '#weight' => 5,
        '#submit' => [[$this, 'setWebhook']]
      ];
    }

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state): int {
    $result = parent::save($form, $form_state);

    $message_args = ['%label' => $this->getEntity()->label()];
    $message = $result == $this->getEntity()->isNew()
      ? $this->t('Created new telegram bot %label.', $message_args)
      : $this->t('Updated telegram bot %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->getEntity()->toUrl('collection'));

    return $result;
  }

  /**
   * Sets a webhook for this bot.
   */
  public function setWebhook(): void {
    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot */
    $telegram_bot = $this->getEntity();

    $telegram = $this->telegramBotApi->getTelegram($telegram_bot);
    $string_url = $telegram_bot->toUrl('webhook', ['absolute' => TRUE])->toString();

    try {
      $telegram->setWebhook(['url' => $string_url]);
      \Drupal::messenger()->addStatus(\t('Webhook successfully installed.'));
    }
    catch (TelegramSDKException $e) {
      \Drupal::messenger()->addError(\t('Error set webhook (see the logs for details).'));
      \Drupal::logger('drupal_telegram_sdk')->error($e->getMessage());
    }
  }

}
