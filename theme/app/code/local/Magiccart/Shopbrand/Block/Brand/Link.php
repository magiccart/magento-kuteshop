<?php
class Magiccart_Shopbrand_Block_Brand_Link extends Mage_Core_Block_Template
{
    /**
     * set the default template for this block, 
     */
    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('magiccart/shopbrand/brand/link.phtml');
    }
    
    /**
     * Return all active/enabled brands
     * @return type 
     */
    public function getBrandCollection()
    {
        return $brandCollection = Mage::getModel('shopbrand/brand')->getCollection()
                                    ->addFieldToFilter('status', array('eq' => 1));
    }
}
