<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture;

use Zeroem\DeferredRequestBundle\Annotation\Queue;

/**
 * @Queue("not_default")
 */
class QueuedClass
{
  public function queued() {

  }
}
