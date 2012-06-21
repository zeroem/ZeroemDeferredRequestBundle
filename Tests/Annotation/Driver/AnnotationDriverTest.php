<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Tests\Annotation\DeferredClassFixture;
use Zeroem\DeferredRequestBundle\Tests\Annotation\DeferredMethodFixture;
use Zeroem\DeferredRequestBundle\Annotation\Driver\AnnotationDriver;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;

class AnnotationDriverTest extends \PHPUnit_Framework_TestCase
{
  private $driver;

  public function setUp() {
    $this->driver = new AnnotationDriver(new AnnotationReader());
  }

  public function testClassAnnotation()
  {
    $event = $this->getFilterControllerEvent(
      array(
	new DeferredClassFixture(),
	'doNothing'
      )
    );

    $this->driver->onKernelController($event);

    $expected = AnnotationDriver::getDeferController();
    $controller = $event->getController();

    $this->assertInstanceOf(get_class($expected[0]),$controller[0]);
    $this->assertEquals($expected[1],$controller[1]);
  }

  public function testMethodAnnotation() {
    $event = $this->getFilterControllerEvent(
      array(
	new DeferredMethodFixture(),
	'doNothing'
      )
    );

    $this->driver->onKernelController($event);

    $expected = AnnotationDriver::getDeferController();
    $controller = $event->getController();

    $this->assertInstanceOf(get_class($expected[0]),$controller[0]);
    $this->assertEquals($expected[1],$controller[1]);
  }

  /**
   * Shamelessly copied from sensio/framework-extra-bundle
   */
  protected function getFilterControllerEvent($controller)
  {
    $mockKernel = $this->getMockForAbstractClass('Symfony\Component\HttpKernel\Kernel', array('', ''));

    return new FilterControllerEvent($mockKernel, $controller, new Request(), HttpKernelInterface::MASTER_REQUEST);
  }
}
