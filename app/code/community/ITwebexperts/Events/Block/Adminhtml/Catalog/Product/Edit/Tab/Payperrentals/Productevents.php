<?php

class ITwebexperts_Events_Block_Adminhtml_Catalog_Product_Edit_Tab_Payperrentals_Productevents extends Mage_Adminhtml_Block_Widget implements Varien_Data_Form_Element_Renderer_Interface{
	protected $_events;

	public function __construct(){
        $this->setTemplate('itwebexpertsevents/product/edit/tab/payperrentals/productevents.phtml');
    }
    public function getProduct(){
        return Mage::registry('product');
    }
	public function render(Varien_Data_Form_Element_Abstract $element){
		$this->setElement($element);
		return $this->toHtml();
	}
    
    
    protected function _prepareLayout()
    {
        $this->setChild('add_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('payperrentals')->__('Add Rule'),
                    'onclick'   => 'producteventsControl.addItem()',
                    'class' => 'add'
                )));
                
        return parent::_prepareLayout();
    }

	public function getEvents()
	{
			$collection = Mage::getResourceModel('itwebexperts_events/events_collection');
			$this->_events = array();

			foreach ($collection as $item) {
				$this->_events[$item->getEventsId()] = $item->getTitle();
			}


		return $this->_events;
	}


	public function getDefaultCustomerGroup()
	{
		return Mage_Customer_Model_Group::CUST_GROUP_ALL;
	}


	public function js_string_escape($data)
	{
		$safe = "";
		for($i = 0; $i < strlen($data); $i++)
		{
			if(ctype_alnum($data[$i]))
				$safe .= $data[$i];
			else
				$safe .= sprintf("\\x%02X", ord($data[$i]));
		}
		return $safe;
	}


	public function makeDateTimeBlock($name, $val){

		$element = $this->getLayout()->createBlock('core/html_date')
				->setTime('true')
				->setImage(Mage::getDesign()->getSkinUrl('images/grid-cal.gif'))
				->setFormat(Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT))
				->setName($name.'[{{index}}]['.$val.']')
				->setId('productevents_row_{{index}}_'.$val)
				->setClass('datetime-picker input-text require')
		;
		return $this->js_string_escape($element->toHtml()); //str_replace('<script',"'+'<scr'+'ipt'+'",$element->toHtml());
	}



	public function setElement(Varien_Data_Form_Element_Abstract $element){
        $this->_element = $element;
        return $this;
    }

    public function getElement(){
        return $this->_element;
    }    
    
    
    public function getWebsites()
    {

        if (!is_null($this->_websites)) {
            return $this->_websites;
        }
        $websites = array();
        $websites[0] = array(
            'name'      => $this->__('All Websites'),
            'currency'  => Mage::app()->getBaseCurrencyCode()
        );
        if (Mage::app()->isSingleStoreMode() || $this->getElement()->getEntityAttribute()->isScopeGlobal()) {
            return $websites;
        }
        elseif ($storeId = $this->getProduct()->getStoreId()) {
            $website = Mage::app()->getStore($storeId)->getWebsite();
            $websites[$website->getId()] = array(
                'name'      => $website->getName(),
                'currency'  => $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            );
        }
        else {
            $websites[0] = array(
                'name'      => $this->__('All Websites'),
                'currency'  => Mage::app()->getBaseCurrencyCode()
            );
            foreach (Mage::app()->getWebsites() as $website) {
                if (!in_array($website->getId(), $this->getProduct()->getWebsiteIds())) {
                    continue;
                }
                $websites[$website->getId()] = array(
                    'name'      => $website->getName(),
                    'currency'  => $website->getConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                );
            }
        }
        $this->_websites = $websites;
        return $this->_websites;
    }
    
    public function getValues(){
		return Mage::getModel('itwebexperts_events/productevents')->getCollection()
					->addProductIdFilter($this->getProduct()->getId())
					->addStoreIdFilter($this->getProduct()->getStoreId())
					->getItems();
	}
}
