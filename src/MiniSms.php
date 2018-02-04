<?php

namespace Nilnice\MiniSms;

use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Nilnice\MiniSms\Scheme\RandomScheme;

class MiniSms
{
    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var \Nilnice\MiniSms\Smser
     */
    protected $smser;

    /**
     * @var array
     */
    protected $schemes = [];

    /**
     * @var array
     */
    protected $providers = [];

    /**
     * @var string
     */
    protected $defaultProvider;

    /**
     * MiniSms constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (! empty($default = Arr::get($config, 'default'))) {
            $this->setDefaultProvider('default');
        }
        $this->config = new Repository($config);
    }

    /**
     * Send a short message.
     *
     * @param string $to
     * @param array  $message
     * @param array  $providers
     *
     * @return \Illuminate\Support\Collection
     *
     * @throws \InvalidArgumentException
     */
    public function send(
        string $to,
        array $message,
        array $providers = []
    ) : Collection {
        return $this->getSmser()->send($to, new Message($message), $providers);
    }

    /**
     * @return \Illuminate\Config\Repository
     */
    public function getConfig() : Repository
    {
        return $this->config;
    }

    /**
     * Get provider of message.
     *
     * @param string|null $name
     *
     * @return \Nilnice\MiniSms\SmsInterface
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function getSmsProvider($name = null) : SmsInterface
    {
        $name = $name ?: $this->getDefaultProvider();

        if (! isset($this->providers[$name])) {
            $this->providers[$name] = $this->createSms($name);
        }

        return $this->providers[$name];
    }

    /**
     * Get a scheme instance.
     *
     * @param string|null $scheme
     *
     * @return \Nilnice\MiniSms\SchemeInterface
     * @throws \InvalidArgumentException
     */
    public function getScheme($scheme = null) : SchemeInterface
    {
        if (null === $scheme) {
            $scheme = $this->config->get(
                'default.scheme',
                RandomScheme::class
            );
        }

        if (! class_exists($scheme)) {
            $scheme = __NAMESPACE__ . '\\Schema\\' . Str::studly($scheme);
        }

        if (! class_exists($scheme)) {
            throw new \InvalidArgumentException('Invalid [schema] argument.');
        }

        if (empty($this->schemes[$scheme])
            || ! ($this->schemes[$scheme] instanceof SchemeInterface)
        ) {
            $this->schemes[$scheme] = new $scheme($this);
        }

        return $this->schemes[$scheme];
    }

    /**
     * @return \Nilnice\MiniSms\Smser
     */
    public function getSmser() : Smser
    {
        return $this->smser ?: $this->smser = new Smser($this);
    }

    /**
     * Set default provider.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setDefaultProvider(string $name)
    {
        $this->defaultProvider = $name;

        return $this;
    }


    /**
     * Get default provider name.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    public function getDefaultProvider() : string
    {
        if (empty($this->defaultProvider)) {
            throw new \RuntimeException('Default provider is not configured.');
        }

        return $this->defaultProvider;
    }

    /**
     * Create a SMS provider.
     *
     * @param string $name
     *
     * @return \Nilnice\MiniSms\SmsInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function createSms(string $name) : SmsInterface
    {
        if (class_exists($name)) {
            return $name;
        }
        $name = ucfirst(str_replace(['-', '_'], '', $name));
        $name = __NAMESPACE__ . "\\Sms\\{$name}Sms";

        $key = "provider.{$name}";
        $gateway = $this->makeSms($name, $this->config->get($key, []));

        if (! $gateway instanceof SmsInterface) {
            throw new \InvalidArgumentException("Invalid [$gateway] argument.");
        }

        return $gateway;
    }

    /**
     * Make SMS instance.
     *
     * @param string $name
     * @param array  $config
     *
     * @return \Nilnice\MiniSms\SmsInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function makeSms(
        string $name,
        array $config
    ) : SmsInterface {
        if (! class_exists($name)) {
            throw new \InvalidArgumentException("Sms provider [{$name}] not exists.");
        }

        return new $name($config);
    }
}
