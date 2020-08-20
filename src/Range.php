<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Range
{

    public int $start;
    public int $end;

    public function __construct(int $start = 0, int $end = 0)
    {
        $this->start = $start;
        $this->end = $end;
    }
}
