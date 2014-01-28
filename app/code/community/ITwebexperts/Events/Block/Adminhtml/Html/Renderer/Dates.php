<?php


class ITwebexperts_Events_Block_Adminhtml_Html_Renderer_Dates extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $data = $row->getData($this->getColumn()->getIndex());
        $coll = Mage::getModel('itwebexperts_events/ordertodates')
            ->getCollection()
            ->addSelectFilter("orders_id='" . $data . "'");
        $resp = '';
        foreach($coll as $item){
            $resp .= $item->getEventDate().', ';
        }
        return $resp;
    }

}
