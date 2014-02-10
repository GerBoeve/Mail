<?php
/**
 * Test configuration
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   http://opensource.org/licenses/BSD-3-Clause
 * @link      http://boevewebdevelopment.nl
 */
return [
    'modules' => [
        'Mail',
    ],
    'module_listener_options'   => [
        'config_glob_paths' => [
            __DIR__ . '/{,*.}{test}.php',
        ],
        'module_paths' => [
            'module',
            'vendor',
        ],
    ],
];
