<?php
/**
 * Attachment Interface
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace Mail\Service;

interface AttachmentInterface
{
    /**
     * Add attachment
     *
     * @param string $attachment
     */
    public function addAttachment($attachment);
}
