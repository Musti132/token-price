<?php
namespace TokenPrice\Market\Traits;

trait HasApiSecret {
    
    /**
     * Set API Key
     * 
     * @param string $apiKey
     */
    public function setApiKey(string $apiKey) {
        $this->apiKey = $apiKey;

        return $this;
    }
}
