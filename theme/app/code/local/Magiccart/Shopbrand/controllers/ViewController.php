<?php
Class Magiccart_Shopbrand_ViewController extends Mage_Core_Controller_Front_Action
{
    public function viewAction()
    { 
        $this->loadLayout();
        try {
            $id = $this->getRequest()->getParam('id', $this->getRequest()->getParam('id', false));
            
            if($brand = Mage::helper('shopbrand/brand')->renderLink($this, $id)) {
                // $head = $this->getLayout()->getBlock("head");
                // $head->setTitle($brand->getTitle());
                // $head->setKeywords($brand->getMetaKey());
                // $head->setDescription($brand->getMetaDescription());
                Mage::register('current_brand', $brand);    
            }
        } catch(Exception $e) {
            
        }
        
        $this->renderLayout();
    }
}

