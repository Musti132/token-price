<?php

namespace TokenPrice\Market\Traits;

use ReflectionClass;
use TokenPrice\Token;

trait HasPrices
{
    public function __construct(Token|array $tokens, string $apiKey = null)
    {
        $this->apiKey = $apiKey;

        
        $this->checkForApiRequirements();

        return $this->tokens = $tokens;
    }
    
    /**
     * Return price of token
     */
    public function getPrice(callable $callable = null) {}

    /**
     * Return name of market from where we get prices.
     */
    public function getMarketName()
    {
        return $this->marketName;
    }

    /**
     * Alias for getPrice() method
     */
    public function price(callable $function = null)
    {
        if(!$this->canCheckPrice($this->tokens->getShortName())) {
            $reflect = new ReflectionClass($this);

            $name = $reflect->getShortName();

            return (object)['error' => $name.' can\'t check prices other than: '. implode(', ', $this->only)];
        }

        return $this->getPrice($function);
    }
}
