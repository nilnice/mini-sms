<?php

namespace Nilnice\MiniSms\Sms;

use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Nilnice\MiniSms\Constant;
use Nilnice\MiniSms\Exception\InvalidSmsException;
use Nilnice\MiniSms\MessageInterface;

class HuyiSms extends AbstractSms
{
    protected const HUYI_SUCCESS_CODE = 2;

    /**
     * Get the name of the SMS provider.
     *
     * @return string
     */
    public function getName() : string
    {
        return 'huyi';
    }

    /**
     * Send SMS using the `Huyi` provider.
     *
     * @param string                            $to
     * @param \Nilnice\MiniSms\MessageInterface $message
     * @param \Illuminate\Config\Repository     $config
     *
     * @return array
     *
     * @throws \Nilnice\MiniSms\Exception\InvalidSmsException
     * @throws \RuntimeException
     */
    public function send(
        string $to,
        MessageInterface $message,
        Repository $config
    ) : array {
        $parameter = [
            'account' => $config->get('app_id'),
            'mobile'  => $to,
            'content' => $message->getContent(),
            'time'    => time(),
            'format'  => Constant::HUYI_FORMAT,
        ];
        $apiKey = $config->get('app_key');
        $parameter['password'] = $this->generateSign($parameter, $apiKey);
        $result = $this->post(Constant::HUYI_URI, $parameter);
        $code = Arr::get($result, 'code');

        if (self::HUYI_SUCCESS_CODE !== $code) {
            throw new InvalidSmsException($result['msg'], $result['code']);
        }

        return $result;
    }

    /**
     * Generate password signature.
     *
     * @param array  $array
     * @param string $key
     *
     * @return string
     */
    protected function generateSign(array $array, string $key) : string
    {
        $account = Arr::get($array, 'account');
        $mobile = Arr::get($array, 'mobile');
        $content = Arr::get($array, 'content');
        $time = Arr::get($array, 'time');

        return md5($account . $key . $mobile . $content . $time);
    }
}
