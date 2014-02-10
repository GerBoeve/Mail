<?php
/**
 * Mail Test
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
return [
    'mail_config' => [

        /**
         * Default email sender
         */
        'from_name'  => 'Hardie Boeve',
        'from_email' => 'hdboeve@boevewebdevelopment.nl',

        /**
         * Default email receiver(s)
         */
        'to' => [
            [
                'name'  => 'Hardie Boeve',
                'email' => 'hdboeve@boevewebdevelopment.nl'
            ]
        ],

        /**
         * Transport type
         *
         * Available options: mail, smtp, file (see transport config)
         *
         */
        'transport_type' => 'mail',

        /**
         *  Transport config
         *
         *  mail:
         *   - uses  : Zend\Mail\Transport\Sendmail
         *   - config: emty array, no further configuration needed
         *
         *  smtp:
         *   - uses  : Zend\Mail\Transport\Smtp
         *   - config: array, for available configuration options go to http://zf2.readthedocs.org/en/latest/modules/zend.mail.smtp.options.html
         *
         *  file:
         *   - uses  : Zend\Mail\Transport\FileOptions
         *   - config: array, for available configuration options go to http://zf2.readthedocs.org/en/latest/modules/zend.mail.file.options.html
         */
        'transport_config' => [],

        /**
         * Default template
         */
        'template' => 'default.phtml',

        /**
         * Template paths
         *
         * For custom templates, copy the folowing lines to the module.config.php of the appropriate module
         *
         * 'mail_module' => [
         *     'template_paths' => [
         *         __DIR__ . '/../view/mail/templates/'
         *     ]
         * ]
        */
    ]
];
