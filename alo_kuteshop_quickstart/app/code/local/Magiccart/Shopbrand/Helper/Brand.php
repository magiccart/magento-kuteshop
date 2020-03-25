<?php
class Magiccart_Shopbrand_Helper_Brand extends Mage_Core_Helper_Abstract
{
    
    public function renderLink(Mage_Core_Controller_Varien_Action $action, $id)
    {
        $brand = Mage::getSingleton('shopbrand/brand');
        $brand->setStoreId(Mage::app()->getStore()->getId());
        if ($brand->load($id)) return $brand;
        return false;
    }
    
    public function getUrl($identifier)
    {
        return Mage::getBaseUrl() . $identifier;
    }
}
