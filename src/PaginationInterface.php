<?php
namespace Falgun\Pagination;

interface PaginationInterface
{

    public function make(): PaginationBag;

    public function setOffset(int $offset): PaginationInterface;

    public function getOffset(): int;

    public function setLimit(int $limit): PaginationInterface;

    public function getLimit(): int;

    public function detectCurrentPage();

    public function setCurrentPage(int $page): PaginationInterface;

    public function getCurrentPage(): int;

    public function setTotalPage(int $total): PaginationInterface;

    public function getTotalPage(): int;

    public function setTotalContent(int $total): PaginationInterface;

    public function getTotalContent(): int;
}
