<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Model_Observer
{
    /**
     * Event before show event item on frontend
     * If specified new post was added recently (term is defined in config) we'll see message about this on front-end.
     *
     * @param Varien_Event_Observer $observer
     */
    public function beforeEventsDisplayed(Varien_Event_Observer $observer)
    {
        $eventsItem = $observer->getEvent()->getEventsItem();
        $currentDate = Mage::app()->getLocale()->date();
        $eventsCreatedAt = Mage::app()->getLocale()->date(strtotime($eventsItem->getCreatedAt()));
        $daysDifference = $currentDate->sub($eventsCreatedAt)->getTimestamp() / (60 * 60 * 24);
        /*if ($daysDifference < Mage::helper('itwebexperts_events')->getDaysDifference()) {
            Mage::getSingleton('core/session')->addSuccess(Mage::helper('itwebexperts_events')->__('Recently added'));
        }*/
    }

    public function showEvents($observer)
    {
        $form = $observer->getForm();
        if ($events = $form->getElement('productevents')) {
            $events->setRenderer(
                Mage::getSingleton('core/layout')->createBlock('itwebexperts_events/adminhtml_catalog_product_edit_tab_payperrentals_productevents')
            );
        }
    }

    public function calendarNewelements($observer){
        $returnObj = $observer->getEvent()->getResult();
        $templateNewElements = 'itwebexpertsevents/calendar/new_elements.phtml';

        $return = Mage::app()->getLayout()
            ->createBlock("core/template")
            ->setData('area','frontend')
            ->setTemplate($templateNewElements)
            ->toHtml();

        $returnObj->setReturn($return);
    }

    public function calendarStyles($observer){
        $returnObj = $observer->getEvent()->getResult();
        //$return = $returnObj->getReturn();
        $templateStyles = 'itwebexpertsevents/calendar/styles.phtml';
        $return = Mage::app()->getLayout()
            ->createBlock("core/template")
            ->setData('area','frontend')
            ->setTemplate($templateStyles)
            ->toHtml();

        $returnObj->setReturn($return);
    }

    public function renderCart($observer){
        $isCart = $observer->getEvent()->getIsCart();
        $product = $observer->getEvent()->getProduct();
        $result = $observer->getEvent()->getResult();

        if ($isCart) {
            if (!is_object($product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID))) {
                $source = unserialize($product->getCustomOption('info_buyRequest')->getValue());

                if(isset($source[ITwebexperts_Events_Helper_Data::EVENT_ID])){
                    $eventName = Mage::helper('itwebexperts_events')->getEventNameById($source[ITwebexperts_Events_Helper_Data::EVENT_ID]);
                }
                if(isset($source[ITwebexperts_Events_Helper_Data::GATE_NAME])){
                    $gateName = $source[ITwebexperts_Events_Helper_Data::GATE_NAME];
                }
            }
            else{
                if(is_object($product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID))){
                    $eventName = Mage::helper('itwebexperts_events')->getEventNameById($product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID)->getValue());
                }
                if(is_object($product->getCustomOption(ITwebexperts_Events_Helper_Data::GATE_NAME))){
                    $gateName = $product->getCustomOption(ITwebexperts_Events_Helper_Data::GATE_NAME)->getValue();
                }
            }
            $options = $result->getResult();
            if(isset($eventName)){
                $options[] = array('label' => Mage::helper('itwebexperts_events')->__('Event Name:'), 'value' => $eventName);
            }

            if(isset($gateName)){
                $options[] = array('label' => Mage::helper('itwebexperts_events')->__('Gate Name:'), 'value' => $gateName);
            }

            $result->setResult($options);

        } else {
            $options = $observer->getEvent()->getOptions();

            if (isset($options)) {
                if (isset($options[ITwebexperts_Events_Helper_Data::EVENT_ID])) {

                    if(isset($options[ITwebexperts_Events_Helper_Data::EVENT_ID])){
                        $eventName = Mage::helper('itwebexperts_events')->getEventNameById($options[ITwebexperts_Events_Helper_Data::EVENT_ID]);
                    }

                    if(isset($options[ITwebexperts_Events_Helper_Data::GATE_NAME])){
                        $gateName = $options[ITwebexperts_Events_Helper_Data::GATE_NAME];
                    }
                }
                $resultArr = $result->getResult();
                    if(isset($eventName)){
                        $resultArr[] = array(
                            'label' => Mage::helper('itwebexperts_events')->__('Event Name:'),
                            'value'=>  $eventName
                        );
                    }

                    if(isset($gateName)){
                        $resultArr[] = array(
                            'label' => Mage::helper('itwebexperts_events')->__('Gate Name:'),
                            'value'=>  $gateName
                        );
                    }
            }

            $result->setResult($resultArr);
        }

    }

    /**
     * @var $_block Mage_Adminhtml_Block_Widget_Grid
     * @return $this
     * */
    public function appendCustomColumns($_observer)
    {
        $_block = $_observer->getBlock();
        if (!isset($_block)) {
            return $this;
        }
        if ($_block->getType() == 'adminhtml/sales_order_grid') {
            if(ITwebexperts_Events_Helper_Data::useEvents()){
                $_block->addColumnAfter('sfo_event_name', array(
                    'header' => Mage::helper('payperrentals')->__('Events Names'),
                    'index' => 'event_name',
                    'type' => 'text',
                    'filter_index' => 'main_table.event_name',
                ), 'shipping_name');

                $_block->addColumnAfter('sfo_gate_name', array(
                    'header' => Mage::helper('payperrentals')->__('Gate Name'),
                    'index' => 'gate_name',
                    'type' => 'text',
                    'filter_index' => 'main_table.gate_name',
                ), 'sfo_event_name');
            }

        }

        return $this;
    }

    public function showDatesIsSingle($observer){
        $result = $observer->getEvent()->getResult();
        $_useEvents = ITwebexperts_Events_Helper_Data::useEvents();
        $retArr = $result->getReturn();
        if($_useEvents){
            $retArr['bool'] = false;
        }
        $result->setReturn($retArr);

    }

    public function beforeProductListing($observer){
        $result = $observer->getEvent()->getResult();
        $_nonSequential = $observer->getEvent()->getNonSequential();
        $_useEvents = ITwebexperts_Events_Helper_Data::useEvents();
        $_additional = $result->getReturn();
        if($_useEvents && $_nonSequential){
            $_additional = array(
                '_query' => array(
                    'options' => array(
                        'start_date' => Mage::getSingleton('core/session')->getData('startDateInitial')?Mage::getSingleton('core/session')->getData('startDateInitial'):'',
                        'event_id' => Mage::getSingleton('core/session')->getData('eventInitial')?Mage::getSingleton('core/session')->getData('eventInitial'):'',
                        'gate_name' => Mage::getSingleton('core/session')->getData('gateInitial')?Mage::getSingleton('core/session')->getData('gateInitial'):'',
                        'global_dates_not' => 1,
                        'qty' => 1
                    ),
                    'start_date' => Mage::getSingleton('core/session')->getData('startDateInitial')?Mage::getSingleton('core/session')->getData('startDateInitial'):'',
                    'event_id' => Mage::getSingleton('core/session')->getData('eventInitial')?Mage::getSingleton('core/session')->getData('eventInitial'):'',
                    'gate_name' => Mage::getSingleton('core/session')->getData('gateInitial')?Mage::getSingleton('core/session')->getData('gateInitial'):'',
                    'global_dates_not' =>1,
                    'qty' => 1
                ),
                '_escape' => true);
        }
        $result->setReturn($_additional);
    }

    public function prepareAdvancedBefore($observer){
        $buyRequest = $observer->getEvent()->getBuyRequest();
        $product = $observer->getEvent()->getProduct();
        $resultObject = $observer->getEvent()->getResult();
        $result = '';
        $_useEvents = ITwebexperts_Events_Helper_Data::useEvents();
        $_useEventsWithDates = ITwebexperts_Events_Helper_Data::useEventDates();
        if($_useEvents){
            if($buyRequest->getEventId()){
                $eventId = $buyRequest->getEventId();
                if(!$_useEventsWithDates){
                    $eventStartDate = Mage::helper('itwebexperts_events')->getEventStartdateById($eventId);
                    $eventEndDate = Mage::helper('itwebexperts_events')->getEventEnddateById($eventId);
                    $buyRequest->setStartDate($eventStartDate);
                    $buyRequest->setEndDate($eventEndDate);
                }
                $product->addCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID, $buyRequest->getEventId(), $product);
                if($buyRequest->getGateName()){
                    $product->addCustomOption(ITwebexperts_Events_Helper_Data::GATE_NAME, $buyRequest->getGateName(), $product);
                }
            }else{
                $result = Mage::helper('payperrentals')->__('Event is not selected');
            }

        }
        $resultObject->setResult($result);
    }

    public function initGlobalsPrepareAdvanced($observer){
        $buyRequest = $observer->getEvent()->getBuyRequest();
        if (Mage::getSingleton('core/session')->getData('eventInitial')) {
            $buyRequest->setEventId(Mage::getSingleton('core/session')->getData('eventInitial'));
        }
        if (Mage::getSingleton('core/session')->getData('gateInitial')) {
            $buyRequest->setGateName(Mage::getSingleton('core/session')->getData('gateInitial'));
        }
    }

    public function saveOrderBeforeInside($observer){
        $items = $observer->getEvent()->getOrder()->getItemsCollection();
        foreach ($items as $item) {
            $Product = Mage::getModel('catalog/product')->load($item->getProductId());
            if ($Product->getTypeId() != ITwebexperts_Payperrentals_Helper_Data::PRODUCT_TYPE && $Product->getTypeId() != ITwebexperts_Payperrentals_Helper_Data::PRODUCT_TYPE_CONFIGURABLE /* && $Product->getTypeId() != ITwebexperts_Payperrentals_Helper_Data::PRODUCT_TYPE_BUNDLE*/) {
                continue;
            }

            $data = $item->getProductOptionByCode('info_buyRequest');

            if(isset($data[ITwebexperts_Events_Helper_Data::EVENT_ID])){
                $observer->getEvent()->getOrder()->setEventId($data[ITwebexperts_Events_Helper_Data::EVENT_ID]);
                $observer->getEvent()->getOrder()->setEventName(Mage::helper('itwebexperts_events')->getEventNameById($data[ITwebexperts_Events_Helper_Data::EVENT_ID]));
            }
            if(isset($data[ITwebexperts_Events_Helper_Data::GATE_NAME])){
                $observer->getEvent()->getOrder()->setGateName($data[ITwebexperts_Events_Helper_Data::GATE_NAME]);
            }

            break;
        }


    }

    public function calendarReady($observer){
        $returnObj = $observer->getEvent()->getResult();

        $_jsContainerPrefix =  $observer->getEvent()->getJsContainerPrefix();
        $_jsFunctionPrefix = $observer->getEvent()->getJsFunctionPrefix();
        $isAdminGlobal = $observer->getEvent()->getIsAdminGlobal();
        $isAdmin = $observer->getEvent()->getIsAdmin();
        $isFrontendGlobal = $observer->getEvent()->getIsFrontendGlobal();

        $quoteItemId = $observer->getEvent()->getQuoteItemId();
        $quoteItem = $observer->getEvent()->getQuoteItem();

        //$return = $returnObj->getReturn();
        $templateReady = 'itwebexpertsevents/calendar/calendar_ready.phtml';
        $return = Mage::app()->getLayout()
            ->createBlock("core/template")
            ->setData('area','frontend')
            ->setData('jsContainerPrefix', $_jsContainerPrefix)
            ->setData('jsFunctionPrefix', $_jsFunctionPrefix)
            ->setData('isAdminGlobal', $isAdminGlobal)
            ->setData('isFrontendGlobal', $isFrontendGlobal)
            ->setData('isAdmin', $isAdmin)
            ->setData('quoteItemId', $quoteItemId)
            ->setData('quoteItem', $quoteItem)
            ->setTemplate($templateReady)
            ->toHtml();

        $returnObj->setReturn($return);
    }

    public function optionsGridNames($observer){
        $returnObj = $observer->getEvent()->getResult();
        $return = '';
        $return .= '<th class="no-link">'. Mage::helper('itwebexperts_events')->__('Event'). '</th>';
        $return .= '<th class="no-link">'. Mage::helper('itwebexperts_events')->__('Gate'). '</th>';
        $returnObj->setReturn($return);
    }
    public function optionsGrid($observer){
        $returnObj = $observer->getEvent()->getResult();
        $_item = $observer->getEvent()->getItem();
        $return = '';

        $buyRequest = $_item->getBuyRequest();
        $eventName = Mage::helper('itwebexperts_events')->getEventNameById($buyRequest->getEventId());
        $gateName = $buyRequest->getGateName();
        $return .= '<td class="">'.$eventName.'</td>';
        $return .= '<td class="">'.$gateName.'</td>';

        $returnObj->setReturn($return);
    }

    public function afterGetquantity($observer){
        $Product = $observer->getEvent()->getProduct();
        $resultQty = $observer->getEvent()->getResult();
        $retQty = $resultQty->getRetQty();
        $_useEvents = ITwebexperts_Events_Helper_Data::useEvents();
        if($_useEvents){
            if(!is_object($Product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID))){
                if(is_object($Product->getCustomOption('info_buyRequest'))){
                    $source = unserialize($Product->getCustomOption('info_buyRequest')->getValue());
                    if(isset($source[ITwebexperts_Events_Helper_Data::EVENT_ID])){
                        $eventId = $source[ITwebexperts_Events_Helper_Data::EVENT_ID];
                    }
                }
            }else{
                if(is_object($Product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID))){
                    $eventId = $Product->getCustomOption(ITwebexperts_Events_Helper_Data::EVENT_ID)->getValue();
                }
            }
            if(isset($eventId)){
                $coll = Mage::getModel('itwebexperts_events/productevents')
                    ->getCollection()
                    ->addProductIdFilter($Product->getId())
                    ->addEventIdFilter($eventId);

                foreach($coll as $item){
                    $retQty = $item->getQty();
                    break;
                }
            }
        }
        $resultQty->setRetQty($retQty);

    }
}
