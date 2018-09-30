<?php

class SgEcom_Upsap_Model_Observer
{
    public function frontorderaccesspoint(Varien_Event_Observer $event)
    {
        $order = $event->getEvent()->getOrder();
        $ship_method = $order->getShippingMethod();
        $ship_method = explode("_", $ship_method);
        if ($ship_method[0] == 'upsap' && Mage::getStoreConfig('carriers/upsap/active' /*multistore*/, $order->getStoreId() /*multistore*/) == 1) {
            $accesspoint = Mage::getModel("upsap/accesspoint")->getCollection()->addFieldToFilter('order_id', $order->getId());
            if (count($accesspoint) == 0) {
                $session = Mage::getSingleton('customer/session');
                $accesspoint = Mage::getModel("upsap/accesspoint");
                $address = array();
                if ($session->getUpsapAddLine1()) {
                    $address['addLine1'] = $session->getUpsapAddLine1();
                    $session->unsUpsapAddLine1();

                    if ($session->getUpsapAddLine2()) {
                        $address['addLine2'] = $session->getUpsapAddLine2();
                        $session->unsUpsapAddLine2();
                    }
                    if ($session->getUpsapAddLine3()) {
                        $address['addLine3'] = $session->getUpsapAddLine3();
                        $session->unsUpsapAddLine3();
                    }
                    if ($session->getUpsapCity()) {
                        $address['city'] = $session->getUpsapCity();
                        $session->unsUpsapCity();
                    }
                    if ($session->getUpsapCountry()) {
                        $address['country'] = $session->getUpsapCountry();
                        $session->unsUpsapCountry();
                    }
                    if ($session->getUpsapFax()) {
                        $address['fax'] = $session->getUpsapFax();
                        $session->unsUpsapFax();
                    }
                    if ($session->getUpsapState()) {
                        $address['state'] = $session->getUpsapState();
                        $session->unsUpsapState();
                    }
                    if ($session->getUpsapPostal()) {
                        $address['postal'] = $session->getUpsapPostal();
                        $session->unsUpsapPostal();
                    }
                    if ($session->getUpsapAppuId()) {
                        $address['appuId'] = $session->getUpsapAppuId();
                        $session->unsUpsapAppuId();
                    }
                    if ($session->getUpsapName()) {
                        $address['name'] = $session->getUpsapName();
                        $session->unsUpsapName();
                    }
                    $accesspoint->setOrderId($order->getId());
                    $accesspoint->setAddress(json_encode($address));
                    $accesspoint->setAppuId($address['appuId']);
                    $accesspoint->save();
                }
            }
        }
        return $this;
    }

    public function frontordersavebefore(Varien_Event_Observer $event)
    {
        $order = $event->getEvent()->getOrder();
        if (Mage::getStoreConfig('carriers/upsap/active' /*multistore*/, $order->getStoreId() /*multistore*/) == 1) {
            $session = Mage::getSingleton('customer/session');
            $shippingAddress = $order->getShippingAddress();
            $ship_method = $order->getShippingMethod();
            $ship_method = explode("_", $ship_method);
            if ($ship_method[0] == 'upsap') {
                $street = array();
                if ($session->getUpsapAddLine1()) {
                    $street[0] = $session->getUpsapAddLine1();

                    if ($session->getUpsapAddLine2()) {
                        $street[1] = $session->getUpsapAddLine2();
                    }
                    if ($session->getUpsapAddLine3()) {
                        $street[1] = $street[1] . ' ' . $session->getUpsapAddLine3();
                    }
                    if ($session->getUpsapCity()) {
                        $shippingAddress->setCity($session->getUpsapCity());
                    }
                    if ($session->getUpsapCountry()) {
                        $shippingAddress->setCountry_id($session->getUpsapCountry());
                    }
                    if ($session->getUpsapFax() && trim($session->getUpsapFax()) != 'undefined') {
                        $shippingAddress->setFax($session->getUpsapFax());
                        $shippingAddress->setTelephone($session->getUpsapFax());
                    }
                    if ($session->getUpsapPostal()) {
                        $shippingAddress->setPostcode($session->getUpsapPostal());
                    }
                    if ($session->getUpsapName()) {
                        $shippingAddress->setCompany($session->getUpsapName());
                    }
                    $shippingAddress->setStreet($street);
                }
            }
        }
        return $this;
    }
}
