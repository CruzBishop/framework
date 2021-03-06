<?php
pload('packfire.view.pView');
require_once('mocks/tSampleModel.php');

/**
 * tMockView class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
class tMockView extends pView {
    
    public function using($template){
        $this->template($template);
    }
    
    protected function create() {
        foreach($this->state as $key => $value){
            $this->define($key, $value);
            $this->filter($key, 'trim');
        }
        $object = new pObjectObserver(new tSampleModel());
        $this->bind('binder', $object, 'title');
        $object->title = 'test2';
        $this->define('route', $this->route('home'));
    }

}