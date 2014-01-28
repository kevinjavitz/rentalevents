<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Model_Resource_Events_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('itwebexperts_events/events');
    }

    /**
     * Prepare for displaying in list
     *
     * @param integer $page
     * @return ITwebexperts_Events_Model_Resource_Events_Collection
     */
    public function prepareForList($page)
    {
        $this->setPageSize(Mage::helper('itwebexperts_events')->getEventsPerPage());
        return $this;
    }
}
