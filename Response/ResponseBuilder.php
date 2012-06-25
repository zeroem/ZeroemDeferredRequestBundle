<?php

namespace Zeroem\DeferredRequestBundle\Response;

use Zeroem\DeferredRequestBundle\DeferredRequestInterface;

use Symfony\Component\HttpFoundation\Response;

class ResponseBuilder implements ResponseBuilderInterface
{
  private $router;

  public function __construct($router) {
    $this->router = $router;
  }

  public function makeHeaders(DeferredRequestInterface $request) {
    $id = $request->getId();

    $response_url = $this->router->generate('defer_request_response', array('id'=>$id), true);
    $monitor_url = $this->router->generate('defer_request_monitor',array('id'=>$id), true);

    return array(
      "content-location" =>$response_url,
      "link" =>  "<{$monitor_url}>; rel=\"monitor\""
    );
  }

  public function makeBody(DeferredRequestInterface $request) {
    $monitor_url = $this->router->generate('defer_request_monitor',array('id'=>$request->getId()), true);
    return "<link rel=\"monitor\" href=\"{$monitor_url}\" />";
  }
}
