<?php

namespace TokenPrice\Market\CoinLib;

use TokenPrice\Market\Market;
use TokenPrice\Market\CoinLib\CoinLibModel;
use TokenPrice\Client\Client;
use TokenPrice\Market\Interfaces\ApiEndpointInterface;
use TokenPrice\Market\Interfaces\ApiSecretInterface;
use TokenPrice\Market\Traits\HasApiEndpoint;
use TokenPrice\Market\Traits\HasApiSecret;
use TokenPrice\Token;

class CoinLib extends Market implements ApiEndpointInterface, ApiSecretInterface
{
    use HasApiEndpoint, HasApiSecret;

    /**
     * API Key for market
     */
    protected $apiKey = null;

    /**
     * Base API Endpoint
     * e.g https://pro-api.coinmarketcap.com/
     */
    private const BASE_ENDPOINT = "https://coinlib.io/";

    /**
     * Path to grab price
     * e.g v1/cryptocurrency/quotes/latest
     */
    private $path = "api/v1/coin";

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

        $model = CoinLibModel::create($request->body(), [
            'currencies' => $this->currencies,
        ]);

        return $model->filter();
    }

    public function executeRequest() {
        $client = new Client();

        $options = [
            'query' => [
                'key' => $this->apiKey,
                'pref' => $this->currencies,
                'symbol' => $this->tokens->getShortName(),
            ],
        ];

        return $client->method('GET')
            ->url($this->endpoint() . $this->path)
            ->options($options)
            ->execute();
    }
}
