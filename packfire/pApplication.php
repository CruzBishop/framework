<?php
pload('IApplication');
pload('packfire.routing.pRoute');
pload('packfire.routing.pRouter');
pload('packfire.collection.pMap');
pload('packfire.net.http.pHttpResponse');
pload('packfire.ioc.pBucketUser');
pload('packfire.ioc.pServiceBucket');
pload('packfire.config.framework.pAppConfig');
pload('packfire.config.framework.pRouterConfig');
pload('packfire.session.pSession');
pload('packfire.session.storage.pSessionStorage');
pload('packfire.ioc.pServiceLoader');

/**
 * Application class
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire
 * @since 1.0-sofia
 */
class pApplication extends pBucketUser implements IApplication {
    
    /**
     * Create the pApplication object 
     */
    public function __construct(){
        $this->services = new pServiceBucket();
        $this->loadBucket();
    }
    
    /**
     * Load the bucket of services 
     * @since 1.0-sofia
     */
    protected function loadBucket(){
        $this->services->put('config.app', array('pAppConfig', 'load'));
        $this->services->put('config.routing', array('pRouterConfig', 'load'));
        $this->services->put('router', $this->loadRouter());
        pServiceLoader::loadConfig($this->services);
        
        $storageId = $this->service('config.app')->get('service', 'storageId');
        $storage = null;
        if($storageId){
            $storage = $this->service($storageId);
        }else{
            $storage = new pSessionStorage();
        }
        $this->services->put('session', new pSession($storage));
    }
    
    /**
     * Receive a request, process, and respond.
     * @param pHttpClientRequest $request The request made
     * @return IAppResponse Returns the http response
     * @since 1.0-sofia
     */
    public function receive($request){
        $router = $this->loadRouter();
        $response = $this->prepareResponse($request);
        $route = $router->route($request);
        if(is_null($route)){
            // todo: page not found
        }else{
            if(strpos($route->actual(), ':')){
                list($class, $action) = explode(':', $route->actual());
            }else{
                $class = $route->actual();
                $action = '';
            }

            if($class instanceof Closure){
                $response = $class($request, $route, $response);
            }else{
                if(is_string($class)){
                    // call controller
                    
                    list($package, $class) =
                            pClassLoader::resolvePackageClass($class);
                    
                    if(substr($class, -11) != 'Controller'){
                        $package .= 'Controller';
                        $class .= 'Controller';
                    }
                    pload('controller.' . $package);
                }

                if(class_exists($class)){
                    $controller = new $class($request, $response);
                    if($controller instanceof IBucketUser){
                        $controller->setBucket($this->services);
                    }
                    $controller->run($route, $action);
                    $response = $controller;
                }
            }
        }
        return $response;
    }
    
    /**
     * Create and prepare the response
     * @param pHttpRequest $request The request to respond to
     * @return pHttpResponse Returns the response prepared
     * @since 1.0-sofia
     */
    protected function prepareResponse($request){
        $response = new pHttpResponse();
        return $response;
    }
    
    /**
     * Load the router and its configuration
     * @return pRouter Returns the router
     * @since 1.0-sofia
     */
    private function loadRouter(){
        $router = new pRouter();
        $settings = $this->service('config.routing');
        $routes = $settings->get();
        foreach($routes as $key => $data){
            $data = new pMap($data);
            $route = new pRoute($data->get('rewrite'), $data->get('actual'),
                    $data->get('method'), $data->get('params'));
            $router->add($key, $route);
        }
        return $router;
    }
    
}
