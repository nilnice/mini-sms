<?php

namespace Nilnice\MiniSms;

final class Constant
{
    /**
     * 公共常量
     */
    public const COMMON_FORMAT = 'json';

    /**
     * 互亿无线短信供应商
     */
    public const HUYI_URI = 'http://106.ihuyi.cn/webservice/sms.php?method=Submit';
    public const HUYI_FORMAT = 'json';

    /**
     * 快用云短信供应商
     */
    public const KUAIYONGYUN_URI = 'http://210.51.191.35:8080/eums/sms/send.do';
}
