<?php
namespace TokenPrice\Market\CoinMarketCap;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinMarketCapModel {
    use IsModel;

    public function filter() {
        $filtered = json_decode(self::$data, true)
            ['data'][1]['quote']
            [self::$options['currencies']]
            ['price'];

        return number_format($filtered, 2);
    }
}
?>