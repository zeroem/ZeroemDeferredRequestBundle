<?php

namespace Zeroem\DeferredRequestBundle;

use Zeroem\DeferredRequestBundle\Entity\Request;
use Symfony\Component\HttpFoundation\Response;

class DeferResponseBuilder
{
  private $router;
  public function __construct($router) {
    $this->router = $router;
  }

  public function buildResponse(Request $request) {
    $response_url = $this->router->generate('defer_request_response', array('id'=>$request->getId()));
    $monitor_url = $this->router->generate('defer_request_monitor',array('id'=>$request->getId()));

    $headers = array(
      "content-location" =>$response_url,
      "link" =>  "<{$monitor_url}>; rel=\"monitor\""
    );

    $link = "<link rel=\"monitor\" href=\"{$monitor_url}\" />";
    return new Response($link,202,$headers);
  }
}