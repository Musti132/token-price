<?php
namespace TokenPrice\Market\PanCakeSwap;

use TokenPrice\Market\Traits\Model\IsModel;

class PanCakeSwapModel {
    use IsModel;

    public function filter() {
        $decoded = json_decode(self::$data, true);

        if(empty($decoded)) {
            return $this->notFound();
        }

        /**
         * Filter out data and return price
         */
         
        return $decoded['data']['price'];
        
    }

}
?>