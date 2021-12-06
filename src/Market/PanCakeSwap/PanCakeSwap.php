<?php

namespace TokenPrice\Market\PanCakeSwap;

use TokenPrice\Market\Market;
use TokenPrice\Market\PanCakeSwap\PanCakeSwapModel;
use TokenPrice\Client\Client;
use TokenPrice\Market\Interfaces\ApiEndpointInterface;
use TokenPrice\Market\Traits\HasApiEndpoint;
use TokenPrice\Token;

class PanCakeSwap extends Market implements ApiEndpointInterface
{
    use HasApiEndpoint;

    /**
     * Base API Endpoint
     * e.g https://pro-api.coinmarketcap.com/
     */
    private const BASE_ENDPOINT = "https://api.pancakeswap.info/";

    /**
     * Path to grab price
     * e.g v1/cryptocurrency/quotes/latest
     */
    private $path = "api/v2/tokens/";

    public $tokens = null;

    public $addressOnly = true;

    /**
     * Return price of token
     */
    public function getPrice(callable $function = null)
    {
        //dd($this->canCheckWithName($this->tokens->getAddress()));
        if(!$this->canCheckWithName($this->tokens->getAddress())) {
            return ['error' => 'PanCakeSwap can only check prices on the BSC network with contract address.'];
        }

        if($this->isValidAddress($this->tokens->getAddress()) === false){
            return ['error' => 'Please provide a valid contract address for PanCakeSwap'];
        }

        if(!$this->canCheckPrice($this->tokens->getShortName())) {
            return ['error' => 'PanCakeSwap can\'t check prices other than: '. implode(',', $this->only)];
        }

        $client = new Client();

        $options = [
            'query' => [],
        ];
        
        $client->method('GET')
            ->url($this->endpoint() . $this->path . $this->tokens->getAddress())
            ->options($options)
            ->execute();

        if(is_callable($function)) {
            return $function(json_decode($client->body(), true));
        }

        $model = PanCakeSwapModel::create($client->body(), [
            'currencies' => $this->currencies,
        ]);

        return $model->filter();
    }
}
