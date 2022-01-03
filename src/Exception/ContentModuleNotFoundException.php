<?php

namespace Contented\Exception;

use RuntimeException;
use Throwable;

class ContentModuleNotFoundException extends RuntimeException
{
    public function __construct(string $module, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Could not find content module %s', $module), 0, $previous);
    }
}