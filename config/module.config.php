<?php
return [
    'service_manager' => [
        'factories' => [
            'Mail\Options\MailOptions'                => 'Mail\Options\Factory\MailOptionsFactory',
            'Mail\Options\ModuleOptions'              => 'Mail\Options\Factory\ModuleOptionsFactory',
            'Mail\Transport\Factory\TransportFactory' => 'Mail\Transport\Factory\TransportFactory'
        ]
    ],
    'mail_module' => [
        'template_paths' => [
            __DIR__ . '/../view/mail/templates/'
        ]
    ]
];