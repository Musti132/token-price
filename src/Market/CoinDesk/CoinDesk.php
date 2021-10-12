<?php

namespace TokenPrice\Market\CoinDesk;

use TokenPrice\Market\Market;
use TokenPrice\Client\Client;
use TokenPrice\Market\CoinDesk\CoinDeskModel;
use TokenPrice\Market\Interfaces\ApiEndpointInterface;
use TokenPrice\Market\Traits\HasApiEndpoint;
use TokenPrice\Token;

class CoinDesk extends Market implements ApiEndpointInterface
{
    use HasApiEndpoint;

    protected array $only = [
        'BTC',
    ];

    /**
     * Base API Endpoint
     */
    private const BASE_ENDPOINT = "https://api.coindesk.com/";

    /**
     * Path to grab price
     */
    private $path = "v1/bpi/currentprice/";

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

        $model = CoinDeskModel::create($request->body(), [
            'currencies' => $this->currencies,
        ]);

        return $model->filter();
    }

    public function executeRequest() {
        $client = new Client();

        /**
         * HTTP GET parameters
         */
        $options = [
            'query' => [
                'convert' => $this->currencies,
            ],
        ];

        return $client->method('GET')
            ->url($this->endpoint() . $this->path . $this->currencies)
            ->options($options)
            ->execute();
    }
}
