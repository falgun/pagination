<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Pager implements PaginationInterface
{

    private Pagination $pagination;

    public function __construct(int $currentPage, int $itemsPerPage = 10)
    {
        $this->pagination = new Pagination($currentPage, $itemsPerPage, 0);
    }

    public function make(): PaginationBag
    {
        return PaginationBag::new(
                Page::new('', 1, false, Page::NO_FLAG),
                Page::new('', 1, false, Page::NO_FLAG),
                PageBuilder::prePage($this->getCurrentPage()),
                PageBuilder::nextPage($this->getCurrentPage(), $this->getTotalPage()),
                new \EmptyIterator()
        );
    }

    public function getCurrentPage(): int
    {
        return $this->pagination->getCurrentPage();
    }

    public function getItemOffset(): int
    {
        return $this->pagination->getItemOffset();
    }

    public function getItemsPerPage(): int
    {
        return $this->pagination->getItemsPerPage();
    }

    public function getTotalItems(): int
    {
        return $this->pagination->getTotalItems();
    }

    public function getTotalPage(): int
    {
        return $this->pagination->getTotalPage();
    }

    public function hasMultiplePage(): bool
    {
        return $this->pagination->hasMultiplePage();
    }

    public function setTotalItems(int $total): PaginationInterface
    {
        return $this->pagination->setTotalItems($total);
    }
}
