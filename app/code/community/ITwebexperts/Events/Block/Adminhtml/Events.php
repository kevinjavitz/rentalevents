<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Adminhtml_Events extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * Block constructor
     */
    public function __construct()
    {
        $this->_blockGroup = 'itwebexperts_events';
        $this->_controller = 'adminhtml_events';
        $this->_headerText = Mage::helper('itwebexperts_events')->__('Manage Events');

        parent::__construct();

        if (Mage::helper('itwebexperts_events/admin')->isActionAllowed('save')) {
            $this->_updateButton('add', 'label', Mage::helper('itwebexperts_events')->__('Add New Event'));
        } else {
            $this->_removeButton('add');
        }
        $this->addButton(
            'events_flush_images_cache',
            array(
                'label'      => Mage::helper('itwebexperts_events')->__('Flush Images Cache'),
                'onclick'    => 'setLocation(\'' . $this->getUrl('*/*/flush') . '\')',
            )
        );

    }
}
