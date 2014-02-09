<?php
namespace Mail\Service;

interface MailServiceInterface
{
    public function send();
    public function render();
}
