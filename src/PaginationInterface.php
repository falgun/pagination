<?php

namespace Falgun\Pagination;

interface PaginationInterface
{

    public function make(int $currentPage): PaginationBag;

    public function setItemOffset(int $offset): PaginationInterface;

    public function getItemOffset(): int;

    public function setItemsPerPage(int $itemsPerPage): PaginationInterface;

    public function getItemsPerPage(): int;

    public function setTotalItems(int $total): PaginationInterface;
}
