<?php

/**
 * IEventWatchable interface
 * 
 * A handler that allows others to watch events
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @copyright Copyright (c) 2010-2012, Sam-Mauris Yong
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.event
 * @since 1.1-sofia
 */
interface IEventWatchable {
    
    /**
     * Bind an event listener to an event of the class
     * @param string $event The name of the event
     * @param IObserver|Closure|callback $listener The function, method or
     *              observer to listen to this event
     * @since 1.1-sofia
     */
    public function on($event, $listener);
    
}