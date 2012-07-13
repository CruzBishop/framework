<?php

pload('packfire.session.pMessenger');
pload('packfire.ioc.pServiceBucket');
pload('packfire.session.pSessionLoader');
require_once(dirname(__FILE__) . '/../../mocks/tMockSessionStorage.php');

/**
 * Test class for pMessenger.
 * Generated by PHPUnit on 2012-07-13 at 12:14:49.
 */
class pMessengerTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pMessenger
     */
    protected $object;
    
    protected $storage;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pMessenger;
        $bucket = new pServiceBucket();
        $this->storage = new tMockSessionStorage();
        $bucket->put('session.storage', $this->storage);
        $bucket->put('session', new pSession($this->storage));
        $bucket->put('messenger', $this->object);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pMessenger::send
     */
    public function testSend() {
        $this->object->send('test', 'sofia');
        $this->assertEquals(array('pMessenger' => array('$sofia/test' => true)), $this->storage->data());
    }

    /**
     * @covers pMessenger::send
     */
    public function testSend2() {
        $this->object->send('test', 'sofia', 'test message');
        $this->assertEquals(array('pMessenger' => array('$sofia/test' => 'test message')), $this->storage->data());
    }

    /**
     * @covers pMessenger::send
     */
    public function testSend3() {
        $this->object->send('test', array('sofia', 'elenor'));
        $this->assertEquals(array('pMessenger' => array('$sofia/test' => true, '$elenor/test' => true)), $this->storage->data());
    }

    /**
     * @covers pMessenger::send
     */
    public function testSend4() {
        $this->object->send('msg');
        $this->assertEquals(array('pMessenger' => array('${global}/msg' => true)), $this->storage->data());
    }

    /**
     * @covers pMessenger::send
     */
    public function testSend5() {
        $this->object->send('msg', null, 10);
        $this->assertEquals(array('pMessenger' => array('${global}/msg' => 10)), $this->storage->data());
    }

    /**
     * @covers pMessenger::check
     */
    public function testCheck() {
        $this->assertFalse($this->object->check('note'));
        $this->assertFalse($this->object->check('msg', null));
        $this->assertFalse($this->object->check('test', 'sofia'));
        
        $this->object->send('msg', null, 'woah');
        $this->assertTrue($this->object->check('msg', null));
        
        $this->object->send('test', 'sofia', 'run');
        $this->assertTrue($this->object->check('test', 'sofia'));
        
        $this->object->send('note', __CLASS__ . ':' . __FUNCTION__);
        $this->assertTrue($this->object->check('note'));
    }

    /**
     * @covers pMessenger::read
     */
    public function testRead() {
        $this->assertNull($this->object->read('note'));
        $this->object->send('note', __CLASS__ . ':' . __FUNCTION__);
        $this->assertEquals(true, $this->object->read('note'));
        $this->assertNull($this->object->read('note'));
        
        $this->assertNull($this->object->read('note2'));
        $this->object->send('note2', __CLASS__ . ':' . __FUNCTION__, 'pretty please?');
        $this->assertEquals('pretty please?', $this->object->read('note2'));
        $this->assertNull($this->object->read('note2'));
        
        $this->assertNull($this->object->read('msg', 'elenor'));
        $this->object->send('msg', 'elenor', 'pretty!');
        $this->assertEquals('pretty!', $this->object->read('msg', 'elenor'));
        $this->assertNull($this->object->read('msg', 'elenor'));
    }

    /**
     * @covers pMessenger::clear
     */
    public function testClear() {
        $this->object->send('note', __CLASS__ . ':' . __FUNCTION__);
        $this->object->send('note2', __CLASS__ . ':' . __FUNCTION__, 'pretty please?');
        $data = $this->storage->data();
        $this->assertCount(2, $data['pMessenger']);
        $this->object->clear();
        $this->assertEquals(array('pMessenger' => array()), $this->storage->data());
    }

}