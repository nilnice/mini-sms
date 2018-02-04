<?php

namespace Nilnice\MiniSms;

interface SchemeInterface
{
    /**
     * Returns the result of the use scheme.
     *
     * @param array $providers
     *
     * @return array
     */
    public function withSchema(array $providers) : array;
}
