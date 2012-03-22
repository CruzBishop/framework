<?php

/**
 * A MySQL LINQ Join statement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.divers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinqJoin {
    
    /**
     * The target table
     * @var string
     * @since 1.0-sofia
     */
    private $target;
    
    /**
     * The outer table to join
     * @var string
     * @since 1.0-sofia
     */
    private $source;
    
    /**
     * The key in the inner table
     * @var string
     * @since 1.0-sofia
     */
    private $innerKey;
    
    /**
     * The key in the outer table
     * @var string
     * @since 1.0-sofia
     */
    private $outerKey;
    
    /**
     * The selector
     * @var string
     * @since 1.0-sofia
     */
    private $selector;
    
    /**
     * Create a new pMySqlLinqJoin object
     * @param string $target The table targeted
     * @param string $source The table to join
     * @param string $innerKey The inner key
     * @param string $outerKey The outer key
     * @param string $selector The selector 
     * @since 1.0-sofia
     */
    public function __construct($target, $source, $innerKey, $outerKey, $selector){
        $this->source = $source;
        $this->target = $target;
        $this->innerKey = $innerKey;
        $this->outerKey = $outerKey;
        $this->selector = $selector;
    }
    
    /**
     * Create the statement
     * @return string 
     * @since 1.0-sofia
     */
    public function create(){
        $join = '';
        if($this->selector){
            $join .= $this->selector .' ';
        }
        $join .= 'JOIN ' . $this->source . ' ON ';
        $join .= $this->target . '.' . $this->innerKey;
        $join .= ' = ';
        $join .= $this->source . '.' . $this->outerKey;
        
        return $join;
    }
    
}