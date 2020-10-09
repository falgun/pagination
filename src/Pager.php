<?php
declare(strict_types=1);

namespace Falgun\Pagination;

class Pager extends Pagination
{

    public function make(): PaginationBag
    {
        return PaginationBag::new(
                Page::new('', 1, false, Page::NO_FLAG),
                Page::new('', 1, false, Page::NO_FLAG),
                PageBuilder::prePage($this->currentPage),
                PageBuilder::nextPage($this->currentPage, $this->totalPage),
                new \EmptyIterator()
        );
    }
}
