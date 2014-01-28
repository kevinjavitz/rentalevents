<?php
/* 
 * @category  Event Manager Module
 * @package   ITwebexperts_Events

 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      N/A 
 */
class ITwebexperts_Events_Block_Item extends Mage_Core_Block_Template
{
    /**
     * Current news item instance
     *
     * @var ITwebexperts_Events_Model_Events
     */
    protected $_item;

    /**
     * Return parameters for back url
     *
     * @param array $additionalParams
     * @return array
     */
    protected function _getBackUrlQueryParams($additionalParams = array())
    {
        return array_merge(array('p' => $this->getPage()), $additionalParams);
    }

    /**
     * Return URL to the news list page
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/', array('_query' => $this->_getBackUrlQueryParams()));
    }

    /**
     * Return URL for resized Events Item image
     *
     * @param ITwebexperts_Events_Model_Events $item
     * @param integer $width
     * @return string|false
     */
    public function getImageUrl($item, $width)
    {
        return Mage::helper('itwebexperts_events/image')->resize($item, $width);
    }
}
