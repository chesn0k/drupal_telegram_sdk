<?php

namespace Drupal\drupal_telegram_sdk\Controller;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\drupal_telegram_sdk\Event\CommandsAfterProcessing;
use Drupal\drupal_telegram_sdk\Event\DrupalTelegramEvents;
use Laminas\Diactoros\Response\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
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
    $instance->eventDispatcher = $container->get('event_dispatcher');

    return $instance;
  }

  /**
   * Telegram webhook.
   */
  public function webhook(Request $request, string $token) {
    $telegram_bot_storage = $this->entityTypeManager->getStorage('telegram_bot');
    $telegram_bots = $telegram_bot_storage->loadByProperties(['token' => $token]);
    if (empty($telegram_bots)) {
      throw new NotFoundHttpException();
    }

    /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramBotInterface $telegram_bot */
    $telegram_bot = reset($telegram_bots);
    $telegram_api = $telegram_bot->getTelegramBotApi();
    $update = $telegram_api->commandsHandler(TRUE);

    $event = new CommandsAfterProcessing($update, $telegram_api);
    $this->eventDispatcher->dispatch(DrupalTelegramEvents::COMMANDS_AFTER_PROCESSING, $event);

    return new JsonResponse([]);
  }

}
