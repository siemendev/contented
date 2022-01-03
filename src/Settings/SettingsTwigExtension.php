<?php

namespace Contented\Settings;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SettingsTwigExtension extends AbstractExtension
{
    public function __construct(private SettingsManager $manager)
    {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('contented_setting', [$this->manager, 'resolve']),
        ];
    }
}