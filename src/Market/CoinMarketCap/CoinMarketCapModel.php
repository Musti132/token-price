<?php
namespace TokenPrice\Market\CoinMarketCap;

use TokenPrice\Market\Traits\Model\IsModel;

class CoinMarketCapModel {
    use IsModel;

    public function filter() {
        $decoded = json_decode(self::$data, true);

        if($this->checkForError($decoded)) {
            return $this->notFound($decoded['status']['error_message']);
        }

        $id = array_key_first($decoded['data']);

        $filtered = $decoded
            ['data']
            [$id]
            ['quote']
            [self::$options['currencies']]
            ['price'];

        return $filtered;
    }

    public function checkForError(array $data) {
        if($data['status']['error_code'] != 0){
            return true;
        }

        return false;
    }
}
?>