<?php
/**
 * @method int getWalletId() ID as defined by merchant
 * @method int getLwid() LWID number ID as defined by Lemon Way
 * @method int getStatus() {2,3,4,5,6,7,8,12}
 * @method float getBal() balance
 * @method string getName() full name
 * @method string getEmail()
 * @method array getKycDocs()
 * @method array getIbans()
 * @method array getssdMandates()
 */
class Sirateck_Lemonway_Model_Apikit_Apimodels_Wallet extends Varien_Object{
	
	function __construct($WALLETArr = array()) {
		if(count($WALLETArr))
		{
			
			$WALLET = $WALLETArr[0];
			$this->_data['wallet_id'] = $WALLET->ID;
			$this->_data['lwid'] = $WALLET->LWID;
			$this->_data['status'] = $WALLET->STATUS;
			$this->_data['bal'] = $WALLET->BAL;
			$this->_data['name'] = $WALLET->NAME;
			$this->_data['email'] = $WALLET->EMAIL;
			$this->_data['kyc_docs'] = array();
			if (isset($WALLET->DOCS))
				foreach ($WALLET->DOCS->DOC as $DOC){
					$this->_data['kyc_docs'][] = Mage::getModel('sirateck_lemonway/apikit_apimodels_kycDoc',array($DOC));//new KycDoc($DOC);
				}
			$this->_data['ibans'] = array();
			if (isset($WALLET->IBANS))
				foreach ($WALLET->IBANS->IBAN as $IBAN){
					$this->_data['ibans'][] = Mage::getModel('sirateck_lemonway/apikit_apimodels_iban',array($IBAN));//new Iban($IBAN);
				}
			$this->_data['ssd_mandates'] = array();
			if (isset($WALLET->SDDMANDATES))
				foreach ($WALLET->SDDMANDATES->SDDMANDATE as $SDDMANDATE){
					$this->_data['ssd_mandates'][] = Mage::getModel('sirateck_lemonway/apikit_apimodels_ssdMandate',array($SDDMANDATE));//new SddMandate($SDDMANDATE);
				}
		}
	}
	
}
