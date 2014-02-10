<?php
/**
 * Transport Factory Test
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace MailTest\Factory;

use MailTest\Util\ServiceManagerFactory;
use PHPUnit_Framework_TestCase;

class TransportFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    protected $mailOptions;

    public function setUp()
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
        $this->mailOptions    = $this->serviceManager->get('Mail\Options\MailOptions');
    }

    public function testServiceSetsCorrectMailTransport()
    {
        $this->mailOptions->setTransportType('mail');

        $this->assertInstanceOf('Zend\Mail\Transport\Sendmail', $this->serviceManager->get('Mail\Factory\TransportFactory'));
    }

    public function testServiceSetsCorrectSmtpTransport()
    {
        $this->mailOptions->setTransportType('smtp');
        $this->mailOptions->setTransportConfig([
            'name' => 'localhost.localdomain',
            'host' => '127.0.0.1',
            'port' => 25,
        ]);

        $this->assertInstanceOf('Zend\Mail\Transport\Smtp', $this->serviceManager->get('Mail\Factory\TransportFactory'));
    }

    public function testServiceSetsCorrectFileTransport()
    {
        $this->mailOptions->setTransportType('file');
        $this->mailOptions->setTransportConfig(['path' => getcwd() . '/../../../data/log/']);

        $this->assertInstanceOf('Zend\Mail\Transport\File', $this->serviceManager->get('Mail\Factory\TransportFactory'));
    }
}
