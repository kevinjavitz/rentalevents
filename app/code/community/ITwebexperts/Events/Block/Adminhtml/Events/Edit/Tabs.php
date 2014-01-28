<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Adminhtml_Events_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize tabs and define tabs block settings
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('itwebexperts_events')->__('Event Item Info'));
    }
}
