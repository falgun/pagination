<?php
declare(strict_types=1);

namespace Falgun\Pagination;

class Pagination implements PaginationInterface
{

    protected int $itemOffset;
    protected int $itemsPerPage;
    protected int $totalContent;
    protected int $totalPage;
    protected int $currentPage;
    protected int $maxLinkToShow;

    public function __construct(int $itemsPerPage = 10, int $maxLinkToShow = 5)
    {
        $this->detectCurrentPage();

        $this->itemsPerPage = $itemsPerPage;
        $this->maxLinkToShow = $maxLinkToShow;
        $this->totalContent = 0;
        $this->totalPage = 0;

        $this->calculateItemOffset();
    }

    protected function calculateItemOffset(): int
    {
        return $this->itemOffset = ($this->currentPage - 1) * $this->itemsPerPage;
    }

    protected function calculateTotalPages(): void
    {
        $this->setTotalPage((int) ceil($this->totalContent / $this->itemsPerPage));
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
        $startPage = (int) ($this->currentPageNo() - intval(floor($this->maxLinkToShow / 2)));

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
                    $this->returnLink($page),
                    $page === $this->currentPage,
                    Page::IS_VALID
            );
        }
    }

    public function make(): PaginationBag
    {
        $this->calculateTotalPages();
        $range = $this->calculatePageRange();

        return PaginationBag::new(
                $this->firstPage(),
                $this->lastPage(),
                $this->prePage(),
                $this->nextPage(),
                $this->links($range)
        );
    }

    protected function firstPage(): Page
    {
        return Page::new(
                '1',
                $this->returnLink(1),
                $this->currentPage === 1,
                $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    protected function lastPage(): Page
    {
        return Page::new(
                strval($this->getTotalPage() ?: 1),
                $this->returnLink($this->getTotalPage()),
                $this->currentPage === $this->getTotalPage(),
                $this->currentPage < $this->getTotalPage() ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    protected function prePage(): Page
    {
        $prePage = $this->currentPage > 1 ? $this->currentPage - 1 : $this->currentPage;

        return Page::new(
                strval($prePage),
                $this->returnLink($prePage),
                false,
                $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    protected function nextPage(): Page
    {
        $nextPage = $this->currentPage < $this->totalPage ? $this->currentPage + 1 : $this->currentPage;

        return Page::new(
                strval($nextPage),
                $this->returnLink($nextPage),
                false,
                $this->currentPage < $this->totalPage ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    protected function returnLink(int $page): string
    {
        $uri = $this->currentUri();

        $urlParams = $this->currentQueryParameters();
        $urlParams['page'] = $page;

        return $uri . '?' . http_build_query($urlParams);
    }

    protected function currentUri(): string
    {
        return (string) ((\strpos($_SERVER['REQUEST_URI'], '?') !== false) ?
            strstr($_SERVER['REQUEST_URI'], '?', true) :
            $_SERVER['REQUEST_URI']);
    }

    protected function currentQueryParameters(): array
    {
        return $_GET;
    }

    protected function currentPageNo(): int
    {
        if (isset($this->currentPage)) {
            return $this->currentPage;
        }

        $pageNo = (int) ($_GET['page'] ?? 1);

        return $this->currentPage = $pageNo < 1 ? 1 : $pageNo;
    }

    protected function detectCurrentPage(): int
    {
        $pageNo = (int) ($_GET['page'] ?? 1);

        return $this->currentPage = $pageNo < 1 ? 1 : $pageNo;
    }

    protected function getTotalPage(): int
    {
        return $this->totalPage;
    }

    protected function setTotalPage(int $totalPage): void
    {
        $this->totalPage = $totalPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    public function getItemOffset(): int
    {
        return $this->itemOffset;
    }

    public function setItemsPerPage(int $itemsPerPage): PaginationInterface
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function setItemOffset(int $itemOffset): PaginationInterface
    {
        $this->itemOffset = $itemOffset;

        return $this;
    }

    public function setTotalContent(int $total): PaginationInterface
    {
        $this->totalContent = $total;

        return $this;
    }
}
