<?php

namespace Drupal\drupal_telegram_sdk\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\drupal_telegram_sdk\Event\DrupalTelegramEvents;
use Drupal\drupal_telegram_sdk\Event\WebhookAfterProcessing;
use Drupal\drupal_telegram_sdk\Event\WebhookBeforeProcessing;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Returns responses for telegram bot routes.
 */
class TelegramBotController implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The telegram bot api.
   *
   * @var \Drupal\drupal_telegram_sdk\TelegramBotApi
   */
  protected $telegramBotApi;

  /**
   * The event dispatcher.
   *
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = new static();
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->telegramBotApi = $container->get('drupal_telegram_sdk.bot_api');
    $instance->eventDispatcher = $container->get('event_dispatcher');

    return $instance;
  }

  /**
   * Telegram webhook.
   */
  public function webhook(Request $request, string $token) {
    $telegram_bot_storage = $this->entityTypeManager->getStorage('telegram_bot');
    $telegram_bots = $telegram_bot_storage->loadByProperties([
      'token' => $token,
      'status' => 1,
    ]);

    if (empty($telegram_bots)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot */
    $telegram_bot = reset($telegram_bots);
    $this->telegramBotApi->setTelegram($telegram_bot->id());

    $before_processing = new WebhookBeforeProcessing($telegram_bot, $this->telegramBotApi->getTelegram());
    $this->eventDispatcher->dispatch(DrupalTelegramEvents::WEBHOOK_BEFORE_PROCESSING, $before_processing);

    if (!$before_processing->isLockProcessing()) {
      $update = $this->telegramBotApi->commandsHandler();

      $after_processing = new WebhookAfterProcessing($telegram_bot, $this->telegramBotApi->getTelegram(), $update);
      $this->eventDispatcher->dispatch(DrupalTelegramEvents::WEBHOOK_AFTER_PROCESSING, $after_processing);
    }

    return new JsonResponse(['message' => 'Webhook processed.']);
  }

}
