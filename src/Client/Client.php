<?php
namespace TokenPrice\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;

Class Client {
    public $client;
    public $request;
    public $url;
    public $method;
    public array $options = [
        'http_errors' => false,
        'verify' => false,
    ];

    public function __construct() {
        $this->client = new HttpClient();
    }

    public function execute() {
        return $this->request = $this->client->request($this->method, $this->url, $this->options);
    }

    public function method(string $method) {
        $this->method = $method;

        return $this;
    }

    public function url(string $url) {
        $this->url = $url;

        return $this;
    }

    public function options(array $options) {
        $this->options += $options;

        return $this;
    }

    public function body() {
        return $this->request->getBody();
    }

    public function statusCode() {
        return $this->request->getStatusCode();
    }
}
