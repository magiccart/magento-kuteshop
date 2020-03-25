<?php
class Magiccart_Shopbrand_Block_Product_List extends Mage_Catalog_Block_Product_List
{
    /**
     * Retrieve loaded category collection
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    protected function _getProductCollection()
    {
        if (is_null($this->_productCollection)) {
            $this->_productCollection = $this->getBrand()->getProductCollection();
        }
        return $this->_productCollection;  
    }

    /**
     * Get array with product ids, which was added to manufacturer
     *
     * @return array
     */
    protected function _getBrandProductIds()
    {
        $brand = $this->getBrand();        
        $productIds = array(0);
        $productIds = $brand->getProductIds();
        
        return $productIds;
    }
    
    public function getBrand()
    {
        $brand = Mage::registry('current_brand');
        if($brand == null) {
            $id = Mage::app()->getRequest()->getParam('id');
            $brand = Mage::getModel('shopbrand/brand')->load($id);
        }
        
        return $brand;
    }

    public function getDescription()
    {
        $processor = Mage::helper('cms')->getBlockTemplateProcessor();
        return $processor->filter($this->getBrand()->getDescription());
    }

}
