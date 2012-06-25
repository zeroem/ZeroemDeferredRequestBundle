<?php

namespace Zeroem\DeferredRequestBundle\Exception;

use  Symfony\Component\HttpKernel\Exception\HttpException;

class AcceptedException extends HttpException
{
    /**
     * Constructor.
     *
     * @param string    $message  The internal exception message
     * @param Exception $previous The previous exception
     * @param integer   $code     The internal exception code
     */
  public function __construct($message = null, \Exception $previous = null, $headers=array(), $code = 0)
    {
        parent::__construct(202, $message, $previous, $headers, $code);
    }
}