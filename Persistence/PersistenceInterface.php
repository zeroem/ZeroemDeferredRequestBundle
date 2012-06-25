<?php

namespace Zeroem\DeferredRequestBundle\Persistence;

use Symfony\Component\HttpFoundation\Request;

interface PersistenceInterface
{
  /**
   * Persist an Http Request and return
   * the associated DeferredRequestInterface
   *
   * @param Request $request Http Request to persist
   * @return DeferredRequestInterface
   */
  function persist(Request $request);

  /**
   * Retrieve a persisted DeferredRequestInterface
   *
   * @param string $id record id
   * @return DeferredRequestInterface
   */
  function find($id);
}