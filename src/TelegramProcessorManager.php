<?php

namespace Drupal\drupal_telegram_sdk;

use Drupal\drupal_telegram_sdk\TelegramProcessor\TelegramProcessorInterface;

/**
 * Telegram processor manager.
 */
class TelegramProcessorManager {

  /**
   * Stores an unsorted array of the processor telegram.
   */
  protected array $processors = [];

  /**
   * Stores sorted array of the processor telegram.
   */
  protected array $sortedProcessors = [];

  /**
   * Adds an telegram processor object to the $processors property.
   *
   * @param \Drupal\drupal_telegram_sdk\TelegramProcessor\TelegramProcessorInterface $processor
   *   The processor object to add.
   * @param int $priority
   *   The priority of the processor being added.
   */
  public function addProcessor(TelegramProcessorInterface $processor, int $priority = 0): void {
    $this->processors[$priority][] = $processor;
    $this->sortedProcessors = [];
  }

  /**
   * Returns the sorted array of telegram processors.
   *
   * @return array
   *   An array of processor objects.
   */
  public function getProcessors(): array {
    if (empty($this->sortedProcessors)) {
      $this->sortedProcessors = $this->sortProcessors();
    }

    return $this->sortedProcessors;
  }

  /**
   * Sorts the processors according to priority.
   *
   * @return array
   *   The sorted processors.
   */
  protected function sortProcessors(): array {
    $sorted = [];
    krsort($this->processors);

    foreach ($this->processors as $processors) {
      $sorted = array_merge($sorted, $processors);
    }
    return $sorted;
  }

}
