<?php

namespace Zeroem\DeferredRequestBundle\Command;

use Zeroem\DeferredRequestBundle\DeferEvents;
use Zeroem\DeferredRequestBundle\Event\ProcessedRequestEvent;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessCommand extends ContainerAwareCommand
{
  protected function configure() {
    $this
      ->setName('deferred-request:process')
      ->setDescription('Execute a deferred HttpRequest')
      ->addArgument('request-id', InputArgument::REQUIRED, 'Deferred request to process')
      ;
  }

  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->getContainer()->get('defer_request_annotation_driver')->disableNextRequest();

    $request_id = $input->getArgument('request-id');
    $em = $this
      ->getContainer()
      ->get('doctrine')
      ->getEntityManager();

    $repository = $em->getRepository('ZeroemDeferredRequestBundle:Request');

    $kernel = $this->getContainer()->get('http_kernel');
    $entity = $repository->find($request_id);

    $entity->setResponse(
      $kernel->handle($entity->getRequest())
    );

    $em->flush();

    $this->getContainer()->get('event_dispatcher')->dispatch(
      DeferEvents::PROCESSED,
      new ProcessedRequestEvent($entity)
    );

    $kernel->terminate($entity->getRequest(),$entity->getResponse());
  }
}
