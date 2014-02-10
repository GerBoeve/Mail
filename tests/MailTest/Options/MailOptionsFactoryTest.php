<?php
/**
 * Mail Options Factory Test
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace MailTest\Options;

use PHPUnit_Framework_TestCase;
use MailTest\Util\ServiceManagerFactory;

class MailOptionsFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $serviceManager;
    protected $mailOptions;

    public function setUp()
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
        $this->mailOptions    = $this->serviceManager->get('Mail\Options\MailOptions');
    }

    public function testFactoryIsCreated()
    {
        $this->assertInstanceOf('Mail\Options\MailOptions', $this->mailOptions);
    }
}
