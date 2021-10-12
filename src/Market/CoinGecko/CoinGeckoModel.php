<?php

namespace TokenPrice\Market\CoinGecko;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinGeckoModel
{
    use IsModel;

    public function filter()
    {
        $decoded = json_decode(self::$data, true);

        if(empty($decoded)) {
            return $this->notFound();
        }

        $filtered = $decoded
            [self::$options['slug']]
            [strtolower(self::$options['currencies'])];

        return $filtered;
    }
}
