<?php
class ITwebexperts_Events_Adminhtml_EventController extends Mage_Adminhtml_Controller_Action{

    public function getEventDescriptionAction(){
        $_respHtml = array(
            'eventContent' => '',
        );
        if ($this->getRequest()->getParam('eventId')) {

            $eventContent = Mage::helper('itwebexperts_events')->getEventDescription($this->getRequest()->getParam('eventId'));
            $_respHtml = array(
                'eventContent' => $eventContent,
            );
        }

        $this->getResponse()->setBody(Zend_Json::encode($_respHtml));
    }

    /**
     *
     */
    public function setEventsAction()
    {
        $_respHtml = array(
            'gates' => '',
            'minDate' => null,
            'maxDate' => null
        );
        if ($this->getRequest()->getParam('event_id')) {
            Mage::getSingleton('core/session')->unsetData('startDateInitial');
            Mage::getSingleton('core/session')->unsetData('endDateInitial');
            Mage::getSingleton('core/session')->setData('eventInitial', $this->getRequest()->getParam('event_id'));
            $gates = Mage::helper('itwebexperts_events')->getGatesDropdownHtmlForEvent($this->getRequest()->getParam('event_id'), $this->getRequest()->getParam('gateInitial'));
            $minDate = Mage::helper('itwebexperts_events')->getEventStartdateById($this->getRequest()->getParam('event_id'));
            $maxDate = Mage::helper('itwebexperts_events')->getEventEnddateById($this->getRequest()->getParam('event_id'));
            if ($this->getRequest()->getParam('gate_name')) {
                Mage::getSingleton('core/session')->setData('gateInitial', $this->getRequest()->getParam('gate_name'));
            }
            $_useNonsequential = ITwebexperts_Payperrentals_Helper_Data::useNonSequential();
            if($_useNonsequential && $this->getRequest()->getParam('start_date')){
                Mage::getSingleton('core/session')->setData('startDateInitial', $this->getRequest()->getParam('start_date'));
            }
            $_respHtml = array(
                'gates' => $gates,
                'minDate' => strtotime($minDate) * 1000,
                'maxDate' => strtotime($maxDate) * 1000
            );
        }

        $this->getResponse()->setBody(Zend_Json::encode($_respHtml));

    }

}

?>