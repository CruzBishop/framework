<?php

pload('packfire.ioc.pBucketUser');
require_once('mocks/tMockBucketUser.php');
require_once('mocks/tMockServiceBucket.php');

/**
 * Test class for pBucketUser.
 * Generated by PHPUnit on 2012-06-13 at 09:57:00.
 */
class pBucketUserTest extends PHPUnit_Framework_TestCase {

    /**
     * @var tMockBucketUser
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new tMockBucketUser;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pBucketUser::setBucket
     */
    public function testSetBucket() {
        $bucket = new pServiceBucket();
        $bucket->put('test', $this);
        $this->object->setBucket($bucket);
        $this->assertEquals($this, $this->object->service('test'));
    }

    /**
     * @covers pBucketUser::copyBucket
     */
    public function testCopyBucket() {
        $bucket = new pServiceBucket();
        $bucket->put('test', $this);
        $this->object->setBucket($bucket);
        
        $user = new tMockBucketUser();
        $user->copyBucket($this->object);
        $this->assertEquals($this, $user->service('test'));
    }

    /**
     * @covers pBucketUser::service
     */
    public function testService() {
        $this->assertNull($this->object->service('test'));
        $bucket = new pServiceBucket();
        $this->object->setBucket($bucket);
        $this->assertNull($this->object->service('test'));
        $bucket->put('test', $this);
        $this->assertEquals($this, $this->object->service('test'));
    }

}