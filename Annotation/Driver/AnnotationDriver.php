<?php

namespace Zeroem\DeferredRequestBundle\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Annotation\Defer;
use Zeroem\DeferredRequestBundle\Controller\DeferController;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\AnnotationReader;

class AnnotationDriver
{
  private $reader;

  private $disabled = false;
  private $disabledForNextOnly = false;

  public function __construct(AnnotationReader $reader)
  {
    $this->reader = $reader;
  }

  /**
   * This event will fire during any controller call
   */
  public function onKernelController(FilterControllerEvent $event)
  {
    if(!$this->isDisabled()) {
      $controller = $event->getController();
    
      if(is_callable($controller)) {
        $object = new \ReflectionObject($controller[0]);
        $method = $object->getMethod($controller[1]);

        $defer = $this->hasDeferAnnotation($this->reader->getClassAnnotations($object));
        if(!$defer) {
          $defer = $this->hasDeferAnnotation($this->reader->getMethodAnnotations($method));
        }

        if($defer) {
          $event->setController($this->getDeferController());
        }
      }
    }

    $this->disableForNextOnly = false;
  }

  /**
   * Disable all Defer Annotations
   */
  public function disable() {
    $this->disabled = true;
    return $this;
  }

  /**
   * Enable all Defer Annotations
   */
  public function enable() {
    $this->disabled = false;
    $this->disableForNextOnly = false;

    return $this;
  }

  
  /**
   * Disable Defer Annotations for the next request only
   */
  public function disableNextRequest() {
    $this->disableForNextOnly = true;
  }

  private function hasDeferAnnotation($annotations) {
    foreach ($annotations as $annotation) {
      if($annotation instanceof Defer) {
        return true;
      }
    }

    return false;
  }

  private function isDisabled() {
    return $this->disabled || $this->disabledForNextOnly;
  }

  public function getDeferController() {
    return array(
      new DeferController(),
      "deferRequest"
    );
  }
}
