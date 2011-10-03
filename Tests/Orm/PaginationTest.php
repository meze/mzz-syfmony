<?php

use Mzz\MzzBundle\Orm\Pagination;

class PaginationTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function calculatesOffsetBasedOnPerPageAndCurrentPage()
    {
        $paging = new Pagination(5);
        $paging->setTotal(100);
        $paging->setCurrentPage(12);
        $this->assertEquals(5, $paging->getPerPage());
        $this->assertEquals(12, $paging->getCurrentPage());
        $this->assertEquals(55, $paging->getOffset()); // perpage * current page
    }

    /**
     * @test
     */
    public function ifCurrentPageOverflowsOffsetThenReturnsOffsetForCLosestExistingPage()
    {
        $paging = new Pagination(10);
        $paging->setTotal(100);
        $paging->setCurrentPage(11);
        $this->assertEquals(10, $paging->getCurrentPage());
        $this->assertEquals(90, $paging->getOffset()); // perpage * current page

        $paging = new Pagination(10);
        $paging->setTotal(101);
        $paging->setCurrentPage(12);
        $this->assertEquals(100, $paging->getOffset()); // perpage * current page
        $this->assertEquals(11, $paging->getCurrentPage());
    }

    /**
     * @test
     */
    public function ignoresNegativeValues()
    {
        $paging = new Pagination(-1);
        $paging->setCurrentPage(-1);
        $paging->setTotal(-1);
        $this->assertEquals(0, $paging->getOffset());
        $this->assertEquals(0, $paging->getTotal());
        $this->assertEquals(0, $paging->getCurrentPage());
    }

    /**
     * @test
     */
    public function calculatesLastPage()
    {
        $paging = new Pagination(5);
        $paging->setTotal(60);
        $paging->setCurrentPage(6);
        $this->assertEquals(12, $paging->getLastPage());
        $paging = new Pagination(5);
        $paging->setTotal(62);
        $paging->setCurrentPage(6);
        $this->assertEquals(13, $paging->getLastPage());
        $paging = new Pagination(5);
        $paging->setTotal(0);
        $paging->setCurrentPage(1);
        $this->assertEquals(0, $paging->getLastPage());
    }

    /**
     * @test
     */
    public function calculatesPreviousPage()
    {
        $paging = new Pagination(5);
        $paging->setCurrentPage(6);
        $paging->setTotal(60);
        $this->assertTrue($paging->hasPreviousPage());
        $this->assertEquals(5, $paging->getPreviousPage());

        $paging = new Pagination(5);
        $paging->setTotal(60);
        $paging->setCurrentPage(1);
        $this->assertFalse($paging->hasPreviousPage());
        $this->assertEquals(1, $paging->getPreviousPage());
    }

    /**
     * @test
     */
    public function calculatesNextPage()
    {
        $paging = new Pagination(10);
        $paging->setTotal(60);
        $paging->setCurrentPage(6);
        $this->assertFalse($paging->hasNextPage());
        $this->assertEquals(6, $paging->getNextPage());
        $paging = new Pagination(5);
        $paging->setTotal(60);
        $paging->setCurrentPage(2);
        $this->assertTrue($paging->hasNextPage());
        $this->assertEquals(3, $paging->getNextPage());
    }

    /**
     * @test
     */
    public function offsetCannotBeNegative()
    {
        $paging = new Pagination(10);
        $paging->setTotal(10);
        $paging->setCurrentPage(1);
        $this->assertEquals(0, $paging->getOffset()); // perpage * current page
    }

    /**
     * @test
     */
    public function daterminesWhetherPaginationHasAnyPages()
    {
        $paging = new Pagination(10);
        $paging->setCurrentPage(1);
        $paging->setTotal(10);
        $this->assertFalse($paging->hasPages());

        $paging = new Pagination(5);
        $paging->setCurrentPage(1);
        $paging->setTotal(10);
        $this->assertTrue($paging->hasPages());

        $paging = new Pagination(5);
        $paging->setCurrentPage(2);
        $paging->setTotal(10);
        $this->assertTrue($paging->hasPages());

        $paging = new Pagination(5);
        $paging->setCurrentPage(1);
        $paging->setTotal(0);
        $this->assertFalse($paging->hasPages());
    }

}
