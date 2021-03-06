<?php
pload('IView');
pload('packfire.collection.pList');
pload('packfire.collection.pMap');
pload('packfire.filter.IFilter');
pload('packfire.template.pTemplate');
pload('packfire.model.pObjectObserver');
pload('packfire.ioc.pBucketUser');

/**
 * The generic view class.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.view
 * @since 1.0-sofia
 */
abstract class pView extends pBucketUser implements IView {
    
    /**
     * The state that is passed from the controller
     * @var pMap
     * @since 1.0-sofia
     */
    protected $state;
    
    /**
     * The fields in the view defined
     * @var pMap
     * @since 1.0-sofia
     */
    private $fields;
    
    /**
     * The filters for the output fields
     * @var pList
     * @since 1.0-sofia
     */
    private $filters;
    
    /**
     * The template for the view to render
     * @var ITemplate 
     * @since 1.0-sofia
     */
    private $template;
    
    /**
     * The template for the view to render
     * @var pTheme 
     * @since 1.0-sofia
     */
    private $theme;
    
    /**
     * Create a new pView object 
     * @since 1.0-sofia
     */
    public function __construct(){
        $this->state = new pMap();
        $this->fields = new pMap();
        $this->filters = new pList();
    }
    
    /**
     * Define a template field to populate.
     * @param string|array|pMap $key Name of the field
     * @param mixed $value (optional) Set the template field value
     * @return mixed Returns the current value set at $key if $value is not set.
     * @since 1.0-sofia
     */
    public function define($key, $value = null){
        if(func_num_args() == 1){
            if(is_string($key)){
                return $this->fields[$key];
            }else{
                $this->fields->append($key);
            }
        }else{
            $this->fields[$key] = $value;
        }
    }
    
    /**
     * Bind a object property to a template field.
     * As the object property gets updated, the template field gets updated too.
     * 
     * @param string $key The template field to bind to
     * @param pObjectObserver $object The object to be binded
     * @param string $property The property of the object to bind to the
     *                  template field.
     * @since 1.1-sofia
     */
    public function bind($key, $object, $property){
        if($object instanceof pObjectObserver){
            $view = $this;
            $object->on('change', function($src, $eventArgs) use ($view, $key, $property) {
                if($eventArgs[0] == $property){
                    $view->define($key, $eventArgs[1]);
                }
            });
        }else{
            throw new pInvalidArgumentException(
                    sprintf('pView::bind() expects'
                        . ' parameter 2 to be an instance of pObjectObserver,'
                        . ' %s given instead.', dtype($object))
                    );
        }
    }
    
    /**
     * Set filters to a parameter.
     * 
     * @param string $name Name of the parameter to add filters to
     * @param IFilter|Closure|callback|array|IList $filter The controller filter,
     *              closure or callback that will process the parameter.
     *              If $filter is an array the method will run through the array
     *              recursively.
     * @since 1.0-sofia
     */
    protected function filter($name, $filter){
        if(is_string($filter)){
            $ex = explode('|', $filter);
            if(count($ex) > 1){
                $filter = $ex;
            }
        }
        if(is_array($filter) || $filter instanceof IList){
            foreach($filter as $f){
                $this->filter($name, $f);
            }
        }else{
            $this->filters[] = array($name, $filter);
        }
    }
    
    /**
     * Set the state from the controller to the view
     * @param pMap $state The state of the controller passed to the view.
     * @since 1.0-sofia
     */
    public function state($state){
        $this->state = $state;
    }
    
    /**
     * Set the template used by the view
     * @param ITemplate $template (optional) The template to use
     * @return pView Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function template($template){
        $this->template = $template;
        return $this;
    }
    
    /**
     * Set the theme used by the view
     * @param pTheme $theme The theme to use
     * @return pView Returns an instance of self for chaining.
     * @since 1.0-sofia
     */
    protected function theme($theme){
        $this->theme = $theme;
        return $this;
    }
    
    /**
     * Get a specific routing URL from the router service
     * @param string $key The routing key
     * @param array $params (optionl) URL Parameters 
     * @return string Returns the URL
     * @since 1.0-sofia
     */
    protected function route($key, $params = array()){
        $router = $this->service('router');
        $url = $router->to($key, $params);
        if(strlen($url) > 0 && $url[0] == '/'){
            $url = $this->service('config.app')->get('app', 'rootUrl') . $url;
        }
        return $url;
    }
    
    /**
     * Prepare and create the view fields
     * @since 1.0-sofia
     */
    protected abstract function create();
    
    /**
     * Get the output of the view.
     * @return string Returns the output of this view.
     * @since 1.0-sofia
     */
    public function render(){
        $output = '';
        ob_start();
        $this->create();
        $output = ob_get_contents();
        ob_end_clean();
        if($this->theme){
            // render the theme
            $this->theme->render();
            // forward the theme fields to the view
            foreach($this->theme->fields() as $key => $value){
                $this->define('theme.' . $key, $value);
            }
        }
        
        if($this->template){
            foreach($this->filters as $filter){
                $name = $filter[0];
                $filter = $filter[1];
                $value = $this->fields[$name];
                if(class_exists($filter)){
                    $filter = new $filter();
                }
                if($filter instanceof IFilter){
                    $value = $filter->filter($value);
                }elseif($filter instanceof Closure || is_callable($filter)){
                    $value = $filter($value);
                }
                $this->fields[$name] = $value;
            }
            
            // allow you to use another view in a view.
            foreach($this->fields as $key => $field){
                if($field instanceof IView){
                    $this->fields[$key] = $field->render();
                }
            }
            $this->template->set($this->fields);
            if($output){
                $this->template->fields()->add('view.output', $output);
            }
            $output = $this->template->parse();
        }
        return $output;
    }
    
}