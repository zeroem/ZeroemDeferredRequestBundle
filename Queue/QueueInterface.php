<?php

namespace Zeroem\DeferredRequestBundle\Queue;

use Zeroem\DeferredRequestBundle\DeferredRequestInterface;

interface QueueInterface
{
  /**
   * Place a processing task onto the queue for the given request
   *
   * @param string $queue queue to add to
   * @param DeferredRequestInterface $request request to queue
   */
  public function enqueue($queue, DeferredRequestInterface $request);

  /**
   * Retrieve a processing task from the queue
   *
   * @param string $queue queue to grab a message from
   *
   * @return DeferredRequestInterface
   */
  public function dequeue($queue);
}