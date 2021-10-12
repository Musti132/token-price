<?php

namespace TokenPrice\Market\CoinGecko;

use TokenPrice\Market\Market;
use TokenPrice\Client\Client;
use TokenPrice\Market\Interfaces\ApiEndpointInterface;
use TokenPrice\Market\Traits\HasApiEndpoint;
use TokenPrice\Market\CoinGecko\CoinGeckoModel;
use TokenPrice\Token;

class CoinGecko extends Market implements ApiEndpointInterface
{
    use HasApiEndpoint;
    
    /**
     * Base API Endpoint
     */
    private const BASE_ENDPOINT = "https://api.coingecko.com/api/v3/";

    /**
     * Path to grab price
     */
    private $path = "simple/price";

    public $tokens = null;

    /**
     * Return price of token
     */
    public function getPrice(callable $function = null)
    {
        $request = $this->executeRequest();

        if (is_callable($function)) {
            return $function(json_decode($request->body(), true));
        }

        $model = CoinGeckoModel::create($request->body(), [
            'currencies' => strtolower($this->currencies),
            'slug' => $this->tokens->slug(),
        ]);

        return $model->filter();
    }

    public function executeRequest() {
        $client = new Client();

        $options = [
            'query' => [
                'ids' => $this->tokens->slug(),
                'vs_currencies' => $this->currencies
            ],
        ];

        return $client->method('GET')
            ->url($this->endpoint() . $this->path)
            ->options($options)
            ->execute();
    }
}
