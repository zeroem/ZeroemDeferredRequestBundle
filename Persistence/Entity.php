<?php

namespace Zeroem\DeferredRequestBundle\Persistence;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Zeroem\DeferredRequestBundle\Entity\Request;

class Entity implements PersistenceInterface
{
  private $entityManager;
  
  public function __construct(EntityManager $entityManager) {
    $this->entityManager = $entityManager;
  }

  public function persist(HttpRequest $request) {
    $entity = new Request();
    $entity->setRequest($request);

    $this->entityManager->persist($entity);
    $this->entityManager->flush();

    return $entity;
  }

  public function find($id) {
    return $this->entityManger->getRepository('ZeroemDeferredRequestBundle:Request')->find($id);
  }
}