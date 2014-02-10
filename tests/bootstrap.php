<?php
/**
 * Bootstrap
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
/**
 * Bootstrap PHPUnit
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   http://opensource.org/licenses/BSD-3-Clause
 * @link      http://boevewebdevelopment.nl
 */
use MailTest\Util\ServiceManagerFactory;

$loader = require __DIR__ . '/../../../vendor/autoload.php';
$loader->add('MailTest\\', __DIR__);

$config = [];

if (file_exists(__DIR__ . '/test.config.php')) {
    $config = require __DIR__ . '/test.config.php';
}

ServiceManagerFactory::setConfig($config);
