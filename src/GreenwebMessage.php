<?php

namespace Sikhlana\GreenwebSmsChannel;

class GreenwebMessage
{
    /**
     * The message content.
     *
     * @var  string
     */
    private $content;

    /**
     * Individual lines for the content to be built.
     *
     * @var  array
     */
    private $lines = [];

    /**
     * Create a new message instance.
     *
     * @param  string $content
     */
    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * Add a line of text to the notification.
     *
     * @param string $line
     * @return $this
     */
    public function line($line = '')
    {
        $this->lines[] = $line;

        return $this;
    }

    /**
     * Builds the message to a string.
     *
     * @return string
     */
    public function buildMessage()
    {
        if (! empty($this->lines)) {
            if (! empty($this->content)) {
                $this->content .= "\r\n";
            }

            $this->content .= implode("\r\n", $this->lines);
        }

        return $this->content;
    }

    /**
     * Set the message content.
     *
     * @param  string $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }
}