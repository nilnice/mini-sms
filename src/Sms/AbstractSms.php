<?php

namespace Nilnice\MiniSms\Sms;

use Illuminate\Config\Repository;
use Nilnice\MiniSms\SmsInterface;
use Nilnice\MiniSms\Traits\RequestTrait;

abstract class AbstractSms implements SmsInterface
{
    use RequestTrait;

    protected const E_GB2312 = 'GB2312';
    protected const E_UTF8 = 'UTF-8';

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * AbstractSms constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = new Repository($config);
    }

    /**
     * Set user config.
     *
     * @param \Illuminate\Config\Repository $config
     *
     * @return $this
     */
    public function setConfig(Repository $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * Get user config.
     *
     * @return \Illuminate\Config\Repository
     */
    public function getConfig() : Repository
    {
        return $this->config;
    }
}
