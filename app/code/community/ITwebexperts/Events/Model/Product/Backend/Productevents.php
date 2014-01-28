<?php
class ITwebexperts_Events_Model_Product_Backend_Productevents extends Mage_Eav_Model_Entity_Attribute_Backend_Abstract
{
    protected $_rates;


    protected function _getResource()
    {
        return Mage::getResourceSingleton('itwebexperts_events/productevents');
    }

    protected function _getProduct()
    {
        return Mage::registry('product');
    }

    public function _getWebsiteRates()
    {
        if (is_null($this->_rates)) {
            $this->_rates = array();
            $baseCurrency = Mage::app()->getBaseCurrencyCode();
            foreach (Mage::app()->getWebsites() as $website) {
                /* @var $website Mage_Core_Model_Website */
                if ($website->getBaseCurrencyCode() != $baseCurrency) {
                    $rate = Mage::getModel('directory/currency')
                        ->load($baseCurrency)
                        ->getRate($website->getBaseCurrencyCode());
                    if (!$rate) {
                        $rate = 1;
                    }
                    $this->_rates[$website->getId()] = array(
                        'code' => $website->getBaseCurrencyCode(),
                        'rate' => $rate
                    );
                } else {
                    $this->_rates[$website->getId()] = array(
                        'code' => $baseCurrency,
                        'rate' => 1
                    );
                }
            }
        }
        return $this->_rates;
    }

    public function validate($object)
    {
        $periods = $object->getData($this->getAttribute()->getName());
        if (empty($periods)) {
            return $this;
        }


        return $this;
    }

    public function afterSave($object)
    {
        $generalStoreId = $object->getStoreId();
        $periods = $object->getData($this->getAttribute()->getName());

        if (is_array($periods)) {
            Mage::getResourceSingleton('itwebexperts_events/productevents')->deleteByProductId($object->getId(), $generalStoreId);
            foreach ($periods as $k => $period) {

                if (!is_numeric($k)) continue;

                $storeId = @$period['use_default_value'] ? 0 : $object->getStoreId();

                $myRes = Mage::getModel('itwebexperts_events/productevents')
                    ->setProductId($object->getId())
                    ->setStoreId($storeId)
                    ->setEventId($period['eventid'])
                    ->setQty($period['eventqty'])
                   ;

                $myRes->save();
            }
        } elseif ($object->getIsDuplicate() == true) {
            $priceCollection = Mage::getModel('itwebexperts_events/productevents')
                ->getCollection()
                ->addProductIdFilter($object->getOriginalId());
            foreach ($priceCollection AS $priceItem) {
                $priceItem
                    ->setProducteventId(null)
                    ->setProductId($object->getId())
                    ->save();
            }
        }

        return $this;
    }


}
