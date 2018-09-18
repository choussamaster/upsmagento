<?php

/**
 * 
 * @method string getMessage()
 * @method int getCode()
 *
 */
class Sirateck_Lemonway_Model_Apikit_Apimodels_LwError Extends Varien_Object{

	
	function __construct($arr = array()) {
		if(count($arr) > 0)
		{		
			$this->_data['code'] = $arr[0];
			$this->_data['message'] = $arr[1];
		}
	}
}
