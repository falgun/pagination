<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class Range
{

    public int $start;
    public int $end;

    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

}
