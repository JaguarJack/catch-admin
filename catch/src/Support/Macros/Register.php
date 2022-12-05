<?php

declare(strict_types=1);

namespace Catch\Support\Macros;

/**
 * boot
 */
class Register
{
    /**
     * macros boot
     */
    public static function boot(): void
    {
        Blueprint::boot();

        Collection::boot();

        Builder::boot();
    }
}
