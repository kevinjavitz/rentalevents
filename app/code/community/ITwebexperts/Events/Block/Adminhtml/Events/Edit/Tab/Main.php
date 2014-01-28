<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Adminhtml_Events_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Prepare form elements for tab
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::helper('itwebexperts_events')->getEventsItemInstance();

        /**
         * Checking if user have permissions to save information
         */
        if (Mage::helper('itwebexperts_events/admin')->isActionAllowed('save')) {
            $isElementDisabled = false;
        } else {
            $isElementDisabled = true;
        }

        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('news_main_');

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('itwebexperts_events')->__('Events Item Info')
        ));

        if ($model->getId()) {
            $fieldset->addField('events_id', 'hidden', array(
                'name' => 'events_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'     => 'title',
            'label'    => Mage::helper('itwebexperts_events')->__('Events Title'),
            'title'    => Mage::helper('itwebexperts_events')->__('Events Title'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));


        $fieldset->addField('start_date', 'date', array(
            'name'     => 'start_date',
            'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'image'    => $this->getSkinUrl('images/grid-cal.gif'),
            'label'    => Mage::helper('itwebexperts_events')->__('Start Date'),
            'title'    => Mage::helper('itwebexperts_events')->__('Start Date'),
            'required' => true
        ));

        $fieldset->addField('end_date', 'date', array(
            'name'     => 'end_date',
            'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'image'    => $this->getSkinUrl('images/grid-cal.gif'),
            'label'    => Mage::helper('itwebexperts_events')->__('End Date'),
            'title'    => Mage::helper('itwebexperts_events')->__('End Date'),
            'required' => true
        ));

        $fieldset->addField('gates', 'text', array(
            'name'     => 'gates',
            'label'    => Mage::helper('itwebexperts_events')->__('Gates'),
            'title'    => Mage::helper('itwebexperts_events')->__('Gates(comma separated)'),
            'required' => true,
            'disabled' => $isElementDisabled
        ));

        Mage::dispatchEvent('adminhtml_events_edit_tab_main_prepare_form', array('form' => $form));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('itwebexperts_events')->__('Events Info');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('itwebexperts_events')->__('Events Info');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }
}
