<?php
pload('packfire.application.http.pHttpApplication');

/**
 * pSetupHttpApplication class
 * 
 * The setup application accessible through Web GUI interface
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.setup.pack.app
 * @since 1.0-elenor
 */
class pSetupHttpApplication extends pHttpApplication {
    
    /**
     * Handle the exception for the setup application
     * @param Exception $exception The exception to be handled.
     * @since 1.0-sofia
     */
    public function handleException($exception) {
        var_dump($exception);
        die('Exception Occurred');
    }
    
}