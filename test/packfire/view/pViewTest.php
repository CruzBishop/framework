<?php

pload('packfire.collection.pMap');
pload('packfire.ioc.pServiceBucket');
pload('packfire.routing.pRouter');
pload('packfire.routing.pRoute');
pload('packfire.template.pTemplate');
require_once(dirname(__FILE__) . '/../../mocks/tMockView.php');
require_once(dirname(__FILE__) . '/../../mocks/tMockConfig.php');

/**
 * Test class for pView.
 * Generated by PHPUnit on 2012-03-25 at 13:34:52.
 */
class pViewTest extends PHPUnit_Framework_TestCase {

    /**
     * @var pView
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp() {
        $this->object = new tMockView();
        $services = new pServiceBucket();
        $this->object->setBucket($services);
        
        $router = new pRouter();
        $config = new pMap(array('rewrite' => '/home', 'actual' => 'Rest'));
        $router->add('home', new pRoute('home', $config));
        $services->put('router', $router);
        
        $config = new tMockConfig();
        $services->put('config.app', $config);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown() {
        
    }

    /**
     * @covers pView::create
     */
    public function testCreate() {
        $this->object->state(new pMap(array('tag' => 'five  ')));
        $this->object->using(new pTemplate('data: {tag} route: {route}'));
        $this->assertEquals('data: five route: http://example.com/test/home', $this->object->render());
    }

}
