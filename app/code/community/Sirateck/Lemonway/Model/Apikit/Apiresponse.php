<?php
class Sirateck_Lemonway_Model_Apikit_Apiresponse{
	
	function __construct($xmlResponseArr) {
		$xmlResponse = $xmlResponseArr[0];
		$this->lwXml = $xmlResponse;
		if (isset($xmlResponse->E)){
			$this->lwError = Mage::getModel("sirateck_lemonway/apikit_apimodels_lwError",array($xmlResponse->E->Code, $xmlResponse->E->Msg));
		}
    }
	
	/**
     * lwXml
     * @var SimpleXMLElement
     */
    public $lwXml;
	
	/**
     * lwError
     * @var Sirateck_Lemonway_Model_Apikit_Apimodels_LwError
     */
    public $lwError;
	
	/**
     * wallet
     * @var Sirateck_Lemonway_Model_Apikit_Apimodels_Wallet
     */
    public $wallet;
	
	/**
     * operations
     * @var array Sirateck_Lemonway_Model_Apikit_Apimodels_Operation
     */
    public $operations;
	
	/**
     * kycDoc
     * @var Sirateck_Lemonway_Model_Apikit_Apimodels_KycDoc
     */
    public $kycDoc;
	
	/**
     * iban
     * @var Sirateck_Lemonway_Model_Apikit_Apimodels_Iban
     */
    public $iban;
	
	/**
     * sddMandate
     * @var Sirateck_Lemonway_Model_Apikit_Apimodels_SddMandate
     */
    public $sddMandate;
}

?>