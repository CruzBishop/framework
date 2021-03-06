<?php
pload('pException');

/**
 * pAuthorizationException Description
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.exception
 * @since 1.0-sofia
 */
class pAuthorizationException extends pException {
    
    protected $responseCode = pHttpResponseCode::HTTP_403;
    
}