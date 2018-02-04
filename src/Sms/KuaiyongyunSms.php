<?php

namespace Nilnice\MiniSms\Sms;

use Illuminate\Config\Repository;
use Illuminate\Support\Str;
use Nilnice\MiniSms\Constant;
use Nilnice\MiniSms\Exception\InvalidSmsException;
use Nilnice\MiniSms\MessageInterface;

class KuaiyongyunSms extends AbstractSms
{
    protected const KUAIYONGYUN_SUCCESS_CODE = 'success';

    /**
     * Get the name of the SMS provider.
     *
     * @return string
     */
    public function getName() : string
    {
        return 'kuaiyongyun';
    }

    /**
     * Send SMS using the `Kuaiyongyun` provider.
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
        $content = $message->getContent();
        $content = mb_convert_encoding($content, self::E_GB2312, self::E_UTF8);
        $parameter = [
            'name'    => $config->get('app_id'),
            'seed'    => date('YmdHis'),
            'dest'    => $to,
            'content' => $content,
        ];
        $appKey = $config->get('app_key');
        $parameter['key'] = strtolower(md5(md5($appKey) . $parameter['seed']));
        $result = $this->get(Constant::KUAIYONGYUN_URI, $parameter);
        $msg = Str::before($result, ':');
        $code = Str::after($result, ':');

        if ($msg !== self::KUAIYONGYUN_SUCCESS_CODE) {
            throw new InvalidSmsException($msg, $code);
        }
        $result = ['msg' => $msg, 'code' => $code];

        return $result;
    }
}
