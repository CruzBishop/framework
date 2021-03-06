<?php
pload('packfire.config.IConfig');

/**
 * tMockConfig class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.test
 * @since 1.0-sofia
 */
class tMockConfig implements IConfig {
    
    private $data;
    
    public function __construct(){
        $this->data = array(
            'app' => array(
                'rootUrl' => 'http://example.com/test'
            )
        );
    }
    
    public function get() {
        $keys = func_get_args();
        $data = $this->data;
        foreach($keys as $key){
            if(is_array($data)){
                if(array_key_exists($key, $data)){
                    $data = $data[$key];
                }else{
                    $data = null;
                    break;
                }
            }else{
                break;
            }
        }
        return $data;
    }
   
}