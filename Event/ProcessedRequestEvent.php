<?php

namespace Zeroem\DeferredRequestBundle\Event;
use Zeroem\DeferredRequestBundle\DeferredRequestInterface;

use Symfony\Component\EventDispatcher\Event;

class ProcessedRequestEvent extends Event
{
  private $request;

  public function __construct(DeferredRequestInterface $request) {
    $this->request = $request;
  }
}