<?php

namespace Nilnice\MiniSms;

use Illuminate\Config\Repository;

interface SmsInterface
{
    /**
     * Get the name of the SMS provider.
     *
     * @return string
     */
    public function getName() : string;

    /**
     * Send a short message.
     *
     * @param string                            $to
     * @param \Nilnice\MiniSms\MessageInterface $message
     * @param \Illuminate\Config\Repository     $config
     *
     * @return array
     */
    public function send(
        string $to,
        MessageInterface $message,
        Repository $config
    ) : array;
}
