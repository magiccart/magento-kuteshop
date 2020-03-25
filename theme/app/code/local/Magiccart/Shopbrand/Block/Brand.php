<?php
class Magiccart_Shopbrand_Block_Brand extends Mage_Core_Block_Template
{
    
    /**
     * @todo  canonical url has to be added to avoid duplication
     */
    protected function _prepareLayout() 
    {
        parent::_prepareLayout();
        
        if ($headBlock = $this->getLayout()->getBlock('head')) {
            
            $brand = $this->getBrand();
            
            $headBlock->setTitle($brand->getTitle());
            $headBlock->setDescription($brand->getMetaDescription());
            $headBlock->setKeywords($brand->getMetaKey());
            
		}
    }
    
    /**
     * return current brand which was added to registery in Magiccart_Shopbrand_ViewController view action
     * 
     * @return type 
     */
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

