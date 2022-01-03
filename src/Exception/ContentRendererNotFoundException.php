<?php

namespace Contented\Exception;

use RuntimeException;

class ContentRendererNotFoundException extends RuntimeException
{
    public function __construct(string $subject)
    {
        parent::__construct(sprintf(
            'Could not find a content renderer to render %s. Either enable twig in your project or implement a custom renderer.',
            $subject
        ));
    }
}