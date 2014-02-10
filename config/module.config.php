<?php
/**
 * Module Config
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */

return [
    'service_manager' => [
        'factories' => [
            'Mail\Options\MailOptions'      => 'Mail\Factory\MailOptionsFactory',
            'Mail\Options\ModuleOptions'    => 'Mail\Factory\ModuleOptionsFactory',
            'Mail\Factory\TransportFactory' => 'Mail\Factory\TransportFactory',
            'Mail\Service\MailService'      => 'Mail\Service\Factory\MailServiceFactory'
        ]
    ],
    'mail_module' => [
        'template_paths' => [
            __DIR__ . '/../view/mail/templates'
        ]
    ]
];
