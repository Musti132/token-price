<?php

namespace TokenPrice;

use TokenPrice\Exceptions\ApiKeyRequired;
use TokenPrice\Market\Traits\HasApiSecret;

class Token
{
    public $market;

    public function __construct(
        public string $name,
        public string $short
    ) {
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getShortName()
    {
        return $this->short;
    }

    public function slug()
    {
        return str_replace(' ', '-', strtolower($this->name));
    }

    public function setMarket($markets, string $apiKey = null)
    {
        if (is_array($markets)) {
            return $this->multipleMarkets($markets);
        }

        $reflection = (new \ReflectionClass($markets))->getTraits();

        if (in_array(HasApiSecret::class, array_keys($reflection))) {
            if ($apiKey === null) {
                throw new ApiKeyRequired("Api Key is required for this market");
            }
        }

        $class = new $markets($this, $apiKey);

        $this->markets = $class;

        return $class;
    }

    public function multipleMarkets(array $markets)
    {
        $class = null;
        $array = [];

        foreach ($markets as $market => $options) {
            if (is_array($options)) {
                $class = $market;
            } else {
                $class = $options;
            }

            $nameSplitted = explode('\\', $class);
            $marketName = end($nameSplitted);

            $reflection = (new \ReflectionClass($class))->getTraits();

            if (in_array(HasApiSecret::class, array_keys($reflection))) {
                if ($options['apiKey'] === null || !array_key_exists('apiKey', $options)) {
                    throw new ApiKeyRequired("Api Key is required for " . $marketName . " market");
                }

                $array[$marketName] = new $class($this, $options['apiKey']);
            } else {
                $array[$marketName] = new $class($this);
            }
        }

        return $this->markets = $array;
    }

    public function fetchPrices()
    {
        $prices = [];

        foreach ($this->markets as $market => $class) {
            $prices[$market] = $class->price();
        }

        return (object) $prices;
    }
}
