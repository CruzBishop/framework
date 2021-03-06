<?php

pload('packfire.collection.pPriorityStack');

/**
 * Test class for pPriorityStack.
 * Generated by PHPUnit on 2012-04-07 at 08:27:43.
 */
class pPriorityStackTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pPriorityStack
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new pPriorityStack(function($a, $b){
                if($a == $b){return 0;}
                return $a < $b ? -1 : 1;
            });
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pPriorityStack::add
     */
    public function testAdd() {
        $this->object->push(5);
        $this->object->add(6);
        $this->object->add(2);
        $this->object->push(8);
        $this->object->add(4);
        $this->object->add(7);
        $this->object->push(5);
        
        $this->assertCount(7, $this->object);
        $this->assertEquals(array(2, 4, 5, 5, 6, 7, 8), $this->object->toArray());
    }

}
