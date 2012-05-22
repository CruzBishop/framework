<?php
pload('packfire.collection.pList');
pload('packfire.collection.pMap');

/**
 * A parser that will process the command line
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.application
 * @since 1.0-sofia
 */
class pCommandParser {
    
    /**
     * The keys set in the argument line
     * @var pList
     * @since 1.0-sofia
     */
    private $keys;
    
    /**
     * The result of the parsing
     * @var pMap
     * @since 1.0-sofia
     */
    private $result;
    
    /**
     * Create a new pCommandParser object
     * @param array|pMap $arguments (optional) The argument to be parsed.
     *              If not specified, the arguments will be taken from 
     *              $_SERVER['args'].
     * @since 1.0-sofia
     */
    public function __construct($arguments = null){
        if(!$arguments){
            $arguments = $_SERVER['argv'];
        }
        $this->parse($arguments);
    }
    
    /**
     * Parses the command line into keys and result
     * @param array|pMap $arguments The arguments to be parsed
     * @since 1.0-sofia
     */
    private function parse($arguments){
        $this->result = new pMap();
        $this->keys = new pList();
        $lastKey = null;
        foreach($arguments as $arg){
            if($this->isKey($arg)){
                $lastKey = $this->cleanKey($arg);
                $this->keys->add($lastKey);
            }else{
                $this->set($lastKey, $arg);
            }
        }
    }
    
    /**
     * Check if an argument is a switch key
     * @param string $part The argument part
     * @return boolean Returns true if the switch is a key, and false otherwise
     * @since 1.0-sofia
     */
    private function isKey($part){
        $result = false;
        $firstChar = substr($part, 0, 1);
        if ($firstChar == '-' || ($firstChar == '/' && strlen($part) == 2)){
            $result = true;
        }
        return $result;
    }
    
    /**
     * Cleans a key and strip off the '--', '-' and '/' in front
     * @param string $key The key to be cleaned
     * @return string Returns the cleaned key
     * @since 1.0-sofia
     */
    private function cleanKey($key){
        if(substr($key, 0, 2) == '--'){
            $key = substr($key, 2);
        }else{
            $firstChar = substr($key, 0, 1);
            if ($firstChar == '-' || ($firstChar == '/' && strlen($key) == 2)){
                $key = substr($key, 1);
            }
        }
        return $key;
    }
    
    /**
     * Set a value to a key in the result
     * @param string $key The key
     * @param string $value The value
     * @since 1.0-sofia
     */
    private function set($key, $value){
        if($this->result->keyExists($key) 
                && ($this->result[$key] instanceof pList)){
            $this->result[$key]->add($value);
        }else if($this->result->keyExists($key)){
            $tmp = new pList();
            $tmp->add($this->result->get($key));
            $tmp->add($value);
            $this->result->add($key, $tmp);
        }else{
            $this->result->add($key, $value);
        }
    }
    
    /**
     * Get the full result of the parser
     * @return pMap Returns the array containing the result
     * @since 1.0-sofia
     */
    public function result(){
        $map = new pMap($this->result->toArray());
        $map->append($this->keys);
        return $map;
    }
    
    /**
     * Get the value(s) of a switch key
     * @param string $switch The switch key. Can be 's', '-s' or '/s'.
     * @param string $alternate The alternative switch key.
     *                      Can be '/switch' or '--switch'.
     * @return mixed Returns the value of the key
     * @since 1.0-sofia
     */
    public function getValue($switch, $alternate = null){
        $switch = $this->cleanKey($switch);
        $result = $this->result->get($switch);
        if($alternate){
            $alternate = $this->cleanKey($alternate);
            $altResult = $this->result->get($alternate);
            if($result == null){
                $result = $altResult;
            }else{
                if($result instanceof pList && $altResult instanceof pList){
                    $result->append($altResult);
                }else if($result instanceof pList){
                    $result->add($altResult);
                }else if($altResult != null){
                    $result = new pList(array($result, $altResult));
                }
            }
        }
        return $result;
    }
    
    /**
     * Check if a switch is flagged
     * @param string $switch The switch key. Can be 's', '-s' or '/s'.
     * @param string $alternate The alternative switch key.
     *                      Can be '/switch' or '--switch'.
     * @return boolean Returns true if flagged, and false otherwise.
     * @since 1.0-sofia
     */
    public function isFlagged($switch, $alternate = null){
        $switch = $this->cleanKey($switch);
        $result = $this->keys->contains($switch);
        if($alternate){
            $alternate = $this->cleanKey($alternate);
            $result = $result || $this->keys->contains($alternate);
        }
        return $result;
    }
    
}