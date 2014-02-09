<?php
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
