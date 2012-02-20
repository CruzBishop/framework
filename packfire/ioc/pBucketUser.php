<?php
pload('IBucketUser');

/**
 * A more concrete implementation of a bucket user
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.ioc
 * @since 1.0-sofia
 */
abstract class pBucketUser implements IBucketUser {
    
    /**
     * The bucket of services
     * @var pServiceBucket
     * @since 1.0-sofia
     */
    protected $services;
    
    /**
     * Set the service bucket to this user
     * @param pServiceBucket $bucket The bucket to let this user use.
     * @since 1.0-sofia 
     */
    public function setBucket($bucket){
        $this->services = $bucket;
    }
    
    /**
     * Get a service from the Service Bucket
     * @param string $service Name of the service to retrieve
     * @return object Returns the service object
     * @since 1.0-sofia
     */
    public function service($service){
        if($this->services == null){
            debug_print_backtrace();
        }
        return $this->services->pick($service);
    }
    
}