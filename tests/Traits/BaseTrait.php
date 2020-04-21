<?php

declare(strict_types=1);

namespace Frontwise\Ar\Test\Traits;

trait BaseTrait
{
    /**
     * Print to console from phpunit test
     */
    protected function printToConsole($msg)
    {
        fwrite(STDERR, print_r($msg, TRUE));
    }
}
