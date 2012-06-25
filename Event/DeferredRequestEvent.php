<?php

namespace Zeroem\DeferredRequestBundle\Event;

use Zeroem\DeferredRequestBundle\DeferredRequestInterface;
use Symfony\Component\EventDispatcher\Event;

class DeferredRequestEvent extends Event
{
  private $request;
  private $controller;

  public function __construct(DeferredRequestInterface $request, $controller) {
    $this->request = $request;
    $this->controller = $controller;
  }

  public function getController() {
    return $this->controller;
  }

  public function getRequest() {
    return $this->request;
  }
}