<?php
/**
 * Mail Service
 * 
 * Service for sending emails
 * 
 * @author    Hardie Boeve
 * @copyright 2014 Boeve Web Development
 * @license   LICENCE
 * 
 * @todo: attachements
 *
 */
namespace Mail\Service;

use Mail\Exception\InvalidArgumentException;
use Zend\Mail\Transport\TransportInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailService implements
    ServiceLocatorAwareInterface,
    MailServiceInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * Transport
     * 
     * @var Zend\Mail\Transport\TransportInterface
     */
    protected $transport;
    
    /**
     * Template
     * 
     * @var string
     */
    protected $template;
    
    /**
     * Message params
     * 
     * @var array
     */
    protected $params = [];
    
    /**
     * Subject
     * 
     * @var string
     */
    protected $subject;
    
    /**
     * Signature
     * 
     * @var string
     */
    protected $signature;
    
    /**
     * From
     * 
     * @var array
     */
    protected $from = [];
    
    /**
     * To
     * 
     * @var array
     */
    protected $to;
    
    /**
     * Cc
     * 
     * @var array
     */
    protected $cc = [];
    
    /**
     * Bcc
     * 
     * @var array
     */
    protected $bcc = [];
    
    /**
     * Module options
     * 
     * @var \Mail\Options\ModuleOptions
     */
    protected $moduleOptions;
    
    /**
     * Mail options
     *
     * @var \Mail\Options\MailOptions
     */
    protected $mailOptions;

	/**
     * Constructor
     * 
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {
    	$this->setServiceLocator($serviceLocator);
    }
    
    public function send()
    {}
    
    public function render()
    {}
    
	/**
     * Get template 
     *
     * @return string
     */
    public function getTemplate()
    {
        if (null === $this->template) {
            $template = $this->getMailOptions()->getTemplate();
            
            if (!$template) {
                throw new InvalidArgumentException('No template file set');
            }
            
            $this->setTemplate($template);
        }
        
        return $this->template;
    }

	/**
     * Set template
     *
     * @param string $template
     * @return MailService
     */
    public function setTemplate($template)
    {
        $paths = $this->getModuleOptions()->getTemplatePaths();
        
        foreach ($paths as $path) {
            if (file_exists($path . $template)) {
                $template = $path . $template;
            } elseif (file_exists($path . $template . '.phtml')) {
                $template = $path . $template . '.phtml';
            }
        }
        
        $this->template = $template;
        return $this;
    }

	/**
     * Get params 
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

	/**
     * Set params
     *
     * @param array $params
     * @return MailService
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

	/**
     * Get subject 
     *
     * @return string
     */
    public function getSubject()
    {
        if (null === $this->subject) {
            $this->setSubject($this->getMailOptions()->getSubject());
        }
        
        return $this->subject;
    }

	/**
     * Set subject
     *
     * @param string $subject
     * @return MailService
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

	/**
     * Get from 
     *
     * @return array
     */
    public function getFrom()
    {
        return $this->from;
    }

	/**
     * Set from
     * 
     * Set the email sender
     * 
     * Array must atleast contain the key email, name is optional
     *
     * @param array $from
     * @return MailService
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }
    
    /**
     * Get to
     *
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }
    
    /**
     * Set to
     *
     * Set the email receiver
     *
     * Array must atleast contain the key email, name is optional
     *
     * @param array $to
     * @return MailService
     */
    public function setTo(array $to)
    {
        $this->to = $to;
        return $this;
    }

	/**
     * Get cc 
     *
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

	/**
     * Set cc
     * 
     * Send copy to one ore more receivers.
     * 
     * Set overide to true when you want to ignore the default
     * bcc settings from config.
     * 
     * @param array $bcc
     * @param bool $overide
     * 
     * @return \Mail\Service\MailService
     */
    public function setCc($cc, $overide = false)
    {
        $this->cc = $cc;
        return $this;
    }

	/**
     * Get bcc 
     *
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

	/**
     * Set bcc
     * 
     * Send blind copy to one ore more receivers.
     * 
     * Set overide to true when you want to ignore the default
     * bcc settings from config.
     * 
     * @param array $bcc
     * @param bool $overide
     * 
     * @return \Mail\Service\MailService
     */
    public function setBcc(array $bcc, $overide = false)
    {
        $this->bcc = $bcc;
        return $this;
    }
    
    /**
     * Get transport 
     *
     * @return \Mail\Service\Zend\Mail\Transport\TransportInterface
     */
    public function getTransport()
    {
        if (null === $this->transport) {
            $this->setTransport($this->getServiceLocator()->get('Mail\Factory\TransportFactory'));
        }
        
        return $this->transport;
    }

	/**
     * Set transport
     *
     * @param \Mail\Service\Zend\Mail\Transport\TransportInterface $transport
     * @return MailService
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

	/**
     * Get ModuleOptions
     *
     * @return \Mail\Options\ModuleOptions
     */
    public function getModuleOptions()
    {
        if (null === $this->moduleOptions) {
            $this->setModuleOptions($this->getServiceLocator()->get('Mail\Options\ModuleOptions'));
        }
        
        return $this->moduleOptions;
    }
    
    /**
     * Set ModuleOptions
     *
     * @param \Mail\Options\ModuleOptions $moduleOptions
     * @return MailService
     */
    public function setModuleOptions($moduleOptions)
    {
        $this->moduleOptions = $moduleOptions;
        return $this;
    }
    
    /**
     * Get MailOptions
     *
     * @return \Mail\Options\MailOptions
     */
    public function getMailOptions()
    {
        if (null === $this->mailOptions) {
            $this->setMailOptions($this->getServiceLocator()->get('Mail\Options\MailOptions'));
        }
        
        return $this->mailOptions;
    }
    
    /**
     * Set MailOptions
     *
     * @param \Mail\Options\MailOptions $mailOptions
     * @return MailService
     */
    public function setMailOptions($mailOptions)
    {
        $this->mailOptions = $mailOptions;
        return $this;
    }
}