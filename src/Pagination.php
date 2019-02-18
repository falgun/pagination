<?php
declare(strict_types=1);
namespace Falgun\Pagination;

class Pagination implements PaginationInterface
{

    protected $offset, $limit, $totalContent, $totalPage, $currentPage, $linkToShow, $pageRange;

    public function __construct(int $limit = 10, int $linkToShow = 5)
    {
        $this->detectCurrentPage();

        $this->limit = $limit;
        $this->linkToShow = $linkToShow;
        $this->totalContent = 0;
        $this->totalPage = 0;

        $this->calculateOffset();
    }

    protected function calculateOffset()
    {
        return $this->offset = ($this->currentPage - 1) * $this->limit;
    }

    protected function calculateTotalPages()
    {
        $this->setTotalPage((int) ceil($this->totalContent / $this->limit));
    }

    protected function calculatePageRange()
    {
        $prepages = (int) ($this->currentPageNo() - intval(floor($this->linkToShow / 2)));
        $prepages = ($prepages < 1) ? 1 : $prepages;

        $nextpages = $prepages + ($this->linkToShow - 1);

        $this->pageRange = new \stdClass();

        if (($this->totalPage > $this->linkToShow) && ($nextpages >= $this->totalPage)) {
            $this->pageRange->end = $this->totalPage;
            $this->pageRange->start = $this->pageRange->end - ($this->linkToShow - 1);
        } elseif (($this->totalPage > $this->linkToShow) && ($nextpages < $this->totalPage)) {
            $this->pageRange->start = $prepages;
            $this->pageRange->end = $nextpages;
        } else {
            $this->pageRange->start = 1;
            $this->pageRange->end = $this->totalPage;
        }
    }

    protected function links(): \Iterator
    {
        for ($page = $this->pageRange->start; $page <= $this->pageRange->end; $page++) {
            yield new Page($page, $this->returnLink($page), $page === $this->currentPage, Page::IS_VALID);
        }
    }

    /**
     * Create a pagination link Bag
     * @return \Falgun\Pagination\PaginationBag
     */
    public function make(): PaginationBag
    {
        $this->calculateTotalPages();
        $this->calculatePageRange();

        return new PaginationBag($this->firstPage(), $this->lastPage(), $this->prePage(), $this->nextPage(), $this->links());
    }

    public function firstPage(): Page
    {
        return new Page(1, $this->returnLink(1), false, $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG);
    }

    public function lastPage(): Page
    {
        return new Page($this->getTotalPage(), $this->returnLink($this->getTotalPage()), false, $this->currentPage < $this->getTotalPage() ? Page::IS_VALID : Page::NO_FLAG);
    }

    public function prePage(): Page
    {
        $prePage = $this->currentPage > 1 ? $this->currentPage - 1 : $this->currentPage;

        return new Page($prePage, $this->returnLink($prePage), false, $this->currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG);
    }

    public function nextPage(): Page
    {
        $nextPage = $this->currentPage < $this->totalPage ? $this->currentPage + 1 : $this->currentPage;

        return new Page($nextPage, $this->returnLink($nextPage), false, $this->currentPage < $this->totalPage ? Page::IS_VALID : Page::NO_FLAG);
    }

    protected function returnLink(int $page): string
    {
        $uri = strpos($_SERVER['REQUEST_URI'], '?') !== false ? strstr($_SERVER['REQUEST_URI'], '?', true) : $_SERVER['REQUEST_URI'];

        $urlParams = $_GET;
        $urlParams['page'] = $page;

        return $uri . '?' . http_build_query($urlParams);
    }

    protected function currentPageNo(): int
    {
        if (is_int($this->currentPage)) {
            return $this->currentPage;
        }

        $pageNo = (int) ($_GET['page'] ?? 1);

        return $this->currentPage = $pageNo < 1 ? 1 : $pageNo;
    }

    public function detectCurrentPage()
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
