<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */

/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

/**
 * Creating table itwebexperts_events
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('itwebexperts_events/productevents'))
    ->addColumn('productevents_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 100, array(
        'nullable' => true,
        'default'  => null,
    ), 'Product Id')
    ->addColumn('event_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 100, array(
        'nullable' => true,
        'default'  => null,
    ), 'Event Id')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 100, array(
        'nullable' => true,
        'default'  => null,
    ), 'Store Id')
    ->addColumn('qty', Varien_Db_Ddl_Table::TYPE_INTEGER, 100, array(
        'nullable' => true,
        'default'  => null,
    ), 'Quantity per Event')
    ->addIndex($installer->getIdxName(
            $installer->getTable('itwebexperts_events/productevents'),
            array('product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->addIndex($installer->getIdxName(
            $installer->getTable('itwebexperts_events/productevents'),
            array('event_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('event_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    );

$installer->getConnection()->createTable($table);

$installer->getConnection()->addColumn($installer->getTable('itwebexperts_events/events'), 'start_date', array(
    'nullable' => false,
    'length' => 9,
    'type' => Varien_Db_Ddl_Table::TYPE_DATE,
    'comment' => 'Start Date'
));

$installer->getConnection()->addColumn($installer->getTable('itwebexperts_events/events'), 'end_date', array(
    'nullable' => false,
    'length' => 9,
    'type' => Varien_Db_Ddl_Table::TYPE_DATE,
    'comment' => 'End Date'
));

$installer->getConnection()->addColumn($installer->getTable('itwebexperts_events/events'), 'gates', array(
    'nullable' => false,
    'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
    'comment' => 'Gates'
));



$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->addAttribute('catalog_product', 'productevents', array(
    'backend'       => 'itwebexperts_events/product_backend_productevents',
    'source'        => '',
    'group'			=> 'Events',
    'label'         => 'Assign Inventory For Event',
    'input'         => 'text',
    'type'          => 'text',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
    'visible'       => true,
    'default' 		=> 0,
    'required'      => false,
    'user_defined'  => false,
    'apply_to'      => 'reservation,configurable,grouped,bundle',
    'visible_on_front' => false,
    'position'      =>  27,
));
$installer->endSetup();