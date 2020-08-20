<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Page
{

    public const NO_FLAG = 0;
    public const IS_VALID = 1;

    public string $link;
    public string $title;
    public bool $current;
    public int $flags;

    private final function __construct(string $title, string $link, bool $current = false, int $flags = 0)
    {
        $this->title = $title;
        $this->link = $link;
        $this->current = $current;
        $this->flags = $flags;
    }

    public static function new(string $title, string $link, bool $current = false, int $flags = 0): self
    {
        return new static($title, $link, $current, $flags);
    }

    public function isValid(): bool
    {
        return ($this->flags & self::IS_VALID) === 1;
    }
}
