<?php
declare(strict_types=1);

namespace Falgun\Pagination;

use Iterator;

final class PaginationBag
{

    public Page $firstPage;
    public Page $lastPage;
    public Page $prePage;
    public Page $nextPage;

    public Iterator $links;

    private final function __construct(Page $firstPage, Page $lastPage, Page $prePage, Page $nextPage, Iterator $links)
    {
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
        $this->prePage = $prePage;
        $this->nextPage = $nextPage;
        $this->links = $links;
    }

    public static function new(Page $firstPage, Page $lastPage, Page $prePage, Page $nextPage, Iterator $links): self
    {
        return new static($firstPage, $lastPage, $prePage, $nextPage, $links);
    }
}
