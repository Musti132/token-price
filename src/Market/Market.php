<?php

namespace TokenPrice\Market;

use TokenPrice\Exceptions\ApiKeyRequired;
use TokenPrice\Market\Interfaces\MarketInterface;
use TokenPrice\Market\Traits\HasPrices;

class Market implements MarketInterface
{
    use HasPrices;

    /**
     * Set default conversion to USD
     */
    protected $currencies = "USD";

    /**
     * Token prices we can get from a market.
     * Change if a market can only check specific cryptos, only using short names
     * e.g BTC, XRP, ETH...
     */
    protected array $only = [];

    /**
     * Determine if market requires a API Key
     */
    protected bool $requiresKey = false;

    /**
     * API Key for market
     */
    protected $apiKey = null;

    protected $currencySeperator = ",";

    public function currency(string|array $currencies)
    {
        if (is_array($currencies)) {
            return $this->currencies = implode($this->currencySeperator, $currencies);
        }

        return $this->currencies = $currencies;
    }

    public function requiresApiKey(): bool
    {
        if ($this->requiresKey) {
            return true;
        }

        return false;
    }

    public function canCheckPrice(string $crypto): bool
    {
        if (count($this->only) === 0) {
            return true;
        }

        if (in_array($crypto, $this->only)) {
            return true;
        }

        return false;
    }

    public function checkForApiRequirements()
    {
        if ($this->requiresApiKey() && $this->apiKey === null) {
            throw new ApiKeyRequired("A API key is required for this market");
        }
    }
}