<?php

namespace Zeroem\DeferredRequestBundle\Response;

use Zeroem\DeferredRequestBundle\DeferredRequestInterface;

interface ResponseBuilderInterface
{
  function makeHeaders(DeferredRequestInterface $request);
  function makeBody(DeferredRequestInterface $request);
}
