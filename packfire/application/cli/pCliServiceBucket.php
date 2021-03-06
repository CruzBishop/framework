<?php
pload('packfire.ioc.pBucketLoader');
pload('packfire.routing.cli.pCliRouter');
pload('packfire.config.framework.pCliRouterConfig');

/**
 * pCliServiceBucket class
 * 
 * The CLI application service bucket loader
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2012, Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.cli
 * @since 1.0-elenor
 */
class pCliServiceBucket extends pBucketLoader {
    
    /**
     * Perform loading of services for CLI
     * @since 1.0-elenor
     */
    public function load(){
        $this->put('config.routing', array('pCliRouterConfig', 'load'));
        $this->put('router', new pCliRouter());
        if($this->pick('config.app')){
            // load the debugger
            $this->put('debugger', new pDebugger());
            $this->pick('debugger')->enabled(false);
        }
    }
    
}