<?php

namespace Falgun\Pagination;

interface PaginationInterface
{

    public function make(): PaginationBag;

    public function setItemOffset(int $offset): PaginationInterface;

    public function getItemOffset(): int;

    public function setItemsPerPage(int $itemsPerPage): PaginationInterface;

    public function getItemsPerPage(): int;

    public function getCurrentPage(): int;

    public function setTotalContent(int $total): PaginationInterface;
}
