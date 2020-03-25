<?php
/**
 * Magiccart 
 * @category    Magiccart 
 * @copyright   Copyright (c) 2014 Magiccart (http://www.magiccart.net/) 
 * @license     http://www.magiccart.net/license-agreement.html
 * @Author: DOng NGuyen<nguyen@dvn.com>
 * @@Create Date: 2014-03-14 20:26:27
 * @@Modify Date: 2015-08-05 18:35:40
 * @@Function:
 */

class Magiccart_Shopbrand_Block_Product_Grid extends Mage_Catalog_Block_Product_List
{

    public function getType()
    {
        $type = $this->getRequest()->getParam('type');
        if(!$type){
            $type = $this->getActive(); // get form setData in Block
        }
        return $type;
    }

    public function getWidgetCfg($cfg=null)
    {
        $info = $this->getRequest()->getParam('info');
        if($info){
            if(isset($info[$cfg])) return $info[$cfg];
            return $info;          
        }else {
            $info = $this->getCfg();
            if(isset($info[$cfg])) return $info[$cfg];
            return $info;
        }
    }

    protected function _getProductCollection()
    {
       $productCollection = $this->getBrand()->getProductCollection()->setPageSize($this->getWidgetCfg('limit'));
       return $productCollection;
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
        if($this->hasData('brand')) return $this->getData('brand');
        $brand = Mage::registry('current_brand');
        if($brand == null) {
            $id = $id = $this->getType();
            $brand = Mage::getModel('shopbrand/brand')->load($id);
        }
        $this->setData('brand', $brand);
        return $brand;
    }

    public function getDescription()
    {
        $processor = Mage::helper('cms')->getBlockTemplateProcessor();
        return $processor->filter($this->getBrand()->getDescription());
    }

}
