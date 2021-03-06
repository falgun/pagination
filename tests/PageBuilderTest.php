<?php
declare(strict_types=1);

namespace Falgun\Pagination\Tests;

use Falgun\Pagination\Page;
use Falgun\Pagination\PageBuilder;
use \PHPUnit\Framework\TestCase;

final class PageBuilderTest extends TestCase
{

    public function testFirstPageOnPage1()
    {
        $first = PageBuilder::firstPage(1);

        $this->assertInstanceOf(Page::class, $first);

        $this->assertSame('First', $first->title());
        $this->assertSame(1, $first->page());
        $this->assertTrue($first->isCurrent());
        $this->assertFalse($first->isVisitable());
    }

    public function testFirstPageOnPage2()
    {
        $first = PageBuilder::firstPage(2);

        $this->assertSame(1, $first->page());
        $this->assertFalse($first->isCurrent());
        $this->assertTrue($first->isVisitable());
    }

    public function testLastPageOnMidPage()
    {
        $last = PageBuilder::lastPage(5, 9);

        $this->assertInstanceOf(Page::class, $last);

        $this->assertSame('Last', $last->title());
        $this->assertSame(9, $last->page());
        $this->assertFalse($last->isCurrent());
        $this->assertTrue($last->isVisitable());
    }

    public function testLastPageOnLastPage()
    {
        $last = PageBuilder::lastPage(9, 9);

        $this->assertSame(9, $last->page());
        $this->assertTrue($last->isCurrent());
        $this->assertFalse($last->isVisitable());
    }

    public function testPrePageOnPage1()
    {
        $pre = PageBuilder::prePage(1);

        $this->assertInstanceOf(Page::class, $pre);

        $this->assertSame('Pre', $pre->title());
        $this->assertSame(1, $pre->page());
        // previous page is never equals to current page
        $this->assertFalse($pre->isCurrent());
        $this->assertFalse($pre->isVisitable());
    }

    public function testPrePageOnPage2()
    {
        $pre = PageBuilder::firstPage(2);

        $this->assertSame(1, $pre->page());
        $this->assertFalse($pre->isCurrent());
        $this->assertTrue($pre->isVisitable());
    }

    public function testNextPageOnMidPage()
    {
        $next = PageBuilder::nextPage(5, 9);

        $this->assertInstanceOf(Page::class, $next);

        $this->assertSame('Next', $next->title());
        $this->assertSame(6, $next->page());
        // next page is never equals to current page
        $this->assertFalse($next->isCurrent());
        $this->assertTrue($next->isVisitable());
    }

    public function testNextPageOnLastPage()
    {
        $next = PageBuilder::nextPage(9, 9);

        $this->assertSame(9, $next->page());
        $this->assertFalse($next->isCurrent());
        $this->assertFalse($next->isVisitable());
    }

}
