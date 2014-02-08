<?php
namespace Mail\Options;

use Zend\Stdlib\AbstractOptions;

class MailOptions extends AbstractOptions implements MailOptionsInterface
{
    /**
     * Turn off strict options mode
     *
     * @var bool
     */
    protected $__strictMode__ = false;
    
    /**
     * @var string
     */
    protected $fromName;
    
    /**
     * @var string
     */
    protected $fromEmail;
    
    /**
     * @var array
     */
    protected $to = [];
    
    /**
     * @var array
     */
    protected $cc = [];
    
    /**
     * @var array
     */
    protected $bcc = [];
    
    /**
     * @var string
     */
    protected $subject;
    
    /**
     * @var string
     */
    protected $template;
    
    /**
     * @var string
     */
    protected $transportType;
    
    /**
     * @var array
     */
    protected $transportConfig;
    
	/**
     * Get from name
     * 
     * @return string
     */
    public function getFromName()
    {
        return $this->fromName;
    }

	/**
     * Set from name
     * 
     * @param string $fromName
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setFromName($fromName)
    {
        $this->fromName = $fromName;
        return $this;
    }

	/**
     * Get email from
     * 
     * @return string
     */
    public function getFromEmail()
    {
        return $this->fromEmail;
    }

	/**
     * Set email from
     * 
     * @param string $fromEmail
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setFromEmail($fromEmail)
    {
        $this->fromEmail = $fromEmail;
        return $this;
    }

	/**
     * Get send email to
     * 
     * @return array
     */
    public function getTo()
    {
        return $this->to;
    }

	/**
     * Set send email to
     * 
     * @param array $to
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

	/**
     * Get send cc to
     * 
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

	/**
     * Set send cc to
     * 
     * @param array $cc
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setCc($cc)
    {
        $this->cc = $cc;
        return $this;
    }

	/**
     * Get send bcc to
     * 
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

	/**
     * Set send bcc to
     * 
     * @param array $bcc
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setBcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }

	/**
     * Get subject 
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

	/**
     * Set subject
     *
     * @param string $subject
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

	/**
     * Get template 
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

	/**
     * Set template
     *
     * @param string $template
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

	/**
     * Set transport type
     * 
     * @return string
     */
    public function getTransportType()
    {
        return $this->transportType;
    }

	/**
     * Set transport type
     * 
     * @param string $transportType
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTransportType($transportType)
    {
        $this->transportType = $transportType;
        return $this;
    }

	/**
     * Get transport config
     * 
     * @return array
     */
    public function getTransportConfig()
    {
        return $this->transportConfig;
    }

	/**
     * Set transport config
     * 
     * @param array $transportConfig
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTransportConfig($transportConfig)
    {
        $this->transportConfig = $transportConfig;
        return $this;
    }
}