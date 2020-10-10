<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Page
{

    public const CURRENT_PAGE = true;
    public const NOT_CURRENT_PAGE = false;
    public const VISITABLE_PAGE = true;
    public const NOT_VISITABLE_PAGE = false;

    public string $title;
    public int $page;
    public bool $current;
    public bool $isVisitable;

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

    public function isVisitable(): bool
    {
        return $this->isVisitable;
    }
}
