<?php
pload('packfire.ioc.pBucketLoader');
pload('packfire.session.pSessionLoader');
pload('packfire.config.framework.pRouterConfig');

/**
 * pHttpServiceBucket class
 * 
 * Application service bucket that loads the application's HTTP services
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application.http
 * @since 1.0-sofia
 */
class pHttpServiceBucket extends pBucketLoader {
    
    /**
     * Perform loading
     * @since 1.0-elenor
     */
    public function load(){
        $this->put('config.routing', array('pRouterConfig', 'load'));
        if($this->pick('config.app') && $this->pick('config.app')->get('session', 'enabled')){
            // load the session
            $sessionLoader = new pSessionLoader($this);
            $sessionLoader->load();
        }
    }

}