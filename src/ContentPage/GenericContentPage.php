<?php

namespace Contented\ContentPage;

class GenericContentPage extends AbstractContentPage
{
    public string $layout;
    public array $areas = [];

    public function getLayout(): string
    {
        return $this->layout;
    }

    public function getAreas(): array
    {
        return $this->areas;
    }
}