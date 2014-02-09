<?php
namespace Mail\Factory;

use Mail\Exception\InvalidArgumentException;
use Mail\Options\ModuleOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ModuleOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['mail_module'])) {
            throw new InvalidArgumentException('Configuration key "mail_module" not found!');
        }

        if (!isset($config['mail_module']['template_paths'])) {
            throw new InvalidArgumentException('Configuration for template_paths not found in "mail_module"!');
        }

        $options = new ModuleOptions($config['mail_module']);

        return $options;
    }
}
