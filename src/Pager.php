<?php
namespace Falgun\Pagination;

class Pager extends Pagination
{

    public function make(): PaginationBag
    {
        $this->calculateTotalPages();

        return new PaginationBag(new Page('', '', false, Page::NO_FLAG),
            new Page('', '', false, Page::NO_FLAG),
            $this->prePage(),
            $this->nextPage(),
            new \EmptyIterator());
    }
}
