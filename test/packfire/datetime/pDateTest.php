<?php

pload('packfire.datetime.pDate');

/**
 * Test class for pDate.
 * Generated by PHPUnit on 2012-04-25 at 14:30:59.
 */
class pDateTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pDate
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pDate(1999, 9, 19);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pDate::day
     */
    public function testDay() {
        $this->assertEquals(19, $this->object->day());
        $this->object->day(10);
        $this->assertEquals(10, $this->object->day());
        $this->object->day(50);
        $this->assertEquals(20, $this->object->day());
        $this->assertEquals(10, $this->object->month());
        $this->assertEquals(2, $this->object->day(2));
        $this->object->day(-2);
        $this->assertEquals(29, $this->object->day());
        $this->assertEquals(9, $this->object->month());
    }

    /**
     * @covers pDate::month
     */
    public function testMonth() {
        $this->assertEquals(9, $this->object->month());
        $this->object->month(11);
        $this->assertEquals(11, $this->object->month());
        $this->object->month(14);
        $this->assertEquals(2, $this->object->month());
        $this->assertEquals(2000, $this->object->year());
        $this->assertEquals(1, $this->object->month(1));
    }

    /**
     * @covers pDate::year
     */
    public function testYear() {
        $this->assertEquals(1999, $this->object->year());
        $this->object->year(2008);
        $this->assertEquals(2008, $this->object->year());
        $this->assertEquals(2005, $this->object->year(2005));
    }

    /**
     * @covers pDate::shortYear
     */
    public function testShortYear() {
        $this->assertEquals(99, $this->object->shortYear());
        $this->object->year(2008);
        $this->assertEquals(8, $this->object->shortYear());
    }

    /**
     * @covers pDate::century
     */
    public function testCentury() {
        $this->assertEquals(20, $this->object->century());
        $this->object->year(2012);
        $this->assertEquals(21, $this->object->century());
    }

    /**
     * @covers pDate::totalDays
     */
    public function testTotalDays() {
        $this->assertEquals(730412, $this->object->totalDays());
        $this->object->day($this->object->day() + 1);
        $this->assertEquals(730413, $this->object->totalDays());
        $this->object->month($this->object->month() + 1);
        $this->assertEquals(730444, $this->object->totalDays());
    }

    /**
     * @covers pDate::add
     */
    public function testAdd() {
        $ts = new pTimeSpan(86400);
        $date = $this->object->add($ts);
        $this->assertEquals(19, $this->object->day());
        $this->assertEquals(20, $date->day());
    }

    /**
     * @covers pDate::subtract
     */
    public function testSubtract() {
        $ts = new pTimeSpan(86400);
        $date = $this->object->subtract($ts);
        $this->assertEquals(19, $this->object->day());
        $this->assertEquals(18, $date->day());
        $this->assertEquals($this->object->month(), $date->month());
        $this->assertEquals($this->object->year(), $date->year());
        $ts = new pTimeSpan(3600);
        $date = $this->object->subtract($ts);
        $this->assertEquals(19, $this->object->day());
        $this->assertEquals(19, $date->day());
    }

    /**
     * @covers pDate::compareTo
     */
    public function testCompareTo() {
        $comp = new pDate(1992, 12, 25);
        $equal = new pDate(1999, 9, 19);
        $this->assertEquals(-1, $this->object->compareTo($comp));
        $this->assertEquals(0, $this->object->compareTo($equal));
        $this->assertEquals(0, $this->object->compareTo($this->object));
        $this->assertEquals(1, $comp->compareTo($this->object));
    }

}