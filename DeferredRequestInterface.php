<?php

namespace Zeroem\DeferredRequestBundle;

interface DeferredRequestInterface
{
  /**
   * Returns the id that will be used in the routes
   * to retrieve the request/response data from persistent storage
   *
   * @retrn string
   */
  function getId();

  /**
   * Retreive the Symfony\Component\HttpFoundation\Request object
   * that was deferred to a later time
   *
   * @return Symfony\Component\HttpFoundation\Request
   */
  function getRequest();

  /**
   * Retrieve the Symfony\Component\HttpFoundation\Response object
   * associated with the deferred request.
   *
   * @return Symfony\Component\HttpFoundation\Response
   */
  function getResponse();


  /**
   * Date the request was deferred
   *
   * @return \DateTime
   */
  function getCreatedDate();

  /**
   * Date the response was generated
   *
   * @return \DateTime
   */
  function getFinishedDate();
}