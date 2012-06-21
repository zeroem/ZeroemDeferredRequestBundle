<?php

namespace Zeroem\DeferredRequestBundle\Annotation\Driver;
use Zeroem\DeferredRequestBundle\Annotation\Defer;


class AnnotationDriver
{
  private $reader;

  private $disabled = false;
  private $disabledForNextOnly = false;

  private static $deferController = array(
    "\\Zeroem\\DeferredRequestBundle\\Controller\\DeferController",
    "deferRequest"
  );

  public function __construct($reader)
  {
    $this->reader = $reader;
  }

  /**
   * This event will fire during any controller call
   */
  public function onKernelController(FilterControllerEvent $event)
  {
    if(!$this->isDisabled()) {
      $controller_parts = $event->getController();
    
      if(is_array($controller_parts)) {
	$object = new \ReflectionObject($controller[0]);
	$method = $object->getMethod($controller[1]);

	$defer = $this->hasDeferAnnotation($this->reader->getClassAnnotations($object));
	if(!$defer) {
	  $defer = $this->checkANnotations($this->reader->getMethodAnnotations($method));
	}

	if($defer) {
	  $evet->setController(self::$deferredController);
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
      foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
        if($annotation instanceof Defer) {
	  return true;
        }
      }

      return false;
  }

  private function isDisabled() {
    return $this->disabled || $this->disabledForNextOnly;
  }

  public static function getDeferController() {
    return self::$deferController;
  }
}
