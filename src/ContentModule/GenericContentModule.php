<?php

namespace Contented\ContentModule;

class GenericContentModule extends AbstractContentModule
{
    public string $tag;

    public function getTag(): string
    {
        return $this->tag;
    }
}