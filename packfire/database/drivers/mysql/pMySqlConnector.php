<?php
pload('packfire.database.pDbConnector');

/**
 * Provides functionalities to and operations of a MySQL table
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.database.drivers.mysql
 * @since 1.0-sofia
 */
class pMySqlConnector extends pDbConnector {
    
    /**
     *
     * @param PDOStatement $statement
     * @param array|pMap $params 
     * @since 1.0-sofia
     */
    public function binder($statement, $params){
        foreach($params as $name => $value){
            if($value instanceof pDbExpression){
                $statement->bindValue($name, $value->expression(), PDO::PARAM_STMT);
            }else{
                $statement->bindValue($name, $value);
            }
        }
    }
    
    public function translateType($type) {
        $types = array(
            'pk' => 'int(11) NOT NULL auto_increment',
            'string' => 'varchar(255)',
            'integer' => 'int(11)',
            'timestamp' => 'datetime',
            'binary' => 'blob',
            'boolean' => 'tinyint(1)'
        );
        if(array_key_exists($type, $types)){
            return $types[$type];
        }
        return $type;
    }
    
    public function database(){
        $database = new pMySqlDatabase($this);
        if($this->config['dbname']){
            $database = $database->select($this->config['dbname']);
        }
        return $database;
    }
    
}