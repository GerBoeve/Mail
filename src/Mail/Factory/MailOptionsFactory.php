<?php
/**
 * Mail Options Factory
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace Mail\Factory;

use Mail\Exception\InvalidArgumentException;
use Mail\Options\MailOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');

        if (!isset($config['mail_config'])) {
            throw new InvalidArgumentException('Configuration key "mail_config" not found!');
        }

        $options = new MailOptions($config['mail_config']);

        return $options;
    }
}
