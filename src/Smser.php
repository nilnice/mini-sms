<?php

namespace Nilnice\MiniSms;

use Illuminate\Config\Repository;
use Illuminate\Support\Collection;

class Smser
{
    public const SEND_SMS_SUCCESS = 'success';
    public const SEND_SMS_FAILURE = 'failure';

    /**
     * @var \Nilnice\MiniSms\MiniSms
     */
    protected $miniSms;

    /**
     * Smser constructor.
     *
     * @param \Nilnice\MiniSms\MiniSms $miniSms
     */
    public function __construct(MiniSms $miniSms)
    {
        $this->miniSms = $miniSms;
    }

    /**
     * Send a message.
     *
     * @param string                            $to
     * @param \Nilnice\MiniSms\MessageInterface $message
     * @param array                             $providers
     *
     * @return Collection
     *
     * @throws \InvalidArgumentException
     */
    public function send(
        string $to,
        MessageInterface $message,
        array $providers = []
    ) : Collection {
        if (empty($providers)) {
            $providers = $message->getProviders();
        }

        if (empty($providers)) {
            $providers = $this->miniSms
                ->getConfig()
                ->get('default.provider', []);
        }

        $providers = $this->withProviders($providers);
        $schemes = $this->miniSms->getScheme()->withSchema($providers);

        $result = [];
        $isSuccess = false;
        foreach ($schemes as $scheme) {
            try {
                $config = new Repository($providers[$scheme]);
                $return = $this->miniSms
                    ->getSmsProvider($scheme)
                    ->send($to, $message, $config);
                $result[$scheme] = [
                    'status' => self::SEND_SMS_SUCCESS,
                    'result' => $return,
                ];
                $isSuccess = true;
                break;
            } catch (\Exception $e) {
                $result[$scheme] = [
                    'status' => self::SEND_SMS_FAILURE,
                    'result' => $e->getMessage(),
                ];

                continue;
            }
        }

        if (! $isSuccess) {
            throw new \InvalidArgumentException($result);
        }

        return new Collection($result);
    }

    /**
     * @param array $providers
     *
     * @return array
     */
    protected function withProviders(array $providers) : array
    {
        $array = [];
        $config = $this->miniSms->getConfig();

        foreach ($providers as $provider => $item) {
            if (\is_int($provider) && \is_string($item)) {
                $provider = $item;
                $item = [];
            }
            $array[$provider] = $item;
            $default = $config->get("provider.{$provider}", []);

            if (\is_string($provider)
                && ! empty($default)
                && \is_array($default)
            ) {
                $array[$provider] = array_merge($default, $item);
            }
        }

        return $array;
    }
}
