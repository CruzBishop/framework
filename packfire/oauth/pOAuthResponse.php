<?php
pload('packfire.net.http.pHttpResponse');
pload('IOAuthHttpEntity');
pload('packfire.collection.pMap');
pload('pOAuthHelper');

/**
 * pOAuthTokenResponse class
 * 
 * OAuth Response for any token requests
 *
 * @package packfire.oaut
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD Licenseh.response
 * @since 1.1-sofia
 */
class pOAuthResponse extends pHttpResponse implements IOAuthHttpEntity, IAppResponse {

    /**
     * The OAuth parameters
     * @var pMap
     * @since 1.1-sofia
     */
    private $oauthParams;
    
    /**
     * Create a new pOAuthResponse object
     */
    public function __construct() {
        parent::__construct();
        $this->oauthParams = new pMap();
    }
    
    /**
     * Get or set the OAuth parameters
     * @param string $key The OAuth parameter key
     * @param string $value (optional) If set, this value will be set to the key.
     * @return string Returns the value of the OAuth parameter if $value is not set.
     * @since 1.1-sofia
     */
    public function oauth($key, $value = null){
        if(func_num_args() == 2){
            $this->oauthParams->add($key, $value);
        }else{
            return $this->oauthParams->get($key);
        }
    }
    
    /**
     * Get or set the body of the OAuth response
     * @param string $body (optional) If set, the new value will be set.
     * @return string Returns the body response
     * @since 1.1-sofia
     */
    public function body($body = null){
        if(func_num_args() == 1){
            $output = array();
            parse_str(trim($body), $output);
            $this->oauthParams->append($output);
        }
        return pOAuthHelper::buildQuery($this->oauthParams);
    }
    
    public function output(){
        return $this->body();
    }

}