<?php
pload('ISessionStorage');
pload('packfire.session.bucket.pSessionBucket');

/**
 * pSessionStorage class
 * 
 * Provides session storage access
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.session.storage
 * @since 1.0-sofia
 */
class pSessionStorage implements ISessionStorage {
    
    /**
     * The container of buckets
     * @var pMap
     * @since 1.0-sofia
     */
    private $buckets;
    
    /**
     * The overall storage
     * @var pSessionBucket
     * @since 1.0-sofia
     */
    private $overallBucket;
    
    /**
     * Create a new pSessionStorage object
     * @since 1.0-sofia 
     */
    public function __construct(){
        $this->buckets = new pMap();
        $this->overallBucket = new pSessionBucket($this->id());
        $this->registerHandler();
        $this->registerShutdown();
    }

    /**
     * Get the bucket ID for this storage
     * @return string Returns the bucket ID
     * @since 1.0-sofia
     */
    public function id() {
        return 'packfireDefault';
    }

    /**
     * Get a value from the default session bucket
     * @param string $key The key to identify value in the session bucket
     * @param mixed $default (optional) The default value to return if the key
     *              is not found in the session bucket. Defaults to null.
     * @return mixed Returns the value
     * @since 1.0-sofia
     */
    public function get($key, $default = null) {
        return $this->overallBucket->get($key, $default);
    }

    /**
     * Remove a value from the default session bucket
     * @param string $key The key to remove
     * @since 1.0-sofia
     */
    public function remove($key) {
        $this->overallBucket->remove($key);
    }

    /**
     * Set a value to the default session bucket
     * @param string $key The key to uniquely identify the value
     * @param mixed $data The value to set to the bucket
     * @since 1.0-sofia
     */
    public function set($key, $data) {
        $this->overallBucket->set($key, $data);
    }
    
    /**
     * Regenerate a new session ID
     * @param boolean $delete (optional) Set to delete old session or not
     * @since 1.0-sofia
     */
    public function regenerate($delete = false) {
        session_regenerate_id($delete);
    }
    
    /**
     * Register the session handler
     * @since 1.0-sofia 
     */
    protected function registerHandler(){
        if($this instanceof ISessionHandler
                || $this instanceof SessionHandlerInterface){
            session_set_save_handler(
                array($this, 'open'),
                array($this, 'close'),
                array($this, 'read'),
                array($this, 'write'),
                array($this, 'destroy'),
                array($this, 'gc')
            );
        }
    }
    
    /**
     * Register the write close shutdown function
     * @since 1.0-sofia 
     */
    protected function registerShutdown(){
        register_shutdown_function('session_write_close');
    }
    
    /**
     * Register a bucket to the storage
     * @param ISessionBucket $bucket The bucket to register
     * @since 1.0-sofia
     */
    public function register($bucket) {
        $id = $bucket->id();
        if($id){
            $this->buckets[$id] = $bucket;
            $bucket->load($_SESSION[$id]);
        }
    }
    
    public function bucket($id){
        return $this->buckets->get($id);
    }
    
    public function clear() {
        $this->overallBucket->clear();
    }

    public function load(&$data = null) {
        if(func_num_args() == 0){
            $data = &$_SESSION;
        }
        
        $this->overallBucket->load($data);
        
        foreach($this->buckets as $id => $bucket){
            if(!array_key_exists($id, $data)){
                $data[$id] = array();
            }
            $bucket->load($data[$id]);
        }
        
    }

    public function has($key){
        $this->overallBucket->has($key);
    }
    
}