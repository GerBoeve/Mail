<?php
/**
 * Mail Options 
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace Mail\Options;

use Zend\Stdlib\AbstractOptions;
use Zend\View\Renderer\RendererInterface;

class MailOptions extends AbstractOptions implements MailOptionsInterface
{
    /**
     * Turn off strict options mode
     *
     * @var bool
     */
    protected $__strictMode__ = false;

    /**
     * @var \Zend\View\Renderer\RendererInterface
     */
    protected $renderer = 'Zend\View\Renderer\PhpRenderer';

    /**
     * @var array
     */
    protected $from;

    /**
     * @var string
     */
    protected $fromEmail;

    /**
     * @var array
     */
    protected $to;

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
     * Get Renderer
     *
     * @return \Zend\View\Renderer\RendererInterface
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * Set Renderer
     *
     * @param  \Zend\View\Renderer\RendererInterface $renderer
     * @return MailOptions
     */
    public function setRenderer(RendererInterface $renderer)
    {
        $this->renderer = $renderer;

        return $this;
    }

    /**
     * Get from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set from
     *
     * @param  array                              $from
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setFrom($from)
    {
        $this->from = $from;

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
     * @param  array                              $to
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTo($to)
    {
        $this->to = $to;

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
     * @param  string                             $subject
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
     * @param  string                             $template
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
     * @param  string                             $transportType
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
     * @param  array                              $transportConfig
     * @return \Mail\Options\MailOptionsInterface
     */
    public function setTransportConfig($transportConfig)
    {
        $this->transportConfig = $transportConfig;

        return $this;
    }
}
