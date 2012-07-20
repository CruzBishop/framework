<?php

pload('packfire.datetime.pDateTime');
pload('packfire.datetime.pDaysOfWeek');

/**
 * Test class for pDateTime.
 * Generated by PHPUnit on 2012-04-28 at 02:31:37.
 */
class pDateTimeTest extends PHPUnit_Framework_TestCase {

    
    public function __construct(){
        date_default_timezone_set('UTC');
    }
    
    /**
     * @var pDateTime
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pDateTime(1999, 6, 24, 10, 30, 35);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pDateTime::timezone
     */
    public function testTimezone() {
        $this->assertEquals(0, $this->object->timezone());
        
        $this->object->timezone(-5);
        $this->assertEquals(-5, $this->object->timezone());
        $this->assertEquals(5, $this->object->time()->hour());
        
        $this->object->timezone(12);
        $this->assertEquals(22, $this->object->time()->hour());
        $this->assertEquals(24, $this->object->day());
        
        $this->object->timezone(-12);
        $this->assertEquals(22, $this->object->time()->hour());
        $this->assertEquals(23, $this->object->day());
    }

    /**
     * @covers pDateTime::time
     */
    public function testTime() {
        $this->assertInstanceOf('pTime', $this->object->time());
        $this->assertEquals(10, $this->object->time()->hour());
        $this->assertEquals(30, $this->object->time()->minute());
        $this->assertEquals(35, $this->object->time()->second());
    }

    /**
     * @covers pDateTime::dayOfWeek
     */
    public function testDayOfWeek() {
        $this->assertEquals(pDaysOfWeek::THURSDAY, $this->object->dayOfWeek());
        $this->object->day(26);
        $this->assertEquals(pDaysOfWeek::SATURDAY, $this->object->dayOfWeek());
        $this->object->day(21);
        $this->assertEquals(pDaysOfWeek::MONDAY, $this->object->dayOfWeek());
    }

    /**
     * @covers pDateTime::isLeapYear
     */
    public function testIsLeapYear() {
        $this->assertFalse(pDateTime::isLeapYear(1999));
        $this->assertFalse(pDateTime::isLeapYear(2013));
        $this->assertFalse(pDateTime::isLeapYear(1963));
        $this->assertFalse(pDateTime::isLeapYear(3021));
        $this->assertFalse(pDateTime::isLeapYear(1000));
        $this->assertTrue(pDateTime::isLeapYear(1996));
        $this->assertTrue(pDateTime::isLeapYear(1200));
        $this->assertTrue(pDateTime::isLeapYear(1992));
        $this->assertTrue(pDateTime::isLeapYear(2000));
        $this->assertTrue(pDateTime::isLeapYear(2012));
    }

    /**
     * @covers pDateTime::daysInMonth
     */
    public function testDaysInMonth() {
        $this->assertEquals(31, pDateTime::daysInMonth(10));
        $this->assertEquals(30, pDateTime::daysInMonth(11));
        $this->assertEquals(31, pDateTime::daysInMonth(12));
        $this->assertEquals(31, pDateTime::daysInMonth(12, 1992));
        $this->assertEquals(29, pDateTime::daysInMonth(2, 1992));
        $this->assertEquals(28, pDateTime::daysInMonth(2, 2001));
        $this->assertEquals(31, pDateTime::daysInMonth(1, 1992));
        $this->assertEquals(30, pDateTime::daysInMonth(4, 1992));
    }

    /**
     * @covers pDateTime::now
     */
    public function testNow() {
        $now = pDateTime::now();
        $this->assertInstanceOf('pDateTime', $now);
        $this->assertEquals(gmdate('Y'), $now->year());
    }

    /**
     * @covers pDateTime::fromTimestamp
     */
    public function testFromTimestamp() {
        $timestamp = time();
        $obj = pDateTime::fromTimestamp($timestamp);
        $this->assertEquals(gmdate('j', $timestamp), $obj->day());
        $this->assertEquals(gmdate('Y', $timestamp), $obj->year());
        $this->assertEquals(gmdate('m', $timestamp), $obj->month());
        $this->assertEquals(gmdate('H', $timestamp), $obj->time()->hour());
        $this->assertEquals(gmdate('i', $timestamp), $obj->time()->minute());
        $this->assertEquals(gmdate('s', $timestamp), $obj->time()->second());
    }

    /**
     * @covers pDateTime::toTimestamp
     */
    public function testToTimestamp() {
        $timestamp = $this->object->toTimestamp();
        $this->assertInternalType('integer', $timestamp);
        $this->assertTrue($timestamp > 0);
        $this->assertEquals($this->object->day(), gmdate('j', $timestamp));
        $this->assertEquals($this->object->month(), gmdate('m', $timestamp));
        $this->assertEquals($this->object->year(), gmdate('Y', $timestamp));
        $this->assertEquals($this->object->time()->hour(), gmdate('H', $timestamp));
        $this->assertEquals($this->object->time()->minute(), gmdate('i', $timestamp));
        $this->assertEquals($this->object->time()->second(), gmdate('s', $timestamp));
    }

    /**
     * @covers pDateTime::convertTimezone
     */
    public function testConvertTimezone() {
        $dt = pDateTime::convertTimezone($this->object, '-2');
        $this->assertEquals($this->object->time()->hour() - 2, $dt->time()->hour());
        $this->assertEquals($this->object->day(), $dt->day());
        $this->assertEquals($this->object->month(), $dt->month());
        $this->assertEquals($this->object->year(), $dt->year());
        
        $dt = pDateTime::convertTimezone($this->object, '8');
        $this->assertEquals(18, $dt->time()->hour());
        $this->assertEquals($this->object->day(), $dt->day());
        $this->assertEquals($this->object->month(), $dt->month());
        $this->assertEquals($this->object->year(), $dt->year());
        
        $dt = pDateTime::convertTimezone($this->object, '12');
        $this->assertEquals(22, $dt->time()->hour());
        $this->assertEquals($this->object->day(), $dt->day());
        $this->assertEquals($this->object->month(), $dt->month());
        $this->assertEquals($this->object->year(), $dt->year());
        
        $dt = pDateTime::convertTimezone($this->object, '-11');
        $this->assertEquals(23, $dt->time()->hour());
        $this->assertEquals($this->object->day() - 1, $dt->day());
        $this->assertEquals($this->object->month(), $dt->month());
        $this->assertEquals($this->object->year(), $dt->year());
    }

    /**
     * @covers pDateTime::fromString
     */
    public function testFromString() {
        $case = pDateTime::fromString('1986-5-23');
        $this->assertEquals(1986, $case->year());
        $this->assertEquals(5, $case->month());
        $this->assertEquals(23, $case->day());
        $this->assertEquals(0, $case->time()->hour());
        $this->assertEquals(0, $case->time()->minute());
        $this->assertEquals(0, $case->time()->second());
    }

    /**
     * @covers pDateTime::fromString
     */
    public function testFromString2() {
        $case = pDateTime::fromString('today');
        $this->assertEquals(gmdate('Y'), $case->year());
        $this->assertEquals(gmdate('m'), $case->month());
        $this->assertEquals(gmdate('j'), $case->day());
    }

    /**
     * @covers pDateTime::fromString
     */
    public function testFromString3() {
        $case = pDateTime::fromString('1998-7-3 05:24:13+0000');
        $this->assertEquals(1998, $case->year());
        $this->assertEquals(7, $case->month());
        $this->assertEquals(3, $case->day());
        $this->assertEquals(5, $case->time()->hour());
        $this->assertEquals(24, $case->time()->minute());
        $this->assertEquals(13, $case->time()->second());
    }

    /**
     * @covers pDateTime::toISO8601
     */
    public function testToISO8601() {
        $time = time();
        $obj = pDateTime::fromTimestamp($time);
        $this->assertEquals(gmdate(pDateTimeFormat::ISO8601, $time), $obj->toISO8601());
    }

    /**
     * @covers pDateTime::toRFC822
     */
    public function testToRFC822() {
        $time = time();
        $obj = pDateTime::fromTimestamp($time);
        $this->assertEquals(gmdate(pDateTimeFormat::RFC822, $time), $obj->toRFC822());
    }

    /**
     * @covers pDateTime::format
     */
    public function testFormat() {
        $formats = array(
            'Y-m-d',
            'h:i:s',
            pDateTimeFormat::ISO8601,
            pDateTimeFormat::RFC3339
        );
        foreach($formats as $format){
            $this->assertEquals(gmdate($format, $this->object->toTimestamp()),
                    $this->object->format($format));
        }
    }

    /**
     * @covers pDateTime::add
     */
    public function testAdd() {
        $datetime = $this->object->add(new pTimeSpan(7200));
        $this->assertEquals($this->object->time()->hour() + 2, $datetime->time()->hour());
        $datetime = $this->object->add(new pTimeSpan(86400));
        $this->assertEquals($this->object->day() + 1, $datetime->day());
        // year: 31536000
        $datetime = $this->object->add(new pTimeSpan(31622400));
        $this->assertEquals($this->object->year() + 1, $datetime->year());
    }

    /**
     * @covers pDateTime::subtract
     */
    public function testSubtract() {
        $datetime = $this->object->subtract(new pTimeSpan(7200));
        $this->assertEquals($this->object->time()->hour() - 2, $datetime->time()->hour());
        $datetime = $this->object->subtract(new pTimeSpan(86400));
        $this->assertEquals($this->object->day() - 1, $datetime->day());
        // year: 31536000
        $datetime = $this->object->subtract(new pTimeSpan(31622400));
        $this->assertEquals($this->object->year() - 1, $datetime->year());
    }

    /**
     * @covers pDateTime::calculateAge
     */
    public function testCalculateAge() {
        $birthday = new pDateTime(2010, 1, 1);
        $this->assertEquals(gmdate('Y') - 2010, pDateTime::calculateAge($birthday));
        
        // test that if birthday is next month, the age is one year less
        $now = pDateTime::now();
        $birthday = new pDateTime(2010, $now->month() + 1, $now->day());
        $this->assertEquals(gmdate('Y', $now->toTimestamp()) - 2011, pDateTime::calculateAge($birthday));
    }

    /**
     * @covers pDateTime::microtime
     */
    public function testMicrotime() {
        $this->assertInternalType(
                PHPUnit_Framework_Constraint_IsType::TYPE_FLOAT,
                pDateTime::microtime()
            );
        $this->assertTrue(pDateTime::microtime() > 0);
    }

    /**
     * @covers pDateTime::compareTo
     */
    public function testCompareTo() {
        $dt = $this->object->subtract(new pTimeSpan(31622400));
        $this->assertEquals(-1, $this->object->compareTo($dt));
        $this->assertEquals(0, $dt->compareTo($dt));
        $this->assertEquals(1, $dt->compareTo($this->object));
    }

}