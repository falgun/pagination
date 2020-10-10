<?php

namespace Falgun\Pagination;

interface PaginationInterface
{

    public function make(): PaginationBag;

    public function getItemOffset(): int;

    public function getItemsPerPage(): int;

    public function getMaxLinkToShow(): int;

    public function setTotalItems(int $total): void;

    public function getTotalItems(): int;

    public function getCurrentPage(): int;

    public function getTotalPage(): int;

    public function hasMultiplePage(): bool;
}
