<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use Falgun\Pagination\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{

    public function testPagerConstruct()
    {
        $page = 9;

        $pager = new Pager($page, 11);

        $this->assertSame($page, $pager->getCurrentPage());
        $this->assertSame(11, $pager->getItemsPerPage());
    }

    public function testDefaultConstructParams()
    {
        $pager = new Pager(1);

        $this->assertSame(10, $pager->getItemsPerPage());
        $this->assertSame(0, $pager->getMaxLinkToShow());
    }

    public function testPageGetter()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $this->assertSame(80, $pager->getItemOffset());
        $this->assertSame(10, $pager->getItemsPerPage());
        $this->assertSame(287, $pager->getTotalItems());
        $this->assertSame(true, $pager->hasMultiplePage());
    }

    public function testPager()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $pages = $pager->make();


        $this->assertEquals(false, $pages->first()->isVisitable(), 'First page valid failed');
        $this->assertEquals(false, $pages->last()->isVisitable(), 'Last page valid failed');
        $this->assertEquals(false, $pages->pages()->valid(), 'Link Iterator valid failed');

        $this->assertEquals(true, $pages->previous()->isVisitable(), 'Pre page valid failed');
        $this->assertEquals('Pre', $pages->previous()->title(), 'Pre page title failed');
        $this->assertEquals(8, $pages->previous()->page(), 'Pre page Number failed');
        $this->assertEquals('Next', $pages->next()->title(), 'Next page title failed');
        $this->assertEquals(10, $pages->next()->page(), 'Next page Number failed');
    }

//    public static function pagerDataProvider(): array
//    {
//        return [
//            'first page' => [
//                'current_page' => 5,
//                'title' => '',
//                'page' => 1,
//                'isCurrent' => false,
//                'isVisible' => false,
//            ],
//            'last page' => [
//                'current_page' => 5,
//                'title' => '',
//                'page' => 1,
//                'isCurrent' => false,
//                'isVisible' => false,
//            ],
//            'pre page' => [
//                'current_page' => 5,
//                'title' => 'Pre',
//                'page' => 1,
//                'isCurrent' => false,
//                'isVisible' => true,
//            ],
//            'next page' => [
//                'current_page' => 5,
//                'title' => 'Next',
//                'page' => 2,
//                'isCurrent' => false,
//                'isVisible' => true,
//            ],
//        ];
//    }

    public function testPagerFirstPage()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $pages = $pager->make();

        $this->assertSame('', $pages->first()->title());
        $this->assertSame(1, $pages->first()->page());
        $this->assertSame(false, $pages->first()->isCurrent());
        $this->assertSame(false, $pages->first()->isVisitable());
    }

    public function testPagerLastPage()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $pages = $pager->make();

        $this->assertSame('', $pages->last()->title());
        $this->assertSame(1, $pages->last()->page());
        $this->assertSame(false, $pages->last()->isCurrent());
        $this->assertSame(false, $pages->last()->isVisitable());
    }

    public function testPagerPrePage()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $pages = $pager->make();

        $this->assertSame('Pre', $pages->previous()->title());
        $this->assertSame(8, $pages->previous()->page());
        $this->assertSame(false, $pages->previous()->isCurrent());
        $this->assertSame(true, $pages->previous()->isVisitable());
    }

    public function testPagerNextPage()
    {
        $page = 9;

        $pager = new Pager($page);
        $pager->setTotalItems(287);

        $pages = $pager->make();

        $this->assertSame('Next', $pages->next()->title());
        $this->assertSame(10, $pages->next()->page());
        $this->assertSame(false, $pages->next()->isCurrent());
        $this->assertSame(true, $pages->next()->isVisitable());
    }
}
