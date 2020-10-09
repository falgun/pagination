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


        $this->assertEquals(false, $pages->firstPage->isValid(), 'First page valid failed');
        $this->assertEquals(false, $pages->lastPage->isValid(), 'Last page valid failed');
        $this->assertEquals(false, $pages->links->valid(), 'Link Iterator valid failed');

        $this->assertEquals(true, $pages->prePage->isValid(), 'Pre page valid failed');
        $this->assertEquals('Pre', $pages->prePage->title, 'Pre page title failed');
        $this->assertEquals(8, $pages->prePage->page, 'Pre page Number failed');
        $this->assertEquals('Next', $pages->nextPage->title, 'Next page title failed');
        $this->assertEquals(10, $pages->nextPage->page, 'Next page Number failed');
    }
}
