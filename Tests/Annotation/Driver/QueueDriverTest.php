<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture\QueuedClass;
use Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture\QueuedMethod;

use Zeroem\DeferredRequestBundle\Annotation\Driver\QueueDriver;
use Zeroem\DeferredRequestBundle\Event\DeferredRequestEvent;

use Doctrine\Common\Annotations\AnnotationReader;

class QueueDriverTest extends \PHPUnit_Framework_TestCase
{
  private function getQueueDriver($event, $queued = true, $queueName='default') {
    $queueManager = $this->getMockForAbstractClass('Zeroem\DeferredRequestBundle\Queue\QueueInterface');

    if($queued) {
      $queueManager
        ->expects($this->once())
        ->method('enqueue')
        ->with(
          $this->equalTo($queueName),
          $this->anything()
        );
    } else {
      $queueManager
        ->expects($this->never())
        ->method('enqueue');
    }


    return new QueueDriver(
      new AnnotationReader(),
      $queueManager
    );
  }

  public function testMethodAnnotation() {
    $event = $this->getDeferredRequestEvent(
      array(new QueuedMethod(), 'queued')
    );

    $driver = $this->getQueueDriver($event);
    $driver->onDefer($event);


    $event = $this->getDeferredRequestEvent(
      array(new QueuedMethod(), 'notQueued'),
      false
    );

    $driver = $this->getQueueDriver($event, false);

    $driver->onDefer($event);
  }

  public function testClassAnnotation() {
    $event = $this->getDeferredRequestEvent(
      array(new QueuedClass(), 'queued')
    );

    $driver = $this->getQueueDriver($event, true,'not_default');

    $driver->onDefer($event);
  }

  private function getDeferredRequestEvent($controller,$queued=true)
  {
    $deferredRequest = $this->getMockForAbstractClass('Zeroem\DeferredRequestBundle\DeferredRequestInterface');

    $event = $this
      ->getMockBuilder('Zeroem\DeferredRequestBundle\Event\DeferredRequestEvent')
      ->disableOriginalConstructor()
      ->getMock();

    $event->expects($this->once())->method('getController')->will($this->returnValue($controller));

    if($queued) {
      $event->expects($this->once())->method('getRequest')->will($this->returnValue($deferredRequest));
    }




    return $event;
  }

}
