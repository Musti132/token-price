<?php

namespace TokenPrice\Market\CoinDesk;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinDeskModel
{
    use IsModel;

    public function filter()
    {
        $filtered = json_decode(self::$data, true)['bpi']['USD']['rate'];

        return number_format((float) $filtered, 2);
    }
}
