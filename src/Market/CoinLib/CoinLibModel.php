<?php
namespace TokenPrice\Market\CoinLib;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinLibModel {
    use IsModel;

    public function filter() {
        $decoded = json_decode(self::$data, true);

        if(empty($decoded)) {
            return $this->notFound();
        }

        /**
         * Filter out data and return price
         */
        
         return $decoded['price'];
    }

}
?>