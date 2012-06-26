<?php

namespace Zeroem\DeferredRequestBundle\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Event\DeferredRequestEvent;
use Zeroem\DeferredRequestBundle\Queue\QueueInterface;

use Doctrine\Common\Annotations\Reader;

class QueueDriver
{
  private $queueManager;
  private $reader;

  public function __construct(Reader $reader, QueueInterface $queueManager) {
    $this->queueManager = $queueManager;
    $this->reader = $reader;
  }

  public function onDefer(DeferredRequestEvent $event) {
    $queue = $this->getQueueAnnotation($event->getController());

    if(false !== $queue) {
      $this->queueManager->enqueue($queue->getQueue(),$event->getRequest());
    }
  }

  private function getQueueAnnotation($controller) {
    $object = new \ReflectionObject($controller[0]);
    $method = $object->getMethod($controller[1]);

    // Check the method first so we get the more specific annotation
    $queue = $this->reader->getMethodAnnotation($method,'Zeroem\DeferredRequestBundle\Annotation\Queue');

    if(!isset($queue)) {
      $queue = $this->reader->getClassAnnotation($object, 'Zeroem\DeferredRequestBundle\Annotation\Queue');
    }

    if(!isset($queue)) {
      return false;
    }

    return $queue;
  }
}