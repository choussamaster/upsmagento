<?php

class SgEcom_Upsap_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{
    protected $_code = 'upsap';
    private $ratesUps = null;
    private $ratesUpsWithNR = null;
    private $ratesUpsWithoutNR = null;
    private $orderAmount = 0;
    private $freeQty = null;
    private $storeId = 0;
    private $ups;

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        if (Mage::registry('isUpsapGotRates') !== null) {
            return Mage::registry('isUpsapGotRates');
        }

        /* @var $result Mage_Shipping_Model_Rate_Result */
        $result = Mage::getModel('shipping/rate_result');

        if (($request->getDestCountryId() && $request->getDestPostcode() /*&& $request->getDestCity()*/)) {
            $this->storeId = Mage::app()->getStore()->getId();
            $quantity = $request->getPackageQty();
            $this->orderAmount = $request->getOrderSubtotal();
            if ($this->orderAmount <= 0) {
                $totals = Mage::getSingleton('checkout/cart')->getQuote()->getTotals();
                $this->orderAmount = $totals["subtotal"]->getValue();
                if ($this->orderAmount <= 0) {
                    $items = $request->getAllItems();
                    $subtotal = 0;
                    foreach ($items as $item) {
                        $subtotal += $item->getProduct()->getPrice();
                    }
                    $this->orderAmount = $subtotal;
                }
            }

            $weight = $request->getPackageWeight();
            $zip = $request->getDestPostcode();

            $quote = Mage::helper('checkout')->getQuote()->getShippingAddress()->getData();
            $userGroupId = 0;


            if (isset($quote['total_qty']) && Mage::getSingleton('customer/session')->isLoggedIn()) {
                $userGroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
            } elseif (!isset($quote['total_qty'])) {
                $userGroupId = Mage::getSingleton('adminhtml/session_quote')->getCustomer()->getGroupId();
                $this->storeId = Mage::getSingleton('adminhtml/session_quote')->getStore()->getId();
            }

            if ($this->freeQty === null) {
                $this->freeQty = 0;
                if ($request->getAllItems()) {
                    //$freePackageValue = 0;
                    foreach ($request->getAllItems() as $item) {
                        if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                            continue;
                        }

                        if ($item->getHasChildren() && $item->isShipSeparately()) {
                            foreach ($item->getChildren() as $child) {
                                if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                                    $freeShipping = is_numeric($child->getFreeShipping()) ? $child->getFreeShipping() : 0;
                                    $this->freeQty += $item->getQty() * ($child->getQty() - $freeShipping);
                                }
                            }
                        } else if ($item->getFreeShipping()) {
                            $freeShipping = is_numeric($item->getFreeShipping()) ? $item->getFreeShipping() : 0;
                            $this->freeQty += $item->getQty() - $freeShipping;
                        }
                    }
                }
            }

            $this->ups = Mage::getModel('upsap/ups');
            $this->ups->invoiceLineTotal = $this->orderAmount;
            $this->ups->currency = Mage::getStoreConfig('currency/options/base', $this->storeId);
            $this->ups = Mage::helper('upsap/ups')->setParams($this->ups, $request);

            $model = Mage::getModel('upsap/method')->getCollection()
                ->addFieldToFilter(array('is_store_all', 'store_id'), array(array('eq' => 0), array(
                        array(
                            array('like' => '%,' . $this->storeId . ',%'),
                            array('like' => '%,' . $this->storeId),
                            array('like' => $this->storeId . ',%'),
			                array('like' => $this->storeId),
                        )
                    )
                    )
                )
                ->addFieldToFilter('status', 1)
                ->addFieldToFilter('amount_min', array(array('lteq' => $this->orderAmount), array('eq' => 0)))
                ->addFieldToFilter('amount_max', array(array('gteq' => $this->orderAmount), array('eq' => 0)))
                ->addFieldToFilter('weight_min', array(array('lteq' => $weight), array('eq' => 0)))
                ->addFieldToFilter('weight_max', array(array('gteq' => $weight), array('eq' => 0)))
                ->addFieldToFilter('qty_min', array(array('lteq' => $quantity), array('eq' => 0)))
                ->addFieldToFilter('qty_max', array(array('gteq' => $quantity), array('eq' => 0)))
                ->addFieldToFilter('zip_min', array(array('lteq' => $zip), array('eq' => ''), array('null' => true)))
                ->addFieldToFilter('zip_max', array(array('gteq' => $zip), array('eq' => ''), array('null' => true)))
                ->addFieldToFilter('user_group_ids', array(array('eq' => ''), array('null' => true), array('like' => "%," . $userGroupId . ",%")))
                ->addFieldToFilter(array('is_country_all', 'country_ids'), array(array('eq' => 0), array('like' => '%' . $request->getDestCountryId() . '%')));
            foreach ($model as $method) {
                $methodEnd = $this->_getStandardShippingRate($request, $method);
                if ($methodEnd !== false) {
                    $result->append($methodEnd);
                }
            }
        }

        if (Mage::registry('isUpsapGotRates') === null) {
            Mage::register('isUpsapGotRates', $result);
        }

        return $result;
    }

    protected function _getStandardShippingRate(Mage_Shipping_Model_Rate_Request $request, $method)
    {
        $this->storeId = Mage::app()->getStore()->getId();
        $this->configMethod = Mage::getModel('upsap/config_upsmethod');

        $rate = Mage::getModel('shipping/rate_result_method');
        $rate->setCarrier($this->_code);

        if (strlen(Mage::getStoreConfig('carriers/upsap/title', $this->storeId)) > 0) {
            $rate->setCarrierTitle(Mage::getStoreConfig('carriers/upsap/title', $this->storeId));
        }

        $mTitle = $method->getName();

        $ratePrice = $method->getPrice();

        if (($request->getFreeShipping() == true || ($request->getPackageQty() == $this->freeQty)) && $method->getFreeShipping() == 1) {
            $ratePrice = 0;
        } else {
            if ($method->getDinamicPrice() == 1) {

                if($method->getNegotiated() == 1 && $this->orderAmount >= $method->getNegotiatedAmountFrom()) {
                    if ($this->ratesUpsWithNR === null) {
                        $this->ratesUpsWithNR = $this->ups->getShipRate(1);
                    }

                    $this->ratesUps = $this->ratesUpsWithNR;
                } else if($method->getNegotiated() == 0 || $this->orderAmount < $method->getNegotiatedAmountFrom()) {
                    if ($this->ratesUpsWithoutNR === null) {
                        $this->ratesUpsWithoutNR = $this->ups->getShipRate(0);
                    }

                    $this->ratesUps = $this->ratesUpsWithoutNR;
                }

                if (isset($this->ratesUps[$method->getUpsmethodId()])) {
                    $ratecode2 = $this->ratesUps[$method->getUpsmethodId()];
                    if (isset($ratecode2['def'])) {
                        $nameOfPriceType = 'def';

                        if($method->getNegotiated() == 1 && $this->orderAmount >= $method->getNegotiatedAmountFrom()){
                            if($method->getTax() == 1 && isset($ratecode2['nrtax'])){
                                $nameOfPriceType = 'nrtax';
                            } else {
                                $nameOfPriceType = 'nr';
                            }
                        } else {
                            if($method->getTax() == 1 && isset($ratecode2['deftax'])){
                                $nameOfPriceType = 'deftax';
                            }
                        }

                        if(isset($ratecode2[$nameOfPriceType])) {
                            $ratePrice = (float)$ratecode2[$nameOfPriceType]['price'];
                            $rateCurrency = (string)$ratecode2[$nameOfPriceType]['currency'];
                        } else {
                            Mage::log("Upsap Error: Price type ".$nameOfPriceType." does not exist", null, 'upsap_debug.log');
                            Mage::log($this->ratesUps, null, 'upsap_debug.log');
                            return false;
                        }

                        $to = Mage::app()->getStore()->getCurrentCurrencyCode();
                        if ($rateCurrency != $to) {
                            $baseCurrency = Mage::app()->getStore()->getBaseCurrencyCode();
                            $allowedCurrencies = Mage::getModel('directory/currency')->getConfigAllowCurrencies();
                            $rates = Mage::getModel('directory/currency')->getCurrencyRates($baseCurrency, array_values($allowedCurrencies));
                            if (isset($rates[$rateCurrency]) && $rates[$rateCurrency] > 0) {
                                $basePrice = $ratePrice / $rates[$rateCurrency];
                            } else {
                                $basePrice = $ratePrice / str_replace(",", ".", Mage::getStoreConfig('carriers/upsap/rate', $this->storeId));
                            }
                            if ($baseCurrency != $to) {
                                $ratePrice = Mage::helper('directory')->currencyConvert($ratePrice, $baseCurrency, $to);
                            } else {
                                $ratePrice = $basePrice;
                            }
                        }

                        if ($method->getTimeintransit() == 1 && isset($ratecode2['day'])) {
                            if ($method->getTitShowFormat() === 'days') {
                                $mTitle .= ' (' . ($ratecode2['day']['days'] + $method->getAddday()) . Mage::helper('adminhtml')->__(' day(s)') . ')';
                            } else if ($method->getTitShowFormat() === 'datetime') {
                                $dateFormat = new \Datetime($ratecode2['day']['datetime']['date']);
                                $mTitle .= ' (' . $dateFormat->format('d') . ' ' . Mage::helper('adminhtml')->__($dateFormat->format('F')) . ' ' . $dateFormat->format('Y') . ' ' . substr($ratecode2['day']['datetime']['time'], 0, -3) . ')';
                            }
                        }
                    } else {
                        $message = $this->ratesUps;
                        Mage::log($message, null, 'upsap_debug.log');
                        return false;
                    }
                } else {
                    $message = $this->ratesUps;
                    Mage::log($message, null, 'upsap_debug.log');
                    return false;
                }

                if ($method->getAddedValue() != 0 && $method->getAddedValue() != "") {
                    if ($method->getAddedValueType() == 'static') {
                        $ratePrice += (float)str_replace(",", ".", $method->getAddedValue());
                    } else {
                        $ratePrice += ($ratePrice / 100) * str_replace(",", ".", $method->getAddedValue());
                    }
                }
            }
        }

        $rate->setMethod($method->getId());

        $rate->setMethodTitle($mTitle);

        if ($ratePrice > 1 && Mage::getStoreConfig('carriers/upsap/price_format', $this->storeId) == 1) {
            $ratePrice = ceil($ratePrice * 10) / 10;
        }

        $rate->setPrice(round($ratePrice, Mage::getStoreConfig('carriers/upsap/price_format', $this->storeId)));
        $rate->setCost(0);
        return $rate;
    }

    public function getAllowedMethods()
    {

        if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getStore()) > 0) {
            $storeId = Mage::getModel('core/store')->load($code)->getId();
        } else if (strlen($code = Mage::getSingleton('adminhtml/config_data')->getWebsite())) {
            $website_id = Mage::getModel('core/website')->load($code)->getId();
            $storeId = Mage::app()->getWebsite($website_id)->getDefaultStore()->getId();
        } else {
            $storeId = Mage::app()->getStore()->getId();
        }

        $arrMethods = array();
        $model = Mage::getModel('upsap/method')->getCollection();

        if ($storeId !== null && $storeId != 0) {
            $model->addFieldToFilter(array('is_store_all', 'store_id'), array(array('eq' => 0), array(
                    array(
                        array('like' => '%,' . $storeId . ',%'),
                        array('like' => '%,' . $storeId),
                        array('like' => $storeId . ',%'),
                        array('like' => $storeId),
                    )
                )
                )
            );
        }

        $model->addFieldToFilter('status', 1);
        foreach ($model AS $method) {
            $arrMethods[$method->getId()] = $method->getTitle();
        }

        return $arrMethods;
    }
}
