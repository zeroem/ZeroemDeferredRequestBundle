<?php

namespace Zeroem\DeferredRequestBundle\Annotation;

/**
 * @Annotation
 * @Target({"METHOD","CLASS"})
 */
final class Defer
{
  private $methods = "ALL";

  public function __construct(array $args=array()) {
    if(isset($args['value'])) {
      $this->methods = (array)$args['value'];
    }
  }

  public function isDeferredMethod($verb) {
    if("ALL" === $this->methods) {
      return true;
    }

    if(in_array($verb, $this->methods)) {
      return true;
    }

    return false;
  }
}