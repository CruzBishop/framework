<?php
pload('ILinqQuery');

/**
 * A worker query that allows the specification of a worker closure or callback.
 *
 * @author Sam-Mauris Yong / mauris@hotmail.sg
 * @license http://www.opensource.org/licenses/bsd-license New BSD License
 * @package packfire.plinq
 * @since 1.0-sofia
 */
interface ILinqWorkerQuery extends ILinqQuery {
    
    public function worker();
    
}
