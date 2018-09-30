
<?php

class SgEcom_Upsap_Adminhtml_Upsap_IndexController extends Mage_Adminhtml_Controller_Action
{

    protected function _isAllowed()
    {
        return true;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public
    function setSessionAddressAPAction()
    {
        $address = Mage::app()->getRequest()->getParams();
        $session = Mage::getSingleton('customer/session');
        if (isset($address['upsap_addLine1'])) {
            $session->setUpsapAddLine1($address['upsap_addLine1']);

            if (isset($address['upsap_addLine2'])) {
                $session->setUpsapAddLine2($address['upsap_addLine2']);
            }
            if (isset($address['upsap_addLine3'])) {
                $session->setUpsapAddLine3($address['upsap_addLine3']);
            }
            if (isset($address['upsap_city'])) {
                $session->setUpsapCity($address['upsap_city']);
            }
            if (isset($address['upsap_country'])) {
                $session->setUpsapCountry($address['upsap_country']);
            }
            if (isset($address['upsap_fax'])) {
                $session->setUpsapFax($address['upsap_fax']);
            }
            if (isset($address['upsap_state'])) {
                $session->setUpsapState($address['upsap_state']);
            }
            if (isset($address['upsap_postal'])) {
                $session->setUpsapPostal($address['upsap_postal']);
            }
            if (isset($address['upsap_appuId'])) {
                $session->setUpsapAppuId($address['upsap_appuId']);
            }
            if (isset($address['upsap_name'])) {
                $session->setUpsapName($address['upsap_name']);
            }
        }
        $this->getResponse()->setBody(json_encode($address));
        return;
    }

    public
    function getSessionAddressAPAction()
    {
        $session = Mage::getSingleton('customer/session');
        $address = array();
        if ($session->getUpsapAddLine1()) {
            $address['addLine1'] = $session->getUpsapAddLine1();

            if ($session->getUpsapAddLine2()) {
                $address['addLine2'] = $session->getUpsapAddLine2();
            }
            if ($session->getUpsapAddLine3()) {
                $address['addLine3'] = $session->getUpsapAddLine3();
            }
            if ($session->getUpsapCity()) {
                $address['city'] = $session->getUpsapCity();
            }
            if ($session->getUpsapCountry()) {
                $address['country'] = $session->getUpsapCountry();
            }
            if ($session->getUpsapFax()) {
                $address['fax'] = $session->getUpsapFax();
            }
            if ($session->getUpsapState()) {
                $address['state'] = $session->getUpsapState();
            }
            if ($session->getUpsapPostal()) {
                $address['postal'] = $session->getUpsapPostal();
            }
            if ($session->getUpsapAppuId()) {
                $address['appuId'] = $session->getUpsapAppuId();
            }
            if ($session->getUpsapName()) {
                $address['name'] = $session->getUpsapName();
            }
            $this->getResponse()->setBody(json_encode($address));
            return;
        } else {
            $this->getResponse()->setBody(json_encode(array("error" => "empty")));
            return;
        }
    }

    public
    function customerAddressAction()
    {
        $address = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getShippingAddress();
        /*$addressId = Mage::app()->getRequest()->getParam('id');
         $address = Mage::getModel('customer/address')->load((int)$addressId);*/
        if (!$address) {
            $address = Mage::getSingleton('adminhtml/session_quote')->getQuote()->getBillingAddress();
        }
        $address->explodeStreetAddress();
        if ($address->getRegionId()) {
            $region = Mage::getModel('directory/region')->load($address->getRegionId());
            $state_code = $region->getCode();
            $address->setRegion($state_code);
        }
        $this->getResponse()->setBody(json_encode($address->getData()));
        return;
    }

    public
    function getShippingMethodsAction()
    {
        $storeId = 1;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        /*multistore*/
        if (Mage::getStoreConfig('carriers/upsap/active') == 1) {
            $this->getResponse()->setBody(json_encode(array(
                /*'methods' => Mage::getStoreConfig('carriers/upsap/shipping_method', $storeId),*/
                'countries' => Mage::getStoreConfig('carriers/upsap/specificcountry', $storeId)
            )));
            return;
        } else {
            $this->getResponse()->setBody('{}');
            return;
        }
    }
}
