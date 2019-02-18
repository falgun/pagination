<?php
namespace Falgun\Pagination;

class Page
{

    const NO_FLAG = 0;
    const IS_VALID = 1;

    public $link, $title, $current, $flags;

    public function __construct($title, string $link, bool $current = false, int $flags = 0)
    {
        $this->title = $title;
        $this->link = $link;
        $this->current = $current;
        $this->flags = $flags;
    }

    public function isValid()
    {
        return $this->flags & self::IS_VALID;
    }
}
