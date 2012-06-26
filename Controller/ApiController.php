<?php

namespace Zeroem\DeferredRequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ApiController extends Controller
{
  public function deferredResponseAction($id)
  {
    $entity = $this->getRequestEntity($id);

    if($entity->getResponse()) {
      $response = $entity->getResponse();

      // modify cache headers as the response will never change
      $response->setPublic();
      $response->setMaxAge(600);
      $response->setLastModified($entity->getFinishedDate());
      $response->isNotModified($this->getRequest());

      return $response;
    }

    throw new NotFoundHttpException('Deferred Request does not have a response.');
  }

  public function deferredRequestStatusAction($id) {
    $entity = $this->getRequestEntity($id);

    if($entity->getResponse()) {
      return new Response("done");
    }

    return new Response("not done");
  }

  private function getRequestEntity($id) {
    $repository = $this
      ->getDoctrine()
      ->getEntityManager()
      ->getRepository('ZeroemDeferredRequestBundle:Request')
      ;

    $entity = $repository->find($id);

    if($entity) {
      return $entity;
    }

    throw new NotFoundHttpException('Deferred Request does not exist.');
  }
}
