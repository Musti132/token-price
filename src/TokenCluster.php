<?php

namespace TokenPrice;

use TokenPrice\Exceptions\InvalidToken;

class TokenCluster
{
    public function __construct(public array $tokens)
    {
        foreach ($tokens as $token) {
            if (!$token instanceof Token) {
                throw new InvalidToken("{$token} is not a instance of TokenPrice\Token");
            }
        }
    }

    public function getTokens()
    {
        return $this->tokens;
    }
}
