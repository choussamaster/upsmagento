<?php

class Sirateck_Lemonway_Model_Apikit_Apimodels_KycDoc {

	/**
     * ID as defined by Lemon Way
     * @var string
     */
    public $ID;
	
	/**
     * STATUS {1,2,3,4,5}
     * @var string
     */
    public $STATUS;
	
	/**
     * TYPE {0,1,2,3,4,5,6,7,11,12,13,14,15,16,17,18,19,20}
     * @var string
     */
    public $TYPE;
	
	/**
     * VD validity date
     * @var string
     */
    public $VD;
	
	function __construct($nodeArr) {
		$node = $nodeArr[0];
		$this->ID = $node->ID;
		$this->STATUS = $node->S;
		$this->TYPE = $node->TYPE;
		$this->VD = $node->VD;
	}
}
