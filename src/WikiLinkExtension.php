<?php

declare(strict_types=1);

namespace Jnjxp\CommonMarkWikiLinks;

use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Environment\EnvironmentBuilderInterface;

class WikiLinkExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new WikiLinkParser(), 100);
    }
}
