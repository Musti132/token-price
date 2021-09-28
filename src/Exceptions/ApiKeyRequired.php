<?php
namespace TokenPrice\Exceptions;

use Exception;

class ApiKeyRequired extends Exception {
    public function __construct(string $message) {
        return parent::__construct($message);
    }
}
?>