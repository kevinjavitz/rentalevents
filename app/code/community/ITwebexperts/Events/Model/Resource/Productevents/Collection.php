<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Model_Resource_Productevents_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Define collection model
     */
    protected function _construct()
    {
        $this->_init('itwebexperts_events/productevents');
    }

    public function addProductIdFilter($id){
        $this->getSelect()
            ->where('product_id=?', $id);
        return $this;
    }

    public function addEventIdFilter($id){
        $this->getSelect()
            ->where('event_id=?', $id);
        return $this;
    }

    public function addStoreIdFilter($id){
        $this->getSelect()
            ->where('store_id="'.$id.'" OR store_id="0"');

        return $this;
    }

}
