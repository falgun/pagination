<?php
declare(strict_types=1);

namespace Falgun\Pagination;

final class PageBuilder
{

    public static function firstPage(int $currentPage): Page
    {
        return Page::new(
                'First',
                1,
                $currentPage === 1 ? Page::CURRENT_PAGE : Page::NOT_CURRENT_PAGE,
                $currentPage > 1 ? Page::VISITABLE_PAGE : Page::NOT_VISITABLE_PAGE,
        );
    }

    public static function lastPage(int $currentPage, int $totalPages): Page
    {
        return Page::new(
                'Last',
                $totalPages,
                $currentPage === $totalPages ? Page::CURRENT_PAGE : Page::NOT_CURRENT_PAGE,
                ($currentPage < $totalPages) ? Page::VISITABLE_PAGE : Page::NOT_VISITABLE_PAGE,
        );
    }

    public static function prePage(int $currentPage): Page
    {
        $prePage = ($currentPage > 1) ? ($currentPage - 1) : $currentPage;

        return Page::new(
                'Pre',
                $prePage,
                // previous page is never considered as current page
                Page::NOT_CURRENT_PAGE,
                ($currentPage > 1) ? Page::VISITABLE_PAGE : Page::NOT_VISITABLE_PAGE,
        );
    }

    public static function nextPage(int $currentPage, int $totalPages): Page
    {
        $nextPage = ($currentPage < $totalPages) ? ($currentPage + 1) : $currentPage;

        return Page::new(
                'Next',
                $nextPage,
                // next page is never considered as current page
                Page::NOT_CURRENT_PAGE,
                ($currentPage < $totalPages) ? Page::VISITABLE_PAGE : Page::NOT_VISITABLE_PAGE,
        );
    }
}
