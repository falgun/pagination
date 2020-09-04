<?php
declare(strict_types=1);

namespace Falgun\Pagination;

class Pager extends Pagination
{

    public function make(int $currentPage): PaginationBag
    {
        $this->prepareCurrentPage($currentPage);
        $this->calculateItemOffset();

        $this->calculateTotalPages();

        return PaginationBag::new(
                Page::new('', '', false, Page::NO_FLAG),
                Page::new('', '', false, Page::NO_FLAG),
                $this->prePage(),
                $this->nextPage(),
                new \EmptyIterator()
        );
    }
}
