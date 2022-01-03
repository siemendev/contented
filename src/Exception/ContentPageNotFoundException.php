<?php

namespace Contented\Exception;

use RuntimeException;
use Throwable;

class ContentPageNotFoundException extends RuntimeException
{
    public function __construct(string $page, ?Throwable $previous = null)
    {
        parent::__construct(sprintf('Could not find content module %s', $page), 0, $previous);
    }
}