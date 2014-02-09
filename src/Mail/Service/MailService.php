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
 * @todo: plain text body
 *
 */
namespace Mail\Service;

use Mail\Exception\InvalidArgumentException;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Renderer\RendererInterface;
use Zend\View\Resolver\AggregateResolver;
use Zend\View\Resolver\TemplatePathStack;


class MailService implements
    ServiceLocatorAwareInterface,
    MailServiceInterface
{
    use ServiceLocatorAwareTrait;
    
    /**
     * Renderer
     *
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $renderer;
    
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
     * From
     * 
     * @var array
     */
    protected $from;
    
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
    protected $cc;
    
    /**
     * Bcc
     * 
     * @var array
     */
    protected $bcc;
    
    /**
     * Reply to
     * 
     * @var string
     */
    protected $replyTo = null;
    
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
    {
        $result    = null;
        $from      = $this->getFrom();
        $fromEmail = $from['email'];
        $fromName  = (isset($from['name'])) ? $from['name'] : $from['email'];
        
        /**
         * Mass mail
         * 
         * This will silently ignore all cc and bcc messages to prevent overload,
         * when you want an email to, add yourself to the receivers.
         * 
         * @todo: time out when there are to many receivers.
         */
    	if (count($this->getTo()) > 1) {
    	    foreach ($this->getTo() as $to) {
    	        $email                = $to['email'];
    	        $name                 = (isset($to['name'])) ? $to['name'] : '';
    	        $this->params['name'] = ('' !== $name) ? $to['name'] : $to['email'];
    	        
    	        $body = new MimeMessage();
    	        $body->addPart($this->render());
    	        
    	        $message = new Message();
    	        $message->addFrom($fromEmail, $fromName);
    	        $message->addTo($email, $name);
    	        $message->setSubject($this->getSubject());
    	        $message->setBody($body);
    	        
    	        /**
    	         * Send email
    	         * 
    	         * @todo: When error occors, we trust that ZF2 throws exception, or we want to catch it?
    	         */
    	        $this->getTransport()->send($message);
    	    }
    	} else {
    	    foreach ($this->getTo() as $to) {
    	        $email                = $to['email'];
    	        $name                 = (isset($to['name'])) ? $to['name'] : '';
    	        $this->params['name'] = ('' !== $name) ? $to['name'] : $to['email'];
    	         
    	        $body = new MimeMessage();
    	        $body->addPart($this->render());
    	         
    	        $message = new Message();
    	        $message->addFrom($fromEmail, $fromName);
    	        $message->addTo($email, $name);
    	        $message->setSubject($this->getSubject());
    	        $message->setBody($body);
    	        
    	        /**
    	         * Add cc if not null
    	         */
    	        if (null !== $this->getCc()) {
    	            foreach ($this->getCc() as $cc) {
    	                $ccEmail = $cc['email'];
    	                $ccName  = (isset($cc['name'])) ? $cc['name'] : $ccEmail;
    	                
    	                $message->addCc($ccEmail, $ccName);
    	            }
    	        }
    	        
    	        /**
    	         * Add bcc if not null
    	         */
    	        if (null !== $this->getBcc()) {
    	            foreach ($this->getBcc() as $bcc) {
    	                $bccEmail = $bcc['email'];
    	                $bccName  = (isset($bcc['name'])) ? $bcc['name'] : $bccEmail;
    	                 
    	                $message->addBcc($bccEmail, $bccName);
    	            }
    	        }
    	         
    	        /**
    	         * Send email
    	         *
    	         * @todo: When error occors, we trust that ZF2 throws exception, or we want to catch it?
    	        */
    	        $this->getTransport()->send($message);
    	    }
    	}
    }
    
    /**
     * Render
     * 
     * Renders an html body.
     * 
     * @return \Zend\Mime\Part
     */
    public function render()
    {
        if (!$this->getModuleOptions()->getTemplatePaths()) {
            throw new InvalidArgumentException('No template paths found');
        }
        $paths    = $this->getModuleOptions()->getTemplatePaths();
        $template = $this->getTemplate();
        
        $templateStack = new TemplatePathStack();
        $templateStack->addPaths($paths);
        
        $resolver = new AggregateResolver();
        $resolver->attach($templateStack);
        
        $renderer = $this->getRenderer();
        $renderer->setResolver($resolver);
        
    	$viewModel = new ViewModel();
    	$viewModel->setTemplate($template);
    	$viewModel->setVariables($this->getParams());
    	
    	$body = new MimePart($this->getRenderer()->render($viewModel));
    	$body->charset = 'UTF-8';
    	$body->type    = Mime::TYPE_HTML;
    	
    	return $body;
    }
    
    /**
     * Get Renderer
     * 
     * @return \Zend\View\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        if (null === $this->renderer) {
            if ($this->mailOptions->getRenderer()) {
                $renderer =  $this->getMailOptions()->getRenderer();
                $this->setRenderer(new $renderer());
            } else {
                $this->setRenderer(new PhpRenderer());
            }
        }
        return $this->renderer;
    }
    
    /**
     * Set Renderer
     * 
     * @param RendererInterface $renderer
     * @return \Mail\Service\MailService
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;
        return $this;
    }
    
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
     * Each param must have keyname and value.
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
        if (null === $this->from) {
            $this->setFrom($this->getMailOptions()->getFrom());
        } 
        
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
        if (!count($from)) {
            throw new InvalidArgumentException('No email sender is set!');
        }
        
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
        if (null === $this->to) {
            $this->setTo($this->getMailOptions()->getTo());
        }
        
        return $this->to;
    }
    
    /**
     * Set to
     *
     * Set the email receiver
     *
     * Array must atleast contain the key email, name is optional.
     * 
     * This will standard overide the default receiver, set the 
     * param $overide to false to email also the default receiver.
     *
     * @param array $to
     * @return MailService
     */
    public function setTo(array $to, $overide = true)
    {
        $error = false;
        
        foreach ($to as $array) {
            if (!isset($array['email'])) {
                $error = true;
            }
        }
        
        if ($error) {
            throw new InvalidArgumentException('Key "email" not set in "to"');
        }
        
        if (false === $overide) {
            if (is_array($this->getMailOptions()->getTo())) {
                $this->to = array_merge_recursive($this->getMailOptions()->getTo(), $to);
            } else {
                $this->to = $to;
            }
        } else {
            $this->to = $to;
        }
        
        return $this;
    }

	/**
     * Get cc 
     *
     * @return array
     */
    public function getCc()
    {
        if (null === $this->cc) {
            $this->setCc($this->getMailOptions()->getCc());
        }
        
        return $this->cc;
    }

	/**
     * Set cc
     * 
     * Send copy to one ore more receivers.
     * 
     * This will standard overide the default cc, set the 
     * param $overide to false to email also the default receiver(s).
     * 
     * @param array $cc
     * @param bool $overide
     * 
     * @return \Mail\Service\MailService
     */
    public function setCc($cc, $overide = true)
    {
        if (is_array($cc) && count($cc)) {
            $error = false;
            
            foreach ($cc as $array) {
                if (!isset($array['email'])) {
                    $error = true;
                }
            }
            
            if ($error) {
                throw new InvalidArgumentException('Key "email" not set in "cc"');
            }
            
            if (false === $overide) {
                if (is_array($this->getMailOptions()->getCc())) {
                    $this->cc = array_merge_recursive($this->getMailOptions()->getCc(), $cc);
                } else {
                    $this->cc = $cc;
                }
            } else {
                $this->cc = $cc;
            }
        } else {
            $this->cc = null;
        }
        
        return $this;
    }

	/**
     * Get bcc 
     *
     * @return array
     */
    public function getBcc()
    {
        if (null === $this->bcc) {
            $this->setBcc($this->getMailOptions()->getBcc());
        }
        
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
        if (is_array($bcc) && count($bcc)) {
            $error = false;
            
            foreach ($bcc as $array) {
                if (!isset($array['email'])) {
                    $error = true;
                }
            }
            
            if ($error) {
                throw new InvalidArgumentException('Key "email" not set in "bcc"');
            }
            
            if (false === $overide) {
                if (is_array($this->getMailOptions()->getBcc())) {
                    $this->cc = array_merge_recursive($this->getMailOptions()->getBcc(), $bcc);
                } else {
                    $this->bcc = $bcc;
                }
            } else {
                $this->bcc = $bcc;
            }
        } else {
            $this->bcc = null;
        }
        
        return $this;
    }
    
    /**
     * Get reply to 
     *
     * @return string
     */
    public function getReplyTo()
    {
        return $this->replyTo;
    }

	/**
     * Set reply to
     *
     * @param string $replyTo
     * @return MailService
     */
    public function setReplyTo($replyTo)
    {
        $this->replyTo = $replyTo;
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