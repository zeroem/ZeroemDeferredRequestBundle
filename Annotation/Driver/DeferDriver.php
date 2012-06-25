<?php

namespace Zeroem\DeferredRequestBundle\Annotation\Driver;

use Zeroem\DeferredRequestBundle\Annotation\Defer;
use Zeroem\DeferredRequestBundle\Exception\AcceptedException;
use Zeroem\DeferredRequestBundle\DeferEvents;
use Zeroem\DeferredRequestBundle\Response\ResponseBuilderInterface;
use Zeroem\DeferredRequestBundle\Event\DeferredRequestEvent;
use Zeroem\DeferredRequestBundle\Persistence\PersistenceInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Annotations\Reader;

class DeferDriver
{
  private $reader;
  private $dispatcher;
  private $responseBuilder;
  private $persister;

  private $disabled = false;
  private $disabledForNextOnly = false;

  public function __construct(
    Reader $reader,
    EventDispatcherInterface $dispatcher,
    PersistenceInterface $persister,
    ResponseBuilderInterface $responseBuilder)
  {
    $this->reader = $reader;
    $this->dispatcher = $dispatcher;
    $this->persister = $persister;
    $this->responseBuilder = $responseBuilder;
  }

  /**
   * This event will fire during any controller call
   */
  public function onKernelController(FilterControllerEvent $event)
  {
    if($this->isDisabled()) {
      return;
    }

    $this->disabledForNextOnly = false;

    $controller = $event->getController();
    
    if(is_callable($controller)) {
      $defer = $this->getDeferAnnotation($controller);
      if($defer !== false) {
        
        $entity = $this->persister->persist($event->getRequest());
        
        $this->dispatcher->dispatch(
          DeferEvents::DEFERRED,
          new DeferredRequestEvent(
            $entity,
            $controller
          )
        );
          
        throw new AcceptedException(
          $this->responseBuilder->makeBody($entity),
          null,
          $this->responseBuilder->makeHeaders($entity)
        );
      }
    }
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
    $this->disabledForNextOnly = false;

    return $this;
  }

  /**
   * Disable Defer Annotations for the next request only
   */
  public function disableNextRequest() {
    $this->disabledForNextOnly = true;
    return $this;
  }

  private function isDisabled() {
    return $this->disabled || $this->disabledForNextOnly;
  }

  private function persistRequest($httpRequest) {
    $request = new Request();
    $request->setRequest($httpRequest);

    $this->entityManager->persist($request);
    $this->entityManager->flush();

    return $request;
  }

  private function getDeferAnnotation($controller) {
    $object = new \ReflectionObject($controller[0]);

    $defer = $this->reader->getClassAnnotation($object, 'Zeroem\DeferredRequestBundle\Annotation\Defer');

    if(!isset($defer)) {
      $method = $object->getMethod($controller[1]);
      $defer = $this->reader->getMethodAnnotation($method,'Zeroem\DeferredRequestBundle\Annotation\Defer');
    }

    if(!isset($defer)) {
      return false;
    }

    return $defer;
  }

}
