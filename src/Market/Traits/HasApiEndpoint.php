<?php
namespace TokenPrice\Market\Traits;

trait HasApiEndpoint {

    /**
     * Return base API endpoint
     * 
     * @return string $endpoint
     */
    public function endpoint()
    {
        return self::BASE_ENDPOINT;
    }

    public function setEndpoint(string $endpoint) {
        return $this->endpoint = $endpoint;
    }
}
