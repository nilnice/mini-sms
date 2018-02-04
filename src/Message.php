<?php

namespace Nilnice\MiniSms;

class Message implements MessageInterface
{
    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $data = [];

    /**
     * Message constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }

    /**
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return string
     */
    public function getContent(?SmsInterface $sms = null) : string
    {
        return $this->content;
    }

    /**
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return string
     */
    public function getTemplate(?SmsInterface $sms = null) : string
    {
        return $this->template;
    }

    /**
     * @param \Nilnice\MiniSms\SmsInterface|null $sms
     *
     * @return array
     */
    public function getData(?SmsInterface $sms = null) : array
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getProviders() : array
    {
        return $this->providers;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function __isset(string $property)
    {
        return isset($this->{$property});
    }

    /**
     * @param string $property
     * @param mixed  $value
     */
    public function __set(string $property, $value)
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }
    }

    /**
     * @param string $property
     *
     * @return mixed
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
    }
}
