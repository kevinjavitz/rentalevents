<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Adminhtml_Events_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Initialize edit form container
     *
     */
    public function __construct()
    {
        $this->_objectId   = 'id';
        $this->_blockGroup = 'itwebexperts_events';
        $this->_controller = 'adminhtml_events';

        parent::__construct();

        if (Mage::helper('itwebexperts_events/admin')->isActionAllowed('save')) {
            $this->_updateButton('save', 'label', Mage::helper('itwebexperts_events')->__('Save Events Item'));
            $this->_addButton('saveandcontinue', array(
                'label'   => Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ), -100);
        } else {
            $this->_removeButton('save');
        }

        if (Mage::helper('itwebexperts_events/admin')->isActionAllowed('delete')) {
            $this->_updateButton('delete', 'label', Mage::helper('itwebexperts_events')->__('Delete Events Item'));
        } else {
            $this->_removeButton('delete');
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('page_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'page_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'page_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Retrieve text for header element depending on loaded page
     *
     * @return string
     */
    public function getHeaderText()
    {
        $model = Mage::helper('itwebexperts_events')->getEventsItemInstance();
        if ($model->getId()) {
            return Mage::helper('itwebexperts_events')->__("Edit Events Item '%s'",
                 $this->escapeHtml($model->getTitle()));
        } else {
            return Mage::helper('itwebexperts_events')->__('New Events Item');
        }
    }
}
