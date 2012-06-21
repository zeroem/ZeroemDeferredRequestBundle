<?php

namespace Zeroem\DeferredRequestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Zeroem\DeferredRequestBundle\Entity\Request
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Request
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var datetimetz $created
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;

    /**
     * @var datetimetz $finished
     *
     * @ORM\Column(name="finished", type="datetimetz")
     */
    private $finished;

    /**
     * @var HttpRequest $request
     *
     * @ORM\Column(name="request", type="object")
     */
    private $request;

    /**
     * @var HttpResponse $request
     *
     * @ORM\Column(name="response", type="object")
     */
    private $response;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param datetimetz $created
     * @return Request
     */
    public function setCreated($created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * Get created
     *
     * @return datetimetz 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set request
     *
     * @param HttpRequest $request
     * @return Request
     */
    public function setRequest(HttpRequest $request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * Get request
     *
     * @return HttpRequest
     */
    public function getRequest()
    {
        return $this->request;
    }


    /**
     * Set response
     *
     * @param HttpResponse $response
     * @return Response
     */
    public function setResponse(HttpResponse $response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * Get response
     *
     * @return HttpResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue()
    {
      if(!isset($this->created)) {
	$this->created = new \DateTime();
      }
    }


    /**
     * @ORM\PreUpdate
     */
    public function setFinishedValue()
    {
      $this->finished = new \DateTime();
    }

}
