<?php
class ITwebexperts_Events_IndexController extends Mage_Core_Controller_Front_Action{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->loadLayout();

        $listBlock = $this->getLayout()->getBlock('events.list');

        if ($listBlock) {
            $currentPage = abs(intval($this->getRequest()->getParam('p')));
            if ($currentPage < 1) {
                $currentPage = 1;
            }
            $listBlock->setCurrentPage($currentPage);
        }

        $this->renderLayout();
    }

    /**
     * Events view action
     */
    public function viewAction()
    {
        $eventsId = $this->getRequest()->getParam('id');
        if (!$eventsId) {
            return $this->_forward('noRoute');
        }

        /** @var $model ITwebexperts_Events_Model_Events */
        $model = Mage::getModel('itwebexperts_events/events');
        $model->load($eventsId);

        if (!$model->getId()) {
            return $this->_forward('noRoute');
        }

        Mage::register('events_item', $model);

        Mage::dispatchEvent('before_events_item_display', array('events_item' => $model));

        $this->loadLayout();
        $itemBlock = $this->getLayout()->getBlock('events.item');
        if ($itemBlock) {
            $listBlock = $this->getLayout()->getBlock('events.list');
            if ($listBlock) {
                $page = (int)$listBlock->getCurrentPage() ? (int)$listBlock->getCurrentPage() : 1;
            } else {
                $page = 1;
            }
            $itemBlock->setPage($page);
        }
        $this->renderLayout();
    }
}

?>