<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use Falgun\Pagination\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{

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
        $this->assertEquals('Pre', $pages->previous()->title, 'Pre page title failed');
        $this->assertEquals(8, $pages->previous()->page, 'Pre page Number failed');
        $this->assertEquals('Next', $pages->next()->title, 'Next page title failed');
        $this->assertEquals(10, $pages->next()->page, 'Next page Number failed');
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
}
