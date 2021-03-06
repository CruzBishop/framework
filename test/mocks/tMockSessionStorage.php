<?php

/**
 * tMockSessionStorage class
 * 
 * Mock session storage location
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0
 */
class tMockSessionStorage extends pSessionStorage {
    
    private $data;
    
    public function __construct($data = array()){
        $this->data = $data;
        parent::__construct();
    }
    
    public function load(&$data = null) {
        parent::load($this->data);
    }
    
    public function register($bucket) {
        $id = $bucket->id();
        if($id){
            $this->buckets[$id] = $bucket;
            if(!array_key_exists($id, $this->data)){
                $this->data[$id] = array();
            }
            $bucket->load($this->data[$id]);
        }
    }
    
    public function data(){
        return $this->data;
    }

}