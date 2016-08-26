<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Factory
 *
 * @author jinlee
 */
class Factory {
	static $_mapObject = array();
    private function __construct() {
        
    }

    static function create($sClass, $sClassType, $sApp) {    	
    	//$time_start = Factory::outPutTime();
        //var_dump(debug_backtrace());exit();
        //缓存对象,保证唯一。    	
        $sClassName = $sClass . '_' . $sClassType . '_' . $sApp;
        $arrClassName = explode('_', $sClassName);
        while (!class_exists($sClassName)) {
            array_pop($arrClassName);
            $sClassName = implode('_', $arrClassName);
        }        
        $object = self::$_mapObject[$sClassName];
        if (empty($object)) {        	
        	//$time_start = Factory::outPutTime();
            $object = new $sClassName($sClassType, $sApp);
            self::$_mapObject[$sClassName] = &$object;
        }else{//如果已经缓存的对象重新调用一边构造函数，保证读取新数据。
        	//$time_start = Factory::outPutTime();
            $object->__construct($sClassType, $sApp);
        }
                         
        /*$time_end = Factory::outPutTime();
        $time = $time_start-$time_end;
        if($sApp == 'BusApplyFile')
        	echo "$sClassName---$time</br>";*/
        return $object;
    }
    
    static function outPutTime(){
    	list($usec, $sec) = explode(" ", microtime());
    	$time = (float)$usec + (float)$sec;
    	return $time;
    }

    static function save($sClass, $object) {        
        $sKey = $sClass . '_' . $object->getClassType() . '_' . $object->getApp();        
        Registor::create()->set($sKey, $object);
    }

}

?>
