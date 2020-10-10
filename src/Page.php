<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Page
{

    public const CURRENT_PAGE = true;
    public const NOT_CURRENT_PAGE = false;
    public const VISITABLE_PAGE = true;
    public const NOT_VISITABLE_PAGE = false;

    private string $title;
    private int $page;
    private bool $current;
    private bool $isVisitable;

    private final function __construct(string $title, int $page, bool $current, bool $isVisitable)
    {
        $this->title = $title;
        $this->page = $page;
        $this->current = $current;
        $this->isVisitable = $isVisitable;
    }

    public static function new(string $title, int $page, bool $current, bool $isVisitable): self
    {
        return new static($title, $page, $current, $isVisitable);
    }

    public function title(): string
    {
        return $this->title;
    }

    public function page(): int
    {
        return $this->page;
    }

    public function isCurrent(): bool
    {
        return $this->current;
    }

    public function isVisitable(): bool
    {
        return $this->isVisitable;
    }
}
