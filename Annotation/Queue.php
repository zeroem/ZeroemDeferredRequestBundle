<?php

namespace Zeroem\DeferredRequestBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
class Queue
{
  private $queue;

  public function __construct(array $args=array()) {
    if(isset($args['value'])) {
      $this->queue = $args['value'];
    } else {
      $this->queue = 'default';
    }
  }

  public function getQueue() {
    return $this->queue;
  }
}