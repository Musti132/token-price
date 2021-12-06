<?php

namespace TokenPrice;

use TokenPrice\Exceptions\ApiKeyRequired;
use TokenPrice\Market\Traits\HasApiSecret;

class Token
{
    public $market;

    public function __construct(
        public string $name,
        public string $short,
        public string $address = "",
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

    public function getAddress()
    {
        return $this->address;
    }

    public function slug()
    {
        return str_replace(' ', '-', strtolower($this->name));
    }

    /**
     * @param mixed $markets
     * @param string|null $apiKey
     * 
     * @throws \TokenPrice\Exceptions\ApiKeyRequired
     * 
     * @return mixed
     */
    public function setMarket($markets, string $apiKey = null)
    {
        if (is_array($markets)) {
            return $this->multipleMarkets($markets);
        }

        $traits = (new \ReflectionClass($markets))->getTraits();

        if (in_array(HasApiSecret::class, array_keys($traits))) {
            if ($apiKey === null) {
                throw new ApiKeyRequired("Api Key is required for this market");
            }
        }

        $class = new $markets($this, $apiKey);

        $this->markets = $class;

        return $class;
    }

    /**
     * @param array $markets
     * 
     * @throws \TokenPrice\Exceptions\ApiKeyRequired
     * 
     * @return mixed
     */
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

            if (is_array($options) && array_key_exists('function', $options)) {
                $array[$marketName]['function'] = $options['function'];
            }

            $traits = (new \ReflectionClass($class))->getTraits();

            if (in_array(HasApiSecret::class, array_keys($traits))) {
                if ($options['apiKey'] === null || !array_key_exists('apiKey', $options)) {
                    throw new ApiKeyRequired("Api Key is required for " . $marketName . " market");
                }

                $array[$marketName]['instance'] = new $class($this, $options['apiKey']);
            } else {
                $array[$marketName]['instance'] = new $class($this);
            }
        }

        return $this->markets = $array;
    }

    public function fetchPrices()
    {
        $prices = [];

        foreach ($this->markets as $market => $value) {
            $function = (array_key_exists('function', $value)) ? $value['function'] : null;

            $prices[$market] = $value['instance']->price($function);
        }

        return (object) $prices;
    }
}
