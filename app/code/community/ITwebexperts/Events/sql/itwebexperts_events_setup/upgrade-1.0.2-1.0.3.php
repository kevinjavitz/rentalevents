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
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
try {
    $setup->removeAttribute('catalog_product', 'productevents');

}catch(Exception $E) {

}

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