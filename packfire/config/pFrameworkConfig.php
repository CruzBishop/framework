<?php
pload('pConfigFactory');

/**
 * Framework Application configuration parser
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.config
 * @since 1.0-sofia
 */
class pFrameworkConfig {
    
    /**
     * Load an application configuration file located the the config folder.
     * @param string $name Name of the configuration file to load
     * @param string $context (optional) The context from which we are loading
     *                        the configuration file. $context can be any string
     *                        value such as 'local', 'test' or 'live' to determine
     *                        what values are loaded.
     * @return pConfig Returns a pConfig that has read and parsed the configuration file,
     *                 or NULL if the file is not recognized or not found.
     * @since 1.0-sofia
     */
    public static function load($name, $context = __ENVIRONMENT__){
        $path = __APP_ROOT__ . 'pack/config/' . $name;
        
        // parsers
        $testFiles = array(
            'yml' => 'pYamlConfig',
            'yaml' => 'pYamlConfig',
            'ini' => 'pIniConfig',
        );
        
        $factory = new pConfigFactory();
        
        if($context){
            foreach($testFiles as $type => $class){
                if(is_file($path . '.' . $context . '.' . $type)){
                    return $factory->load($path . '.' . $context . '.' . $type);
                }
            }
        }
        
        // fall back if with context the file is not found
        foreach($testFiles as $type => $class){
            if(is_file($path . '.' .  $type)){
                return $factory->load($path . '.' . $type);
            }
        }
        
        return null;
    }
    
}