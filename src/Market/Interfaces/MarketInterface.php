<?php

namespace TokenPrice\Market\Interfaces;

use TokenPrice\Token;

interface MarketInterface
{
    /**
     * Return price of token
     */
    public function getPrice(callable $callable = null);

    /**
     * Return name of market from where we get prices.
     */
    public function getMarketName();

    /**
     * Alias for getPrice() method
     */
    public function price();

    public function __construct(Token|array $tokens);
}
