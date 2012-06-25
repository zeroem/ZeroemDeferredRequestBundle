<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture\DeferredClass;
use Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture\DeferredMethod;
use Zeroem\DeferredRequestBundle\Annotation\Driver\DeferDriver;
use Zeroem\DeferredRequestBundle\DeferResponseBuilder;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class DeferDriverTest extends \PHPUnit_Framework_TestCase
{
  public function getDeferDriver() {
    $entity = $this->getMockForAbstractClass('Zeroem\DeferredRequestBundle\DeferredRequestInterface');
    $entity
      ->expects($this->any())
      ->method('getId')
      ->will($this->returnValue('1'));

    $persister = 
      $this->getMockForAbstractClass('Zeroem\DeferredRequestBundle\Persistence\PersistenceInterface');
    $persister
      ->expects($this->once())
      ->method('persist')
      ->will($this->returnValue($entity));

    $responseBuilder = 
      $this->getMockForAbstractClass('Zeroem\DeferredRequestBundle\Response\ResponseBuilderInterface');
    $responseBuilder
      ->expects($this->once())
      ->method('makeHeaders')
      ->will($this->returnValue(array('headers')));

    $responseBuilder
      ->expects($this->once())
      ->method('makeBody')
      ->will($this->returnValue('body'));

    return new DeferDriver(
      new AnnotationReader(),
      $this->getMockEventDispatcher(),
      $persister,
      $responseBuilder
    );
  }


  /**
   * @expectedException Zeroem\DeferredRequestBundle\Exception\AcceptedException
   */
  public function testClassAnnotation()
  {
    $event = $this->getFilterControllerEvent(
      array(
        new DeferredClass(),
        'doNothing'
      )
    );

    $driver = $this->getDeferDriver();
    $driver->onKernelController($event);
  }

  /**
   * @expectedException Zeroem\DeferredRequestBundle\Exception\AcceptedException
   */
  public function testMethodAnnotation() {
    $deferredMethod = new DeferredMethod();

    // Test Deferred Method
    $event = $this->getFilterControllerEvent(
      array(
        $deferredMethod,
        'doNothing'
      )
    );

    $driver = $this->getDeferDriver();
    $driver->onKernelController($event);
  }

  /**
   * Shamelessly copied from sensio/framework-extra-bundle
   */
  protected function getFilterControllerEvent($controller)
  {
    $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

    return new FilterControllerEvent($mockKernel, $controller, new Request(), HttpKernelInterface::MASTER_REQUEST);
  }
  
  private function getMockEventDispatcher() {
    $dispatcher = $this->getMockForAbstractClass('Symfony\Component\EventDispatcher\EventDispatcherInterface');
    $dispatcher->expects($this->once())->method('dispatch');

    return $dispatcher;
  }
}
