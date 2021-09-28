<?php

namespace TokenPrice\Market\Interfaces;

interface ApiEndpointInterface
{
    /**
     * Return base API endpoint
     */
    public function endpoint();

    /**
     * Set new endpoint
     */
    public function setEndpoint(string $endpoint);
}
