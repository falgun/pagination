<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Pagination implements PaginationInterface
{

    protected int $itemOffset;
    protected int $itemsPerPage;
    protected int $totalItems;
    protected int $totalPage;
    protected int $currentPage;
    protected int $maxLinkToShow;

    public function __construct(int $currentPage, int $itemsPerPage = 10, int $maxLinkToShow = 5)
    {
        $this->currentPage = $this->prepareCurrentPage($currentPage);
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

    protected function calculateItemOffset(): int
    {
        return $this->itemOffset = ($this->currentPage - 1) * $this->itemsPerPage;
    }

    protected function calculateTotalPages(): void
    {
        $this->setTotalPage((int) ceil($this->totalItems / $this->itemsPerPage));
    }

    protected function calculatePageRange(): Range
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

    protected function calculateStartPage(): int
    {
        $startPage = (int) ($this->currentPage - intval(floor($this->maxLinkToShow / 2)));

        return ($startPage < 1) ? 1 : $startPage;
    }

    protected function calculateEndPage(int $startPage): int
    {
        return $startPage + ($this->maxLinkToShow - 1);
    }

    protected function isAlmostPaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->maxLinkToShow) && ($endPage >= $this->totalPage);
    }

    protected function isBeforePaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->maxLinkToShow) && ($endPage < $this->totalPage);
    }

    protected function links(Range $range): \Iterator
    {
        for ($page = $range->start; $page <= $range->end; $page++) {
            yield Page::new(
                    strval($page),
                    $page,
                    $page === $this->currentPage,
                    Page::IS_VALID
            );
        }
    }

    protected function setTotalPage(int $totalPage): void
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

    public function setTotalItems(int $total): PaginationInterface
    {
        $this->totalItems = $total;

        $this->calculateTotalPages();

        return $this;
    }
}
