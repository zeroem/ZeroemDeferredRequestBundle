<?php

namespace Zeroem\DeferredRequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

use Zeroem\DeferredRequestBundle\Entity\Request;
use Zeroem\DeferredRequestBundle\DeferResponseBuilder;

class DeferController
{

  private $entityManager;
  private $responseBuilder;

  public function __construct($entityManager, DeferResponseBuilder $responseBuilder) {
    $this->entityManager = $entityManager;
    $this->responseBuilder = $responseBuilder;
  }

  public function deferRequest(HttpRequest $httpRequest) {
    $request = new Request();
    $request->setRequest($httpRequest);

    $this->entityManager->persist($request);
    $this->entityManager->flush();

    return $this->responseBuilder->buildResponse($request);
  }
}