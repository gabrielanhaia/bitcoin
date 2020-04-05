<?php

namespace App\Helpers;

use Illuminate\Support\Str;

/**
 * Class Token
 * @package App\Helpers
 */
class Token
{
    /**
     * Generate a API token.
     *
     * @return string
     */
    public function generateApiToken(): string
    {
        return Str::random(60);
    }
}
