<?php

namespace Zeroem\DeferredRequestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Zeroem\DeferredRequestBundle\Entity\Request;

class DeferController extends Controller
{
  public function deferRequest() {
    $request = new Request();
    $request->setRequest($this->getRequest());

    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($request);
    $em->flush();
  }
}