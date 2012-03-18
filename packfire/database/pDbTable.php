<?php
pload('packfire.collection.pMap');

abstract class pDbTable {
    
    /**
     *
     * @var pDbDriver
     */
    protected $driver;
    
    /**
     * The name of this table
     * @var string
     */
    protected $name;
    
    public function __construct($connection, $name){
        $this->driver = $driver;
        $this->name = $name;
    }
    
    public function name(){
        return $this->name;
    }
    
    public abstract function create();
    
    public abstract function add($column);
    
    public abstract function remove($column);
    
    public abstract function insert($row);
    
    public abstract function delete($row);
    
    public abstract function update($row);
    
}