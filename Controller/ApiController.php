<?php

namespace Zeroem\DeferredRequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class ApiController extends Controller
{
  public function deferredResponse($id)
  {
    $entity = $this->getRequestEntity($id);

    if($entity->getResponse()) {
      return $entity->getResponse();
    }

    throw new NotFoundHttpException('Deferred Request does not have a response.');
  }

  public function deferredRequestStatus($id) {
    $entity = $this->getRequestEntity($id);

    if($entity->getResponse()) {
      return "done";
    }

    return "not done";
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
