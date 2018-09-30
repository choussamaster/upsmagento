
<?php

class SgEcom_Upsap_IndexController extends Mage_Core_Controller_Front_Action
{
    
    public function callbackAction()
    {
        $url = $this->getRequest()->getParams();
        $html = '
        <!DOCTYPE html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        </head>
        <body>

        <script type="text/javascript">
            window.onload = function () {';

        if ($url['action'] == "cancel") {
            $html .= 'window.top.popupCloseMap();';
        }
        if ($url['action'] == "select") {
            $arrUrl = array();
            foreach ($url AS $k => $v) {
                $arrUrl[$k] = $v;
            }
            $html .= 'window.top.setAccessPointToCheckout(' . json_encode($arrUrl) . ');';
        }
        $html .= '}</script>
</body>
</html> ';
        $this->getResponse()->setBody($html);
        return;
    }
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function frameAction()
    {
        $url = str_replace('&', '&amp;', str_replace('&amp;', '&', $_SERVER['QUERY_STRING']));
        $http = 'https:';
        if (strpos(Mage::getUrl('/'), 'https:') === FALSE) {
            $http = 'http:';
        }
        $html = '
        <!DOCTYPE html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1">
        </head>
        <body>
        <style type="text/css">
            body {
                margin: 0;
                padding: 0;
            }
        </style>
        <script type="text/javascript">
            window.onload = function () {
                var el = document.querySelector("iframe");
                el.style.width = window.innerWidth + "px";
                el.style.height = window.innerHeight + "px";
            }
        </script>
        <iframe src="' . $http . '//www.ups.com/lsw/invoke?' . $url . '" frameborder="0" width="1080px" height="750px"
                name="dialog_upsap_access_points2"></iframe>
        </body>
        </html>';
        header('Access-Control-Allow-Origin: http://www.ups.com, http://ups.com, https://www.ups.com, https://ups.com');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT');
        header("Access-Control-Allow-Headers: Authorization, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        header('P3P: CP="NON DSP LAW CUR ADM DEV TAI PSA PSD HIS OUR DEL IND UNI PUR COM NAV INT DEM CNT STA POL HEA PRE LOC IVD SAM IVA OTC"');
        header('Access-Control-Max-Age: 1');
        $this->getResponse()->setBody($html);
        return;
    }


    public function setSessionAddressAPAction()
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

    public function getSessionAddressAPAction()
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

    public function customerAddressAction()
    {
        $address = Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress();

        if (!$address) {
            $address = Mage::getSingleton('checkout/session')->getQuote()->getBillingAddress();
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

    public function getShippingMethodsAction()
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
