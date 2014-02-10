<?php
/**
 * Mail Service
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
/**
 * Mail Service
 *
 * Service for sending emails
 *
 * @author    Hardie Boeve
 * @copyright 2014 Boeve Web Development
 * @license   LICENCE
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
    MailServiceInterface,
    AttachmentInterface
{
    use ServiceLocatorAwareTrait;
    use AttachmentTrait;

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

    /**
     * Send email
     */
    public function send()
    {
        $from      = $this->getFrom();
        $fromEmail = $from['email'];
        $fromName  = (isset($from['name'])) ? $from['name'] : $from['email'];

        foreach ($this->getTo() as $to) {
            $message = new Message();
            $message->setSubject($this->getSubject());
            $message->setFrom($fromEmail, $fromName);
            $message->setTo($to['email'], $to['name']);

            $this->setParam('name', $to['name']);

            if (count($this->getCc())) {
                foreach ($this->getCc() as $cc) {
                    $message->addCc($cc['email'], $cc['name']);
                }
            }

            if (count($this->getBcc())) {
                foreach ($this->getBcc() as $bcc) {
                    $message->addBcc($bcc['email'], $bcc['name']);
                }
            }

            $parts       = [];
            $parts[]     = $this->render();
            $attachments = $this->prepareAttachments();

            if (false !== $attachments) {
                $parts = array_merge_recursive($parts, $attachments);
            }

            $mime = new MimeMessage();
            $mime->setParts($parts);

            $message->setBody($mime);

            /* Send email */
            $this->getTransport()->send($message);
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
     * @param  RendererInterface         $renderer
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
     * @param  string      $template
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
     * @param  array       $params
     * @return MailService
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get param
     *
     * @param  string             $key
     * @return multitype:|boolean
     */
    public function getParam($key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }

        return false;
    }

    /**
     * Set param
     *
     * @param  string                    $key
     * @param  string                    $value
     * @return \Mail\Service\MailService
     */
    public function setParam($key, $value)
    {
        $this->params[$key] = $value;

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
     * @param  string      $subject
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
     * @param  array       $from
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
     * @throws InvalidArgumentException
     * @return multitype:
     */
    public function getTo()
    {
        if (null === $this->to) {
            $to = $this->getMailOptions()->getTo();

            if (!$to) {
                throw new InvalidArgumentException('Send to is not set!');
            }

            foreach ($to as $receiver) {
                $this->setTo($receiver['name'], $receiver['email']);
            }
        }

        return $this->to;
    }

    /**
     * Set to
     *
     * @param  string                    $name
     * @param  string                    $email
     * @return \Mail\Service\MailService
     */
    public function setTo($name, $email)
    {
        $this->to[] = ['name' => $name, 'email' => $email];

        return $this;
    }

    /**
     * Get cc
     *
     * @return multitype:
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Set cc
     *
     * @param  string                    $name
     * @param  string                    $email
     * @return \Mail\Service\MailService
     */
    public function setCc($name, $email)
    {
        $this->cc[] = ['name' => $name, 'email' => $email];

        return $this;
    }

    /**
     * Get bcc
     *
     * @return multitype:
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Set bcc
     *
     * @param  string                    $name
     * @param  string                    $email
     * @return \Mail\Service\MailService
     */
    public function setBcc($name, $email)
    {
        $this->bcc[] = ['name' => $name, 'email' => $email];

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
     * @param  string      $replyTo
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
     * @param  \Mail\Service\Zend\Mail\Transport\TransportInterface $transport
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
     * @param  \Mail\Options\ModuleOptions $moduleOptions
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
     * @param  \Mail\Options\MailOptions $mailOptions
     * @return MailService
     */
    public function setMailOptions($mailOptions)
    {
        $this->mailOptions = $mailOptions;

        return $this;
    }
}
