<?php
/**
 * Sirateck_Lemonway extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category       Sirateck
 * @package        Sirateck_Lemonway
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Method  standard model
 *
 * @category    Sirateck
 * @package     Sirateck_Lemonway
 * @author Kassim Belghait kassim@sirateck.com
 */
 class Sirateck_Lemonway_Model_Method_Webkit extends Mage_Payment_Model_Method_Abstract{


    protected $_code  = 'lemonway_webkit';
    protected $_formBlockType = 'sirateck_lemonway/form_webkit';
    protected $_infoBlockType = 'sirateck_lemonway/info_webkit';

    /**
     * Availability options
     */
    protected $_isGateway              = true;
    protected $_canAuthorize           = true;
    protected $_canCapture             = true;
    protected $_canCapturePartial      = false;
    protected $_canRefund              = false;
    protected $_canVoid                = false;
    protected $_canUseInternal         = false;
    protected $_canUseCheckout         = true;
    protected $_canUseForMultishipping = false;
    protected $_isInitializeNeeded     = true; // when initialize we get token for webkit url

    protected $_order;

    protected $_supportedLangs = array(
        'en',
        'no',
        'sp',
        'fr',
        'xz',
        'ge',
        'it',
        'br',
        'da',
        'fi',
        'sw',
        'po',
        'fl',
        'ci',
        'pl',
        'ne',
        'ru'
    );
    protected $_defaultLang = 'en';
    
    const IS_TEST_MODE = 'is_test_mode';
    
    /**
     * @return Mage_Checkout_Model_Session
     */
    protected function _getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    /**
     * Assign data to info model instance
     *
     * @param   mixed $data
     * @return  Mage_Payment_Model_Info
     */
    public function assignData($data)
    {
        if (!($data instanceof Varien_Object)) {
            $data = new Varien_Object($data);
        }
        $info = $this->getInfoInstance();
    
        $info->setAdditionalInformation('register_card',$data->getOneclic() == "register_card" ? 1 : 0)
        ->setAdditionalInformation('use_card',$data->getOneclic() == "use_card" ? 1 : 0);
    
        return $this;
    }

    public function initialize($paymentAction, $stateObject){
        $useCard = (bool)$this->getInfoInstance()->getAdditionalInformation('use_card');
        $registerCard = (bool)$this->getInfoInstance()->getAdditionalInformation('register_card');
        
        //Init APi kit
        /* @var $kit Sirateck_Lemonway_Model_Apikit_Kit */
        $kit = Mage::getSingleton('sirateck_lemonway/apikit_kit');
        
        $amountCom = 0;
        /*if(!Mage::helper('core')->isModuleEnabled('Sirateck_Lemonwaymkt')){
            $amountCom = $this->getOrder()->getBaseGrandTotal();
        }
        else{
            $seller_totals = Mage::helper('lemonwaymkt')->getOrderCommissionDetails($this->getOrder());
            if($seller_totals->getTotalSellerAmount() > 0 && !Mage::getStoreConfigFlag('sirateck_lemonway/lemonwaymkt/include_shipping')){
                $amountCom = $this->getOrder()->getBaseGrandTotal() - ($seller_totals->getTotalSellerAmount() + $seller_totals->getTotalCommision());
            }
        }*/
        
        $comment = Mage::helper('sirateck_lemonway')->__(
            "%s - Order #%s by %s %s %s",
            Mage::app()->getStore()->getName(),
            $this->getOrder()->getIncrementId(),
            $this->getOrder()->getCustomerLastname(),
            $this->getOrder()->getCustomerFirstname(),
            $this->getOrder()->getCustomerEmail()
        );
        
        //We call MoneyInwebInit and save token in session
        //Token is used in getOrderRedirectUrl method
        if(!$useCard)
        {
            //call directkit to get Webkit Token
            $params = array(
                    'wkToken' => $this->getOrder()->getIncrementId(),
                    'wallet' => $this->getHelper()->getConfig()->getWalletMerchantId(),
                    'amountTot' => sprintf("%.2f" ,(float)$this->getOrder()->getBaseGrandTotal()),
                    'amountCom' => sprintf("%.2f" ,(float)$amountCom),
                    'comment' => $comment,
                    'returnUrl' => urlencode(Mage::getUrl($this->getConfigData('return_url'))),
                    'cancelUrl' => urlencode(Mage::getUrl($this->getConfigData('cancel_url'))),
                    'errorUrl' => urlencode(Mage::getUrl($this->getConfigData('error_url'))),
                    'autoCommission' => 1,
                    'registerCard' => $registerCard, //For Atos
                    'useRegisteredCard' => $registerCard || $useCard, //For payline
            );
            $this->_debug($params);
            
            
            $res = $kit->MoneyInWebInit($params);
            
            $this->_debug($res);
    
            if (isset($res->lwError)){
                Mage::throwException("Error code: " . $res->lwError->getCode() . " Message: " . $res->lwError->getMessage());
            }
            $moneyInToken = (string)$res->lwXml->MONEYINWEB->TOKEN;
            $this->getInfoInstance()->setAdditionalInformation('moneyin_token',$moneyInToken);
            $this->_getCheckout()->setMoneyInToken($moneyInToken);
            
            $hasCardId = isset($res->lwXml->MONEYINWEB->CARD->ID); 
            
            //Save CardId if register card asked by user
            if($registerCard && $hasCardId){
                
                $cardId = (string)$res->lwXml->MONEYINWEB->CARD->ID;
                
                $customer = Mage::getModel('customer/customer')->load($this->getOrder()->getCustomerId());
                if($customer->getId())
                {               
                    $customer->setLwCardId($cardId);            
                    $customer->getResource()->saveAttribute($customer, 'lw_card_id');
                }
            }
        }
        else{ //Customer want to use his last card, so we call MoneyInWithCardID directly
            
            $customer = Mage::getModel('customer/customer')->load($this->getOrder()->getCustomerId());
            if($customer->getId())
            {
                $cardId = $customer->getLwCardId();
                //call directkit for MoneyInWithCardId
                $params = array(
                        'wkToken' => $this->getOrder()->getIncrementId(),
                        'wallet' => $this->getHelper()->getConfig()->getWalletMerchantId(),
                        'amountTot' => sprintf("%.2f" ,(float)$this->getOrder()->getBaseGrandTotal()),
                        'amountCom' => sprintf("%.2f" ,(float)$amountCom),
                        'message' => $comment . " -- "  .Mage::helper('sirateck_lemonway')->__('Oneclic mode (card id: %s)', $cardId),
                        'autoCommission' => 1,
                        'cardId' => $cardId, 
                        'isPreAuth' => 0, 
                        'specialConfig' => '',
                        'delayedDays' => 6 //not used because isPreAuth always false
                );
                
                $this->_debug($params);
                $res = $kit->MoneyInWithCardId($params);
                $this->_debug($res);
                
                if (isset($res->lwError)){
                    Mage::throwException("Error code: " . $res->lwError->getCode() . " Message: " . $res->lwError->getMessage());
                }
                
                /* @var $op Sirateck_Lemonway_Model_Apikit_Apimodels_Operation */
                foreach ($res->operations as $op) {
                    if($op->getStatus() == "3")
                    {
                        $this->getOrder()->getPayment()->setAmountAuthorized($this->getOrder()->getTotalDue());
                        $this->getOrder()->getPayment()->setBaseAmountAuthorized($this->getOrder()->getBaseTotalDue());
                        $this->getOrder()->getPayment()->capture(null);
                        $stateObject->setState(Mage_Sales_Model_Order::STATE_PROCESSING);
                        $stateObject->setStatus(true);
                        $stateObject->setIsNotified(false);
                        
                        $this->getOrder()->setCanSendNewEmailFlag(true);
                        break;
                    }
                        
                }
                
            }
            else{
                Mage::throwException(Mage::helper('sirateck_lemonway')->__("Customer not found!"));
            }
        }

    }

    /**
     * Retrieve payment iformation model object
     *
     * @return Mage_Payment_Model_Info
     */
    public function getInfoInstance()
    {
        $instance = $this->getData('info_instance');
        if (!($instance instanceof Mage_Payment_Model_Info)) {
            Mage::throwException(Mage::helper('payment')->__('Cannot retrieve the payment information object instance.'));
        }
        return $instance;
    }

    /**
     * Get order model
     *
     * @return Mage_Sales_Model_Order
     */
    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = $this->getInfoInstance()->getOrder();
        }
        return $this->_order;
    }

    /**
     * Return url for redirection after order placed
     * @return string
     */
    public function getOrderPlaceRedirectUrl()
    {
        $moneyInToken = $this->_getCheckout()->getMoneyInToken();
        if(!empty($moneyInToken)){
            
            $this->_getCheckout()->unsMoneyInToken();
    
            $cssUrl = $this->getConfigData('css_url');
            $redirectUrl = $this->getHelper()->getConfig()->getWebkitUrl() . "?moneyintoken=" . $moneyInToken . '&p=' . urlencode($cssUrl) . '&lang=' . $this->getLang();
            Mage::log($redirectUrl, null, "debug_lw.log");
            //Redirect to webkit page
            return $redirectUrl;
        }
        
        return false;
    }


    /**
     * Return url current lang code
     *
     * @return string
     */
    public function getLang()
    {
        $locale = explode('_', Mage::app()->getLocale()->getLocaleCode());
        if (is_array($locale) && !empty($locale) && in_array($locale[0], $this->_supportedLangs)) {
            return $locale[0];
        }
        return $this->getDefaultLang();
    }
    
    /**
     * @return Sirateck_Lemonway_Helper_Data
     */
    public function getHelper(){
        return Mage::helper('sirateck_lemonway');
    }

    /*
     Generate random ID for wallet IDs or tokens
     */
    private function getRandomId(){
        return str_replace('.', '', microtime(true).rand());
    }

 }
