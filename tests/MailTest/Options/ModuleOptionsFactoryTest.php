<?php
namespace MailTest\Options;

use PHPUnit_Framework_TestCase;
use MailTest\Util\ServiceManagerFactory;

class ModuleOptionsFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $serviceManager;

    public function setUp()
    {
        $this->serviceManager = ServiceManagerFactory::getServiceManager();
    }

    public function testFactoryIsCreated()
    {
        $moduleOptions = $this->serviceManager->get('Mail\Options\ModuleOptions');

        $this->assertInstanceOf('Mail\Options\ModuleOptions', $moduleOptions);
    }
}
