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
                $currentPage === 1,
                $currentPage > 1 ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public static function lastPage(int $currentPage, int $totalPages): Page
    {
        return Page::new(
                'Last',
                $totalPages,
                $currentPage === $totalPages,
                ($currentPage < $totalPages) ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public static function prePage(int $currentPage): Page
    {
        $prePage = ($currentPage > 1) ? ($currentPage - 1) : $currentPage;

        return Page::new(
                'Pre',
                $prePage,
                // previous page is never considered as current page
                false,
                ($currentPage > 1) ? Page::IS_VALID : Page::NO_FLAG
        );
    }

    public static function nextPage(int $currentPage, int $totalPages): Page
    {
        $nextPage = ($currentPage < $totalPages) ? ($currentPage + 1) : $currentPage;

        return Page::new(
                'Next',
                $nextPage,
                // next page is never considered as current page
                false,
                ($currentPage < $totalPages) ? Page::IS_VALID : Page::NO_FLAG
        );
    }
}
