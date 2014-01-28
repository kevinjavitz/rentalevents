<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Model_Resource_Productevents extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Initialize connection and define main table and primary key
     */
    protected function _construct()
    {
        $this->_init('itwebexperts_events/productevents', 'productevents_id');
    }

    public function deleteByProductId($id, $storeId = null){
        $condition =   'product_id='.intval($id);
        if(!is_null($storeId)){
            $condition .= ' AND store_id='.intval($storeId);
        }
        $this->_getWriteAdapter()->delete($this->getMainTable(), $condition);

        return $this;
    }
}
