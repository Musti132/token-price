<?php

namespace TokenPrice\Market\CoinGecko;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinGeckoModel
{
    use IsModel;

    public function filter()
    {
        $filtered = json_decode(self::$data, true)
            [self::$options['slug']]
            [strtolower(self::$options['currencies'])];

        return number_format($filtered, 2);
    }
}
