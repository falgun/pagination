<?php
declare(strict_types=1);

namespace Falgun\Pagination;

use stdClass;

class Pagination implements PaginationInterface
{

    protected int $offset;
    protected int $limit;
    protected int $totalContent;
    protected int $totalPage;
    protected int $currentPage;
    protected int $linkToShow;

    public function __construct(int $limit = 10, int $linkToShow = 5)
    {
        $this->detectCurrentPage();

        $this->limit = $limit;
        $this->linkToShow = $linkToShow;
        $this->totalContent = 0;
        $this->totalPage = 0;

        $this->calculateOffset();
    }

    protected function calculateOffset(): int
    {
        return $this->offset = ($this->currentPage - 1) * $this->limit;
    }

    protected function calculateTotalPages(): void
    {
        $this->setTotalPage((int) ceil($this->totalContent / $this->limit));
    }

    protected function calculatePageRange(): Range
    {
        $startPage = $this->calculateStartPage();
        $endPage = $this->calculateEndPage($startPage);

        $pageRange = new Range();

        if ($this->isAlmostPaginationEnd($startPage, $endPage)) {
            // pagination range is almost over
            $pageRange->end = $this->totalPage;
            $pageRange->start = $pageRange->end - ($this->linkToShow - 1);
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
        $startPage = (int) ($this->currentPageNo() - intval(floor($this->linkToShow / 2)));

        return ($startPage < 1) ? 1 : $startPage;
    }

    protected function calculateEndPage(int $startPage): int
    {
        return $startPage + ($this->linkToShow - 1);
    }

    protected function isAlmostPaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->linkToShow) && ($endPage >= $this->totalPage);
    }

    protected function isBeforePaginationEnd(int $startPage, int $endPage): bool
    {
        return ($this->totalPage > $this->linkToShow) && ($endPage < $this->totalPage);
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

    public function firstPage(): Page
    {
        return Page::new(
                '1',
                $this->returnLink(1),
                false,
                $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public function lastPage(): Page
    {
        return Page::new(
                strval($this->getTotalPage()),
                $this->returnLink($this->getTotalPage()),
                false,
                $this->currentPage < $this->getTotalPage() ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public function prePage(): Page
    {
        $prePage = $this->currentPage > 1 ? $this->currentPage - 1 : $this->currentPage;

        return Page::new(
                strval($prePage),
                $this->returnLink($prePage),
                false,
                $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public function nextPage(): Page
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

    public function detectCurrentPage(): int
    {
        $pageNo = (int) ($_GET['page'] ?? 1);

        return $this->currentPage = $pageNo < 1 ? 1 : $pageNo;
    }

    public function setCurrentPage(int $page): PaginationInterface
    {
        $this->currentPage = $page;

        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset = (int) (($this->currentPageNo() - 1) * $this->limit);
    }

    public function getTotalContent(): int
    {
        return $this->totalContent;
    }

    public function getTotalPage(): int
    {
        return $this->totalPage;
    }

    public function setLimit(int $limit): PaginationInterface
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(int $offset): PaginationInterface
    {
        $this->offset = $offset;

        return $this;
    }

    public function setTotalContent(int $total): PaginationInterface
    {
        $this->totalContent = $total;

        return $this;
    }

    public function setTotalPage(int $total): PaginationInterface
    {
        $this->totalPage = $total;

        return $this;
    }
}
