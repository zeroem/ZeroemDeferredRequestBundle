<?php

$loader = require_once(__DIR__.'/../vendor/autoload.php');

use Doctrine\Common\Annotations\AnnotationRegistry;

//spl_autoload_register("derpAutoload");
AnnotationRegistry::registerLoader(
  function ($class) {
    if (0 === strpos(ltrim($class, '/'), 'Zeroem\DeferredRequestBundle')) {
      if (file_exists($file = __DIR__.'/../'.substr(str_replace('\\', '/', $class), strlen('Zeroem\DeferredRequestBundle')).'.php')) {
        require_once $file;
        return true;
      }
    }
  }
);
