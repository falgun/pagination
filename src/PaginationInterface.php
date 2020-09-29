<?php

namespace Falgun\Pagination;

interface PaginationInterface
{

    public function make(int $currentPage): PaginationBag;

    public function getItemOffset(): int;

    public function setItemsPerPage(int $itemsPerPage): PaginationInterface;

    public function getItemsPerPage(): int;

    public function setTotalItems(int $total): PaginationInterface;

    public function getTotalItems(): int;

    public function getCurrentPage(): int;

    public function setCurrentPage(int $page): PaginationInterface;

    public function getTotalPage(): int;

    public function hasMultiplePage(): bool;
}
