<?php

namespace Nilnice\MiniSms;

interface MessageInterface
{
    /**
     * Return content of message.
     *
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return string
     */
    public function getContent(SmsInterface $sms = null) : string;

    /**
     * Return the template ID of message.
     *
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return string
     */
    public function getTemplate(SmsInterface $sms = null) : string;

    /**
     * Return the template data of message.
     *
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return array
     */
    public function getData(SmsInterface $sms = null) : array;

    /**
     * Return all of the providers of message.
     *
     * @return array
     */
    public function getProviders() : array;
}
