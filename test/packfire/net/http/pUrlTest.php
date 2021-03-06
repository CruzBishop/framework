<?php

pload('packfire.net.http.pUrl');

/**
 * Test class for pUrl.
 * Generated by PHPUnit on 2012-02-15 at 18:02:01.
 */
class pUrlTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pUrl
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pUrl('ftp://user:pwd@git.example.com:2103/path/test.html?query=1#true');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pUrl::fragment
     */
    public function testFragment() {
        $this->assertEquals('true', $this->object->fragment());
    }

    /**
     * @covers pUrl::host
     */
    public function testHost() {
        $this->assertEquals('git.example.com', $this->object->host());
    }

    /**
     * @covers pUrl::pass
     */
    public function testPass() {
        $this->assertEquals('pwd', $this->object->pass());
    }

    /**
     * @covers pUrl::port
     */
    public function testPort() {
        $this->assertEquals('2103', $this->object->port());
    }

    /**
     * @covers pUrl::path
     */
    public function testPath() {
        $this->assertEquals('/path/test.html', $this->object->path());
    }

    /**
     * @covers pUrl::scheme
     */
    public function testScheme() {
        $this->assertEquals('ftp', $this->object->scheme());
    }

    /**
     * @covers pUrl::user
     */
    public function testUser() {
        $this->assertEquals('user', $this->object->user());
    }

    /**
     * @covers pUrl::params
     */
    public function testParams() {
        $this->assertInstanceOf('pMap', $this->object->params());
        $this->assertEquals(1, $this->object->params()->get('query'));
    }

    /**
     * @covers pUrl::__toString
     */
    public function test__toString() {
        $this->assertEquals('ftp://user:pwd@git.example.com:2103/path/test.html?query=1#true', $this->object .'');
    }

    /**
     * @covers pUrl::encode
     */
    public function testEncode() {
        $this->assertEquals('%3C', pUrl::encode('<'));
        $this->assertEquals('%3E', pUrl::encode('>'));
        $this->assertEquals('%26', pUrl::encode('&'));
        $this->assertEquals('%2B', pUrl::encode('+'));
        $this->assertEquals('+', pUrl::encode(' '));
    }

    /**
     * @covers pUrl::decode
     */
    public function testDecode() {
        $this->assertEquals('<', pUrl::decode('%3C'));
        $this->assertEquals('>', pUrl::decode('%3E'));
        $this->assertEquals('&', pUrl::decode('%26'));
        $this->assertEquals('+', pUrl::decode('%2B'));
        $this->assertEquals(' ', pUrl::decode('+'));
    }

    /**
     * @covers pUrl::combine
     */
    public function testCombine() {
        $final = pUrl::combine('http://example.com/test', '../object');
        $this->assertEquals('http://example.com/object', (string)$final);
        $final = pUrl::combine('/test/data', '../object/true');
        $this->assertEquals('/test/object/true', (string)$final);
    }

}