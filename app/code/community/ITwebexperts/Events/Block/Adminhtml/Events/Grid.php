<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Adminhtml_Events_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Init Grid default properties
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('events_list_grid');
        $this->setDefaultSort('created_at');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection for Grid
     *
     * @return ITwebexperts_Events_Block_Adminhtml_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('itwebexperts_events/events')->getResourceCollection();
		//echo "<pre>";var_dump($collection);echo "</pre>";

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare Grid columns
     *
     * @return Mage_Adminhtml_Block_Catalog_Search_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('events_id', array(
            'header'    => Mage::helper('itwebexperts_events')->__('ID #'),
            'width'     => '50px',
            'index'     => 'events_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('itwebexperts_events')->__('Title'),
            'index'     => 'title',
        ));
		
		/*$this->addColumn('details', array(
            'header'    => Mage::helper('itwebexperts_events')->__('Details'),
            'index'     => 'details',
        ));*/
		$this->addColumn('start_date', array(
            'header'    => Mage::helper('itwebexperts_events')->__('Start Date'),
            'index'     => 'start_date',
        ));

        $this->addColumn('end_date', array(
            'header'    => Mage::helper('itwebexperts_events')->__('End Date'),
            'index'     => 'end_date',
        ));

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('itwebexperts_events')->__('Action'),
                'width'     => '100px',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(array(
                    'caption' => Mage::helper('itwebexperts_events')->__('Edit'),
                    'url'     => array('base' => '*/*/edit'),
                    'field'   => 'id'
                )),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'news',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Return row URL for js event handlers
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * Grid url getter
     *
     * @return string current grid url
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}
