<?php

namespace Drupal\drupal_telegram_sdk\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the TelegramChatIdConstraint constraint.
 */
class TelegramChatIdConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   */
  protected EntityStorageInterface $entityStorage;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container): self {
    $instance = new self();
    $instance->entityStorage = $container->get('entity_type.manager')
      ->getStorage('telegram_chat');

    return $instance;
  }

  /**
   * {@inheritDoc}
   */
  public function validate($items, Constraint $constraint): void {
    if (!$items = $items->first()) {
      return;
    }

    $chat_id = $items->value;
    if (isset($chat_id) && $chat_id !== '') {
      /** @var \Drupal\drupal_telegram_sdk\Entity\TelegramChatInterface $chat */
      $chat = $items->getEntity();
      $chat_id_exits = $this->entityStorage
        ->getQuery()
        ->condition('chat_id', $chat_id)
        ->condition('bot', $chat->bundle())
        ->condition('id', (int) $chat->id(), '<>')
        ->range(0, 1)
        ->count()
        ->execute();

      if (!empty($chat_id_exits)) {
        $this->context->buildViolation($constraint->massage)
          ->setParameter('%chat_id', $this->formatValue($chat_id))
          ->addViolation();
      }

    }
  }
}
