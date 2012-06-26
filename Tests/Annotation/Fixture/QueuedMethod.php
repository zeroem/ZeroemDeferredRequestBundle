<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture;

use Zeroem\DeferredRequestBundle\Annotation\Queue;

class QueuedMethod
{
  /**
   * @Queue
   */
  public function queued() {
    // do nothing
  }

  public function notQueued() {
    // Not deferred
  }
}