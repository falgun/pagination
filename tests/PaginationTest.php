<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Pagination\Pagination;

class PaginationTest extends TestCase
{

    public function testPaginationGetSet()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/skeleton/public';
        $_GET['page'] = 3;

        $pagination = new Pagination(15, 9);

        $this->assertEquals(0, $pagination->getItemOffset(), 'Offset detection failed');
        $this->assertEquals(15, $pagination->getItemsPerPage(), 'Items Per Page detection failed');
//        $this->assertEquals(0, $pagination->getTotalContent(), 'Total Content detection failed');
//        $this->assertEquals(0, $pagination->getTotalPage(), 'Total Page detection failed');

        $pagination->setItemOffset(99);
        $pagination->setItemsPerPage(5);
        $pagination->setTotalContent(1000);
//        $pagination->setTotalPage(10);

        $this->assertEquals(99, $pagination->getItemOffset(), 'Offset detection failed');
        $this->assertEquals(5, $pagination->getItemsPerPage(), 'Items Per Page detection failed');
//        $this->assertEquals(1000, $pagination->getTotalContent(), 'Total Content detection failed');
//        $this->assertEquals(10, $pagination->getTotalPage(), 'Total Page detection failed');
    }

    private function buildPagination(int $page): Pagination
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/skeleton/public';


        if ($page > 1) {
            $_GET['page'] = $page;
        } elseif (isset($_GET['page'])) {
            unset($_GET['page']);
        }

        return new Pagination();
    }

    public static function paginationDataProvider(): array
    {
        return [
            'no content' => [
                'testCase' => 'page1->total0',
                'currentPage' => 1,
                'totalItems' => 0,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => false,
                'expected_link_start_at' => 1,
                'expected_link_end_at' => 1,
                'expected_first_page_valid' => false,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => true,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => '1',
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => '1',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => '1',
                'expected_next_page_current' => false,
            ],
            'first and only page' => [
                'testCase' => 'page1->total3',
                'currentPage' => 1,
                'totalItems' => 3,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => true,
                'expected_link_start_at' => 1,
                'expected_link_end_at' => 1,
                'expected_first_page_valid' => false,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => true,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => '1',
                'expected_last_page_current' => true,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => '1',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => '1',
                'expected_next_page_current' => false,
            ],
            'first and many page' => [
                'testCase' => 'page1->total300',
                'currentPage' => 1,
                'totalItems' => 300,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => true,
                'expected_link_start_at' => 1,
                'expected_link_end_at' => 5,
                'expected_first_page_valid' => false,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => true,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => '30',
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => '1',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => '2',
                'expected_next_page_current' => false,
            ],
            'middle and many page' => [
                'testCase' => 'page15->total300',
                'currentPage' => 15,
                'totalItems' => 300,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => true,
                'expected_link_start_at' => 13,
                'expected_link_end_at' => 17,
                'expected_first_page_valid' => true,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => false,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => '30',
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => '14',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => '16',
                'expected_next_page_current' => false,
            ],
            'almost last and many page' => [
                'testCase' => 'page29->total300',
                'currentPage' => 29,
                'totalItems' => 300,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => true,
                'expected_link_start_at' => 26,
                'expected_link_end_at' => 30,
                'expected_first_page_valid' => true,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => false,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => '30',
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => '28',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => '30',
                'expected_next_page_current' => false,
            ],
            'last and many page' => [
                'testCase' => 'page30->total300',
                'currentPage' => 30,
                'totalItems' => 299,
                'itemPerPage' => 10,
                'maxLinks' => 5,
                'expected_links_valid' => true,
                'expected_link_start_at' => 26,
                'expected_link_end_at' => 30,
                'expected_first_page_valid' => true,
                'expected_first_page_title' => '1',
                'expected_first_page_current' => false,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => '30',
                'expected_last_page_current' => true,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => '29',
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => '30',
                'expected_next_page_current' => false,
            ],
        ];
    }

    /**
     *  @dataProvider paginationDataProvider
     */
    public function testPaginationWithDifferentData(
        string $testCase,
        int $currentPage,
        int $totalItems,
        int $itemPerPage,
        int $maxLinks,
        bool $expected_links_valid,
        int $expected_link_start_at,
        int $expected_link_end_at,
        bool $expected_first_page_valid,
        string $expected_first_page_title,
        bool $expected_first_page_current,
        bool $expected_last_page_valid,
        string $expected_last_page_title,
        bool $expected_last_page_current,
        bool $expected_pre_page_valid,
        string $expected_pre_page_title,
        bool $expected_pre_page_current,
        bool $expected_next_page_valid,
        string $expected_next_page_title,
        bool $expected_next_page_current
    )
    {
        $pagination = $this->buildPagination($currentPage);

        $pagination->setTotalContent($totalItems);

        $pages = $pagination->make($currentPage);

        $this->assertEquals($expected_links_valid, $pages->links->valid(), 'Link iterator validation failed');

        if ($pages->links->valid()) {
            $i = $expected_link_start_at;
            $linkEndedAt = 0;

            foreach ($pages->links as $link) {
                $this->assertEquals(true, $link->isValid(), 'Link iterator page must be valid');
                $this->assertEquals($i, $link->title, 'Link iterator page title failed');
                $this->assertEquals($i === $currentPage, $link->current, 'Link iterator page current failed');

                $linkEndedAt = $i;
                $i++;
            }
            $this->assertEquals($expected_link_end_at, $linkEndedAt, 'Link iterator has wrong count');
        }

        $this->assertEquals($expected_first_page_valid, $pages->firstPage->isValid(), 'First page valid failed');
        $this->assertEquals($expected_first_page_title, $pages->firstPage->title, 'First page title failed');
//        $this->assertEquals('', $pages->firstPage->link);
        $this->assertEquals($expected_first_page_current, $pages->firstPage->current, 'First page current failed');

        $this->assertEquals($expected_last_page_valid, $pages->lastPage->isValid(), 'Last page valid failed');
        $this->assertEquals($expected_last_page_title, $pages->lastPage->title, 'Last page title failed');
        $this->assertEquals($expected_last_page_current, $pages->lastPage->current, 'Last page current failed');

        $this->assertEquals($expected_pre_page_valid, $pages->prePage->isValid(), 'Pre page valid failed');
        $this->assertEquals($expected_pre_page_title, $pages->prePage->title, 'Pre page title failed');
        $this->assertEquals($expected_pre_page_current, $pages->prePage->current, 'Pre page current failed');

        $this->assertEquals($expected_next_page_valid, $pages->nextPage->isValid(), 'Next page valid failed');
        $this->assertEquals($expected_next_page_title, $pages->nextPage->title, 'Next page title failed');
        $this->assertEquals($expected_next_page_current, $pages->nextPage->current, 'Next page current failed');
    }
//    
//    public function testPageAtStartLowItems()
//    {
//        $pagination = $this->buildPagination(1);
//
//        $pagination->setTotalContent(3);
//
//        $pages = $pagination->make();
//
//        $this->assertEquals(false, $pages->firstPage->isValid());
//        $this->assertEquals(false, $pages->lastPage->isValid());
//        $this->assertEquals(false, $pages->nextPage->isValid());
//        $this->assertEquals(false, $pages->prePage->isValid());
//        $this->assertEquals(true, $pages->links->valid());
//
//        $this->assertEquals(true, $pages->links->current()->isValid());
//        $this->assertEquals(true, $pages->links->current()->current);
//        $pages->links->next();
//        $this->assertEquals(false, $pages->links->valid());
//
//        $this->assertEquals(1, $pages->firstPage->title);
////        $this->assertEquals('', $pages->firstPage->link);
//        $this->assertEquals(true, $pages->firstPage->current);
//
//        $this->assertEquals(1, $pages->lastPage->title);
//        $this->assertEquals(true, $pages->lastPage->current);
//
//        $this->assertEquals(1, $pages->prePage->title);
//        $this->assertEquals(1, $pages->nextPage->title);
//    }
//
//    public function testPageAtStartManyItems()
//    {
//        $currentPage = 1;
//        $pagination = $this->buildPagination($currentPage);
//
//        $pagination->setTotalContent(300);
//
//        $pages = $pagination->make();
//
//        $this->assertEquals(false, $pages->firstPage->isValid());
//        $this->assertEquals(true, $pages->lastPage->isValid());
//        $this->assertEquals(true, $pages->nextPage->isValid());
//        $this->assertEquals(false, $pages->prePage->isValid());
//        $this->assertEquals(true, $pages->links->valid());
//
//        $i = $currentPage;
//        foreach ($pages->links as $link) {
//            $this->assertEquals(true, $link->isValid());
//            $this->assertEquals($i, $link->title);
//            $this->assertEquals($i === $currentPage, $link->current);
//
//            $i++;
//        }
//        $this->assertEquals($currentPage + 6, $i);
//
//        $this->assertEquals($currentPage, $pages->firstPage->title);
////        $this->assertEquals('', $pages->firstPage->link);
//        $this->assertEquals(true, $pages->firstPage->current);
//
//        $this->assertEquals(30, $pages->lastPage->title);
//        $this->assertEquals(false, $pages->lastPage->current);
//
//        $this->assertEquals(1, $pages->prePage->title);
//        $this->assertEquals(2, $pages->nextPage->title);
//    }
}
