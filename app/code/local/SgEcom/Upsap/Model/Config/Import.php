<?php

class SgEcom_Upsap_Model_Config_Import extends Mage_Adminhtml_Model_System_Config_Backend_File
{
    protected function _beforeSave()
    {
        $value = $this->getValue();
        if ($_FILES['groups']['tmp_name'][$this->getGroupId()]['fields'][$this->getField()]['value']) {

            $uploadDir = $this->_getUploadDir();

            try {
                $file = array();
                $tmpName = $_FILES['groups']['tmp_name'];
                $file['tmp_name'] = $tmpName[$this->getGroupId()]['fields'][$this->getField()]['value'];
                $csvLines = file($file['tmp_name']);
                $delimiter = ",";
                if (strpos($csvLines[0], ';') !== FALSE) {
                    $delimiter = ";";
                }
                $head = str_getcsv($csvLines[0], $delimiter);
                unset($csvLines[0]);

                foreach ($csvLines AS $row) {
                    $csv = str_getcsv($row, $delimiter);
                    $zips = array('');
                    if ($head['zip_min']) {
                        $zips = explode(',', $csv[array_search('zip_min', $head)]);
                        $zipsMax = explode(',', $csv[array_search('zip_max', $head)]);
                    }

                    $items = Mage::getModel('upsap/method')->getCollection();
                    if (count($zips) > 1) {
                        for ($i = 0; $i < 2; $i++) {
                            $model = Mage::getModel('upsap/method');
                            foreach ($csv AS $key => $col) {
                                $colData = trim($col);
                                switch (trim($head[$key])) {
                                    case 'title':
                                        $items->addFieldToFilter('title', $colData);
                                        if (count($items) > 0) {
                                            $model = Mage::getModel('upsap/method')->load($items->getFirstItem()->getId());
                                        } else {
                                            $model->setTitle($colData);
                                        }
                                        break;
                                    case 'name':
                                        $model->setName($colData);
                                        break;
                                    case 'ups_method_code':
                                        if (strlen($colData) == 1) {
                                            $colData = "0" . $colData;
                                        }
                                        $model->setUpsmethodId($colData);
                                        break;

                                    case 'store_id':
                                        if (strtolower($colData) != 'all') {
                                            $model->setStoreId($colData);
                                            $items->addFieldToFilter('store_id', $colData);
                                            $model->setIsStoreAll(1);
                                        } else {
                                            $model->setIsStoreAll(0);
                                        }
                                        break;
                                    case 'country_ids':
                                        if (strtolower($colData) != 'all') {
                                            $model->setCountryIds($colData);
                                            $items->addFieldToFilter('country_ids', $colData);
                                            $model->setIsCountryAll(1);
                                            /*$items->addFieldToFilter('is_country_all', 1);*/
                                        } else {
                                            $model->setIsCountryAll(0);
                                            /*$items->addFieldToFilter('is_country_all', 0);*/
                                        }
                                        break;
                                    case 'price':
                                        $model->setPrice($colData);
                                        break;
                                    case 'status':
                                        $model->setStatus($colData);
                                        break;
                                    case 'dinamic_price':
                                        $model->setDinamicPrice($colData);
                                        break;
                                    /*case 'carrier_code':
                                        $model->setCompanyType(strtolower($colData));
                                        break;*/
                                    case 'order_amount_min':
                                        $model->setAmountMin(str_replace(',', '.', $colData));
                                        break;
                                    case 'order_amount_max':
                                        $model->setAmountMax(str_replace(',', '.', $colData));
                                        break;
                                    case 'negotiated':
                                        $model->setNegotiated($colData);
                                        break;
                                    case 'negotiated_amount_from':
                                        $model->setNegotiatedFmountFrom(str_replace(',', '.', $colData));
                                        break;
                                    case 'tax':
                                        $model->setTax($colData);
                                        break;
                                    case 'weight_min':
                                        $model->setWeightMin(str_replace(',', '.', $colData));
                                        break;
                                    case 'weight_max':
                                        $model->setWeightMax(str_replace(',', '.', $colData));
                                        break;
                                    case 'qty_min':
                                        $model->setQtyMin(str_replace(',', '.', $colData));
                                        break;
                                    case 'qty_max':
                                        $model->setQtyMax(str_replace(',', '.', $colData));
                                        break;
                                    case 'zip_min':
                                        if (isset($zips[$i])) {
                                            $model->setZipMin($zips[$i]);
                                        }
                                        break;
                                    case 'zip_max':
                                        if (isset($zipsMax[$i])) {
                                            $model->setZipMax($zipsMax[$i]);
                                        }
                                        break;
                                    case 'free_shipping':
                                        $model->setFreeShipping($colData);
                                        break;
                                    /*case 'weight_jump_for_price_doubling':
                                        $model->setIncrementPriceByWeight($colData);
                                        break;
                                    case 'weight_jump_for_new_package':
                                        $model->setIncrementPackageByWeight($colData);
                                        break;*/
                                    case 'user_group_ids':
                                        if(strlen(trim($colData, ",")) > 0){
                                            $model->setUserGroupIds(','.trim($colData, ",").',');
                                        } else {
                                            $model->setUserGroupIds('');
                                        }
                                        /*$items->addFieldToFilter('status', $colData);*/
                                        break;
                                }
                            }
                            if (!isset($head['status'])) {
                                $model->setStatus(1);
                            }
                            $model->save();
                        }
                    } else {
                        $model = Mage::getModel('upsap/method');
                        foreach ($csv AS $key => $col) {
                            $colData = trim($col);
                            switch (trim($head[$key])) {
                                case 'title':
                                    $items->addFieldToFilter('title', $colData);
                                    if (count($items) > 0) {
                                        $model = Mage::getModel('upsap/method')->load($items->getFirstItem()->getId());
                                    } else {
                                        $model->setTitle($colData);
                                    }
                                    break;
                                case 'name':
                                    $model->setName($colData);
                                    break;
                                case 'ups_method_code':
                                    if (strlen($colData) == 1) {
                                        $colData = "0" . $colData;
                                    }
                                    $model->setUpsmethodId($colData);
                                    break;
                                case 'store_id':
                                    $model->setStoreId($colData);
                                    break;
                                case 'country_ids':
                                    if (strtolower($colData) != 'all') {
                                        $model->setCountryIds($colData);
                                        $items->addFieldToFilter('country_ids', $colData);
                                        $model->setIsCountryAll(1);
                                        /*$items->addFieldToFilter('is_country_all', 1);*/
                                    } else {
                                        $model->setIsCountryAll(0);
                                        /*$items->addFieldToFilter('is_country_all', 0);*/
                                    }
                                    break;
                                case 'price':
                                    $model->setPrice($colData);
                                    break;
                                case 'added_value_type':
                                    $model->setAddedValueType($colData);
                                    /*$items->addFieldToFilter('price', $colData);*/
                                    break;
                                case 'added_value':
                                    $model->setAddedValue($colData);
                                    /*$items->addFieldToFilter('price', $colData);*/
                                    break;
                                case 'status':
                                    $model->setStatus($colData);
                                    break;
                                case 'dinamic_price':
                                    if (strtolower($colData) != 'static') {
                                        $model->setDinamicPrice(1);
                                    } else if (strtolower($colData) != 'static' && strtolower($colData) != 'dynamic') {
                                        $model->setDinamicPrice($colData);
                                    } else {
                                        $model->setDinamicPrice(0);
                                    }
                                    break;
                                /*case 'carrier_code':
                                    $model->setCompanyType(strtolower($colData));
                                    break;*/
                                case 'order_amount_min':
                                    $model->setAmountMin(str_replace(',', '.', $colData));
                                    break;
                                case 'order_amount_max':
                                    $model->setAmountMax(str_replace(',', '.', $colData));
                                    break;
                                case 'negotiated':
                                    $model->setNegotiated($colData);
                                    break;
                                case 'negotiated_amount_from':
                                    $model->setNegotiatedFmountFrom(str_replace(',', '.', $colData));
                                    break;
                                case 'tax':
                                    $model->setTax($colData);
                                    break;
                                case 'weight_min':
                                    $model->setWeightMin(str_replace(',', '.', $colData));
                                    break;
                                case 'weight_max':
                                    $model->setWeightMax(str_replace(',', '.', $colData));
                                    break;
                                case 'qty_min':
                                    $model->setQtyMin(str_replace(',', '.', $colData));
                                    break;
                                case 'qty_max':
                                    $model->setQtyMax(str_replace(',', '.', $colData));
                                    break;
                                case 'zip_min':
                                    if ($colData == 0) {
                                        $colData = '';
                                    }
                                    $model->setZipMin($colData);
                                    break;
                                case 'zip_max':
                                    if ($colData == 0) {
                                        $colData = '';
                                    }
                                    $model->setZipMax($colData);
                                    break;
                                case 'free_shipping':
                                    $model->setFreeShipping($colData);
                                    break;
                                /*case 'weight_jump_for_price_doubling':
                                    $model->setIncrementPriceByWeight($colData);
                                    break;
                                case 'weight_jump_for_new_package':
                                    $model->setIncrementPackageByWeight($colData);
                                    break;*/
                                case 'user_group_ids':
                                    $model->setUserGroupIds(','.trim($colData, ',').',');
                                    /*$items->addFieldToFilter('status', $colData);*/
                                    break;
                            }
                        }
                        if (!isset($head['status'])) {
                            $model->setStatus(1);
                        }
                        $model->save();
                    }
                }
                $name = $_FILES['groups']['name'];
                $file['name'] = $name[$this->getGroupId()]['fields'][$this->getField()]['value'];
                $uploader = new Mage_Core_Model_File_Uploader($file);
                $uploader->setAllowedExtensions($this->_getAllowedExtensions());
                $uploader->setAllowRenameFiles(true);
                $uploader->addValidateCallback('size', $this, 'validateMaxSize');
                $result = $uploader->save($uploadDir);


            } catch (Exception $e) {
                Mage::throwException($e->getMessage());
                return $this;
            }

            $filename = $result['file'];
            if ($filename) {
                if ($this->_addWhetherScopeInfo()) {
                    $filename = $this->_prependScopeInfo($filename);
                }
                $this->setValue($filename);
            }
        } else {
            if (is_array($value) && !empty($value['delete'])) {
                // Delete record before it is saved
                $this->delete();
                // Prevent record from being saved, since it was just deleted
                $this->_dataSaveAllowed = false;
            } else {
                $this->unsValue();
            }
        }

        return $this;
    }

    protected function _getAllowedExtensions()
    {
        return array('csv');
    }
}