<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation\Fixture;

use Zeroem\DeferredRequestBundle\Annotation\Defer;

class DeferredMethod
{
  /**
   *@Defer
   */
  public function doNothing() {
    // do nothing
  }

  public function doSomething() {
    // Not deferred
  }
}