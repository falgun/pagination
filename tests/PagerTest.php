<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use Falgun\Pagination\Pager;
use PHPUnit\Framework\TestCase;

class PagerTest extends TestCase
{

    public function testPager()
    {
        $_SERVER['REQUEST_URI'] = 'http://localhost/skeleton/public';

        $page = $_GET['page'] = 9;

        $pager = new Pager();
        $pager->setTotalContent(287);

        $pages = $pager->make($page);


        $this->assertEquals(false, $pages->firstPage->isValid(), 'First page valid failed');
        $this->assertEquals(false, $pages->lastPage->isValid(), 'Last page valid failed');
        $this->assertEquals(false, $pages->links->valid(), 'Link Iterator valid failed');

        $this->assertEquals(true, $pages->prePage->isValid(), 'Pre page valid failed');
        $this->assertEquals(8, $pages->prePage->title, 'Pre page title failed');
        $this->assertEquals(10, $pages->nextPage->title, 'Next page title failed');
    }
}
