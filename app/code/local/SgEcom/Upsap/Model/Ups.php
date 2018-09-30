
<?php

class SgEcom_Upsap_Model_Ups
{

    public $AccessLicenseNumber;
    public $UserId;
    public $Password;
    public $shipperNumber;

    public $packages;
    public $weightUnits;
    public $packageWeight;
    public $weightUnitsDescription;
    public $largePackageIndicator;
    public $dimentionUnitKoef = 1;
    public $weightUnitKoef = 1;

    public $shipperCity;
    public $shipperStateProvinceCode;
    public $shipperPostalCode;
    public $shipperCountryCode;
    public $shipmentDescription;
    public $shipperAttentionName;
    public $shipperResidential=0;

    public $shiptoCity;
    public $shiptoStateProvinceCode;
    public $shiptoPostalCode;
    public $shiptoCountryCode;

    public $shipfromCity;
    public $shipfromStateProvinceCode;
    public $shipfromPostalCode;
    public $shipfromCountryCode;
    public $residentialAddress = '01';
    public $invoiceLineTotal = 0;
    public $currency = '';
    public $shipmentIndicationType = '01';

    public $testing;

    public $adult = 0;

    public $storeId = null;

    public function timeInTransit($weightSum = 0.1)
    {
        $cie = 'onlinetools';
        $data = "<?xml version=\"1.0\" ?>
<AccessRequest xml:lang='en-US'>
<AccessLicenseNumber>" . $this->AccessLicenseNumber . "</AccessLicenseNumber>
<UserId>" . $this->UserId . "</UserId>
<Password>" . $this->Password . "</Password>
</AccessRequest>
<?xml version=\"1.0\" ?>
<TimeInTransitRequest xml:lang='en-US'>
<Request>
<TransactionReference>
<CustomerContext>Shipper</CustomerContext>
<XpciVersion>1.0002</XpciVersion>
</TransactionReference>
<RequestAction>TimeInTransit</RequestAction>
</Request>
<TransitFrom>
<AddressArtifactFormat>
<CountryCode>" . $this->shipfromCountryCode . "</CountryCode>
<PostcodePrimaryLow>" . $this->shipfromPostalCode . "</PostcodePrimaryLow>
</AddressArtifactFormat>
</TransitFrom>
<TransitTo>
<AddressArtifactFormat>
<PoliticalDivision2>" . $this->shiptoCity . "</PoliticalDivision2>
<PoliticalDivision1>" . $this->shiptoStateProvinceCode . "</PoliticalDivision1>
<CountryCode>" . $this->shiptoCountryCode . "</CountryCode>
<PostcodePrimaryLow>" . $this->shiptoPostalCode . "</PostcodePrimaryLow>
</AddressArtifactFormat>
</TransitTo>
<ShipmentWeight>
<UnitOfMeasurement>
<Code>" . $this->weightUnits . "</Code>
</UnitOfMeasurement>
<Weight>" . $weightSum . "</Weight>
</ShipmentWeight>
<PickupDate>" . date('Ymd') . "</PickupDate>
<DocumentsOnlyIndicator />";
        if ($this->shiptoCountryCode != $this->shipfromCountryCode) {
            $data .= "<InvoiceLineTotal><MonetaryValue>" . $this->invoiceLineTotal . "</MonetaryValue><CurrencyCode>" . $this->currency . "</CurrencyCode></InvoiceLineTotal>";
        }
        $data .= "
</TimeInTransitRequest>";
        $curl = Mage::helper('upsap');

        $result = $curl->curlSend('https://' . $cie . '.ups.com/ups.app/xml/TimeInTransit', $data);
        if (Mage::getStoreConfig('carriers/upsap/debug') == 1) {
            Mage::log($data, null, 'upsap_debug.log');
            Mage::log($result, null, 'upsap_debug.log');
        }

        if (!$curl->error) {
            $xml = $this->xml2array(simplexml_load_string($result));
            if ($xml['Response']['ResponseStatusCode'] == 0 || $xml['Response']['ResponseStatusDescription'] != 'Success') {
                return array('error' => 1);
            } else {
                $countDay = array();
                if (isset($xml['TransitResponse'])) {
                    foreach ($xml['TransitResponse']['ServiceSummary'] as $v) {
                        $codes = $curl->getUpsCode($v['Service']['Code']);
                        if (!is_array($codes)) {
                            $codes = array($codes);
                        }

                        if (isset($v['EstimatedArrival']['TotalTransitDays'])) {
                            foreach ($codes as $v2) {
                                $countDay[$v2]['days'] = $v['EstimatedArrival']['TotalTransitDays'];
                                $countDay[$v2]['datetime']['date'] = $v['EstimatedArrival']['Date'];
                                $countDay[$v2]['datetime']['time'] = $v['EstimatedArrival']['Time'];
                            }

                        } else if (isset($v['EstimatedArrival']['BusinessTransitDays'])) {
                            foreach ($codes as $v2) {
                                $countDay[$v2]['days'] = $v['EstimatedArrival']['BusinessTransitDays'];
                                $countDay[$v2]['datetime']['date'] = $v['EstimatedArrival']['Date'];
                                $countDay[$v2]['datetime']['time'] = $v['EstimatedArrival']['Time'];
                            }
                        }
                    }
                }

                return array('error' => 0, 'days' => $countDay);
            }
        } else {
            return $result;
        }
    }

    public function xml2array($xmlObject, $out = array())
    {
        foreach ((array)$xmlObject as $index => $node) {
            $out[$index] = (is_object($node)) ? $this->xml2array($node) : (is_array($node)) ? $this->xml2array($node) : $node;
        }

        return $out;
    }

    function getShipRate($nr = 0)
    {
        $weightSum = 0;
        $data = "<?xml version=\"1.0\" ?>
<AccessRequest xml:lang='en-US'>
<AccessLicenseNumber>" . $this->AccessLicenseNumber . "</AccessLicenseNumber>
<UserId>" . $this->UserId . "</UserId>
<Password>" . $this->Password . "</Password>
</AccessRequest>
<?xml version=\"1.0\"?>
<RatingServiceSelectionRequest xml:lang=\"en-US\">
  <Request>
    <TransactionReference>
      <CustomerContext>Rating and Service</CustomerContext>
      <XpciVersion>1.0</XpciVersion>
    </TransactionReference>
    <RequestAction>Rate</RequestAction>
    <RequestOption>Shop</RequestOption>
  </Request>
  <PickupType>
          <Code>03</Code>
          <Description>Customer Counter</Description>
  </PickupType>
  <Shipment>
  <TaxInformationIndicator/>";
        if ($nr == 1) {
            $data .= "
   <RateInformation>
      <NegotiatedRatesIndicator/>
    </RateInformation>";
        }
        $data .= "<Shipper>";
        $data .= "<ShipperNumber>" . $this->shipperNumber . "</ShipperNumber>
      <Address>
    	<City>" . $this->shipperCity . "</City>
    	<StateProvinceCode>" . $this->shipperStateProvinceCode . "</StateProvinceCode>
    	<PostalCode>" . $this->shipperPostalCode . "</PostalCode>
    	<CountryCode>" . $this->shipperCountryCode . "</CountryCode>";
        if ($this->shipperResidential == 1) {
            $data .= "<ResidentialAddressIndicator/>";
        }
        $data .= "</Address>
    </Shipper>
	<ShipTo>
      <Address>
        <StateProvinceCode>" . $this->shiptoStateProvinceCode . "</StateProvinceCode>
        <PostalCode>" . $this->shiptoPostalCode . "</PostalCode>
        <CountryCode>" . $this->shiptoCountryCode . "</CountryCode>";
        if ($this->residentialAddress == 1) {
            $data .= "<ResidentialAddressIndicator/>";
        }
        $data .= "
      </Address>
    </ShipTo>
    <ShipFrom>
      <Address>
    	<StateProvinceCode>" . $this->shipfromStateProvinceCode . "</StateProvinceCode>
    	<PostalCode>" . $this->shipfromPostalCode . "</PostalCode>
    	<CountryCode>" . $this->shipfromCountryCode . "</CountryCode>";
        if ($this->shipperResidential == 1) {
            $data .= "<ResidentialAddressIndicator/>";
        }
        $data .= "</Address>
    </ShipFrom>";
$data .= "<AlternateDeliveryAddress>
<Address>";
        if($this->shiptoCity != '') {
            $data .= "<City>" . $this->shiptoCity . "</City>";
        }
            $data .= "<StateProvinceCode>" . $this->shiptoStateProvinceCode . "</StateProvinceCode>
        <PostalCode>" . $this->shiptoPostalCode . "</PostalCode>
        <CountryCode>" . $this->shiptoCountryCode . "</CountryCode>";

$data .= "</Address>
</AlternateDeliveryAddress>";
        $data .= "<ShipmentIndicationType><Code>". $this->shipmentIndicationType ."</Code></ShipmentIndicationType>";
        foreach ($this->packages as $pv) {
            $data .= "<Package>
      <PackagingType>
        <Code>" . $pv["packagingtypecode"] . "</Code>
      </PackagingType>";
            $data .= array_key_exists('additionalhandling', $pv) ? $pv['additionalhandling'] : '';
            if ($this->includeDimensions == 1) {
                $data .= "<Dimensions>
<UnitOfMeasurement>
<Code>" . $this->unitOfMeasurement . "</Code>";
                $data .= "</UnitOfMeasurement>";
                if (!isset($pv['dimansion_id']) || $pv['dimansion_id'] == 0) {
                    if (isset($pv['length']) && strlen($pv['length']) > 0) {
                        $data .= "<Length>" . ($pv['length'] * $this->dimentionUnitKoef) . "</Length>
<Width>" . ($pv['width'] * $this->dimentionUnitKoef) . "</Width>
<Height>" . ($pv['height'] * $this->dimentionUnitKoef) . "</Height>";
                    }
                } else {
                    $data .= "<Length>" . (Mage::getStoreConfig('upslabel/dimansion_' . $pv['dimansion_id'] . '/length', $this->storeId) * $this->dimentionUnitKoef) . "</Length>
<Width>" . (Mage::getStoreConfig('upslabel/dimansion_' . $pv['dimansion_id'] . '/width', $this->storeId) * $this->dimentionUnitKoef) . "</Width>
<Height>" . (Mage::getStoreConfig('upslabel/dimansion_' . $pv['dimansion_id'] . '/height', $this->storeId) * $this->dimentionUnitKoef) . "</Height>";
                }
                $data .= "</Dimensions>";
            }
            $data .= "<PackageWeight>
        <UnitOfMeasurement>
            <Code>" . $this->weightUnits . "</Code>";
            $packweight = array_key_exists('packweight', $pv) ? $pv['packweight'] : '';
            $weight = array_key_exists('weight', $pv) ? $pv['weight'] : '';
            $weightSum += $weight;
            $data .= "</UnitOfMeasurement>
        <Weight>" . round(($weight * $this->weightUnitKoef + (is_numeric($packweight = str_replace(',', '.', $packweight)) ? $packweight * $this->weightUnitKoef : 0)), 1) . "</Weight>" . (array_key_exists('large', $pv) ? $pv['large'] : '') . "
      </PackageWeight>";
            if ($this->isAdult('P')) {
                $data .= "<PackageServiceOptions>";
                $data .= "<DeliveryConfirmation><DCISType>" . $this->adult . "</DCISType></DeliveryConfirmation>";
                $data .= "</PackageServiceOptions>";
            }
            $data .= "</Package>";
        }

        if ($this->isAdult('S')) {
            $data .= "<ShipmentServiceOptions>";
            $data .= "<DeliveryConfirmation><DCISType>" . $this->adult . "</DCISType></DeliveryConfirmation>";
            $data .= "</ShipmentServiceOptions>";
        }

        $data .= "</Shipment></RatingServiceSelectionRequest>";

        $cie = 'onlinetools';



        $curl = Mage::helper('upsap');
        $result = $curl->curlSend('https://' . $cie . '.ups.com/ups.app/xml/Rate', $data);

        if (Mage::getStoreConfig('carriers/upsap/debug') == 1) {
            Mage::log($data, null, 'upsap_debug.log');
            Mage::log($this->xml2array($result), null, 'upsap_debug.log');
        }

        if ($curl->error === false) {
            $result = strstr($result, '<?xml');
            //return $data;
            $xml = $this->xml2array(simplexml_load_string($result));
            if ($xml['Response']['ResponseStatusCode'] == 1 || $xml['Response']['ResponseStatusCode'] == 1) {
                $rates = array();
                $timeInTransit = null;

                if (!isset($xml['RatedShipment'][0])) {
                    $xml['RatedShipment'] = array($xml['RatedShipment']);
                }

                foreach ($xml['RatedShipment'] as $rated) {
                    $rateCode = (string)$rated['Service']['Code'];


                    $rates[$rateCode]["def"] = array(
                        'price' => $rated['TotalCharges']['MonetaryValue'],
                        'currency' => $rated['TotalCharges']['CurrencyCode'],
                    );

                    if (isset($rated['TotalChargesWithTaxes'])) {
                        $totalChargesWithTaxes = $rated['TotalChargesWithTaxes'];
                        if (isset($totalChargesWithTaxes[0])) {
                            $totalChargesWithTaxes = $totalChargesWithTaxes[0];
                        }

                        if (isset($totalChargesWithTaxes) && isset($totalChargesWithTaxes['CurrencyCode'])
                            && isset($totalChargesWithTaxes['MonetaryValue'])
                        ) {
                            $rates[$rateCode]["deftax"] = array(
                                'price' => $totalChargesWithTaxes['MonetaryValue'],
                                'currency' => $totalChargesWithTaxes['CurrencyCode'],
                            );
                        }
                    }


                    if (isset($rated['NegotiatedRates'])) {
                        $defaultPrice = $rated['NegotiatedRates'];
                        if (isset($defaultPrice[0])) {
                            $defaultPrice = $defaultPrice[0];
                        }

                        $defaultPrice = $defaultPrice['NetSummaryCharges'];
                        if (isset($defaultPrice[0])) {
                            $defaultPrice = $defaultPrice[0];
                        }

                        if (isset($defaultPrice['GrandTotal'][0])) {
                            $rates[$rateCode]["nr"] = array(
                                'price' => $defaultPrice['GrandTotal'][0]['MonetaryValue'],
                                'currency' => $defaultPrice['GrandTotal'][0]['CurrencyCode'],
                            );
                        } else {
                            $rates[$rateCode]["nr"] = array(
                                'price' => $defaultPrice['GrandTotal']['MonetaryValue'],
                                'currency' => $defaultPrice['GrandTotal']['CurrencyCode'],
                            );
                        }



                        if (isset($defaultPrice['TotalChargesWithTaxes'])) {
                            $defaultPrice = $defaultPrice['TotalChargesWithTaxes'];
                            if (isset($defaultPrice[0])) {
                                $defaultPrice = $defaultPrice[0];
                            }

                            $rates[$rateCode]["nrtax"] = array(
                                'price' => $defaultPrice['MonetaryValue'],
                                'currency' => $defaultPrice['CurrencyCode'],
                            );
                        }
                    }

                    /*}*/
                    if ($timeInTransit === null) {
                        $timeInTransit = $this->timeInTransit($weightSum);
                    }

                    if (is_array($timeInTransit) && isset($timeInTransit['days'][$rateCode])) {
                        $rates[$rateCode]['day'] = $timeInTransit['days'][$rateCode];
                    }
                }

                return $rates;
            } else {
                $errorDescription = array();
                if(is_array($xml['Response']['Error']) && isset($xml['Response']['Error'][0])){
                    foreach ($xml['Response']['Error'] as $item) {
                        $errorDescription[] = $item['ErrorDescription'];
                    }
                } else {
                    $errorDescription[] = $xml['Response']['Error']['ErrorDescription'];
                }

                $error = array('error' => implode('; ', $errorDescription));
                if (Mage::getStoreConfig('carriers/upsap/debug') == 1) {
                    $errorLog = Mage::getModel("upsap/errorlog");
                    $errorLog->setErrorMessage($error['error'])->save();
                }

                return $error;
            }
        } else {
            $error = array('error' => $result["errordesc"]);
            if (Mage::getStoreConfig('carriers/upsap/debug') == 1) {
                $errorLog = Mage::getModel("upsap/errorlog");
                $errorLog->setErrorMessage($error['error'])->save();
            }

            return $error;
        }
    }

    protected function isAdult($typeService)
    {
        if ($this->adult == 4) {
            if ($typeService === "P") {
                return false;
            } else if ($typeService === "S") {
                return true;
            }
        }

        if ($typeService === "S") {
            $this->adult = $this->adult - 1;
        }

        if ($this->adult <= 0) {
            return false;
        }

        $adult = 'DC';
        if ($typeService === 'P') {
            if ($this->adult == 2) {
                $adult = 'DC-SR';
            } else if ($this->adult == 3) {
                $adult = 'DC-ASR';
            }
        } else if ($typeService === 'S') {
            if ($this->adult == 1) {
                $adult = 'DC-SR';
            } else if ($this->adult == 2) {
                $adult = 'DC-ASR';
            }
        }

        switch ($this->shipfromCountryCode) {
            case 'US':
            case 'CA':
            case 'PR':
                switch ($this->shiptoCountryCode) {
                    case 'US':
                    case 'PR':
                        if ($typeService === 'P') {
                            return true;
                        }

                        break;
                    default:
                        if ($typeService === 'S' && ($adult === 'DC-SR' || $adult === 'DC-ASR')) {
                            return true;
                        }

                        break;
                }
                break;
            default:
                if ($typeService === 'S' && ($adult === 'DC-SR' || $adult === 'DC-ASR')) {
                    return true;
                }

                break;
        }

        return false;
    }
}
