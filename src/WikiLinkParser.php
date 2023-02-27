<?php

declare(strict_types=1);

namespace Jnjxp\CommonMarkWikiLinks;

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Parser\InlineParserContext;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;

class WikiLinkParser implements InlineParserInterface
{
    protected $prefix = '/';

    public function __construct(string $prefix = '/')
    {
        $this->prefix = $prefix;
    }

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex('\[\[([^\]]+)\]\]');
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $cursor = $inlineContext->getCursor();
        [$text] = $inlineContext->getSubMatches();

        if (! $text) {
            return false;
        }

        $cursor->advanceBy($inlineContext->getFullMatchLength());
        $link = $this->getLink($text);
        $inlineContext->getContainer()->appendChild($link);
        return true;
    }

    protected function getLink(string $text) : Link
    {
        $target = $text;
        if (str_contains($text, '|')) {
            [$target, $text] = explode('|', $text, 2);
        }
        return new Link($this->url($target), trim($text));
    }

    protected function url(string $text) : string
    {
        return $this->prefix . $this->sluggify($text);
    }

    protected function sluggify(string $text) : string
    {
        $slug = $text;
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        $slug = preg_replace('/[^a-z0-9-]+/', '-', strtolower($slug));
        $slug = preg_replace("/-+/", '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
