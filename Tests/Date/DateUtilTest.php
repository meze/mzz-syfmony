<?php

use Mzz\MzzBundle\Date\DateUtil;

class DateUtilTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function convertsArrayOfMonthYearDayIntoDateTime()
    {

        $this->assertEquals(new DateTime("2011-12-21"), DateUtil::createFromArray(
                array('month' => 12, 'year' => 2011, 'day' => 21)
            ));
    }

    /**
     * @test
     */
    public function noDateIsReturnedIfAllDateElementsAreNotPresent()
    {
        $this->assertNull(DateUtil::createFromArray(
                array('month' => '', 'year' => '', 'day' => '')
            ));
    }

    /**
     * @test
     */
    public function convertsArrayOfMonthYearDayHourMinuteIntoDateTime()
    {
        $this->assertEquals(new DateTime("2011-12-21 11:31:00"), DateUtil::createFromArray(
                array('month' => 12, 'year' => 2011, 'day' => 21, 'hour' => 11, 'minute' => 31)
            ));
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function throwsAnExceptionIfDateDoesNotExist()
    {
        DateUtil::createFromArray(
                array('month' => 2, 'year' => 2011, 'day' => 31, 'hour' => 11, 'minute' => 31)
            );
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function throwsAnExceptionIfTimeDoesNotExist()
    {
        DateUtil::createFromArray(
                array('month' => 2, 'year' => 2011, 'day' => 12, 'hour' => 31, 'minute' => 61)
            );
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function throwsAnExceptionIfOneOrMoreElementsOfDateIsMissing()
    {
        DateUtil::createFromArray(
                array('month' => 2, 'year' => 2011, 'day' => '', 'hour' => 11, 'minute' => 21)
            );
    }

}
