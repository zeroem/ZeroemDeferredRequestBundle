<?php

namespace Zeroem\DeferredRequestBundle\Tests\Annotation;

use Zeroem\DeferredRequestBundle\Annotation\Defer;

class DeferredMethodFixture
{
  /**
   *@Defer
   */
  public function doNothing() {
    // do nothing
  }
}