<?php
namespace Mail\Service\Factory;

use Mail\Service\MailService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	$service = new MailService($serviceLocator);
    	return $service;
    }
}