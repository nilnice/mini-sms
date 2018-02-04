<?php

namespace Nilnice\MiniSms\Scheme;

use Nilnice\MiniSms\SchemeInterface;

class RandomScheme implements SchemeInterface
{
    /**
     * @param array $providers
     *
     * @return array
     */
    public function withSchema(array $providers) : array
    {
        $array = array_keys($providers);
        shuffle($array);

        return $array;
    }
}
