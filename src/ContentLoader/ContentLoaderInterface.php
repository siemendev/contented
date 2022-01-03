<?php

namespace Contented\ContentLoader;

use Contented\ContentModule\ContentModuleInterface;
use Contented\ContentPage\ContentPageInterface;
use Contented\Exception\ContentModuleNotFoundException;
use Contented\Exception\ContentPageNotFoundException;

interface ContentLoaderInterface
{
    // todo maybe rename these methods to prefix with "generate" or "load"

    /**
     * @throws ContentPageNotFoundException
     */
    public function getContentPage(array $config): ContentPageInterface;

    /**
     * @throws ContentModuleNotFoundException
     */
    public function getContentModule(array $config): ContentModuleInterface;
}