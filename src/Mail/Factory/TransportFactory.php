<?php
namespace Mail\Factory;

use Mail\Exception\InvalidArgumentException;
use Mail\Exception\RuntimeException;
use Zend\Mail\Transport\File;
use Zend\Mail\Transport\FileOptions;
use Zend\Mail\Transport\Sendmail;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TransportFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if (!$serviceLocator->has('Mail\Options\MailOptions')) {
            throw new RuntimeException('Service "Mail\Options\MailOptions" is not created');
        }
        
    	$options   = $serviceLocator->get('Mail\Options\MailOptions');
    	$type      = $options->getTransportType();
    	$config    = $options->getTransportConfig();
    	$transport = null;
    	
    	switch ($type) {
    	    case 'mail':
    	        $transport = new Sendmail();
    	        break;
    	    
    	    case 'smtp':
    	        if (!count($config)) {
    	            throw new InvalidArgumentException('No configuration for "transport_config" found!');
    	        }
    	        
    	        $smtpOptions = new SmtpOptions($config);
    	        $transport   = new Smtp($smtpOptions);
    	        break;
    	    
    	    case 'file':
    	        if (!count($config)) {
    	            throw new InvalidArgumentException('No configuration for "transport_config" found!');
    	        }
    	        
    	        $fileOptions = new FileOptions($config);
    	        $transport   = new File($fileOptions);
    	        break;
    	    
    	    default:
    	        throw new InvalidArgumentException('Transport type ( ' . $type . ' ) is not supported');
    	}
    	
    	return $transport;
    }
}