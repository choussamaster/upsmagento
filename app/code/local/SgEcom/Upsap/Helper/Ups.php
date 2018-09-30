
<?php

class SgEcom_Upsap_Helper_Ups extends Mage_Core_Helper_Abstract
{
    public
    function setParams($lbl, $request)
    {
        $configOptions = new SgEcom_Upsap_Model_Config_Options;
        /*multistore*/
        $storeId = Mage::app()->getStore()->getId();
        $lbl->storeId = $storeId;
        /*multistore*/

        $packages = array();
        $packages[0]['weight'] = $request->getPackageWeight();
        $packages[0]['large'] = $request->getPackageWeight() > 90 ? '<LargePackageIndicator />' : '';
        $lbl->shiptoStateProvinceCode = SgEcom_Upsap_Helper_Data::escapeXML($request->getDestRegionCode());
        $lbl->shiptoCity = $request->getDestCity();
        $lbl->shiptoPostalCode = $request->getDestPostcode();
        $lbl->shiptoCountryCode = $request->getDestCountryId();
        $lbl->shipmentIndicationType = Mage::getStoreConfig('carriers/upsap/type', $storeId);
        if (Mage::getStoreConfig('carriers/upsap/company_type', $storeId) == 'upssgecom'/*Mage::helper('core')->isModuleOutputEnabled("SgEcom_Upslabel")*/) {

            $packages = $this->intermediateHandy($request, $storeId);

            $lbl->AccessLicenseNumber = Mage::getStoreConfig('upslabel/credentials/accesslicensenumber', $storeId);
            $lbl->UserId = Mage::getStoreConfig('upslabel/credentials/userid', $storeId);
            $lbl->Password = Mage::getStoreConfig('upslabel/credentials/password', $storeId);
            $lbl->shipperNumber = Mage::getStoreConfig('upslabel/credentials/shippernumber', $storeId);

            $lbl->shipperCountryCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId) . '/countrycode', $storeId));
            $lbl->shipperCity = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId) . '/city', $storeId));
            $lbl->shipperStateProvinceCode = SgEcom_Upsap_Helper_Data::escapeXML($configOptions->getProvinceCode(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId) . '/stateprovincecode', $storeId), $lbl->shipperCountryCode));
            $lbl->shipperPostalCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId) . '/postalcode', $storeId));
            $lbl->shipperResidential = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipper', $storeId) . '/residential', $storeId)) == "Y"?1:0;

            $lbl->shipfromCountryCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipfrom', $storeId) . '/countrycode', $storeId));
            $lbl->shipfromStateProvinceCode = SgEcom_Upsap_Helper_Data::escapeXML($configOptions->getProvinceCode(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipfrom', $storeId) . '/stateprovincecode', $storeId), $lbl->shipfromCountryCode));
            $lbl->shipfromPostalCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('upslabel/address_' . Mage::getStoreConfig('upslabel/shipping/defaultshipfrom', $storeId) . '/postalcode', $storeId));

            $lbl->residentialAddress = 0;
            if(Mage::getStoreConfig('upslabel/shipping/dest_type', $storeId) == 1) {
                $lbl->residentialAddress = 1;
            } else if(Mage::getStoreConfig('upslabel/shipping/dest_type', $storeId) == 0) {
                $lbl->residentialAddress = strlen(Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCompany())!=0?0:1;
            }

            $lbl->includeDimensions = Mage::getStoreConfig('upslabel/weightdimension/includedimensions', $storeId);
            $lbl->weightUnits = Mage::getStoreConfig('upslabel/weightdimension/weightunits', $storeId);
            $lbl->unitOfMeasurement = Mage::getStoreConfig('upslabel/weightdimension/unitofmeasurement', $storeId);
            /*$lbl->weightUnits = Mage::helper('upslabel/help')->getWeightUnitByCountry($lbl->shiptoCountryCode);
            if (Mage::getStoreConfig('upslabel/weightdimension/weightunits', $storeId) != $lbl->weightUnits) {
                if ($lbl->weightUnits == 'KGS') {
                    $lbl->weightUnitKoef = 2.2046;
                } else {
                    $lbl->weightUnitKoef = 1 / 2.2046;
                }
            }

            $lbl->unitOfMeasurement = Mage::helper('upslabel/help')->getDimensionUnitByCountry($lbl->shiptoCountryCode);
            if (Mage::getStoreConfig('upslabel/weightdimension/unitofmeasurement', $storeId) != $lbl->unitOfMeasurement) {
                if ($lbl->unitOfMeasurement == 'CM') {
                    $lbl->dimentionUnitKoef = 2.54;
                } else {
                    $lbl->dimentionUnitKoef = 1 / 2.54;
                }
            }*/

            if (Mage::getStoreConfig('upslabel/quantum/adult', $storeId) != 1 || strpos(Mage::getStoreConfig('upslabel/quantum/adult_allow_country', $storeId), $lbl->shiptoCountryCode) !== FALSE) {
                $lbl->adult = SgEcom_Upslabel_Helper_Help::escapeXML(Mage::getStoreConfig('upslabel/quantum/adult', $storeId));
            }

            $lbl->testing = Mage::getStoreConfig('upslabel/testmode/testing', $storeId);
        } else {
            $typeCodes = array(
                'CP' => '00', // Customer Packaging
                'ULE' => '01', // UPS Letter Envelope
                'CSP' => '02', // Customer Supplied Package
                'UT' => '03', // UPS Tube
                'PAK' => '04', // PAK
                'UEB' => '21', // UPS Express Box
                'UW25' => '24', // UPS Worldwide 25 kilo
                'UW10' => '25', // UPS Worldwide 10 kilo
                'PLT' => '30', // Pallet
                'SEB' => '2a', // Small Express Box
                'MEB' => '2b', // Medium Express Box
                'LEB' => '2c', // Large Express Box
            );
            $packages[0]['packagingtypecode'] = $typeCodes[Mage::getStoreConfig('carriers/ups/container', $storeId)];
            $packages[0]['packweight'] = 0;
            $packages[0]['additionalhandling'] = strlen(Mage::getStoreConfig('carriers/ups/handling_fee', $storeId)) > 0 && Mage::getStoreConfig('carriers/ups/handling_fee', $storeId) > 0 ? '<AdditionalHandling />' : '';

            $lbl->AccessLicenseNumber = Mage::getStoreConfig('carriers/ups/access_license_number', $storeId);
            $lbl->UserId = Mage::getStoreConfig('carriers/ups/username', $storeId);
            $lbl->Password = Mage::getStoreConfig('carriers/ups/password', $storeId);
            $lbl->shipperNumber = Mage::getStoreConfig('carriers/ups/shipper_number', $storeId);

            $lbl->residentialAddress = Mage::getStoreConfig('carriers/ups/dest_type', $storeId)=="RES"?1:0;

            $lbl->shipperCity = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('shipping/origin/city', $storeId));
            $region = Mage::getStoreConfig('shipping/origin/region_id', $storeId);
            if(is_numeric($region)){
                $region = Mage::getModel('directory/region')->load($region);
                $region = $region->getCode();
            }
            $lbl->shipperStateProvinceCode = SgEcom_Upsap_Helper_Data::escapeXML($region);
            $lbl->shipperPostalCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('shipping/origin/postcode', $storeId));
            $lbl->shipperCountryCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('shipping/origin/country_id', $storeId));

            $lbl->shipfromStateProvinceCode = SgEcom_Upsap_Helper_Data::escapeXML($region);
            $lbl->shipfromPostalCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('shipping/origin/postcode', $storeId));
            $lbl->shipfromCountryCode = SgEcom_Upsap_Helper_Data::escapeXML(Mage::getStoreConfig('shipping/origin/country_id', $storeId));

            $lbl->weightUnits = Mage::getStoreConfig('carriers/ups/unit_of_measure', $storeId);
            $lbl->includeDimensions = 0;
            $lbl->testing = Mage::getStoreConfig('carriers/ups/mode_xml', $storeId) == 1 ? 0 : 1;
        }

        $lbl->packages = $packages;

        return $lbl;
    }

    private function intermediateHandy(Mage_Shipping_Model_Rate_Request $request, $storeId)
    {
        $countProductInBox = 0;
        $dimensionSets = Mage::getModel("upslabel/config_defaultdimensionsset")->toOptionArray(/*multistore*/
            $storeId /*multistore*/);
        $i = 0;
        $packages = array();
        if (count($dimensionSets) > 0) {
            $packer = new SgEcom_Upslabel_Model_Packer_Packer();
            $cartHelper = Mage::helper('checkout/cart');
            $shipmentAllItems = $cartHelper->getCart()->getItems();
            foreach ($shipmentAllItems AS $item) {
                if ($item->getOrderItemId()) {
                    $item = $this->imOrder->getItemById($item->getOrderItemId());
                }

                if (!$item->isDeleted() && !$item->getParentItemId()) {
                    $itemData = $item->getData();
                    $myproduct = Mage::getModel('catalog/product')->load($itemData['product_id']);
                    $myproduct = $myproduct->getData();
                    if (
                        isset($myproduct['width']) && $myproduct['width'] != ""
                        && isset($myproduct['height']) && $myproduct['height'] != ""
                        && isset($myproduct['length']) && $myproduct['length'] != ""
                    ) {
                        $countProductInBox++;
                    } else {
                        $countProductInBox = 0;
                        Mage::getSingleton('adminhtml/session')->addError("Product " . $myproduct['name'] . " does not have width or height or length");
                        break;
                    }
                    for ($ik = 0; $ik < $itemData['qty']; $ik++) {
                        $packer->addItem(new SgEcom_Upslabel_Model_Packer_TestItem($itemData['price'], $myproduct['width'], $myproduct['length'], $myproduct['height'], $itemData['weight'], true));
                    }
                }
            }
            if ($countProductInBox > 0) {
                foreach ($dimensionSets AS $v) {
                    if ($v['value'] !== 0) {
                        $packer->addBox(new SgEcom_Upslabel_Model_Packer_TestBox(
                            $v['value'],
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/outer_width', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/outer_length', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/outer_height', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/emptyWeight', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/width', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/length', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/height', $storeId),
                            Mage::getStoreConfig('upslabel/dimansion_' . $v['value'] . '/maxWeight', $storeId)
                        ));
                    }
                }
                $packedBoxes = $packer->pack();
                if ($packer->isError == false && count($packedBoxes) > 0) {
                    foreach ($packedBoxes as $packedBox) {
                        $itemData = array();
                        $boxType = $packedBox->getBox();
                        $itemData['width'] = $boxType->getOuterWidth();
                        $itemData['length'] = $boxType->getOuterLength();
                        $itemData['height'] = $boxType->getOuterDepth();
                        $itemData['weight'] = $packedBox->getWeight();
                        $itemsInTheBox = $packedBox->getItems();
                        $itemData['price'] = 0;
                        foreach ($itemsInTheBox as $item) {
                            $itemData['price'] += $item->getDescription();
                        }
                        $packages[] = $this->setDefParams($itemData/*multistore*/, $storeId /*multistore*/);
                        $i++;
                    }
                } else {
                    $countProductInBox = 0;
                }
            }
        }

        if ($countProductInBox == 0) {
            $packages = array();
            $packages[0] = $this->setDefParams(array('weight' => $request->getPackageWeight())/*multistore*/, $storeId /*multistore*/);
        }
        return $packages;
    }

    private function setDefParams($itemData/*multistore*/, $storeId/*multistore*/)
    {
        $packages = array();
        $packages['weight'] = $itemData['weight'];
        if (isset($itemData['width'], $itemData['height'], $itemData['length'])) {
            $packages['width'] = $itemData['width'];
            $packages['height'] = $itemData['height'];
            $packages['length'] = $itemData['length'];
        }
        $packages['large'] = $itemData['weight'] > 90 ? '<LargePackageIndicator />' : '';
        $packages['packagingtypecode'] = Mage::getStoreConfig('upslabel/packaging/packagingtypecode', $storeId);
        if (!isset($itemData['width'], $itemData['height'], $itemData['length'])) {
            $packages['packweight'] = round(Mage::getStoreConfig('upslabel/weightdimension/packweight', $storeId), 1) > 0 ? round(Mage::getStoreConfig('upslabel/weightdimension/packweight', $storeId), 1) : '0';
        } else {
            $packages['packweight'] = '0';
        }
        $packages['additionalhandling'] = Mage::getStoreConfig('upslabel/ratepayment/additionalhandling', $storeId) == 1 ? '<AdditionalHandling />' : '';
        $packages['insuredmonetaryvalue'] = 0;
        return $packages;
    }
}
