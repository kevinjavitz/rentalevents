<?php
/*
<!--  
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A
 -->
 */
class ITwebexperts_Events_Block_List extends Mage_Core_Block_Template
{
    /**
     * Events collection
     *
     * @var ITwebexperts_Events_Model_Resource_Events_Collection
     */
    protected $_eventsCollection = null;

    /**
     * Retrieve events collection
     *
     * @return ITwebexperts_Events_Model_Resource_Events_Collection
     */
    protected function _getCollection()
    {
        return  Mage::getResourceModel('itwebexperts_events/events_collection');
    }

    /**
     * Retrieve prepared events collection
     *
     * @return ITwebexperts_Events_Model_Resource_Events_Collection
     */
    public function getCollection()
    {
        if (is_null($this->_eventsCollection)) {
            $this->_eventsCollection = $this->_getCollection();
            $this->_eventsCollection->prepareForList($this->getCurrentPage());
        }

        return $this->_eventsCollection;
    }

    /**
     * Return URL to item's view page
     *
     * @param ITwebexperts_Events_Model_Events $eventsItem
     * @return string
     */
    public function getItemUrl($eventsItem)
    {
        return $this->getUrl('*/*/view', array('id' => $eventsItem->getId()));
    }

    /**
     * Fetch the current page for the events list
     *
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ? $this->getData('current_page') : 1;
    }

    /**
     * Get a pager
     *
     * @return string|null
     */
    public function getPager()
    {
        $pager = $this->getChild('events_list_pager');
        if ($pager) {
            $eventsPerPage = Mage::helper('itwebexperts_events')->getEventsPerPage();

            $pager->setAvailableLimit(array($eventsPerPage => $eventsPerPage));
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(true);

            return $pager->toHtml();
        }

        return null;
    }

    /**
     * Return URL for resized Events Item image
     *
     * @param ITwebexperts_Events_Model_Events $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return Mage::helper('itwebexperts_events/image')->resize($item, $width);
    }
}
