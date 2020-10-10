<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Pagination\Pagination;

class PaginationTest extends TestCase
{

    public function testPaginationConstructor()
    {
        $pagination = new Pagination(7, 15, 9);

        $this->assertEquals(15, $pagination->getItemsPerPage(), 'Items Per Page detection failed');
        $this->assertEquals(90, $pagination->getItemOffset(), 'Offset detection failed');
        $this->assertEquals(7, $pagination->getCurrentPage(), 'current page calcuation failed');
    }

    public function testDefaultConstructParams()
    {
        $pagination = new Pagination(1);

        $this->assertSame(10, $pagination->getItemsPerPage());
        $this->assertSame(5, $pagination->getMaxLinkToShow());
    }

    public function testUninitilizedProperties()
    {
        $pagination = new Pagination(1);

        $this->expectException(\Error::class);

        $pagination->getTotalItems();
        $pagination->getTotalPage();
    }

    public function testInvalidCurrentPage()
    {
        $pagination = new Pagination(-10);

        $this->assertEquals(1, $pagination->getCurrentPage(), 'current page calcuation for negative number failed');

        $pagination = new Pagination(0);

        $this->assertEquals(1, $pagination->getCurrentPage(), 'current page calcuation for negative number failed');
    }

    public function testValidItemsPerPage()
    {
        $pagination = new Pagination(1, 1);
        $this->assertSame(1, $pagination->getItemsPerPage());
    }

    public function testValidLinksToShow()
    {
        $pagination = new Pagination(1, 1, 0);
        $this->assertSame(0, $pagination->getMaxLinkToShow());
    }

    public function testInvalidItemsPerPage()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Pagination(1, 0);
    }

    public function testInvalidLinksToShow()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Pagination(1, 1, -1);
    }

    public function testSetGetTotalItems()
    {
        $pagination = new Pagination(1, 15, 9);

        $pagination->setTotalItems(1000);

        $this->assertEquals(1000, $pagination->getTotalItems(), 'Total Item get/set failed');
    }

    public function testGetTotalPages()
    {
        $pagination = new Pagination(1, 15, 9);

        $pagination->setTotalItems(1000);

        $this->assertEquals(67, $pagination->getTotalPage(), 'Total Page get failed');
    }

    public function testHasMultiplePage()
    {
        $pagination = new Pagination(1, 15, 9);

        $pagination->setTotalItems(1000);

        $this->assertEquals(true, $pagination->hasMultiplePage(), 'Multiple page check failed');

        $pagination->setTotalItems(10);

        $this->assertEquals(false, $pagination->hasMultiplePage(), 'Multiple page check failed');
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => true,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 0,
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 1,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 1,
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => true,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 1,
                'expected_last_page_current' => true,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 1,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 1,
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => true,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 30,
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => false,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 1,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 2,
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => false,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 30,
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 14,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 16,
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => false,
                'expected_last_page_valid' => true,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 30,
                'expected_last_page_current' => false,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 28,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => true,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 30,
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
                'expected_first_page_title' => 'First',
                'expected_first_page_number' => 1,
                'expected_first_page_current' => false,
                'expected_last_page_valid' => false,
                'expected_last_page_title' => 'Last',
                'expected_last_page_number' => 30,
                'expected_last_page_current' => true,
                'expected_pre_page_valid' => true,
                'expected_pre_page_title' => 'Pre',
                'expected_pre_page_number' => 29,
                'expected_pre_page_current' => false,
                'expected_next_page_valid' => false,
                'expected_next_page_title' => 'Next',
                'expected_next_page_number' => 30,
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
        int $expected_first_page_number,
        bool $expected_first_page_current,
        bool $expected_last_page_valid,
        string $expected_last_page_title,
        int $expected_last_page_number,
        bool $expected_last_page_current,
        bool $expected_pre_page_valid,
        string $expected_pre_page_title,
        int $expected_pre_page_number,
        bool $expected_pre_page_current,
        bool $expected_next_page_valid,
        string $expected_next_page_title,
        int $expected_next_page_number,
        bool $expected_next_page_current
    )
    {
        $pagination = new Pagination($currentPage);

        $pagination->setTotalItems($totalItems);

        $pages = $pagination->make();

        $this->assertEquals($expected_links_valid, $pages->pages()->valid(), 'Link iterator validation failed');

        if ($pages->pages()->valid()) {
            $i = $expected_link_start_at;
            $linkEndedAt = 0;

            foreach ($pages->pages() as $page) {
                $this->assertEquals(true, $page->isVisitable(), 'Link iterator page must be valid');
                $this->assertEquals($i, $page->title(), 'Link iterator page title failed');
                $this->assertEquals($i, $page->page(), 'Link iterator page number failed');
                $this->assertEquals($i === $currentPage, $page->isCurrent(), 'Link iterator page current failed');

                $linkEndedAt = $i;
                $i++;
            }
            $this->assertEquals($expected_link_end_at, $linkEndedAt, 'Link iterator has wrong count');
        }

        $this->assertEquals($expected_first_page_valid, $pages->first()->isVisitable(), 'First page valid failed');
        $this->assertEquals($expected_first_page_title, $pages->first()->title(), 'First page title failed');
        $this->assertEquals($expected_first_page_number, $pages->first()->page(), 'First page number failed');
        $this->assertEquals($expected_first_page_current, $pages->first()->isCurrent(), 'First page current failed');

        $this->assertEquals($expected_last_page_valid, $pages->last()->isVisitable(), 'Last page valid failed');
        $this->assertEquals($expected_last_page_title, $pages->last()->title(), 'Last page title failed');
        $this->assertEquals($expected_last_page_number, $pages->last()->page(), 'Last page number failed');
        $this->assertEquals($expected_last_page_current, $pages->last()->isCurrent(), 'Last page current failed');

        $this->assertEquals($expected_pre_page_valid, $pages->previous()->isVisitable(), 'Pre page valid failed');
        $this->assertEquals($expected_pre_page_title, $pages->previous()->title(), 'Pre page title failed');
        $this->assertEquals($expected_pre_page_number, $pages->previous()->page(), 'Pre page number failed');
        $this->assertEquals($expected_pre_page_current, $pages->previous()->isCurrent(), 'Pre page current failed');

        $this->assertEquals($expected_next_page_valid, $pages->next()->isVisitable(), 'Next page valid failed');
        $this->assertEquals($expected_next_page_title, $pages->next()->title(), 'Next page title failed');
        $this->assertEquals($expected_next_page_number, $pages->next()->page(), 'Next page number failed');
        $this->assertEquals($expected_next_page_current, $pages->next()->isCurrent(), 'Next page current failed');
    }
}
