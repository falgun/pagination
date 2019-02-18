<?php
namespace Falgun\Pagination;

use Generator;

class PaginationBag
{

    /**
     *
     * @var \Falgun\Pagination\Page
     */
    public $firstPage;

    /**
     *
     * @var \Falgun\Pagination\Page
     */
    public $lastPage;

    /**
     *
     * @var \Falgun\Pagination\Page
     */
    public $prePage;

    /**
     *
     * @var \Falgun\Pagination\Page
     */
    public $nextPage;

    /**
     *
     * @var \Falgun\Pagination\Page[]
     */
    public $links;

    public function __construct(Page $firstPage, Page $lastPage, Page $prePage, Page $nextPage, \Iterator $links)
    {
        $this->firstPage = $firstPage;
        $this->lastPage = $lastPage;
        $this->prePage = $prePage;
        $this->nextPage = $nextPage;
        $this->links = $links;
    }
}
