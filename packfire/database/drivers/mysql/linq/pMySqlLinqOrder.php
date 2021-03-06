<?php
pload('IMySqlLinqQuery');

/**
 * pMySqlLinqOrder class 
 * 
 * A MySQL LINQ Order statement
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql.linq
 * @since 1.0-sofia
 */
class pMySqlLinqOrder implements IMySqlLinqQuery {
    
    /**
     * The field to sort
     * @var string
     * @since 1.0-sofia
     */
    private $field;
 
    /**
     * Flags whether the order is descending or not
     * @var boolean
     * @since 1.0-sofia
     */
    private $descending;
    
    /**
     * Create a new pMySqlLinqOrder object
     * @param string $field The field to sort
     * @param boolean $descending (optional) Sets whether the order is descending
     *                or not, defaults to false.
     * @since 1.0-sofia
     */
    public function __construct($field, $descending = false){
        $this->descending = $descending;
        $this->field = $field;
    }
    
    /**
     * Create and get the statement
     * @return string Returns the resulting statement
     * @since 1.0-sofia
     */
    public function create(){
        return $this->field . ($this->descending ? ' DESC': '');;
    }
    
}