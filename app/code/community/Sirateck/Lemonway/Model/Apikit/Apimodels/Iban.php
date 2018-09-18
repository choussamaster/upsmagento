<?php
/**
 * @method int getIbanId() ID as defined by merchant
 * @method int getStatus() {0,5,6,8,9}
 * @method string getIban() iban number
 * @method string getSwift() BIC or swift code
 */
class Sirateck_Lemonway_Model_Apikit_Apimodels_Iban extends Varien_Object{
	
	function __construct($nodeArr=array()) {
		if(count($nodeArr))
		{		
			$node = $nodeArr[0];
			$this->_data['iban_id'] = $node->ID;
			if (isset($node->STATUS))
				$this->_data['status'] = $node->STATUS;
			if (isset($node->S))
				$this->_data['status'] = $node->S;
			if (isset($node->DATA))
				$this->_data['iban'] = $node->DATA;
			if (isset($node->SWIFT))
				$this->_data['swift'] = $node->SWIFT;
		}
	}
	
}
