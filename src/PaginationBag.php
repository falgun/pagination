<?php
declare(strict_types=1);

namespace Falgun\Pagination;

use Iterator;

final class PaginationBag
{

    private Page $first;
    private Page $last;
    private Page $pre;
    private Page $next;
    private Iterator $pages;

    private function __construct(Page $first, Page $last, Page $pre, Page $next, Iterator $pages)
    {
        $this->first = $first;
        $this->last = $last;
        $this->pre = $pre;
        $this->next = $next;
        $this->pages = $pages;
    }

    public static function new(Page $first, Page $last, Page $pre, Page $next, Iterator $pages): self
    {
        return new static($first, $last, $pre, $next, $pages);
    }

    public function first(): Page
    {
        return $this->first;
    }

    public function last(): Page
    {
        return $this->last;
    }

    public function previous(): Page
    {
        return $this->pre;
    }

    public function next(): Page
    {
        return $this->next;
    }

    public function pages(): Iterator
    {
        return $this->pages;
    }
}
