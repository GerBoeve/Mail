<?php
/**
 * Attachment Trait
 *
 * @author    Hardie Boeve (hdboeve@boevewebdevelopment.nl)
 * @copyright 2014 Boeve Web Development
 * @license   LICENSE
 * @link      http://boevewebdevelopment.nl
 */
namespace Mail\Service;

use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;

trait AttachmentTrait
{
    protected $attachments;

    /**
     * Add attachment
     *
     * The param must contain the full path to the file.
     *
     * @param  multitype                        $attachment
     * @return \Mail\Service\AtachmentInterface
     */
    public function addAttachment($attachment)
    {
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Get attachments
     *
     * @return array
    */
    protected function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Prepare attachments
     *
     * @return \Zend\Mime\Part
    */
    protected function prepareAttachments()
    {
        if ($this->getAttachments()) {
            $type  = new \finfo(FILEINFO_MIME_TYPE);
            $mimeParts = [];

            foreach ($this->getAttachments() as $attachment) {
                if (is_file($attachment)) {
                    $part              = new MimePart(fopen($attachment, 'r'));
                    $part->filename    = basename($attachment);
                    $part->type        = $type->file($attachment);
                    $part->encoding    = Mime::ENCODING_BASE64;
                    $part->disposition = Mime::DISPOSITION_ATTACHMENT;

                    $mimeParts[] = $part;
                } else {
                    continue;
                }
            }

            return $mimeParts;
        }

        return false;
    }
}
