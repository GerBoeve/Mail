<?php
/**
 * Mail Service Interface
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace Mail\Service;

interface MailServiceInterface
{
    public function send();
    public function render();
}
