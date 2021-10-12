<?php

namespace TokenPrice\Market\CoinMarketCap;

use TokenPrice\Market\Market;
use TokenPrice\Market\CoinMarketCap\CoinMarketCapModel;
use TokenPrice\Market\Interfaces\ApiSecretInterface;
use TokenPrice\Market\Traits\HasApiSecret;
use TokenPrice\Client\Client;
use TokenPrice\Market\Interfaces\ApiEndpointInterface;
use TokenPrice\Market\Traits\HasApiEndpoint;
use TokenPrice\Token;

class CoinMarketCap extends Market implements ApiSecretInterface, ApiEndpointInterface
{
    use HasApiSecret, HasApiEndpoint;

    /**
     * API Key for market
     */
    protected $apiKey = null;

    /**
     * Base API Endpoint
     */
    private const BASE_ENDPOINT = "https://pro-api.coinmarketcap.com/";

    /**
     * Path to grab price
     */
    private $path = "v1/cryptocurrency/quotes/latest";

    public $tokens = null;

    protected bool $requiresKey = true;

    /**
     * Return price of token
     */
    public function getPrice(callable $function = null)
    {
        $request = $this->executeRequest();

        if(is_callable($function)) {
            return $function(json_decode($request->body(), true));
        }

        $model = CoinMarketCapModel::create($request->body(), [
            'currencies' => $this->currencies,
        ]);

        return $model->filter();
    }

    public function executeRequest() {
        $client = new Client();

        $options = [
            'query' => [
                'CMC_PRO_API_KEY' => $this->apiKey,
                'slug' => $this->tokens->slug(),
                'convert' => $this->currencies,
            ],
        ];
        
        return $client->method('GET')
            ->url($this->endpoint() . $this->path)
            ->options($options)
            ->execute();
    }
}
