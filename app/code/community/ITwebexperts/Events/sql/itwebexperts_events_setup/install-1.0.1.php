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

/**
 * Creating table itwebexperts_events
 */
$table = $installer->getConnection()
    ->newTable($installer->getTable('itwebexperts_events/events'))
    ->addColumn('events_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'Title')
	 ->addColumn('details', Varien_Db_Ddl_Table::TYPE_TEXT, '2M', array(
        'nullable' => true,
        'default'  => null,
    ), 'Details')
    ->addColumn('small_details', Varien_Db_Ddl_Table::TYPE_TEXT, 400, array(
        'nullable' => true,
        'default'  => null,
    ), 'Smal Description')

    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Events image media path')

    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Creation Time')
    ->setComment('Event item');

$installer->getConnection()->createTable($table);
