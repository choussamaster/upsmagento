<?php
/**
 * @method string getType() {p2p, moneyin, moneyout}
 * @method int getHpayId() id lemonway
 * @method string getLabelCode() IBAN or Card number
 * @method string getSenderWallet()  sender wallet id
 * @method string getReceiverWallet() receiver wallet id
 * @method float getDebitAmount() amount debited from sender wallet
 * @method float getCreditAmount() amount credited to receiver wallet or external bank account
 * @method float getFees() fees automatically sent to merchant wallet
 * @method string getMessage() comment
 * @method int getStatus() {0,3,4}
 * @method string getErrorMessage() internal error message with codes
 * @method EXTRA getExtra()  Detailed information regarding Card payment
 * 
 * @author kassim belghait
 *
 */
class Sirateck_Lemonway_Model_Apikit_Apimodels_Operation extends Varien_Object {


	
	function __construct($hpayXmlArr = array()) {
		if(count($hpayXmlArr))
		{
			
			$hpayXml = $hpayXmlArr[0];
			$this->_data['hpay_id'] = (int)$hpayXml->ID;
			$this->_data['sender_wallet'] = (string)$hpayXml->SEN;
			$this->_data['receiver_wallet'] = (string)$hpayXml->REC;
			$this->_data['debit_amount'] = (float)$hpayXml->DEB;
			$this->_data['credit_amount'] = (float)$hpayXml->CRED;
			$this->_data['fees'] = (float)$hpayXml->COM;
			$this->_data['status'] = (int)$hpayXml->STATUS;
			$this->_data['label_code'] = (string)$hpayXml->MLABEL;
			$this->_data['error_message'] = (string)$hpayXml->INT_MSG;
			$this->_data['extra'] = new EXTRA($hpayXml->EXTRA);
		}
	}
}

/**
 * Detailed information regarding Card payment
 */
class EXTRA{
	/**
     * IS3DS indicates if payment was 3D Secure
     * @var bool
     */
	public $IS3DS;
	
	/**
     * CTRY country of card
     * @var string
     */
	public $CTRY;
	
	/**
     * AUTH authorization number
     * @var string
     */
	public $AUTH;
	
	/**
	 * Number of registered card
	 * @var string
	 * @since api version 1.8
	 */
	public $NUM;
	
	/**
	 * Expiration date of registered card
	 * @var string
	 * @since api version 1.8
	 */
	public $EXP;

	/**
	 * Type of card
	 * @var string
	 * @since api version 1.8
	 */
	public $TYP;
	
	function __construct($extraXml) {
		$this->AUTH = $extraXml->AUTH;
		$this->IS3DS = $extraXml->IS3DS;
		$this->CTRY = $extraXml->CTRY;
		$this->NUM = $extraXml->NUM;
		$this->EXP = $extraXml->EXP;
		$this->TYP = $extraXml->TYP;
	}
}
