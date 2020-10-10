<?php
declare(strict_types=1);

namespace Falgun\Pagination;

use InvalidArgumentException;

final class Pagination implements PaginationInterface
{

    private int $itemOffset;
    private int $itemsPerPage;
    private int $totalItems;
    private int $totalPage;
    private int $currentPage;
    private int $maxLinkToShow;

    /**
     * 
     * @param int $currentPage
     * @param int $itemsPerPage
     * @param int $maxLinkToShow
     * @throws InvalidArgumentException
     */
    public function __construct(int $currentPage, int $itemsPerPage = 10, int $maxLinkToShow = 5)
    {
        $this->currentPage = $this->prepareCurrentPage($currentPage);

        if ($itemsPerPage < 1) {
            throw new InvalidArgumentException('ItemsPerPage value must be greater than 0');
        }

        if ($maxLinkToShow < 0) {
            throw new InvalidArgumentException('MaxLinkToShow value must be positive number');
        }

        $this->itemsPerPage = $itemsPerPage;
        $this->maxLinkToShow = $maxLinkToShow;

        $this->calculateItemOffset();
    }

    public function make(): PaginationBag
    {
        $range = $this->calculatePageRange();

        return PaginationBag::new(
                PageBuilder::firstPage($this->currentPage),
                PageBuilder::lastPage($this->currentPage, $this->totalPage),
                PageBuilder::prePage($this->currentPage),
                PageBuilder::nextPage($this->currentPage, $this->totalPage),
                $this->links($range)
        );
    }

    private function prepareCurrentPage(int $page): int
    {
        return $page < 1 ? 1 : $page;
    }

    private function calculateItemOffset(): int
    {
        return $this->itemOffset = ($this->currentPage - 1) * $this->itemsPerPage;
    }

    private function calculateTotalPages(): void
    {
        $this->setTotalPage((int) ceil($this->totalItems / $this->itemsPerPage));
    }

    private function calculatePageRange(): Range
    {
        $startPage = $this->calculateStartPage();
        $endPage = $this->calculateEndPage($startPage);

        $pageRange = new Range();

        if ($this->isAlmostPaginationEnd($startPage, $endPage)) {
            // pagination range is almost over
            $pageRange->end = $this->totalPage;
            $pageRange->start = $pageRange->end - ($this->maxLinkToShow - 1);
        } elseif ($this->isBeforePaginationEnd($startPage, $endPage)) {
            // enough range exits
            $pageRange->start = $startPage;
            $pageRange->end = $endPage;
        } else {
            // not enough pages to cover range
            $pageRange->start = 1;
            $pageRange->end = $this->totalPage;
        }

        return $pageRange;
    }

    private function calculateStartPage(): int
    {
        $startPage = (int) ($this->currentPage - intval(floor($this->maxLinkToShow / 2)));

        return ($startPage < 1) ? 1 : $startPage;
    }

    private function calculateEndPage(int $startPage): int
    {
        return $startPage + ($this->maxLinkToShow - 1);
    }

    private function isAlmostPaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->maxLinkToShow) && ($endPage >= $this->totalPage);
    }

    private function isBeforePaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->maxLinkToShow) && ($endPage < $this->totalPage);
    }

    private function links(Range $range): \Iterator
    {
        for ($page = $range->start; $page <= $range->end; $page++) {
            yield Page::new(
                    strval($page),
                    $page,
                    $page === $this->currentPage ? Page::CURRENT_PAGE : Page::NOT_CURRENT_PAGE,
                    Page::VISITABLE_PAGE,
            );
        }
    }

    private function setTotalPage(int $totalPage): void
    {
        $this->totalPage = $totalPage;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getItemOffset(): int
    {
        return $this->itemOffset;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getTotalPage(): int
    {
        return $this->totalPage;
    }

    public function hasMultiplePage(): bool
    {
        return $this->totalPage > 1;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setTotalItems(int $total): void
    {
        $this->totalItems = $total;

        $this->calculateTotalPages();
    }
}
